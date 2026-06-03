<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; // <-- Importante
use App\Models\User;                 // <-- Importante

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Regla: Para crear productos, debes ser admin o editor
        Gate::define('crear-producto', function (User $user) {
            return in_array($user->rol, ['admin', 'editor']);
        });

        // Regla: Para editar productos, debes ser admin o editor
        Gate::define('editar-producto', function (User $user) {
            return in_array($user->rol, ['admin', 'editor']);
        });

        // Regla VIP: Solo el admin supremo puede borrar cosas
        Gate::define('eliminar-producto', function (User $user) {
            return $user->esAdmin();
        });
    }
}