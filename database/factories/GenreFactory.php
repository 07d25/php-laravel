<?php

namespace Database\Factories;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * FACTORY (fábrica) del modelo Genre.
 *
 * ¿QUÉ ES? Una "fábrica" genera datos de prueba FALSOS pero realistas para un
 * modelo. En vez de escribir a mano cada género en los tests o seeders, pedís
 * "dame 10 géneros" y esta clase los arma solita.
 *
 * ¿QUÉ HACE? Su método definition() define la FORMA por defecto de un Genre:
 * qué campos tiene y con qué valores de ejemplo (nombre, descripción, slug...).
 *
 * ¿PARA QUÉ SIRVE?
 *  - En tests: crear datos rápido y sin depender de la BD real.
 *  - En seeders: poblar la base de datos con información de prueba.
 *
 * Se usa gracias al trait HasFactory que tiene el modelo Genre (guía 01):
 *
 *      Genre::factory()->count(10)->create();   // crea 10 géneros de mentira
 *      Genre::factory()->inactive()->create();  // crea uno inactivo (estado "inactive")
 *
 * Los datos falsos los inventa la librería "Faker" ($this->faker).
 *
 * @extends Factory<Genre>   ← le dice a PHP que esta fábrica crea modelos Genre
 */
class GenreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * Define los valores por defecto de cada género que genere esta fábrica.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Faker genera 3 palabras aleatorias únicas y las une en un string
        // (ej: "magnificent blue donut"). unique() evita que se repita el nombre.
        $name = $this->faker->unique()->words(3, true);

        return [
            'name' => $name,                              // nombre falso único
            'description' => $this->faker->sentence(),    // frase de relleno
            'slug' => Str::slug($name),                   // slug generado desde el nombre
            'is_active' => true,                          // activo por defecto
        ];
    }

    /**
     * Estado "inactive": marca el género como inactivo.
     *
     * ¿QUÉ ES un "state"? Es una VARIANTES que podés aplicar encima de los
     * valores por defecto. El método se llama como quieras y devuelve
     * $this->state([...]) con los campos a sobreescribir.
     *
     * Uso: Genre::factory()->inactive()->create();
     */
    public function inactive(): static
    {
        // Cambia solo el campo is_active a false; el resto mantiene los defaults.
        return $this->state(['is_active' => false]);
    }
}
