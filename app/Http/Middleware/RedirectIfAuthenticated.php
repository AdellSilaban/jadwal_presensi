<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
   public function handle($request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {

                if ($guard === 'volunteer') {
                    $volunteer = Auth::guard('volunteer')->user()->load('divisi', 'subDivisi');
                    $divisi = $volunteer->divisi->nama_divisi ?? '';
                    $subDivisi = $volunteer->subDivisi->nama_subdivisi ?? '';

                    if ($divisi === 'Creative' && $subDivisi === 'Desain') {
                        return redirect('/home_vltcreative');
                    } elseif ($divisi === 'Konseling') {
                        return redirect('/home_vltcreative');
                    } else {
                        return redirect('/home_vlt');
                    }
                }

                // Guard default = kepala atau koordinator
                $user = Auth::user();
                switch ($user->jabatan) {
                    case 'Kepala LPKKSK':
                        return redirect('/dashboard');
                    case 'Koordinator Divisi Creative':
                    case 'Koordinator Divisi Tim Ibadah Kampus':
                    case 'Koordinator Divisi Konseling':
                        return redirect('/home_koor');
                    default:
                        return redirect('/'); // fallback aja
                }
            }
        }

        return $next($request);
    }
}
