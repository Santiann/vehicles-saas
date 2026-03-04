<?php

namespace App\Models;

use App\Enums\Cambio;
use App\Enums\Combustivel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'placa',
        'chassi',
        'marca',
        'modelo',
        'versao',
        'valor_venda',
        'cor',
        'km',
        'cambio',
        'combustivel',
        'user_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'valor_venda' => 'decimal:2',
        'km' => 'integer',
        'cambio' => Cambio::class,
        'combustivel' => Combustivel::class,
    ];

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Vehicle $vehicle) {
            if (auth()->check()) {
                $vehicle->user_id = $vehicle->user_id ?? auth()->id();
                $vehicle->created_by = auth()->id();
                $vehicle->updated_by = auth()->id();
            }
        });

        static::updating(function (Vehicle $vehicle) {
            if (auth()->check()) {
                $vehicle->updated_by = auth()->id();
            }
        });

        static::deleting(function (Vehicle $vehicle) {
            // Remove all images physically and from database
            foreach ($vehicle->images as $image) {
                Storage::disk('public')->delete($image->path);
            }
            // Delete the vehicle directory
            Storage::disk('public')->deleteDirectory("vehicles/{$vehicle->id}");
        });
    }

    /**
     * Get the owner of the vehicle.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who created the vehicle.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the vehicle.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get all images for the vehicle.
     */
    public function images(): HasMany
    {
        return $this->hasMany(VehicleImage::class);
    }

    /**
     * Get the cover image for the vehicle.
     */
    public function coverImage(): HasOne
    {
        return $this->hasOne(VehicleImage::class)->where('is_cover', true);
    }

    /**
     * Get the formatted plate.
     */
    public function getFormattedPlacaAttribute(): string
    {
        return strtoupper($this->placa);
    }

    /**
     * Get the formatted price.
     */
    public function getFormattedValorVendaAttribute(): string
    {
        return 'R$ ' . number_format($this->valor_venda, 2, ',', '.');
    }

    /**
     * Get the formatted km.
     */
    public function getFormattedKmAttribute(): string
    {
        return number_format($this->km, 0, ',', '.') . ' km';
    }

    /**
     * Scope a query to search vehicles.
     */
    public function scopeSearch($query, ?string $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('placa', 'like', "%{$search}%")
              ->orWhere('chassi', 'like', "%{$search}%")
              ->orWhere('marca', 'like', "%{$search}%")
              ->orWhere('modelo', 'like', "%{$search}%")
              ->orWhere('versao', 'like', "%{$search}%")
              ->orWhere('cor', 'like', "%{$search}%");
        });
    }

    /**
     * Scope a query to filter by brand.
     */
    public function scopeFilterByMarca($query, ?string $marca)
    {
        if (!$marca) {
            return $query;
        }

        return $query->where('marca', $marca);
    }

    /**
     * Scope a query to filter by model.
     */
    public function scopeFilterByModelo($query, ?string $modelo)
    {
        if (!$modelo) {
            return $query;
        }

        return $query->where('modelo', $modelo);
    }

    /**
     * Scope a query to filter by plate.
     */
    public function scopeFilterByPlaca($query, ?string $placa)
    {
        if (!$placa) {
            return $query;
        }

        return $query->where('placa', 'like', "%{$placa}%");
    }

    /**
     * Scope a query to order by a specific field.
     */
    public function scopeOrderByField($query, ?string $orderBy, string $direction = 'asc')
    {
        $allowedFields = ['km', 'valor_venda', 'created_at', 'marca', 'modelo'];

        if (!$orderBy || !in_array($orderBy, $allowedFields)) {
            return $query->orderBy('created_at', 'desc');
        }

        return $query->orderBy($orderBy, $direction);
    }
}
