<?php

use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

// ============================================================
//  CRUD de Géneros — probamos los 5 endpoints de la API.
//  Usamos el GenreFactory para crear datos de prueba rápido.
// ============================================================

it('lists all genres', function () {
    // La fábrica crea 3 géneros de mentira en la BD de prueba
    Genre::factory()->count(3)->create();

    $this->getJson('/api/v1/genres')
        ->assertOk()                                   // HTTP 200
        ->assertJsonCount(3, 'data')                   // vienen 3 en "data"
        ->assertJsonStructure([                        // la forma del JSON es correcta
            'data' => [
                ['id', 'name', 'slug', 'description', 'is_active', 'created_at', 'updated_at'],
            ],
        ]);
});

it('shows a genre by id', function () {
    // Creamos 1 género y guardamos sus datos para comparar después
    $genre = Genre::factory()->create();

    $this->getJson("/api/v1/genres/{$genre->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $genre->id)
        ->assertJsonPath('data.name', $genre->name)
        ->assertJsonPath('data.slug', $genre->slug);
});

it('returns 404 when the genre does not exist', function () {
    // No creamos nada: pedimos un id que no existe
    $this->getJson('/api/v1/genres/9999')
        ->assertNotFound();                            // HTTP 404
});

it('creates a genre', function () {
    $this->postJson('/api/v1/genres', [
        'name' => 'Acción',
        'description' => 'Películas de acción.',
        'is_active' => true,
    ])
        ->assertCreated()                              // HTTP 201 (Created)
        ->assertJsonPath('data.name', 'Acción')
        ->assertJsonPath('data.slug', 'accion')        // el slug se genera solo
        ->assertJsonPath('message', 'Género creado exitosamente.');

    // Comprobamos que quedó guardado en la base de datos
    $this->assertDatabaseHas('genres', [
        'name' => 'Acción',
        'slug' => 'accion',
    ]);
});

it('validates the name is required when creating', function () {
    // Enviamos un request sin "name" → debe fallar la validación
    $this->postJson('/api/v1/genres', ['description' => 'Sin nombre'])
        ->assertUnprocessable()                        // HTTP 422
        ->assertJsonValidationErrors('name');          // el error menciona "name"
});

it('validates the name is unique when creating', function () {
    Genre::factory()->create(['name' => 'Drama']);

    // Intentamos crear otro con el mismo nombre → 422 por duplicado
    $this->postJson('/api/v1/genres', ['name' => 'Drama'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('name');
});

it('updates a genre', function () {
    $genre = Genre::factory()->create();

    $this->putJson("/api/v1/genres/{$genre->id}", [
        'name' => 'Horror',
        'description' => 'Películas de horror.',
        'is_active' => false,
    ])
        ->assertOk()                                  // HTTP 200
        ->assertJsonPath('data.name', 'Horror')
        ->assertJsonPath('data.slug', 'horror')       // el slug se regenera
        ->assertJsonPath('message', 'Genero actualizado exitosamente.');

    // Verificamos los cambios en la BD
    $this->assertDatabaseHas('genres', [
        'id' => $genre->id,
        'name' => 'Horror',
        'slug' => 'horror',
        'is_active' => false,
    ]);
});

it('allows a genre to keep its own name when updating', function () {
    // Es el caso tricky del unique: al editar SIN cambiar el nombre,
    // no debe fallar por "duplicado" (el UpdateGenreRequest ignora el propio id).
    $genre = Genre::factory()->create(['name' => 'Terror']);

    $this->putJson("/api/v1/genres/{$genre->id}", [
        'name' => 'Terror',                           // mismo nombre
        'description' => 'Actualizado sin renombrar.',
    ])
        ->assertOk();
});

it('deletes a genre', function () {
    // El género usa SoftDeletes, así que NO se borra de verdad
    $genre = Genre::factory()->create();

    $this->deleteJson("/api/v1/genres/{$genre->id}")
        ->assertOk()
        ->assertJsonPath('message', 'Género eliminado exitosamente.');

    // Comprobamos que quedó marcado como borrado (deleted_at), no eliminado
    $this->assertSoftDeleted('genres', ['id' => $genre->id]);
});
