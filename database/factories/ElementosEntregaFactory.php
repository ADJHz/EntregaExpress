<?php

namespace Database\Factories;

use App\Models\ElementosEntrega;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ElementosEntrega>
 */
class ElementosEntregaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->name(),
            'csp' => fake()->numerify('#########'),
            'ubicacion' => fake()->city(),
            'coordinacion' => fake()->sentence(2),
            'genero' => fake()->randomElement(['Femenino', 'Masculino']),
            'tipo_uniforme' => 'Administrativo',
            'color_franja' => null,
            'camisola' => fake()->randomElement(['CH', 'M', 'G']),
            'pantalon' => fake()->randomElement(['28', '30', '32']),
            'chamarra' => fake()->randomElement(['CH', 'M', 'G']),
            'bota' => '26',
            'cinturon' => 'M',
            'recibio' => false,
        ];
    }
}
