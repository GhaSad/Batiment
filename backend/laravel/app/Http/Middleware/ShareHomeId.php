<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShareHomeId
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur est authentifié
        if (Auth::check()) {
            // Partager la variable home_id avec toutes les vues
            view()->share('home_id', Auth::user()->home_id);
        }

        return $next($request);
    }
}
