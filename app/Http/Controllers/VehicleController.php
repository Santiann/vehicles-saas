<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Vehicle::with(['coverImage', 'user']);

        // Global search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by marca
        if ($request->filled('marca')) {
            $query->filterByMarca($request->marca);
        }

        // Filter by modelo
        if ($request->filled('modelo')) {
            $query->filterByModelo($request->modelo);
        }

        // Filter by placa
        if ($request->filled('placa')) {
            $query->filterByPlaca($request->placa);
        }

        // Order by field
        $orderBy = $request->get('order_by', 'created_at');
        $orderDirection = $request->get('order_direction', 'desc');
        $query->orderByField($orderBy, $orderDirection);

        $vehicles = $query->paginate(12)->withQueryString();

        // Get unique brands and models for filters
        $marcas = Vehicle::select('marca')->distinct()->orderBy('marca')->pluck('marca');
        $modelos = Vehicle::select('modelo')->distinct()->orderBy('modelo')->pluck('modelo');

        return view('vehicles.index', compact('vehicles', 'marcas', 'modelos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('vehicles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleRequest $request): RedirectResponse
    {
        $vehicle = Vehicle::create($request->validated());

        return redirect()
            ->route('vehicles.show', $vehicle)
            ->with('success', 'Veículo cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle): View
    {
        $vehicle->load(['images', 'coverImage', 'user', 'creator', 'updater']);

        return view('vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle): View
    {
        Gate::authorize('update', $vehicle);

        $vehicle->load(['images', 'coverImage']);

        return view('vehicles.edit', compact('vehicle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle): RedirectResponse
    {
        Gate::authorize('update', $vehicle);

        $vehicle->update($request->validated());

        return redirect()
            ->route('vehicles.show', $vehicle)
            ->with('success', 'Veículo atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        Gate::authorize('delete', $vehicle);

        $vehicle->delete();

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Veículo excluído com sucesso!');
    }
}
