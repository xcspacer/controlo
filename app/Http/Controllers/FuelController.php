<?php

namespace App\Http\Controllers;

use App\Models\Fuel;
use App\Models\Station;
use Illuminate\Http\Request;

class FuelController extends Controller
{
    public function index()
    {
        $fuels = Fuel::all();
        return view('fuels.index', compact('fuels'));
    }

    public function create(Request $request)
    {
        $station_id = $request->query('station_id');

        if (!$station_id) {
            return redirect()->route('stations.index')
                ->with('error', 'ID do posto não foi fornecido.');
        }

        $stationExists = Station::find($station_id);

        if (!$stationExists) {
            return redirect()->route('stations.index')
                ->with('error', 'O posto com ID ' . $station_id . ' não existe.');
        }

        return view('fuels.create', compact('station_id'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'name' => ['required', 'string', 'max:255'],
            'counter' => ['required', 'integer'],
            'capacity' => ['required', 'integer'],
        ]);

        Fuel::create([
            'station_id' => $validated['station_id'],
            'name' => $validated['name'],
            'counter' => $validated['counter'],
            'capacity' => $validated['capacity'],
        ]);

        return redirect()->route('stations.show', $request->station_id)
            ->with('success', 'Combustível criado com sucesso.');
    }

    public function show(Fuel $fuel)
    {
        $fuels = $fuel->fuels;

        return view('fuels.show', compact('fuel', 'fuels'));
    }

    public function edit(Fuel $fuel)
    {
        return view('fuels.edit', compact('fuel'));
    }

    public function update(Request $request, Fuel $fuel)
    {
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'name' => ['required', 'string', 'max:255'],
            'counter' => ['required', 'integer'],
            'capacity' => ['required', 'integer'],
            'is_active' => ['string'],
        ]);

        $fuel->name = $validated['name'];
        $fuel->counter = $validated['counter'];
        $fuel->capacity = $validated['capacity'];

        $fuel->is_active = $request->has('is_active');
        $fuel->save();

        return redirect()->route('stations.show', $validated['station_id'])
            ->with('success', 'Combustível atualizado com sucesso.');
    }

    public function destroy(Request $request, Fuel $fuel)
    {
        $fuel->delete();

        return redirect()->route('stations.show', $request->station_id)
            ->with('success', 'Combustível eliminado com sucesso.');
    }
}