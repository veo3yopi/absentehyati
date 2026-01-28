<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTeacher
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('guru.login');
        }

        if (! $request->user()->teacher_id) {
            abort(403);
        }

        return $next($request);
    }
}
