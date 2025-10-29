<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\FuelRequest;
use App\Mail\FuelRequestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FuelRequestController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        if ($user->is_admin) {
            $fuelRequests = FuelRequest::with('user')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            $fuelRequests = FuelRequest::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        return view('fuel-requests.index', compact('fuelRequests'));
    }

    public function show(FuelRequest $fuelRequest): View
    {
        $user = Auth::user();

        if (!$user->is_admin && $fuelRequest->user_id !== $user->id) {
            abort(403, 'Não tem permissão para ver este pedido.');
        }

        $fuelRequest->load('user');

        return view('fuel-requests.show', compact('fuelRequest'));
    }

    public function create(): View
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Acesso negado');
        }

        $groupsCollection = Station::with(['fuels', 'surveys' => function ($query) {
            $query->orderBy('year', 'desc')->orderBy('month', 'desc');
        }])
            ->where('is_active', true)
            ->get()
            ->groupBy('group');

        $groups = [];

        foreach ($groupsCollection as $groupNumber => $stations) {
            $groupData = [
                'group' => $groupNumber,
                'stations' => []
            ];

            foreach ($stations as $station) {
                $latestSurvey = $station->surveys->first();
                $stationData = [
                    'id' => $station->id,
                    'name' => $station->name,
                    'address' => $station->address,
                    'city' => $station->city,
                    'fuels' => []
                ];

                if ($latestSurvey) {
                    foreach ($station->fuels as $fuel) {
                        $currentSounding = $this->getCurrentSounding($latestSurvey, $fuel);
                        $availableSpace = max(0, $fuel->capacity - $currentSounding);
                        $averageConsumption = $this->calculateAverageConsumption($station, $fuel);

                        if ($availableSpace > 0 || $averageConsumption > 0) {
                            $stationData['fuels'][] = [
                                'id' => $fuel->id,
                                'name' => $fuel->name,
                                'capacity' => $fuel->capacity,
                                'current_stock' => $currentSounding,
                                'available_space' => $availableSpace,
                                'average_consumption' => $averageConsumption
                            ];
                        }
                    }
                }

                $groupData['stations'][] = $stationData;
            }

            $groups[] = $groupData;
        }

        return view('fuel-requests.create', compact('groups'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Acesso negado');
        }

        $request->validate([
            'requests' => 'required|array|min:1',
            'requests.*.station_id' => 'required|exists:stations,id',
            'requests.*.fuel_id' => 'required|exists:fuels,id',
            'requests.*.quantity' => 'required|integer|min:1',
            'total_quantity' => 'required|integer|max:32000',
            'delivery_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:1000'
        ], [
            'requests.required' => 'Deve adicionar pelo menos um pedido',
            'requests.min' => 'Deve adicionar pelo menos um pedido',
            'total_quantity.max' => 'O total não pode exceder 32.000 litros',
            'delivery_date.required' => 'A data de entrega é obrigatória',
            'delivery_date.after_or_equal' => 'A data de entrega deve ser hoje ou no futuro'
        ]);

        // Validação adicional para limites individuais por posto/combustível
        $stationIds = collect($request->requests)->pluck('station_id')->filter()->unique();
        
        if ($stationIds->isEmpty()) {
            return back()->withErrors([
                'requests' => 'Nenhum pedido válido foi encontrado.'
            ])->withInput();
        }
        
        // Carregar stations com as mesmas relações e condições usadas no create()
        // IMPORTANTE: Deve ser idêntico ao create() para garantir cálculos consistentes
        $stations = Station::with(['fuels', 'surveys' => function ($query) {
            $query->orderBy('year', 'desc')->orderBy('month', 'desc');
        }])
            ->where('is_active', true) // Mesma condição do create()
            ->whereIn('id', $stationIds)
            ->get()
            ->keyBy('id');

        // Calcular dias até entrega EXATAMENTE como o frontend JavaScript:
        // Frontend: const today = new Date(); today.setHours(0, 0, 0, 0);
        // Frontend: const delivery = new Date(this.deliveryDate); delivery.setHours(0, 0, 0, 0);
        // Frontend: const diffTime = delivery - today; (em milissegundos)
        // Frontend: const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        // Frontend: return Math.max(0, diffDays);
        
        // Parse da data de entrega (formato YYYY-MM-DD do input date)
        $deliveryDateStr = $request->delivery_date;
        $deliveryDate = \Carbon\Carbon::parse($deliveryDateStr)->setTime(0, 0, 0);
        
        // Hoje às 00:00:00 (timezone deve ser o mesmo que o JavaScript usa)
        $today = \Carbon\Carbon::today()->setTime(0, 0, 0);
        
        // JavaScript calcula: diffTime = delivery - today (em milissegundos)
        // PHP: calcular diferença em milissegundos
        $diffInMilliseconds = ($deliveryDate->timestamp - $today->timestamp) * 1000;
        $diffInDays = $diffInMilliseconds / (1000 * 60 * 60 * 24); // Dividir por milissegundos em um dia
        
        // Math.ceil e Math.max(0, ...) - exatamente como o frontend
        $daysUntilDelivery = max(0, (int) ceil($diffInDays));

        // Acumular quantidades solicitadas por posto/combustível para validar corretamente
        // quando há múltiplos pedidos para o mesmo posto/combustível
        $requestedQuantitiesByStationFuel = [];
        foreach ($request->requests as $requestItem) {
            $key = $requestItem['station_id'] . '_' . $requestItem['fuel_id'];
            if (!isset($requestedQuantitiesByStationFuel[$key])) {
                $requestedQuantitiesByStationFuel[$key] = 0;
            }
            $requestedQuantitiesByStationFuel[$key] += intval($requestItem['quantity']);
        }

        foreach ($request->requests as $index => $requestItem) {
            $station = $stations[$requestItem['station_id']];
            
            if (!$station) {
                continue;
            }

            $fuel = $station->fuels->firstWhere('id', $requestItem['fuel_id']);
            
            if (!$fuel) {
                continue;
            }

            // Usar o mesmo método de cálculo usado no create()
            $projectedSpace = $this->calculateProjectedSpace($station, $fuel, $daysUntilDelivery);

            // Calcular quantidade total já solicitada para este posto/combustível (pode haver múltiplos pedidos)
            $key = $requestItem['station_id'] . '_' . $requestItem['fuel_id'];
            $totalRequestedQuantity = $requestedQuantitiesByStationFuel[$key] ?? 0;

            // Verificar se a quantidade TOTAL solicitada excede o espaço projetado
            if ($totalRequestedQuantity > $projectedSpace) {
                return back()->withErrors([
                    "requests.{$index}.quantity" => "A quantidade total solicitada ({$totalRequestedQuantity} LT) para {$station->name} - {$fuel->name} excede a capacidade disponível de {$projectedSpace} LT."
                ])->withInput();
            }
        }

        $organizedData = [];

        foreach ($request->requests as $requestItem) {
            $station = $stations[$requestItem['station_id']];
            $fuel = $station->fuels->firstWhere('id', $requestItem['fuel_id']);

            if (!isset($organizedData[$station->group])) {
                $organizedData[$station->group] = [
                    'group' => $station->group,
                    'stations' => []
                ];
            }

            if (!isset($organizedData[$station->group]['stations'][$station->id])) {
                $organizedData[$station->group]['stations'][$station->id] = [
                    'name' => $station->name,
                    'address' => $station->address,
                    'city' => $station->city,
                    'fuels' => []
                ];
            }

            $organizedData[$station->group]['stations'][$station->id]['fuels'][] = [
                'name' => $fuel->name,
                'quantity' => $requestItem['quantity']
            ];
        }

        $fuelRequest = FuelRequest::create([
            'user_id' => Auth::id(),
            'total_quantity' => $request->total_quantity,
            'delivery_date' => $request->delivery_date,
            'notes' => $request->notes,
            'request_data' => [
                'organized_data' => $organizedData,
                'raw_requests' => $request->requests
            ]
        ]);

        $requestData = [
            'organized_data' => $organizedData,
            'total_quantity' => $request->total_quantity,
            'delivery_date' => \Carbon\Carbon::parse($request->delivery_date)->format('d/m/Y'),
            'notes' => $request->notes,
            'requested_by' => Auth::user()->name,
            'requested_at' => now()->format('d/m/Y H:i'),
            'request_id' => $fuelRequest->id
        ];

        Mail::to([
            'pmorais@gestroilenergy.com',
            'geral@carbuiberia.com', 
            'lfigueiredo@gestroilenergy.com'
        ])->send(new FuelRequestMail($requestData));

        return redirect()->route('fuel-requests.show', $fuelRequest)
            ->with('success', 'Pedido de combustível criado e enviado com sucesso!');
    }

    private function calculateAverageConsumption($station, $fuel): int
    {
        $surveys = \App\Models\Survey::where('station_id', $station->id)
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
                    if (isset($counter['totals'][$day]) && 
                        $counter['totals'][$day] !== '' && 
                        $counter['totals'][$day] !== null) {
                        
                        $counterConsumption = abs(intval($counter['totals'][$day]));
                        $dayTotalConsumption += $counterConsumption;
                        $hasValidData = true;
                    }
                }

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

    private function getCurrentSounding($latestSurvey, $fuel): int
    {
        $lastFilledDay = $this->getLastFilledDay($latestSurvey);
        $currentSounding = 0;

        if ($lastFilledDay && isset($latestSurvey->readings[$fuel->id]['sounding']['values'][$lastFilledDay])) {
            $currentSounding = intval($latestSurvey->readings[$fuel->id]['sounding']['values'][$lastFilledDay]);
        }

        return $currentSounding;
    }

    private function calculateProjectedSpace($station, $fuel, int $daysUntilDelivery): int
    {
        // Garantir que os surveys estejam carregados e ordenados (mesma lógica do create)
        if (!$station->relationLoaded('surveys')) {
            $station->load(['surveys' => function ($query) {
                $query->orderBy('year', 'desc')->orderBy('month', 'desc');
            }]);
        }
        
        // Buscar último levantamento (mesma lógica do create)
        $latestSurvey = $station->surveys->first();
        $currentSounding = 0;

        if ($latestSurvey) {
            $currentSounding = $this->getCurrentSounding($latestSurvey, $fuel);
        }

        $availableSpace = max(0, $fuel->capacity - $currentSounding);

        // Calcular consumo médio (mesma função usada no create)
        $averageConsumption = $this->calculateAverageConsumption($station, $fuel);

        // Calcular espaço projetado (mesma lógica do frontend)
        $projectedSpace = $availableSpace;
        if ($daysUntilDelivery > 0 && $averageConsumption > 0) {
            $projectedSpace += ($averageConsumption * $daysUntilDelivery);
        }
        // Limitar pela capacidade total do tanque (mesma do frontend)
        $projectedSpace = min($projectedSpace, $fuel->capacity);

        return (int) $projectedSpace;
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
        }

        return $lastDay;
    }
}