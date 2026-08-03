<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureAdminAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->has('admin_user')) {
            return redirect()->route('admin.login')->with('error', 'Please log in to access the admin dashboard.');
        }

        return $next($request);
    }
}
