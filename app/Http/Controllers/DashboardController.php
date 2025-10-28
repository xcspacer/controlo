<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        if ($user->is_admin) {
            $stationsData = $this->getAdminDashboardData();
            return view('dashboard', compact('stationsData'));
        } else {
            $userStations = $this->getUserStationsData($user);
            return view('dashboard', compact('userStations'));
        }
    }

    private function getAdminDashboardData(): array
    {
        $stations = Station::with(['fuels', 'surveys' => function ($query) {
            $query->orderBy('year', 'desc')->orderBy('month', 'desc');
        }])->orderBy('group')->get();

        $groupedStations = $stations->groupBy('group');
        $stationsData = [];

        foreach ($groupedStations as $group => $groupStations) {
            $stationsData[$group] = [
                'stations' => [],
                'group_summary' => [
                    'total_capacity' => 0,
                    'total_current_stock' => 0,
                    'total_available_space' => 0,
                    'fuels_summary' => []
                ]
            ];

            foreach ($groupStations as $station) {
                $latestSurvey = $station->surveys->first();
                $stationInfo = [
                    'station' => $station,
                    'latest_survey' => $latestSurvey,
                    'last_filled_day' => null,
                    'fuels_data' => []
                ];

                if ($latestSurvey) {
                    $lastFilledDay = $this->getLastFilledDay($latestSurvey);
                    $stationInfo['last_filled_day'] = $lastFilledDay;

                    $fuelsData = [];
                    foreach ($station->fuels as $fuel) {
                        $fuelData = $this->getFuelAnalysis($station, $fuel, $latestSurvey, $lastFilledDay);

                        $availableSpace = $fuel->capacity - $fuelData['current_sounding'];
                        $fuelData['available_space'] = max(0, $availableSpace);

                        $daysRemaining = null;
                        if ($fuelData['current_sounding'] > 0 && $fuelData['average_daily_consumption'] > 0) {
                            $stockWithSafetyMargin = $fuelData['current_sounding'] - 500;
                            
                            if ($stockWithSafetyMargin > 0) {
                                $daysRemaining = intval($stockWithSafetyMargin / $fuelData['average_daily_consumption']);
                            } else {
                                $daysRemaining = 0;
                            }
                        }
                        $fuelData['days_remaining'] = $daysRemaining;

                        $fuelsData[] = $fuelData;
                    }

                    if (strtolower($station->name) === 'benedita') {
                        $stationInfo['fuels_data'] = $this->unifyGAditivadoForBenedita($fuelsData);
                    } else {
                        $stationInfo['fuels_data'] = $fuelsData;
                    }

                    foreach ($stationInfo['fuels_data'] as $fuelData) {
                        $fuel = $fuelData['fuel'];
                        $stationsData[$group]['group_summary']['total_capacity'] += $fuel->capacity;
                        $stationsData[$group]['group_summary']['total_current_stock'] += $fuelData['current_sounding'];
                        $stationsData[$group]['group_summary']['total_available_space'] += $fuelData['available_space'];

                        $fuelName = $fuel->name;
                        if (!isset($stationsData[$group]['group_summary']['fuels_summary'][$fuelName])) {
                            $stationsData[$group]['group_summary']['fuels_summary'][$fuelName] = [
                                'total_capacity' => 0,
                                'total_current_stock' => 0,
                                'total_available_space' => 0,
                                'count' => 0
                            ];
                        }

                        $stationsData[$group]['group_summary']['fuels_summary'][$fuelName]['total_capacity'] += $fuel->capacity;
                        $stationsData[$group]['group_summary']['fuels_summary'][$fuelName]['total_current_stock'] += $fuelData['current_sounding'];
                        $stationsData[$group]['group_summary']['fuels_summary'][$fuelName]['total_available_space'] += $fuelData['available_space'];
                        $stationsData[$group]['group_summary']['fuels_summary'][$fuelName]['count']++;
                    }
                }

                $stationsData[$group]['stations'][] = $stationInfo;
            }
        }

        return $stationsData;
    }

    private function unifyGAditivadoForBenedita(array $fuelsData): array
    {
        $unifiedData = [];
        $gAditivadoData = null;
        $gAditivadoFuels = [];

        foreach ($fuelsData as $fuelData) {
            if (str_contains($fuelData['fuel']->name, 'G.Aditivado')) {
                $gAditivadoFuels[] = $fuelData;
            } else {
                $unifiedData[] = $fuelData;
            }
        }

        if (count($gAditivadoFuels) > 0) {
            $unifiedFuel = new \stdClass();
            $unifiedFuel->id = $gAditivadoFuels[0]['fuel']->id;
            $unifiedFuel->name = 'G.Aditivado';
            $unifiedFuel->capacity = 0;
            $unifiedFuel->counter = 0;

            $totalCurrentSounding = 0;
            $totalCurrentTotal = 0;
            $totalAverageConsumption = 0;
            $totalCapacity = 0;
            $totalAvailableSpace = 0;

            foreach ($gAditivadoFuels as $gAditivado) {
                $totalCapacity += $gAditivado['fuel']->capacity;
                $totalCurrentSounding += $gAditivado['current_sounding'];
                $totalCurrentTotal += $gAditivado['current_total'];
                $totalAverageConsumption += $gAditivado['average_daily_consumption'];
                $totalAvailableSpace += $gAditivado['available_space'];
            }

            $unifiedFuel->capacity = $totalCapacity;

            $daysRemaining = null;
            if ($totalCurrentSounding > 0 && $totalAverageConsumption > 0) {
                $stockWithSafetyMargin = $totalCurrentSounding - 500;
                
                if ($stockWithSafetyMargin > 0) {
                    $daysRemaining = intval($stockWithSafetyMargin / $totalAverageConsumption);
                } else {
                    $daysRemaining = 0;
                }
            }

            $gAditivadoData = [
                'fuel' => $unifiedFuel,
                'current_sounding' => $totalCurrentSounding,
                'current_total' => $totalCurrentTotal,
                'average_daily_consumption' => $totalAverageConsumption,
                'last_day' => $gAditivadoFuels[0]['last_day'],
                'available_space' => $totalAvailableSpace,
                'days_remaining' => $daysRemaining
            ];

            $unifiedData[] = $gAditivadoData;
        }

        return $unifiedData;
    }

    private function getUserStationsData($user): array
    {
        $stations = $user->stations()->where('is_active', true)->get();
        $userStations = [];

        foreach ($stations as $station) {
            $userStations[] = [
                'station' => $station,
                'has_current_survey' => $station->hasCurrentSurvey(),
                'current_month' => now()->month,
                'current_year' => now()->year
            ];
        }

        return $userStations;
    }

    private function getLastFilledDay($survey): ?int
    {
        $readings = $survey->readings;
        $lastDay = null;

        if (!is_array($readings)) return null;

        foreach ($readings as $fuelId => $fuelData) {
            if (isset($fuelData['sounding']['values'])) {
                foreach ($fuelData['sounding']['values'] as $day => $value) {
                    if (!empty($value) && intval($value) > 0) {
                        $lastDay = max($lastDay, $day);
                    }
                }
            }

            if (isset($fuelData['counters'])) {
                foreach ($fuelData['counters'] as $counter) {
                    if (isset($counter['values'])) {
                        foreach ($counter['values'] as $day => $value) {
                            if (!empty($value) && intval($value) > 0) {
                                $lastDay = max($lastDay, $day);
                            }
                        }
                    }
                }
            }
        }

        return $lastDay;
    }

    private function getFuelAnalysis($station, $fuel, $latestSurvey, $lastFilledDay): array
    {
        $currentSounding = 0;
        $currentTotal = 0;
        $averageConsumption = 0;

        if ($lastFilledDay && isset($latestSurvey->readings[$fuel->id]['sounding']['values'][$lastFilledDay])) {
            $currentSounding = intval($latestSurvey->readings[$fuel->id]['sounding']['values'][$lastFilledDay]);
        }

        if ($lastFilledDay && isset($latestSurvey->readings[$fuel->id]['counters'])) {
            $dayTotal = 0;

            foreach ($latestSurvey->readings[$fuel->id]['counters'] as $counterIndex => $counter) {
                if (isset($counter['totals'][$lastFilledDay])) {
                    $counterValue = abs(intval($counter['totals'][$lastFilledDay]));
                    $dayTotal += $counterValue;
                }
            }

            $currentTotal = $dayTotal;
        }

        $averageConsumption = $this->calculateAverageConsumption($station, $fuel);

        return [
            'fuel' => $fuel,
            'current_sounding' => $currentSounding,
            'current_total' => $currentTotal,
            'average_daily_consumption' => $averageConsumption,
            'last_day' => $lastFilledDay
        ];
    }

    private function calculateAverageConsumption($station, $fuel): int
{
    $surveys = Survey::where('station_id', $station->id)
        ->where(function ($query) {
            $currentMonth = now()->month;
            $currentYear = now()->year;

            $months = [];
            for ($i = 0; $i < 3; $i++) {
                $month = $currentMonth - $i;
                $year = $currentYear;

                if ($month < 1) {
                    $month = 12 + $month;
                    $year = $currentYear - 1;
                }

                $months[] = ['month' => $month, 'year' => $year];
            }

            foreach ($months as $index => $monthData) {
                if ($index == 0) {
                    $query->where(function ($q) use ($monthData) {
                        $q->where('month', $monthData['month'])->where('year', $monthData['year']);
                    });
                } else {
                    $query->orWhere(function ($q) use ($monthData) {
                        $q->where('month', $monthData['month'])->where('year', $monthData['year']);
                    });
                }
            }
        })
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();

    $totalConsumption = 0;
    $daysCount = 0;

    foreach ($surveys as $survey) {
        if (!isset($survey->readings[$fuel->id])) {
            continue;
        }

        $fuelData = $survey->readings[$fuel->id];

        if (!isset($fuelData['counters']) || !is_array($fuelData['counters'])) {
            continue;
        }

        for ($day = 1; $day <= $survey->days_in_month; $day++) {
            $dayTotalConsumption = 0;
            $hasValidData = false;

            foreach ($fuelData['counters'] as $counterIndex => $counter) {
                // Corrigir a verificação - aceitar também valores "0" válidos
                if (isset($counter['totals'][$day]) && 
                    $counter['totals'][$day] !== '' && 
                    $counter['totals'][$day] !== null) {
                    
                    $counterConsumption = abs(intval($counter['totals'][$day]));
                    $dayTotalConsumption += $counterConsumption;
                    $hasValidData = true;
                }
            }

            // Remover o limite de 1000LT ou aumentá-lo para um valor mais realista
            // e aceitar também consumo = 0 como válido
            if ($hasValidData && $dayTotalConsumption <= 5000) {
                $totalConsumption += $dayTotalConsumption;
                $daysCount++;

                if ($daysCount >= 7) {
                    break 2;
                }
            }
        }
    }

    $average = $daysCount > 0 ? intval($totalConsumption / $daysCount) : 0;

    return $average;
}
}