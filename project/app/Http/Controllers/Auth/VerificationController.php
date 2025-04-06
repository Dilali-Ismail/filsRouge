<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Affiche la page de notification de vérification d'email.
     *
     * @return \Illuminate\View\View
     */
    public function notice()
    {
        return view('auth.verify-email');
    }

    /**
     * Marque l'email comme vérifié et redirige l'utilisateur.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->route('login')
            ->with('success', 'Votre adresse email a été vérifiée. Vous pouvez maintenant vous connecter.');
    }

    /**
     * Renvoie l'email de vérification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Un nouveau lien de vérification a été envoyé à votre adresse email.');
    }
}