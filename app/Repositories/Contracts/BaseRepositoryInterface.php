<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * CONTRATO BASE del patrón Repositorio.
 *
 * ¿QUÉ HACE? Define las "firmas" (nombres y tipos) de las operaciones CRUD que
 * cualquier repositorio debe poder realizar: listar, buscar por id, crear,
 * actualizar y borrar. NO contiene lógica, solo declara QUÉ se puede hacer.
 *
 * ¿POR QUÉ EXISTE? Es el corazón del Repository Pattern. El controlador deja de
 * depender directamente de Eloquent (el "detalle") y depende de ESTA interfaz (la
 * "abstracción"). Beneficios:
 *  - Desacopla la lógica de negocio del acceso a datos.
 *  - Permite cambiar la fuente de datos (BD, API externa, caché) sin tocar el controlador.
 *  - Facilita los tests: se puede "falsear" (mock) el repositorio y probar el
 *    controlador sin necesitar una base de datos real.
 */
interface BaseRepositoryInterface
{
    /**
     * Trae todos los registros del recurso.
     *
     * @return Collection Colección de modelos de Eloquent
     */
    public function all(): Collection;

    /**
     * Busca un registro por su id. Si no existe, lanza una excepción (=> HTTP 404).
     *
     * @param  int  $id  Identificador del registro
     * @return Model El modelo encontrado
     */
    public function findOrFail(int $id): Model;

    /**
     * Crea un registro nuevo a partir de un array de datos.
     *
     * @param  array  $data  Atributos a guardar (ya validados antes)
     * @return Model El modelo recién creado (con su id asignado)
     */
    public function create(array $data): Model;

    /**
     * Actualiza un registro existente con nuevos datos.
     *
     * @param  Model  $model  El modelo a modificar (ya obtenido)
     * @param  array  $data  Nuevos valores
     * @return Model El mismo modelo, actualizado y refrescado
     */
    public function update(Model $model, array $data): Model;

    /**
     * Elimina un registro. (Con SoftDeletes solo lo marca como borrado.)
     *
     * @param  Model  $model  El modelo a eliminar
     * @return void
     */
    public function delete(Model $model): void;
}
