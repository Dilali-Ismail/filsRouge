@extends('layouts.app')

@section('title', 'Vérification d\'email')

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12 md:py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto bg-white rounded-xl shadow-lg overflow-hidden p-8">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-[#333333] mb-4 font-display">Vérifiez votre adresse email</h1>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-6">
                    <p class="text-gray-600 mb-4">
                        Un email de vérification a été envoyé à votre adresse. Veuillez cliquer sur le lien dans votre boîte mail pour activer votre compte.
                    </p>
                    <p class="text-gray-600">
                        Si vous n'avez pas reçu l'email, nous vous enverrons volontiers un autre.
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <div class="flex justify-center">
                    <button type="submit" class="bg-[#FADADD] hover:bg-[#C08081] text-[#333333] hover:text-white font-medium py-3 px-6 rounded-lg transition duration-300 shadow-md hover:shadow-lg">
                        Renvoyer l'email de vérification
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-[#C08081] hover:underline">
                        Se déconnecter
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
