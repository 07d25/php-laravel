<?php

use App\Providers\AppServiceProvider;
use App\Providers\RepositoryServiceProvider;

// Registro de los Service Providers de la aplicación.
// OJO: si agregás uno nuevo, acá debe ir con su "use" correspondiente,
// si no, Laravel no lo carga (y su bind/registro no se aplica).
return [
    AppServiceProvider::class,
    RepositoryServiceProvider::class,  // aquí se hace el bind de GenreRepositoryInterface
];
