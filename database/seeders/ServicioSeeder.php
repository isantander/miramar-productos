<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servicio;
use Carbon\Carbon;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = [
            // TRASLADOS AEROPORTUARIOS
            [
                'codigo' => 'TRA-BRC01',
                'nombre' => 'Traslado Aeropuerto Bariloche - Centro',
                'descripcion' => 'Servicio de traslado privado desde/hacia Aeropuerto Teniente Luis Candelaria hasta el centro de San Carlos de Bariloche. Incluye conductor bilingüe y vehículo premium.',
                'destino' => 'Bariloche',
                'fecha' => Carbon::create(2025, 3, 15),
                'costo' => 8500.00
            ],
            [
                'codigo' => 'TRA-SMA01',
                'nombre' => 'Traslado Aeropuerto San Martín de los Andes',
                'descripcion' => 'Traslado ejecutivo desde Aeropuerto Aviador Carlos Campos hasta San Martín de los Andes. Servicio door-to-door con seguimiento en tiempo real.',
                'destino' => 'San Martín de los Andes',
                'fecha' => Carbon::create(2025, 4, 20),
                'costo' => 12000.00
            ],
            [
                'codigo' => 'TRA-CAL01',
                'nombre' => 'Traslado Aeropuerto El Calafate',
                'descripcion' => 'Servicio de traslado compartido desde Aeropuerto Comandante Armando Tola hasta El Calafate centro. Incluye wifi y agua mineral.',
                'destino' => 'El Calafate',
                'fecha' => Carbon::create(2025, 5, 10),
                'costo' => 7800.00
            ],

            // HOTELES BARILOCHE
            [
                'codigo' => 'HTL-BRC01',
                'nombre' => 'Hotel Llao Llao Resort - 3 noches',
                'descripcion' => 'Estadía de 3 noches en suite premium con vista al lago Nahuel Huapi. Incluye desayuno buffet, acceso al spa y actividades recreativas. Resort 5 estrellas.',
                'destino' => 'Bariloche',
                'fecha' => Carbon::create(2025, 3, 16),
                'costo' => 185000.00
            ],
            [
                'codigo' => 'HTL-BRC02',
                'nombre' => 'Hotel Austral Plaza - 2 noches',
                'descripcion' => 'Alojamiento céntrico de 2 noches en habitación superior. Ubicado en pleno centro comercial y gastronómico de Bariloche. Incluye desayuno continental.',
                'destino' => 'Bariloche',
                'fecha' => Carbon::create(2025, 3, 16),
                'costo' => 89000.00
            ],

            // HOTELES SAN MARTÍN DE LOS ANDES
            [
                'codigo' => 'HTL-SMA01',
                'nombre' => 'Hotel La Posada de Marisol - 3 noches',
                'descripción' => 'Boutique hotel de montaña con 3 noches en habitación deluxe. Vista panorámica al lago Lácar, spa con tratamientos patagónicos y cenas gourmet.',
                'destino' => 'San Martín de los Andes',
                'fecha' => Carbon::create(2025, 4, 21),
                'costo' => 167000.00
            ],
            [
                'codigo' => 'HTL-SMA02',
                'nombre' => 'Hotel Patagónico - 2 noches',
                'descripcion' => 'Hotel familiar de 2 noches con habitación standard. Céntrico, a 5 cuadras de la costanera del lago Lácar. Incluye desayuno y estacionamiento.',
                'destino' => 'San Martín de los Andes',
                'fecha' => Carbon::create(2025, 4, 21),
                'costo' => 78000.00
            ],

            // HOTELES EL CALAFATE
            [
                'codigo' => 'HTL-CAL01',
                'nombre' => 'Hotel Los Alamos - 3 noches',
                'descripcion' => 'Hotel premium de 3 noches en suite ejecutiva. Vista privilegiada al Lago Argentino, restaurante de cocina patagónica y servicio de concierge.',
                'destino' => 'El Calafate',
                'fecha' => Carbon::create(2025, 5, 11),
                'costo' => 142000.00
            ],
            [
                'codigo' => 'HTL-CAL02',
                'nombre' => 'Hotel Mirador del Lago - 2 noches',
                'descripcion' => 'Alojamiento confortable de 2 noches en habitación superior. Ubicación estratégica con vista al lago, desayuno regional y transfer gratuito al centro.',
                'destino' => 'El Calafate',
                'fecha' => Carbon::create(2025, 5, 11),
                'costo' => 96000.00
            ],

            // EXCURSIONES CON ALMUERZO
            [
                'codigo' => 'EXC-BRC01',
                'nombre' => 'Circuito Chico + Almuerzo en La Tablita',
                'descripcion' => 'Tour de día completo por el famoso Circuito Chico. Visita a Cerro Campanario, Villa La Angostura y Bahía López. Almuerzo gourmet de cordero patagónico en restaurante La Tablita con vista panorámica.',
                'destino' => 'Bariloche',
                'fecha' => Carbon::create(2025, 3, 18),
                'costo' => 45000.00
            ],
            [
                'codigo' => 'EXC-SMA01',
                'nombre' => 'Volcán Lanín + Almuerzo Mapuche',
                'descripcion' => 'Excursión de día completo al Volcán Lanín con trekking ligero. Visita a comunidad mapuche, almuerzo tradicional con cordero al asador y degustación de productos regionales.',
                'destino' => 'San Martín de los Andes',
                'fecha' => Carbon::create(2025, 4, 23),
                'costo' => 52000.00
            ],
            [
                'codigo' => 'EXC-CAL01',
                'nombre' => 'Glaciar Perito Moreno + Almuerzo Patagónico',
                'descripcion' => 'Tour completo al Glaciar Perito Moreno con navegación opcional. Almuerzo en restaurante La Leona con cordero patagónico, postre regional y vista al glaciar.',
                'destino' => 'El Calafate',
                'fecha' => Carbon::create(2025, 5, 13),
                'costo' => 67000.00
            ]
        ];

        foreach ($servicios as $servicio) {
            Servicio::create($servicio);
        }
    }
}