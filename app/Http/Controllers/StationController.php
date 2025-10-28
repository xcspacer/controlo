<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\User;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function index()
    {
        $stations = Station::with('users')->latest()->paginate(10);
        return view('stations.index', compact('stations'));
    }

    public function create()
    {
        $users = User::all();

        return view('stations.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'group' => ['required', 'numeric'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['exists:users,id'],
        ]);

        $station = Station::create([
            'name' => $validated['name'],
            'group' => $validated['group'],
            'address' => $validated['address'],
            'city' => $validated['city'],
        ]);

        $station->users()->sync($validated['user_ids']);

        return redirect()->route('stations.index')
            ->with('success', 'Posto criado com sucesso.');
    }

    public function show(Station $station)
    {
        $fuels = $station->fuels;

        return view('stations.show', compact('station', 'fuels'));
    }

    public function edit(Station $station)
    {
        $users = User::all();
        $selectedUsers = $station->users->pluck('id')->toArray();

        return view('stations.edit', compact('station', 'users', 'selectedUsers'));
    }

    public function update(Request $request, Station $station)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'group' => ['required', 'numeric'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['exists:users,id'],
            'is_active' => ['string'],
        ]);

        $station->name = $validated['name'];
        $station->group = $validated['group'];
        $station->address = $validated['address'];
        $station->city = $validated['city'];
        $station->is_active = $request->has('is_active');
        $station->save();

        $station->users()->sync($validated['user_ids']);

        return redirect()->route('stations.index')
            ->with('success', 'Posto atualizado com sucesso.');
    }

    public function destroy(Station $station)
    {
        $station->delete();

        return redirect()->route('stations.index')
            ->with('success', 'Posto eliminado com sucesso.');
    }
}