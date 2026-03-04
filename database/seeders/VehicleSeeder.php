<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create admin user
        $admin = User::where('email', 'admin@example.com')->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]);
        }

        // Get or create regular user
        $user = User::where('email', 'user@example.com')->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Regular User',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]);
        }

        // Create vehicles for admin
        $this->createVehiclesForUser($admin, 5);

        // Create vehicles for regular user
        $this->createVehiclesForUser($user, 5);

        $this->command->info('Veículos criados com sucesso!');
        $this->command->info('Faça upload de imagens manualmente através da interface.');
    }

    /**
     * Create vehicles for a user.
     */
    private function createVehiclesForUser(User $user, int $count): void
    {
        Vehicle::factory()
            ->count($count)
            ->create([
                'user_id' => $user->id,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
    }
}
