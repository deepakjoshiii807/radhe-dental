<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureGuestAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->has('admin_user')) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
