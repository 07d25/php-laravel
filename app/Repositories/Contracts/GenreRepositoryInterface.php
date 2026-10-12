<?php

namespace App\Repositories\Contracts;

/**
 * CONTRATO específico del recurso Género.
 *
 * ¿QUÉ HACE? Hereda TODO el CRUD genérico de BaseRepositoryInterface y deja un
 * espacio para añadir métodos propios de Género.
 *
 * ¿POR QUÉ EXISTE? Separa "lo que todos los recursos comparten" (el CRUD base)
 * de "lo que solo hace Género". Cuando necesites reglas exclusivas del género
 * (por ejemplo: contar películas de un género, o buscar por slug), las declaras
 * aquí y las implementas en GenreRepository. Así cada recurso crece sin tocar al
 * resto.
 */
interface GenreRepositoryInterface extends BaseRepositoryInterface
{
    // Ejemplo de lo que podría añadirse en el futuro:
    // public function findBySlug(string $slug): ?Genre;
    //
}
