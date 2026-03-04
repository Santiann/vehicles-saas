<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleImageRequest;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class VehicleImageController extends Controller
{
    /**
     * Store newly created images in storage.
     */
    public function store(StoreVehicleImageRequest $request, Vehicle $vehicle): RedirectResponse
    {
        Gate::authorize('manageImages', $vehicle);

        $images = $request->file('images');
        $isFirstImage = $vehicle->images()->count() === 0;

        foreach ($images as $index => $image) {
            $path = $image->store("vehicles/{$vehicle->id}", 'public');

            $vehicleImage = $vehicle->images()->create([
                'path' => $path,
                'is_cover' => $isFirstImage && $index === 0, // First image is cover if no images exist
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Imagem(ns) adicionada(s) com sucesso!');
    }

    /**
     * Set an image as the cover.
     */
    public function setCover(Vehicle $vehicle, VehicleImage $image): RedirectResponse
    {
        Gate::authorize('manageImages', $vehicle);

        // Verify the image belongs to this vehicle
        if ($image->vehicle_id !== $vehicle->id) {
            abort(404);
        }

        $image->setAsCover();

        return redirect()
            ->back()
            ->with('success', 'Imagem de capa definida com sucesso!');
    }

    /**
     * Remove the specified image from storage.
     */
    public function destroy(Vehicle $vehicle, VehicleImage $image): RedirectResponse
    {
        Gate::authorize('manageImages', $vehicle);

        // Verify the image belongs to this vehicle
        if ($image->vehicle_id !== $vehicle->id) {
            abort(404);
        }

        $wasCover = $image->is_cover;
        $image->delete();

        // If deleted image was cover, set first remaining image as cover
        if ($wasCover) {
            $firstImage = $vehicle->images()->first();
            if ($firstImage) {
                $firstImage->setAsCover();
            }
        }

        return redirect()
            ->back()
            ->with('success', 'Imagem removida com sucesso!');
    }
}
