<?php

namespace App\Http\Middleware;

use App\Models\AcademicYear;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveAcademicYear
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $activeAcademicYear = AcademicYear::query()->where('is_active', true)->first();

        if (! $activeAcademicYear && ! $request->routeIs('admin.*')) {
            return redirect()
                ->route('dashboard')
                ->with('warning', 'Belum ada tahun ajaran aktif. Silakan hubungi administrator untuk mengaktifkan tahun ajaran.');
        }

        return $next($request);
    }
}
