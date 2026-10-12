<?php

use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('lists all genres', function () {
    Genre::create([
        'name' => 'Drama',
        'description' => 'Historias dramáticas.',
        'is_active' => true,
    ]);

    $this->getJson('/api/v1/genres')
        ->assertOk()
        ->assertJsonPath('data.0.name', 'Drama')
        ->assertJsonPath('data.0.slug', 'drama');
});

it('creates a genre', function () {
    $this->postJson('/api/v1/genres', [
        'name' => 'Acción',
        'description' => 'Películas de acción.',
        'is_active' => true,
    ])
        ->assertCreated()
        ->assertJsonPath('data.name', 'Acción')
        ->assertJsonPath('data.slug', 'accion');

    $this->assertDatabaseHas('genres', [
        'name' => 'Acción',
        'slug' => 'accion',
    ]);
});

it('shows a genre by id', function () {
    $genre = Genre::create(['name' => 'Comedia']);

    $this->getJson("/api/v1/genres/{$genre->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $genre->id)
        ->assertJsonPath('data.name', 'Comedia');
});

it('updates a genre', function () {
    $genre = Genre::create(['name' => 'Terror']);

    $this->putJson("/api/v1/genres/{$genre->id}", [
        'name' => 'Horror',
        'description' => 'Películas de horror.',
        'is_active' => false,
    ])
        ->assertOk()
        ->assertJsonPath('data.name', 'Horror')
        ->assertJsonPath('data.slug', 'horror');

    $this->assertDatabaseHas('genres', [
        'id' => $genre->id,
        'name' => 'Horror',
        'slug' => 'horror',
        'is_active' => false,
    ]);
});

it('deletes a genre', function () {
    $genre = Genre::create(['name' => 'Musical']);

    $this->deleteJson("/api/v1/genres/{$genre->id}")
        ->assertOk()
        ->assertJsonPath('message', 'Género eliminado exitosamente.');

    $this->assertSoftDeleted('genres', ['id' => $genre->id]);
});
