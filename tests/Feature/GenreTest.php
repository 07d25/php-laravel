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

// ============================================================
//  Filtros — GET /api/v1/genres?search=...&is_active=...
//  El repositorio arma la query en filter().
// ============================================================

it('filters genres by search term', function () {
    Genre::factory()->create(['name' => 'Acción']);
    Genre::factory()->create(['name' => 'Comedia']);

    // Búsqueda parcial: "acci" solo debe traer "Acción"
    $this->getJson('/api/v1/genres?search=acci')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Acción');

    // La búsqueda ignora mayúsculas/minúsculas
    $this->getJson('/api/v1/genres?search=ACCI')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Acción');
});

it('filters genres by is_active', function () {
    Genre::factory()->create(['name' => 'Activo']);
    Genre::factory()->inactive()->create(['name' => 'Inactivo']);

    $this->getJson('/api/v1/genres?is_active=true')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Activo');

    $this->getJson('/api/v1/genres?is_active=false')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Inactivo');
});

it('combines search and is_active filters', function () {
    Genre::factory()->create(['name' => 'Acción Activa']);
    Genre::factory()->inactive()->create(['name' => 'Acción Inactiva']);
    Genre::factory()->create(['name' => 'Comedia']);

    $this->getJson('/api/v1/genres?search=acci&is_active=true')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Acción Activa');
});

// ============================================================
//  Ordenamiento — GET /api/v1/genres?sort_by=...&order=...
//  Solo se permiten name, created_at e is_active (si no, cae a name).
// ============================================================

it('sorts genres by name ascending by default', function () {
    Genre::factory()->create(['name' => 'Zeta']);
    Genre::factory()->create(['name' => 'Alfa']);

    $names = $this->getJson('/api/v1/genres')
        ->assertOk()
        ->json('data.*.name');

    expect($names)->toBe(['Alfa', 'Zeta']);
});

it('sorts genres by name descending', function () {
    Genre::factory()->create(['name' => 'Alfa']);
    Genre::factory()->create(['name' => 'Zeta']);

    $names = $this->getJson('/api/v1/genres?sort_by=name&order=desc')
        ->assertOk()
        ->json('data.*.name');

    expect($names)->toBe(['Zeta', 'Alfa']);
});

it('sorts genres by created_at descending', function () {
    Genre::factory()->create(['name' => 'Viejo', 'created_at' => now()->subDay()]);
    Genre::factory()->create(['name' => 'Nuevo']);

    $names = $this->getJson('/api/v1/genres?sort_by=created_at&order=desc')
        ->assertOk()
        ->json('data.*.name');

    expect($names)->toBe(['Nuevo', 'Viejo']);
});

it('falls back to name sorting when sort_by is invalid', function () {
    Genre::factory()->create(['name' => 'Zeta']);
    Genre::factory()->create(['name' => 'Alfa']);

    $names = $this->getJson('/api/v1/genres?sort_by=no-existe')
        ->assertOk()
        ->json('data.*.name');

    expect($names)->toBe(['Alfa', 'Zeta']);
});

it('falls back to ascending order when order is invalid', function () {
    Genre::factory()->create(['name' => 'Zeta']);
    Genre::factory()->create(['name' => 'Alfa']);

    $names = $this->getJson('/api/v1/genres?order=random')
        ->assertOk()
        ->json('data.*.name');

    expect($names)->toBe(['Alfa', 'Zeta']);
});

// ============================================================
//  Slug — GET /api/v1/genres/slug/{slug}
//  El slug se genera desde el nombre (incluye acentos).
// ============================================================

it('shows a genre by slug', function () {
    // Lo creamos por la API para que el slug se genere desde el name
    $this->postJson('/api/v1/genres', ['name' => 'Ciencia Ficción'])
        ->assertCreated()
        ->assertJsonPath('data.slug', 'ciencia-ficcion');

    $this->getJson('/api/v1/genres/slug/ciencia-ficcion')
        ->assertOk()
        ->assertJsonPath('data.name', 'Ciencia Ficción')
        ->assertJsonPath('data.slug', 'ciencia-ficcion');
});

it('returns 404 when the slug does not exist', function () {
    $this->getJson('/api/v1/genres/slug/no-existe')
        ->assertNotFound();
});

it('does not find a soft deleted genre by slug', function () {
    $genre = Genre::factory()->create(['name' => 'Fantasía', 'slug' => 'fantasia']);
    $genre->delete();

    // Al estar borrado (soft delete), el slug ya no debe encontrarse
    $this->getJson('/api/v1/genres/slug/fantasia')
        ->assertNotFound();
});

// ============================================================
//  Restore — POST /api/v1/genres/{id}/restore
//  Recupera un género borrado con soft delete.
// ============================================================

it('restores a soft deleted genre', function () {
    $genre = Genre::factory()->create(['name' => 'Drama']);
    $genre->delete();
    $this->assertSoftDeleted('genres', ['id' => $genre->id]);

    $this->postJson("/api/v1/genres/{$genre->id}/restore")
        ->assertOk()
        ->assertJsonPath('message', 'Género restaurado exitosamente.');

    // Ya no está marcado como borrado
    $this->assertNotSoftDeleted('genres', ['id' => $genre->id]);
});

it('returns 404 when restoring a genre that does not exist', function () {
    $this->postJson('/api/v1/genres/9999/restore')
        ->assertNotFound();
});
