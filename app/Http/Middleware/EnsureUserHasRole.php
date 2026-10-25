<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Mengecek apakah role user saat ini ada di dalam array $roles yang diizinkan
        if (! $request->user() || ! in_array($request->user()->role, $roles)) {
            $requiredRoles = implode(', ', $roles);

            abort(403, "Halaman ini hanya untuk peran {$requiredRoles}.");
        }

        return $next($request);
    }
}