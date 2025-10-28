<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\LogsActivity;

class Survey extends Model
{
    use LogsActivity;

    protected $fillable = [
        'station_id',
        'month',
        'year',
        'days_in_month',
        'readings'
    ];

    protected $casts = [
        'readings' => 'array'
    ];

    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class);
    }

    public function fuelLoads(): HasMany
    {
        return $this->hasMany(FuelLoad::class);
    }

    public function calculateTotals(): array
    {
        $readings = $this->readings;

        if (!is_array($readings) || empty($readings)) {
            return [];
        }

        foreach ($readings as $fuelId => &$fuelData) {
            if (!is_array($fuelData)) {
                continue;
            }

            if (isset($fuelData['counters']) && is_array($fuelData['counters'])) {
                foreach ($fuelData['counters'] as $counterIndex => &$counter) {
                    if (!is_array($counter) || !isset($counter['values'])) {
                        continue;
                    }

                    if (!isset($counter['totals'])) {
                        $counter['totals'] = [];
                    }

                    $counter['totals'] = [];

                    for ($day = 1; $day <= $this->days_in_month; $day++) {
                        if (!array_key_exists($day, $counter['values']) || 
                            $counter['values'][$day] === '' || 
                            $counter['values'][$day] === null || 
                            $counter['values'][$day] === '0') {
                            continue;
                        }

                        $currentValue = intval($counter['values'][$day]);

                        if ($day == 1) {
                            $counter['totals'][$day] = $currentValue;
                        } else {
                            $previousValue = null;
                            for ($prevDay = $day - 1; $prevDay >= 1; $prevDay--) {
                                if (array_key_exists($prevDay, $counter['values']) && 
                                    $counter['values'][$prevDay] !== '' && 
                                    $counter['values'][$prevDay] !== null && 
                                    $counter['values'][$prevDay] !== '0') {
                                    $previousValue = intval($counter['values'][$prevDay]);
                                    break;
                                }
                            }

                            if ($previousValue !== null) {
                                $counter['totals'][$day] = $previousValue - $currentValue;
                            } else {
                                $counter['totals'][$day] = $currentValue;
                            }
                        }
                    }
                }
            }

            if (isset($fuelData['sounding']) && is_array($fuelData['sounding'])) {
                if (!isset($fuelData['sounding']['totals'])) {
                    $fuelData['sounding']['totals'] = [];
                }

                if (!isset($fuelData['sounding']['loads'])) {
                    $fuelData['sounding']['loads'] = [];
                }

                $fuelData['sounding']['totals'] = [];

                if (isset($fuelData['sounding']['values']) && is_array($fuelData['sounding']['values'])) {
                    for ($day = 1; $day <= $this->days_in_month; $day++) {
                        if (!array_key_exists($day, $fuelData['sounding']['values']) || 
                            $fuelData['sounding']['values'][$day] === '' || 
                            $fuelData['sounding']['values'][$day] === null || 
                            $fuelData['sounding']['values'][$day] === '0') {
                            continue;
                        }

                        $currentValue = intval($fuelData['sounding']['values'][$day]);

                        if ($day == 1) {
                            $fuelData['sounding']['totals'][$day] = $currentValue;
                        } else {
                            $previousValue = null;
                            for ($prevDay = $day - 1; $prevDay >= 1; $prevDay--) {
                                if (array_key_exists($prevDay, $fuelData['sounding']['values']) && 
                                    $fuelData['sounding']['values'][$prevDay] !== '' && 
                                    $fuelData['sounding']['values'][$prevDay] !== null && 
                                    $fuelData['sounding']['values'][$prevDay] !== '0') {
                                    $previousValue = intval($fuelData['sounding']['values'][$prevDay]);
                                    break;
                                }
                            }

                            if ($previousValue !== null) {
                                $fuelData['sounding']['totals'][$day] = $previousValue - $currentValue;
                            } else {
                                $fuelData['sounding']['totals'][$day] = $currentValue;
                            }
                        }
                    }
                }
            }
        }

        return $readings;
    }

    public function getStationFuels()
    {
        return $this->station?->fuels ?? collect();
    }

    public function getFormattedMonthName(): string
    {
        return Carbon::create()->month((int) $this->month)->translatedFormat('F');
    }

    public function getMonthYearLabel(): string
    {
        return $this->getFormattedMonthName() . ' ' . $this->year;
    }

    public function getPreviousSurvey()
    {
        $previousMonth = $this->month - 1;
        $year = $this->year;

        if ($previousMonth < 1) {
            $previousMonth = 12;
            $year -= 1;
        }

        return Survey::where('station_id', $this->station_id)
            ->where('month', $previousMonth)
            ->where('year', $year)
            ->first();
    }

    public function getNextSurvey()
    {
        $nextMonth = $this->month + 1;
        $year = $this->year;

        if ($nextMonth > 12) {
            $nextMonth = 1;
            $year += 1;
        }

        return Survey::where('station_id', $this->station_id)
            ->where('month', $nextMonth)
            ->where('year', $year)
            ->first();
    }

    public function hasFuelLoadOnDay(int $fuelId, int $day): bool
    {
        return $this->fuelLoads()
            ->where('fuel_id', $fuelId)
            ->where('day', $day)
            ->exists();
    }

    public function getFuelLoadsForDay(int $fuelId, int $day)
    {
        return $this->fuelLoads()
            ->where('fuel_id', $fuelId)
            ->where('day', $day)
            ->get();
    }

    public function addFuelLoad(int $fuelId, int $day, float $loadAmount, int $userId, ?string $notes = null)
    {
        if (!isset($this->readings[$fuelId]['sounding']['values'][$day])) {
            return false;
        }

        $currentSounding = intval($this->readings[$fuelId]['sounding']['values'][$day]);
        
        $readings = $this->readings;
        $readings[$fuelId]['sounding']['loads'][$day] = true;
        
        if (!isset($readings[$fuelId]['stock_entries'])) {
            $readings[$fuelId]['stock_entries'] = [];
        }
        if (!isset($readings[$fuelId]['stock_entries']['values'])) {
            $readings[$fuelId]['stock_entries']['values'] = [];
        }
        
        $existingValue = intval($readings[$fuelId]['stock_entries']['values'][$day] ?? 0);
        $readings[$fuelId]['stock_entries']['values'][$day] = (string)($existingValue + intval($loadAmount));

        $this->readings = $readings;
        $this->readings = $this->calculateTotals();
        $this->save();

        FuelLoad::create([
            'station_id' => $this->station_id,
            'fuel_id' => $fuelId,
            'survey_id' => $this->id,
            'user_id' => $userId,
            'day' => $day,
            'current_sounding' => $currentSounding,
            'load_amount' => intval($loadAmount),
            'notes' => $notes
        ]);

        return true;
    }

    public function logs()
    {
        return $this->hasMany(SurveyLog::class)->orderBy('created_at', 'desc');
    }

    public function updateFuelLoad(int $fuelLoadId, float $newLoadAmount, int $userId, ?string $notes = null)
    {
        $fuelLoad = FuelLoad::find($fuelLoadId);

        if (!$fuelLoad || $fuelLoad->survey_id !== $this->id) {
            return false;
        }

        $oldLoadAmount = $fuelLoad->load_amount;
        $difference = intval($newLoadAmount) - $oldLoadAmount;

        $readings = $this->readings;
        
        if (isset($readings[$fuelLoad->fuel_id]['stock_entries']['values'][$fuelLoad->day])) {
            $currentStockEntry = intval($readings[$fuelLoad->fuel_id]['stock_entries']['values'][$fuelLoad->day]);
            $newStockEntry = $currentStockEntry + $difference;
            $readings[$fuelLoad->fuel_id]['stock_entries']['values'][$fuelLoad->day] = (string)$newStockEntry;
        }

        $this->readings = $readings;
        $this->readings = $this->calculateTotals();
        $this->save();

        $fuelLoad->update([
            'load_amount' => intval($newLoadAmount),
            'notes' => $notes
        ]);

        return true;
    }

    public function deleteFuelLoad(int $fuelLoadId, int $userId)
    {
        $fuelLoad = FuelLoad::find($fuelLoadId);

        if (!$fuelLoad || $fuelLoad->survey_id !== $this->id) {
            return false;
        }

        $readings = $this->readings;
        
        if (isset($readings[$fuelLoad->fuel_id]['stock_entries']['values'][$fuelLoad->day])) {
            $currentStockEntry = intval($readings[$fuelLoad->fuel_id]['stock_entries']['values'][$fuelLoad->day]);
            $newStockEntry = $currentStockEntry - $fuelLoad->load_amount;
            
            if ($newStockEntry <= 0) {
                unset($readings[$fuelLoad->fuel_id]['stock_entries']['values'][$fuelLoad->day]);
            } else {
                $readings[$fuelLoad->fuel_id]['stock_entries']['values'][$fuelLoad->day] = (string)$newStockEntry;
            }
        }

        $remainingLoads = FuelLoad::where('survey_id', $this->id)
            ->where('fuel_id', $fuelLoad->fuel_id)
            ->where('day', $fuelLoad->day)
            ->where('id', '!=', $fuelLoad->id)
            ->count();

        if ($remainingLoads === 0) {
            unset($readings[$fuelLoad->fuel_id]['sounding']['loads'][$fuelLoad->day]);
        }

        $this->readings = $readings;
        $this->readings = $this->calculateTotals();
        $this->save();

        $fuelLoad->delete();

        return true;
    }
}