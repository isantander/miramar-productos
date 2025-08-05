<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Paquete;
use App\Models\Servicio;
use Carbon\Carbon;

class PaqueteApiTest extends TestCase
{
    use RefreshDatabase;

    private function crearServiciosBase(): array
    {
        $servicio1 = Servicio::create([
            'codigo' => 'SRV-001',
            'nombre' => 'Hotel Test',
            'descripcion' => 'Hotel para testing',
            'destino' => 'Bariloche',
            'fecha' => Carbon::create(2025, 6, 15),
            'costo' => 50000
        ]);

        $servicio2 = Servicio::create([
            'codigo' => 'SRV-002',
            'nombre' => 'Excursión Test',
            'descripcion' => 'Excursión para testing',
            'destino' => 'Bariloche',
            'fecha' => Carbon::create(2025, 6, 16),
            'costo' => 30000
        ]);

        $servicio3 = Servicio::create([
            'codigo' => 'SRV-003',
            'nombre' => 'Traslado Test',
            'descripcion' => 'Traslado para testing',
            'destino' => 'Bariloche',
            'fecha' => Carbon::create(2025, 6, 17),
            'costo' => 20000
        ]);

        return [$servicio1, $servicio2, $servicio3];
    }

    /** @test */
    public function puede_crear_paquete_con_minimo_dos_servicios()
    {
        [$servicio1, $servicio2] = $this->crearServiciosBase();

        $paqueteData = [
            'servicios' => [$servicio1->id, $servicio2->id]
        ];

        $response = $this->postJson('/api/paquetes', $paqueteData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'servicios',
                        'precio_calculado',
                        'cantidad_servicios'
                    ]
                ]);

        // Verificar que se creó el paquete
        $paquete = Paquete::latest()->first();
        $this->assertCount(2, $paquete->servicios);
    }

    /** @test */
    public function calcula_correctamente_descuento_diez_porciento()
    {
        [$servicio1, $servicio2, $servicio3] = $this->crearServiciosBase();
        
        // Costo total: 50000 + 30000 + 20000 = 100000
        // Con descuento 10%: 100000 * 0.9 = 90000
        
        $paqueteData = [
            'servicios' => [$servicio1->id, $servicio2->id, $servicio3->id]
        ];

        $response = $this->postJson('/api/paquetes', $paqueteData);

        $response->assertStatus(201);
        
        $paquete = Paquete::latest()->first();
        $precioCalculado = (float) $paquete->precio_calculado;
        
        $this->assertEquals(90000.00, $precioCalculado);
    }

    /** @test */
    public function no_puede_crear_paquete_con_un_solo_servicio()
    {
        [$servicio1] = $this->crearServiciosBase();

        $paqueteData = [
            'servicios' => [$servicio1->id]
        ];

        $response = $this->postJson('/api/paquetes', $paqueteData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['servicios']);
    }

    /** @test */
    public function no_puede_crear_paquete_con_servicios_inexistentes()
    {
        $paqueteData = [
            'servicios' => [999, 998] // IDs que no existen
        ];

        $response = $this->postJson('/api/paquetes', $paqueteData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['servicios.0', 'servicios.1']);
    }

    /** @test */
    public function puede_listar_paquetes_con_servicios_relacionados()
    {
        [$servicio1, $servicio2] = $this->crearServiciosBase();
        
        $paquete = Paquete::create([]);
        $paquete->servicios()->attach([$servicio1->id, $servicio2->id]);

        $response = $this->getJson('/api/paquetes');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'servicios',
                            'precio_calculado',
                            'cantidad_servicios'
                        ]
                    ]
                ]);
    }

    /** @test */
    public function puede_obtener_paquete_por_id_con_servicios()
    {
        [$servicio1, $servicio2] = $this->crearServiciosBase();
        
        $paquete = Paquete::create([]);
        $paquete->servicios()->attach([$servicio1->id, $servicio2->id]);

        $response = $this->getJson("/api/paquetes/{$paquete->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'id' => $paquete->id
                    ]
                ])
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'servicios' => [
                            '*' => [
                                'id',
                                'codigo',
                                'nombre',
                                'costo'
                            ]
                        ],
                        'precio_calculado'
                    ]
                ]);
    }

    /** @test */
    public function puede_actualizar_servicios_de_paquete()
    {
        [$servicio1, $servicio2, $servicio3] = $this->crearServiciosBase();
        
        $paquete = Paquete::create([]);
        $paquete->servicios()->attach([$servicio1->id, $servicio2->id]);

        // Actualizar para incluir servicio3 en lugar de servicio1
        $datosActualizados = [
            'servicios' => [$servicio2->id, $servicio3->id]
        ];

        $response = $this->putJson("/api/paquetes/{$paquete->id}", $datosActualizados);

        $response->assertStatus(200);

        $paquete->refresh();
        $serviciosIds = $paquete->servicios->pluck('id')->toArray();
        
        $this->assertContains($servicio2->id, $serviciosIds);
        $this->assertContains($servicio3->id, $serviciosIds);
        $this->assertNotContains($servicio1->id, $serviciosIds);
    }

    /** @test */
    public function puede_eliminar_paquete_con_soft_delete()
    {
        [$servicio1, $servicio2] = $this->crearServiciosBase();
        
        $paquete = Paquete::create([]);
        $paquete->servicios()->attach([$servicio1->id, $servicio2->id]);

        $response = $this->deleteJson("/api/paquetes/{$paquete->id}");

        $response->assertStatus(204);

        // Verificar soft delete
        $this->assertSoftDeleted('paquetes', ['id' => $paquete->id]);
        
        // Verificar que se desasociaron los servicios
        $this->assertDatabaseMissing('paquete_servicio', [
            'paquete_id' => $paquete->id
        ]);
    }

    /** @test */
    public function retorna_404_para_paquete_inexistente()
    {
        $response = $this->getJson('/api/paquetes/999');

        $response->assertStatus(404)
                ->assertJson(['error' => 'Paquete no encontrado']);
    }

    /** @test */
    public function valida_array_de_servicios_requerido()
    {
        $response = $this->postJson('/api/paquetes', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['servicios']);
    }

    /** @test */
    public function precio_calculado_se_actualiza_automaticamente()
    {
        [$servicio1, $servicio2] = $this->crearServiciosBase();
        
        $paquete = Paquete::create([]);
        $paquete->servicios()->attach([$servicio1->id, $servicio2->id]);

        // Costo: 50000 + 30000 = 80000
        // Con descuento: 80000 * 0.9 = 72000
        $paquete->refresh();
        
        $this->assertEquals('72000.00', $paquete->precio_calculado);
    }

    /** @test */
    public function puede_crear_paquete_sin_codigo_y_nombre_opcionales()
    {
        [$servicio1, $servicio2] = $this->crearServiciosBase();

        $paqueteData = [
            'servicios' => [$servicio1->id, $servicio2->id]
            // Sin código ni nombre (opcionales según especificación)
        ];

        $response = $this->postJson('/api/paquetes', $paqueteData);

        $response->assertStatus(201);
        
        // Verificar que se creó el paquete
        $paquete = Paquete::latest()->first();
        $this->assertNotNull($paquete);
        $this->assertCount(2, $paquete->servicios);
    }
}