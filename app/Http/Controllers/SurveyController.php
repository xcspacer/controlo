<?php

namespace App\Http\Controllers;

use App\Models\FuelLoad;
use App\Models\Survey;
use App\Models\Station;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SurveyExport;

class SurveyController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        if ($user->is_admin) {
            $stations = Station::whereHas('surveys')->with(['surveys' => function ($query) {
                $query->latest('year')->latest('month');
            }])->get();
        } else {
            $stations = $user->stations()->whereHas('surveys')->with(['surveys' => function ($query) {
                $query->latest('year')->latest('month');
            }])->get();
        }

        return view('surveys.index', compact('stations'));
    }

    public function create(): View
    {
        $user = Auth::user();

        if ($user->is_admin) {
            $stations = Station::where('is_active', true)
                ->whereDoesntHave('surveys', function ($query) {
                    $query->where('month', now()->month)
                        ->where('year', now()->year);
                })->get();
        } else {
            $stations = $user->stations()
                ->where('is_active', true)
                ->whereDoesntHave('surveys', function ($query) {
                    $query->where('month', now()->month)
                        ->where('year', now()->year);
                })->get();
        }

        return view('surveys.create', compact('stations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
            'days_in_month' => 'required|integer|between:28,31',
            'readings' => 'required|string'
        ]);

        $readingsArray = json_decode($validated['readings'], true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->with('error', 'Os dados das leituras inseridos são inválidos.');
        }

        $existingSurvey = Survey::where([
            'station_id' => $validated['station_id'],
            'month' => $validated['month'],
            'year' => $validated['year']
        ])->first();

        if ($existingSurvey) {
            return back()->with('error', 'Já existe um registo para este posto neste mês e ano.');
        }

        $survey = Survey::create([
            'station_id' => $validated['station_id'],
            'month' => $validated['month'],
            'year' => $validated['year'],
            'days_in_month' => $validated['days_in_month'],
            'readings' => $readingsArray
        ]);

        $survey->readings = $survey->calculateTotals();
        $survey->save();

        $survey->logActivity(
            'created',
            null,
            $survey->toArray(),
            'Registo criado para o posto ' . $survey->station->name . ' em ' . $survey->getMonthYearLabel()
        );

        return redirect()
            ->route('surveys.show', $survey)
            ->with('success', 'Registo criado com sucesso!');
    }

    public function show(Survey $survey): View
    {
        $this->authorizeView($survey);

        $survey->load('station.fuels');
        $previousSurvey = $survey->getPreviousSurvey();
        $nextSurvey = $survey->getNextSurvey();
        $fuelLoads = $survey->fuelLoads()->with(['fuel', 'user'])->orderBy('day')->get();

        return view('surveys.show', compact('survey', 'previousSurvey', 'nextSurvey', 'fuelLoads'));
    }

    public function edit(Survey $survey): View
    {
        $this->authorizeView($survey);

        $survey->load('station.fuels');

        return view('surveys.edit', compact('survey'));
    }

    public function update(Request $request, Survey $survey): RedirectResponse
    {
        $this->authorizeView($survey);

        $validated = $request->validate([
            'readings' => 'required|string'
        ]);

        $readingsArray = json_decode($validated['readings'], true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->with('error', 'Dados de leituras inválidos.');
        }

        $oldData = $survey->toArray();

        $survey->readings = $readingsArray;
        $survey->readings = $survey->calculateTotals();
        $survey->save();

        $survey->logActivity(
            'updated',
            $oldData,
            $survey->fresh()->toArray(),
            'Leituras atualizadas para o registo de ' . $survey->getMonthYearLabel()
        );

        return redirect()
            ->route('surveys.show', $survey)
            ->with('success', 'Registo atualizado com sucesso!');
    }

    public function destroy(Survey $survey): RedirectResponse
    {
        $this->authorizeView($survey);

        $survey->logActivity(
            'deleted',
            $survey->toArray(),
            null,
            'Registo eliminado para o posto ' . $survey->station->name . ' em ' . $survey->getMonthYearLabel()
        );

        $survey->delete();

        return redirect()
            ->route('surveys.index')
            ->with('success', 'Registo eliminado com sucesso!');
    }

    public function getStationFuels(Station $station)
    {
        return response()->json($station->fuels);
    }

    public function navigateToCurrentMonth(Station $station)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $survey = Survey::where('station_id', $station->id)
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->first();

        if (!$survey) {
            $latestSurvey = $station->getLatestSurvey();

            if (!$latestSurvey) {
                return redirect()->route('surveys.create')->with('info', 'Crie o primeiro registo para este posto.');
            }

            $readingsArray = [];
            $daysInMonth = Carbon::create($currentYear, $currentMonth)->daysInMonth;

            foreach ($station->fuels as $fuel) {
                $readingsArray[$fuel->id] = [
                    'counters' => [],
                    'sounding' => [
                        'values' => [],
                        'totals' => [],
                        'loads' => []
                    ]
                ];

                for ($i = 0; $i < $fuel->counter; $i++) {
                    $readingsArray[$fuel->id]['counters'][$i] = [
                        'values' => [],
                        'totals' => []
                    ];
                }
            }

            $survey = Survey::create([
                'station_id' => $station->id,
                'month' => $currentMonth,
                'year' => $currentYear,
                'days_in_month' => $daysInMonth,
                'readings' => $readingsArray
            ]);

            $survey->readings = $survey->calculateTotals();
            $survey->save();
        }

        return redirect()->route('surveys.show', $survey);
    }

    public function addFuelLoad(Request $request, Survey $survey)
    {
        $this->authorizeView($survey);

        $validated = $request->validate([
            'fuel_id' => 'required|exists:fuels,id',
            'day' => 'required|integer|min:1|max:' . $survey->days_in_month,
            'load_amount' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500'
        ]);

        $fuel = $survey->station->fuels()->find($validated['fuel_id']);
        if (!$fuel) {
            return back()->with('error', 'O combustível selecionado não pertence a este posto.');
        }

        $oldData = $survey->toArray();

        try {
            $success = $survey->addFuelLoad(
                $validated['fuel_id'],
                $validated['day'],
                $validated['load_amount'],
                Auth::id(),
                $validated['notes'] ?? null
            );

            if (!$success) {
                return back()->with('error', 'Não foi possível adicionar a carga. Certifique-se que existe uma leitura de sonda para o dia ' . $validated['day'] . '.');
            }
        } catch (\Exception $e) {
            \Log::error('Erro ao adicionar carga: ' . $e->getMessage());
            return back()->with('error', 'Erro ao adicionar carga: ' . $e->getMessage());
        }

        $survey->logActivity(
            'updated',
            $oldData,
            $survey->fresh()->toArray(),
            'Carga de ' . $validated['load_amount'] . 'LT adicionada ao combustível ' . $fuel->name . ' no dia ' . $validated['day']
        );

        return back()->with('success', 'Carga de combustível adicionada com sucesso!');
    }

    public function fuelLoads(Survey $survey)
    {
        $this->authorizeView($survey);

        $fuelLoads = $survey->fuelLoads()->with(['fuel', 'user'])->orderBy('created_at', 'desc')->get();

        return view('surveys.fuel-loads', compact('survey', 'fuelLoads'));
    }

    private function authorizeView(Survey $survey)
    {
        $user = Auth::user();

        if (!$user->is_admin && !$user->stations->contains($survey->station_id)) {
            abort(403, 'Não tem permissão para aceder a este registo.');
        }
    }

    public function logs(Survey $survey)
    {
        $this->authorizeView($survey);

        $logs = $survey->logs()->with('user')->paginate(15);

        return view('surveys.logs', compact('survey', 'logs'));
    }

    public function editFuelLoad(Survey $survey, FuelLoad $fuelLoad)
    {
        $this->authorizeView($survey);

        if ($fuelLoad->survey_id !== $survey->id) {
            abort(404);
        }

        return response()->json([
            'id' => $fuelLoad->id,
            'fuel_id' => $fuelLoad->fuel_id,
            'fuel_name' => $fuelLoad->fuel->name,
            'day' => $fuelLoad->day,
            'load_amount' => $fuelLoad->load_amount,
            'notes' => $fuelLoad->notes
        ]);
    }

    public function updateFuelLoad(Request $request, Survey $survey, FuelLoad $fuelLoad)
    {
        $this->authorizeView($survey);

        if ($fuelLoad->survey_id !== $survey->id) {
            abort(404);
        }

        $validated = $request->validate([
            'load_amount' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500'
        ]);

        $oldData = $survey->toArray();
        $oldLoadAmount = $fuelLoad->load_amount;

        $success = $survey->updateFuelLoad(
            $fuelLoad->id,
            $validated['load_amount'],
            Auth::id(),
            $validated['notes'] ?? null
        );

        if (!$success) {
            return back()->with('error', 'Não foi possível atualizar a carga. Verifique os valores e tente novamente.');
        }

        $fuel = $fuelLoad->fuel;
        $survey->logActivity(
            'updated',
            $oldData,
            $survey->fresh()->toArray(),
            'Carga alterada de ' . $oldLoadAmount . 'LT para ' . $validated['load_amount'] . 'LT no combustível ' . $fuel->name . ' no dia ' . $fuelLoad->day
        );

        return back()->with('success', 'Carga de combustível atualizada com sucesso!');
    }

    public function destroyFuelLoad(Survey $survey, FuelLoad $fuelLoad)
    {
        $this->authorizeView($survey);

        if ($fuelLoad->survey_id !== $survey->id) {
            abort(404);
        }

        $oldData = $survey->toArray();
        $fuel = $fuelLoad->fuel;
        $loadAmount = $fuelLoad->load_amount;
        $day = $fuelLoad->day;

        $success = $survey->deleteFuelLoad($fuelLoad->id, Auth::id());

        if (!$success) {
            return back()->with('error', 'Não foi possível eliminar a carga.');
        }

        $survey->logActivity(
            'updated',
            $oldData,
            $survey->fresh()->toArray(),
            'Carga de ' . $loadAmount . 'LT eliminada do combustível ' . $fuel->name . ' no dia ' . $day
        );

        return back()->with('success', 'Carga de combustível eliminada com sucesso!');
    }

    public function export(Survey $survey)
    {
        $this->authorizeView($survey);

        $fileName = 'registo_' . $survey->station->name . '_' . $survey->getMonthYearLabel() . '.xlsx';
        $fileName = str_replace(' ', '_', $fileName);
        $fileName = preg_replace('/[^A-Za-z0-9_.-]/', '', $fileName);

        return Excel::download(new SurveyExport($survey), $fileName);
    }
}