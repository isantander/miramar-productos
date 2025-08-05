<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Paquete;
use App\Models\Servicio;

class PaqueteSeeder extends Seeder
{
    public function run(): void
    {
        // PAQUETE BARILOCHE PREMIUM (Traslado + Hotel Premium + Excursión)
        $paqueteBarilocheVip = Paquete::create([
            'codigo' => 'PAQ-BRC-VIP',
            'nombre' => 'Bariloche VIP Experience'
        ]);

        $serviciosBarilocheVip = Servicio::whereIn('codigo', [
            'TRA-BRC01', // Traslado aeropuerto
            'HTL-BRC01', // Hotel Llao Llao 3 noches
            'EXC-BRC01'  // Circuito Chico + almuerzo
        ])->pluck('id');

        $paqueteBarilocheVip->servicios()->attach($serviciosBarilocheVip);

        // PAQUETE BARILOCHE ECONÓMICO (Traslado + Hotel Económico + Excursión)
        $paqueteBarilocheEco = Paquete::create([
            'codigo' => 'PAQ-BRC-ECO',
            'nombre' => 'Bariloche Escapada Familiar'
        ]);

        $serviciosBarilocheEco = Servicio::whereIn('codigo', [
            'TRA-BRC01', // Traslado aeropuerto
            'HTL-BRC02', // Hotel Austral Plaza 2 noches
            'EXC-BRC01'  // Circuito Chico + almuerzo
        ])->pluck('id');

        $paqueteBarilocheEco->servicios()->attach($serviciosBarilocheEco);

        // PAQUETE SAN MARTÍN DE LOS ANDES PREMIUM
        $paqueteSmaVip = Paquete::create([
            'codigo' => 'PAQ-SMA-VIP',
            'nombre' => 'San Martín Deluxe Adventure'
        ]);

        $serviciosSmaVip = Servicio::whereIn('codigo', [
            'TRA-SMA01', // Traslado aeropuerto
            'HTL-SMA01', // Hotel La Posada de Marisol 3 noches
            'EXC-SMA01'  // Volcán Lanín + almuerzo mapuche
        ])->pluck('id');

        $paqueteSmaVip->servicios()->attach($serviciosSmaVip);

        // PAQUETE SAN MARTÍN DE LOS ANDES ECONÓMICO
        $paqueteSmaEco = Paquete::create([
            'codigo' => 'PAQ-SMA-ECO',
            'nombre' => 'San Martín Aventura Natural'
        ]);

        $serviciosSmaEco = Servicio::whereIn('codigo', [
            'TRA-SMA01', // Traslado aeropuerto
            'HTL-SMA02', // Hotel Patagónico 2 noches
            'EXC-SMA01'  // Volcán Lanín + almuerzo mapuche
        ])->pluck('id');

        $paqueteSmaEco->servicios()->attach($serviciosSmaEco);

        // PAQUETE EL CALAFATE PREMIUM
        $paqueteCalVip = Paquete::create([
            'codigo' => 'PAQ-CAL-VIP',
            'nombre' => 'Calafate Glaciar Premium'
        ]);

        $serviciosCalVip = Servicio::whereIn('codigo', [
            'TRA-CAL01', // Traslado aeropuerto
            'HTL-CAL01', // Hotel Los Alamos 3 noches
            'EXC-CAL01'  // Glaciar Perito Moreno + almuerzo
        ])->pluck('id');

        $paqueteCalVip->servicios()->attach($serviciosCalVip);

        // PAQUETE EL CALAFATE ECONÓMICO
        $paqueteCalEco = Paquete::create([
            'codigo' => 'PAQ-CAL-ECO',
            'nombre' => 'Calafate Glaciar Discovery'
        ]);

        $serviciosCalEco = Servicio::whereIn('codigo', [
            'TRA-CAL01', // Traslado aeropuerto
            'HTL-CAL02', // Hotel Mirador del Lago 2 noches
            'EXC-CAL01'  // Glaciar Perito Moreno + almuerzo
        ])->pluck('id');

        $paqueteCalEco->servicios()->attach($serviciosCalEco);

        // PAQUETE PATAGONIA COMPLETA (Mega paquete multi-destino)
        $paquetePatagoniaCompleta = Paquete::create([
            'codigo' => 'PAQ-PAT-FULL',
            'nombre' => 'Patagonia Completa - Circuito de Lujo'
        ]);

        $serviciosPatagoniaCompleta = Servicio::whereIn('codigo', [
            'TRA-BRC01', // Traslado Bariloche
            'HTL-BRC01', // Hotel Llao Llao
            'EXC-BRC01', // Excursión Bariloche
            'TRA-CAL01', // Traslado Calafate
            'HTL-CAL01', // Hotel Los Alamos
            'EXC-CAL01'  // Excursión Calafate
        ])->pluck('id');

        $paquetePatagoniaCompleta->servicios()->attach($serviciosPatagoniaCompleta);
    }
}