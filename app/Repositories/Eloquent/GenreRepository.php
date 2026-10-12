<?php

namespace App\Repositories\Eloquent;

use App\Models\Genre;
use App\Repositories\Contracts\GenreRepositoryInterface;

/**
 * IMPLEMENTACIÓN CONCRETA del repositorio para el recurso Género.
 *
 * ¿QUÉ HACE? Es el repositorio "de verdad" que se usa en la aplicación para
 * Géneros. Hereda todo el CRUD genérico de BaseRepository y solo se encarga de
 * inyectar el modelo correcto (Genre).
 *
 * ¿POR QUÉ EXISTE? Es el punto donde el patrón se hace específico: mientras que
 * BaseRepository es genérico, esta clase le dice "trabaja con el modelo Genre".
 * En un controlador ya no se llama `Genre::all()` directamente; se pide el
 * repositorio ( GenreRepositoryInterface ) y se usa. Eso permite cambiar la
 * implementación (por ejemplo, una que lea de caché) sin tocar el controlador,
 * y "falsearla" (mock) en los tests para no depender de la BD.
 */
class GenreRepository extends BaseRepository implements GenreRepositoryInterface
{
    /**
     * Create a new class instance.
     *
     * Recibe el modelo Genre por inyección de dependencias y se lo pasa al
     * constructor de la clase base (BaseRepository), que será el que haga uso
     * de él en todos los métodos CRUD.
     */
    public function __construct(Genre $model)
    {
        parent::__construct($model);
    }
}
