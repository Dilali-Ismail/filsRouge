<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Traiteur;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(){
        $user = Auth::user();
        $conversations = [];
        $traiteurs = [];

        if ($user->isMariee()) {
            $conversations = Conversation::where('mariee_id', $user->mariee->id)
                ->with(['traiteur.user', 'lastMessage'])
                ->orderBy('last_message_at', 'desc')
                ->get();

            $traiteurs = Traiteur::whereNotIn('id', $conversations->pluck('traiteur_id'))
                ->where('is_verified', true)
                ->with('user')
                ->get();
        } elseif ($user->isTraiteur()) {
            // Si l'utilisateur est un traiteur, on récupère ses conversations avec les mariées
            $conversations = Conversation::where('traiteur_id', $user->traiteur->id)
                ->with(['mariee.user', 'lastMessage'])
                ->orderBy('last_message_at', 'desc')
                ->get();
        }

        return view('messagerie', compact('conversations', 'traiteurs'));
    }

    public function getMessages(Conversation $conversation){
        $this->authorizeConversation($conversation);

        $messages = $conversation->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'content' => $message->content,
                    'user_id' => $message->user_id,
                    'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                    'is_mine' => $message->user_id === auth()->id(),
                    'user_name' => $message->user->name ?? 'Utilisateur'
                ];
            });

        $conversation->messages()
            ->where('user_id', '!=', auth()->id())
            ->where('read', false)
            ->update(['read' => true]);

        return response()->json($messages);
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        // Vérifier que l'utilisateur a accès à cette conversation
        $this->authorizeConversation($conversation);

        // Valider le contenu du message
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        // Créer le message
        $message = new Message([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'read' => false,
        ]);

        // Associer le message à la conversation
        $conversation->messages()->save($message);

        // Mettre à jour la date du dernier message
        $conversation->update(['last_message_at' => now()]);

        // Préparer les données du message
        $messageData = [
            'id' => $message->id,
            'content' => $message->content,
            'user_id' => $message->user_id,
            'created_at' => $message->created_at->format('Y-m-d H:i:s'),
            'user_name' => auth()->user()->name ?? 'Utilisateur'
        ];

        // NOUVEAU: Envoyer l'événement via Pusher directement
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true
            ]
        );

        $pusher->trigger('conversation-' . $conversation->id, 'new-message', $messageData);

        return response()->json([
            'success' => true,
            'message' => $messageData
        ]);
    }

    public function createConversation(Request $request)
    {
        $request->validate([
            'traiteur_id' => 'required|exists:traiteurs,id',
        ]);

        $user = Auth::user();

        if (!$user->isMariee()) {
            return response()->json(['error' => 'Seuls les mariés peuvent créer une conversation'], 403);
        }

        // Vérifier si la conversation existe déjà
        $existingConversation = Conversation::where('mariee_id', $user->mariee->id)
            ->where('traiteur_id', $request->traiteur_id)
            ->first();

        if ($existingConversation) {
            return response()->json([
                'success' => true,
                'conversation_id' => $existingConversation->id
            ]);
        }

        // Créer une nouvelle conversation
        $conversation = Conversation::create([
            'mariee_id' => $user->mariee->id,
            'traiteur_id' => $request->traiteur_id,
            'last_message_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id
        ]);
    }

    private function authorizeConversation(Conversation $conversation)
    {
        $user = Auth::user();

        if ($user->isMariee() && $conversation->mariee_id !== $user->mariee->id) {
            abort(403, 'Vous n\'êtes pas autorisé à accéder à cette conversation.');
        } elseif ($user->isTraiteur() && $conversation->traiteur_id !== $user->traiteur->id) {
            abort(403, 'Vous n\'êtes pas autorisé à accéder à cette conversation.');
        }
    }
}
