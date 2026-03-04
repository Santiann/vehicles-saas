<?php

namespace App\Enums;

enum Combustivel: string
{
    case GASOLINA = 'gasolina';
    case ALCOOL = 'alcool';
    case FLEX = 'flex';
    case DIESEL = 'diesel';
    case HIBRIDO = 'hibrido';
    case ELETRICO = 'eletrico';

    public function label(): string
    {
        return match($this) {
            self::GASOLINA => 'Gasolina',
            self::ALCOOL => 'Álcool',
            self::FLEX => 'Flex',
            self::DIESEL => 'Diesel',
            self::HIBRIDO => 'Híbrido',
            self::ELETRICO => 'Elétrico',
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
