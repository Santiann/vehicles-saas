<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class VehicleImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'path',
        'is_cover',
    ];

    protected $casts = [
        'is_cover' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function (VehicleImage $image) {
            // Remove the physical file when deleting the image record
            Storage::disk('public')->delete($image->path);
        });
    }

    /**
     * Get the vehicle that owns the image.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the full URL for the image.
     */
    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }

    /**
     * Set this image as the cover.
     */
    public function setAsCover(): void
    {
        // Remove cover status from all other images of this vehicle
        self::where('vehicle_id', $this->vehicle_id)
            ->where('id', '!=', $this->id)
            ->update(['is_cover' => false]);

        $this->is_cover = true;
        $this->save();
    }
}
