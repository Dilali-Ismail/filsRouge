<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next , $role)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        switch($role) {
            case 'mariee':
                if (!$user->isMariee()) {
                    return redirect()->route('welcome')->with('error', 'Accès réservé aux mariées.');
                }
                break;
            case 'traiteur':
                if (!$user->isTraiteur()) {
                    return redirect()->route('welcome')->with('error', 'Accès réservé aux traiteurs.');
                }
                break;
            case 'admin':
                if (!$user->isAdmin()) {
                    return redirect()->route('welcome')->with('error', 'Accès réservé aux administrateurs.');
                }
                break;
        }

        return $next($request);
    }
}
