<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Primero crear servicios, luego paquetes (que dependen de servicios)
        $this->call([
            ServicioSeeder::class,
            PaqueteSeeder::class,
        ]);

        $this->command->info('✅ Microservicio de productos poblado exitosamente');
        $this->command->info('📊 Servicios creados: 11 (3 traslados + 6 hoteles + 2 excursiones)');
        $this->command->info('📦 Paquetes creados: 7 (6 destinos específicos + 1 multi-destino)');
    }
}
