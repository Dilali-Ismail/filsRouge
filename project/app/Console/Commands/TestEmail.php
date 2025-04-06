<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class TestEmail extends Command
{
    protected $signature = 'email:test {email}';
    protected $description = 'Envoie un email de test à l\'adresse spécifiée';

    public function handle()
    {
        $email = $this->argument('email');

        $this->info("Envoi d'un email de test à {$email}...");

        Mail::raw("Ceci est un email de test de l'application Alf Mabrouk. Si vous recevez ce message, la configuration d'email fonctionne correctement!", function (Message $message) use ($email) {
            $message->to($email)
                    ->subject('Email de test - Alf Mabrouk');
        });

        $this->info("Email envoyé! Vérifiez votre boîte de réception.");
    }
}
