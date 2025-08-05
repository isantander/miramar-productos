<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Servicio;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Servicio>
 */
class ServicioFactory extends Factory
{
    protected $model = Servicio::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'codigo' => 'SRV-' . $this->faker->unique()->numberBetween(1000, 9999),
            'nombre' => $this->faker->randomElement([
                'Hotel ' . $this->faker->company,
                'Excursión a ' . $this->faker->city,
                'Traslado ' . $this->faker->city,
                'Pasaje ' . $this->faker->city
            ]),
            'descripcion' => $this->faker->sentence(10),
            'destino' => $this->faker->randomElement(['Bariloche', 'San Martín de los Andes', 'El Calafate']),
            'fecha' => Carbon::create(2025, 6, $this->faker->numberBetween(1, 30)),
            'costo' => $this->faker->numberBetween(10000, 200000),
        ];
    }
}