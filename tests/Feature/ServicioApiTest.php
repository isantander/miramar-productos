<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Servicio;
use Carbon\Carbon;

class ServicioApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_servicio_con_todos_los_campos_requeridos()
    {
        $servicioData = [
            'codigo' => 'TUR-2025',
            'nombre' => 'Excursión Test Bariloche',
            'descripcion' => 'Excursión de prueba para testing automatizado',
            'destino' => 'Bariloche',
            'fecha' => '2025-06-15',
            'costo' => 25000.50
        ];

        $response = $this->postJson('/api/servicios', $servicioData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'codigo',
                        'nombre',
                        'descripcion',
                        'destino',
                        'fecha',
                        'costo'
                    ]
                ]);

        $this->assertDatabaseHas('servicios', [
            'codigo' => 'TUR-2025',
            'nombre' => 'Excursión Test Bariloche',
            'costo' => 25000.50
        ]);
    }

    /** @test */
    public function no_puede_crear_servicio_con_codigo_duplicado()
    {
        // Crear servicio inicial
        Servicio::create([
            'codigo' => 'DUP-001',
            'nombre' => 'Servicio Existente',
            'descripcion' => 'Descripción del servicio existente',
            'destino' => 'Bariloche',
            'fecha' => Carbon::create(2025, 6, 15),
            'costo' => 15000
        ]);

        $servicioData = [
            'codigo' => 'DUP-001', // Mismo código
            'nombre' => 'Servicio Duplicado',
            'descripcion' => 'Intento de duplicar código',
            'destino' => 'Calafate',
            'fecha' => '2025-07-20',
            'costo' => 20000
        ];

        $response = $this->postJson('/api/servicios', $servicioData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['codigo']);
    }

    /** @test */
    public function puede_listar_todos_los_servicios()
    {
        Servicio::create([
            'codigo' => 'LST-001',
            'nombre' => 'Servicio Lista 1',
            'descripcion' => 'Descripción 1',
            'destino' => 'Bariloche',
            'fecha' => Carbon::create(2025, 6, 15),
            'costo' => 50000
        ]);

        Servicio::create([
            'codigo' => 'LST-002',
            'nombre' => 'Servicio Lista 2',
            'descripcion' => 'Descripción 2',
            'destino' => 'Calafate',
            'fecha' => Carbon::create(2025, 7, 15),
            'costo' => 75000
        ]);

        Servicio::create([
            'codigo' => 'LST-003',
            'nombre' => 'Servicio Lista 3',
            'descripcion' => 'Descripción 3',
            'destino' => 'San Martín',
            'fecha' => Carbon::create(2025, 8, 15),
            'costo' => 60000
        ]);

        $response = $this->getJson('/api/servicios');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'codigo',
                            'nombre',
                            'descripcion',
                            'destino',
                            'fecha',
                            'costo'
                        ]
                    ]
                ]);
    }

    /** @test */
    public function puede_obtener_servicio_por_id()
    {
        $servicio = Servicio::create([
            'codigo' => 'GET-001',
            'nombre' => 'Servicio Para Obtener',
            'descripcion' => 'Test de obtención por ID',
            'destino' => 'San Martín de los Andes',
            'fecha' => Carbon::create(2025, 8, 10),
            'costo' => 35000
        ]);

        $response = $this->getJson("/api/servicios/{$servicio->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'id' => $servicio->id,
                        'codigo' => 'GET-001',
                        'nombre' => 'Servicio Para Obtener'
                    ]
                ]);
    }

    /** @test */
    public function retorna_404_al_buscar_servicio_inexistente()
    {
        $response = $this->getJson('/api/servicios/999');

        $response->assertStatus(404)
                ->assertJson(['error' => 'Servicio no encontrado']);
    }

    /** @test */
    public function puede_actualizar_servicio_existente()
    {
        $servicio = Servicio::create([
            'codigo' => 'UPD-001',
            'nombre' => 'Servicio Original',
            'descripcion' => 'Descripción original',
            'destino' => 'Bariloche',
            'fecha' => Carbon::create(2025, 6, 15),
            'costo' => 20000
        ]);

        $datosActualizados = [
            'id' => $servicio->id, // Requerido por especificación TP
            'codigo' => 'UPD-001',
            'nombre' => 'Servicio Actualizado',
            'descripcion' => 'Descripción actualizada mediante test',
            'destino' => 'Calafate',
            'fecha' => '2025-09-20',
            'costo' => 28000
        ];

        $response = $this->putJson("/api/servicios/{$servicio->id}", $datosActualizados);

        $response->assertStatus(200);

        $this->assertDatabaseHas('servicios', [
            'id' => $servicio->id,
            'nombre' => 'Servicio Actualizado',
            'destino' => 'Calafate',
            'costo' => 28000
        ]);
    }

    /** @test */
    public function no_puede_actualizar_con_id_inconsistente()
    {
        $servicio = Servicio::create([
            'codigo' => 'INC-001',
            'nombre' => 'Servicio Test',
            'descripcion' => 'Test de inconsistencia',
            'destino' => 'Bariloche',
            'fecha' => Carbon::create(2025, 6, 15),
            'costo' => 15000
        ]);

        $datosInconsistentes = [
            'id' => 999, // ID diferente al de la URL
            'codigo' => 'INC-001',
            'nombre' => 'Intento Inconsistente',
            'descripcion' => 'Esto debería fallar',
            'destino' => 'Calafate',
            'fecha' => '2025-09-20',
            'costo' => 20000
        ];

        $response = $this->putJson("/api/servicios/{$servicio->id}", $datosInconsistentes);

        $response->assertStatus(400);
    }

    /** @test */
    public function puede_eliminar_servicio_soft_delete()
    {
        $servicio = Servicio::create([
            'codigo' => 'DEL-001',
            'nombre' => 'Servicio Para Eliminar',
            'descripcion' => 'Test de eliminación',
            'destino' => 'Bariloche',
            'fecha' => Carbon::create(2025, 6, 15),
            'costo' => 18000
        ]);

        $response = $this->deleteJson("/api/servicios/{$servicio->id}");

        $response->assertStatus(204);

        // Verificar soft delete
        $this->assertSoftDeleted('servicios', ['id' => $servicio->id]);
    }

    /** @test */
    public function valida_campos_requeridos()
    {
        $response = $this->postJson('/api/servicios', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'codigo',
                    'nombre', 
                    'descripcion',
                    'destino',
                    'fecha',
                    'costo'
                ]);
    }

    /** @test */
    public function valida_formato_de_fecha()
    {
        $servicioData = [
            'codigo' => 'DATE-001',
            'nombre' => 'Test Fecha',
            'descripcion' => 'Test de validación de fecha',
            'destino' => 'Bariloche',
            'fecha' => 'fecha-invalida',
            'costo' => 15000
        ];

        $response = $this->postJson('/api/servicios', $servicioData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['fecha']);
    }

    /** @test */
    public function valida_costo_numerico_positivo()
    {
        $servicioData = [
            'codigo' => 'COST-001',
            'nombre' => 'Test Costo',
            'descripcion' => 'Test de validación de costo',
            'destino' => 'Bariloche',
            'fecha' => '2025-06-15',
            'costo' => -500 // Costo negativo
        ];

        $response = $this->postJson('/api/servicios', $servicioData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['costo']);
    }
}