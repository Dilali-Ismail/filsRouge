<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('conversation.{id}', function ($user, $id) {
    $conversation = \App\Models\Conversation::find($id);

    if (!$conversation) return false;

    
    if ($user->isMariee()) {
        return $user->mariee->id === $conversation->mariee_id;
    } elseif ($user->isTraiteur()) {
        return $user->traiteur->id === $conversation->traiteur_id;
    }

    return false;
});
