<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * IMPLEMENTACIÓN BASE (abstracta) del patrón Repositorio con Eloquent.
 *
 * ¿QUÉ HACE? Es una clase "madre" que implementa el CRUD genérico definido en
 * BaseRepositoryInterface, usando los métodos de Eloquent. Contiene la lógica
 * común para TODOS los recursos.
 *
 * ¿POR QUÉ EXISTE? Evita repetir el mismo código CRUD en cada repositorio.
 * Al ser `abstract`, NO se puede instanciar directamente (`new BaseRepository()`
 * daría error); solo sirve como base para repositorios concretos como
 * GenreRepository. Así, cada recurso reutiliza este CRUD sin reescribirlo.
 */
abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * Create a new class instance.
     *
     * ¿POR QUÉ el constructor recibe un Model? Gracias a la inyección de
     * dependencias, Laravel nos entrega automáticamente la instancia del modelo
     * correcta (Genre, Movie, etc.). Así esta clase no sabe de qué recurso habla:
     * es genérica y reutilizable. El modelo concreto lo decide cada hijo.
     */
    public function __construct(protected Model $model)
    {
        //
    }

    /**
     * @inheritDoc
     *
     * Delega en Eloquent: $this->model->all() trae todos los registros de la
     * tabla asociada al modelo inyectado.
     */
    public function all(): Collection {
        return $this->model->all();
    }

    /**
     * @inheritDoc
     *
     * Crea un registro a partir de $data. OJO: el modelo debe tener esos campos
     * en $fillable (protección contra asignación masiva).
     */
    public function create(array $data): Model {
        return $this->model->create($data);
    }

    /**
     * @inheritDoc
     *
     * Borra el modelo recibido. Si el modelo usa el trait SoftDeletes, en realidad
     * solo marca la columna deleted_at (no elimina la fila de la BD).
     */
    public function delete(Model $model): void {
        $model->delete();
    }

    /**
     * @inheritDoc
     *
     * findOrFail busca por id; si no lo encuentra lanza ModelNotFoundException,
     * que Laravel convierte automáticamente en una respuesta HTTP 404.
     */
    public function findOrFail(int $id): Model {
        return $this->model->findOrFail($id);
    }

    /**
     * @inheritDoc
     *
     * Actualiza y luego refresca los atributos desde la BD, para devolver el
     * modelo con todos los cambios ya aplicados (útil si hubo eventos o casts).
     */
    public function update(Model $model, array $data): Model {
        $model->update($data);
        return $model->refresh();
    }
}