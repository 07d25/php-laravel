<?php

namespace App\Providers;

use App\Repositories\Contracts\GenreRepositoryInterface;
use App\Repositories\Eloquent\GenreRepository;
use Illuminate\Support\ServiceProvider;

/**
 * PROVIDER del patrón Repositorio.
 *
 * ¿QUÉ ES? Un "Service Provider" es una clase que le dice al contenedor de
 * dependencias de Laravel cómo construir y entregar los servicios de la app.
 * Es el lugar donde se "montan" las piezas antes de que el resto del código las use.
 *
 * ¿QUÉ HACE? En su método register() hace un BIND (enlace): le enseña al
 * contenedor que cada vez que alguien pida la INTERFAZ
 * (GenreRepositoryInterface), le entregue la IMPLEMENTACIÓN concreta
 * (GenreRepository).
 *
 * ¿PARA QUÉ SIRVE? Es el "pegamento" del Repository Pattern. Gracias a este
 * enlace, en los controladores (y en los tests) puedo pedir la interfaz sin
 * importarme la clase real:
 *
 *      public function __construct(protected GenreRepositoryInterface $genres) {}
 *
 * El contenedor mira el bind y entrega un GenreRepository automáticamente.
 * Ventajas:
 *  - Desacople: si mañana cambio a otra implementación (una que lea de caché,
 *    de una API, etc.), solo cambio ESTA línea y no toco ningún controlador.
 *  - Tests: puedo "falsear" (mock) la interfaz y probar el controlador sin BD.
 *
 * ⚠️ IMPORTANTE: para que este provider funcione, hay que registrarlo en
 * bootstrap/providers.php añadiendo RepositoryServiceProvider::class a la lista.
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // "Del contenedor": cuando se pida la interfaz, entrega la clase concreta.
        // Esto se llama enlace simple (bind): crea una NUEVA instancia en cada petición.
        $this->app->bind(GenreRepositoryInterface::class, GenreRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Vacío: todo el trabajo de este provider ocurre en register().
    }
}
