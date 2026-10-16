<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Http\Resources\GenreResource;
use App\Models\Genre;
use App\Repositories\Contracts\GenreRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as HttpRequest;

/**
 * CONTROLADOR del recurso Género (refactorizado con Repository Pattern).
 *
 * ¿Qué cambió respecto a la versión anterior? El controlador YA NO llama a Eloquent
 * directamente (Genre::all(), Genre::create()...). Ahora pide la INTERFAZ
 * (GenreRepositoryInterface) por su constructor y delega todo el acceso a datos
 * al repositorio.
 *
 * ¿Por qué? Desacople: el controlador solo se preocupa por recibir la petición y
 * responder. Si mañana cambio la fuente de datos, o quiero testear con un repositorio
 * falso (mock), no toco NI UNA línea de esta clase.
 */
class GenreController extends Controller
{
    use ApiResponse;

    /**
     * Inyección de dependencias: al pedir la interfaz en el constructor, el contenedor
     * (gracias al bind del RepositoryServiceProvider) entrega un GenreRepository listo
     * para usar. El controlador no sabe ni cómo ni de dónde se trae los datos.
     */
    public function __construct(private GenreRepositoryInterface $genres) {}

    /**
     * Display a listing of the resource.
     *
     * GET /api/v1/genres → lista todos los géneros.
     */
    public function index(HttpRequest $request)
    {
        $genres = $this->genres->filter(
            filters: $request->only(['search', 'is_active']),
            sortBy: $request->input('sort_by', 'name'),
            order: $request->input('order', 'asc'),
        );

        return $this->successResponse(GenreResource::collection($genres));
    }

    public function showBySlug(string $slug): JsonResponse
    {
        $genre = $this->genres->findBySlugOrFail($slug);

        return $this->successResponse(new GenreResource($genre));
    }

    /**
     * Store a newly created resource in storage.
     *
     * POST /api/v1/genres → crea un género. La validación ya la hizo StoreGenreRequest.
     */
    public function store(StoreGenreRequest $request)
    {
        // $request->validated() trae SOLO los campos que pasaron la validación.
        $genre = $this->genres->create($request->validated());

        return $this->successResponse(new GenreResource($genre),
            'Género creado exitosamente.', 201);
    }

    /**
     * Display the specified resource.
     *
     * GET /api/v1/genres/{genre} → muestra uno. El {genre} llega ya cargado
     * gracias al Route Model Binding (si no existe → 404 automático).
     */
    public function show(Genre $genre)
    {
        return $this->successResponse(new GenreResource($genre));
    }

    /**
     * Update the specified resource in storage.
     *
     * PUT/PATCH /api/v1/genres/{genre} → actualiza. La validación unique
     * ignoró el propio id (ver UpdateGenreRequest).
     */
    public function update(UpdateGenreRequest $request, Genre $genre)
    {
        // El repositorio actualiza y devuelve el modelo refrescado.
        $this->genres->update($genre, $request->validated());

        return $this->successResponse(new GenreResource($genre), 'Genero actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * DELETE /api/v1/genres/{genre} → borra (soft delete: solo marca deleted_at).
     */
    public function destroy(Genre $genre)
    {
        $this->genres->delete($genre);

        return $this->successResponse(null, 'Género eliminado exitosamente.');
    }

    public function restore(int $id): JsonResponse
    {
        $this->genres->restore($id);

        return $this->successResponse(null, 'Género restaurado exitosamente.');
    }
}
