<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerificationController extends Controller
{

    public function notice()
    {
        return view('auth.verify-email');
    }


    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->route('login')
            ->with('success', 'Votre adresse email a été vérifiée. Vous pouvez maintenant vous connecter.');
    }

    
    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Un nouveau lien de vérification a été envoyé à votre adresse email.');
    }
}
