<?php

namespace App\Repositories\Eloquent;

use App\Models\Genre;
use App\Repositories\Contracts\GenreRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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
    private const SORTABLE = ['name', 'created_at', 'is_active'];

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

    public function filter(array $filters, string $sortBy = 'name', string $order = 'asc'): Collection
    {
        $sortBy = in_array($sortBy, self::SORTABLE) ? $sortBy : 'name';
        $order = strtolower($order) === 'desc' ? 'desc' : 'asc';

        return Genre::query()
            ->when(isset($filters['search']),
                fn ($q) => $q->whereRaw('LOWER(name) LIKE ?', ['%'.strtolower($filters['search']).'%'])
            )
            ->when(isset($filters['is_active']),
                fn ($q) => $q->where('is_active', filter_var($filters['is_active'], FILTER_VALIDATE_BOOLEAN))
            )
            ->orderBy($sortBy, $order)
            ->get();
    }

    /**
     * {@inheritDoc}
     */
    public function findBySlugOrFail(string $slug): Model
    {
        return Genre::where('slug', $slug)->firstOrFail();
    }

    /**
     * {@inheritDoc}
     */
    public function restore(int $id): Model
    {
        $genre = Genre::withTrashed()->findOrFail($id);
        $genre->restore();

        return $genre->refresh();
    }
}
