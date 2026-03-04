<?php

namespace App\Enums;

enum Cambio: string
{
    case MANUAL = 'manual';
    case AUTOMATICO = 'automatico';

    public function label(): string
    {
        return match($this) {
            self::MANUAL => 'Manual',
            self::AUTOMATICO => 'Automático',
        };
    }

    public static function options(): array
    {
        return array_map(
            fn($case) => ['value' => $case->value, 'label' => $case->label()],
            self::cases()
        );
    }
}
