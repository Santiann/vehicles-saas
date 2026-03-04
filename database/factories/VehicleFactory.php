<?php

namespace Database\Factories;

use App\Enums\Cambio;
use App\Enums\Combustivel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $marcas = [
            'Toyota' => ['Corolla', 'Hilux', 'Yaris', 'Camry', 'RAV4'],
            'Honda' => ['Civic', 'HR-V', 'CR-V', 'Fit', 'City'],
            'Volkswagen' => ['Gol', 'Polo', 'T-Cross', 'Virtus', 'Jetta'],
            'Chevrolet' => ['Onix', 'Tracker', 'S10', 'Spin', 'Cruze'],
            'Ford' => ['Ka', 'EcoSport', 'Ranger', 'Territory', 'Bronco'],
            'Hyundai' => ['HB20', 'Creta', 'Tucson', 'Santa Fe', 'i30'],
            'Fiat' => ['Argo', 'Cronos', 'Strada', 'Toro', 'Mobi'],
            'Jeep' => ['Renegade', 'Compass', 'Commander', 'Gladiator'],
            'Nissan' => ['Kicks', 'Versa', 'Sentra', 'Frontier', 'March'],
            'Renault' => ['Kwid', 'Sandero', 'Logan', 'Duster', 'Captur'],
        ];

        $versoes = ['1.0', '1.4', '1.6', '1.8', '2.0', 'S', 'SE', 'SEL', 'XE', 'XEi', 'LX', 'EX', 'EXL', 'Sport', 'Limited'];
        $cores = ['Branco', 'Prata', 'Preto', 'Cinza', 'Vermelho', 'Azul', 'Verde', 'Bege', 'Marrom'];

        $marca = fake()->randomElement(array_keys($marcas));
        $modelo = fake()->randomElement($marcas[$marca]);

        return [
            'user_id' => User::factory(),
            'placa' => $this->generatePlaca(),
            'chassi' => $this->generateChassi(),
            'marca' => $marca,
            'modelo' => $modelo,
            'versao' => fake()->randomElement($versoes),
            'valor_venda' => fake()->randomFloat(2, 25000, 350000),
            'cor' => fake()->randomElement($cores),
            'km' => fake()->numberBetween(0, 200000),
            'cambio' => fake()->randomElement(Cambio::cases()),
            'combustivel' => fake()->randomElement(Combustivel::cases()),
        ];
    }

    /**
     * Generate a valid Mercosul format plate.
     */
    private function generatePlaca(): string
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $digits = '0123456789';
        $alphanumeric = $letters . $digits;

        return fake()->randomElement(str_split($letters)) .
               fake()->randomElement(str_split($letters)) .
               fake()->randomElement(str_split($letters)) .
               fake()->randomElement(str_split($digits)) .
               fake()->randomElement(str_split($alphanumeric)) .
               fake()->randomElement(str_split($digits)) .
               fake()->randomElement(str_split($digits));
    }

    /**
     * Generate a valid chassi (17 chars without I, O, Q).
     */
    private function generateChassi(): string
    {
        $chars = 'ABCDEFGHJKLMNPRSTUVWXYZ0123456789';
        $chassi = '';
        
        for ($i = 0; $i < 17; $i++) {
            $chassi .= fake()->randomElement(str_split($chars));
        }
        
        return $chassi;
    }
}
