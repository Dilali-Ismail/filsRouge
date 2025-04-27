@extends('layouts.app')

@section('title', 'Messagerie')

@section('styles')
<style>
    .message-bubble {
        max-width: 70%;
        padding: 10px 15px;
        border-radius: 15px;
        margin-bottom: 10px;
    }
    .message-mine {
        background-color: #FADADD;
        margin-left: auto;
        color: #333333;
    }
    .message-other {
        background-color: #f1f1f1;
        margin-right: auto;
        color: #333333;
    }
    .messages-container {
        height: 450px;
        overflow-y: auto;
        padding: 20px;
    }
    .contact-active {
        background-color: #fef3f3;
        border-left: 3px solid #C08081;
    }
    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }
    .typing-indicator {
        display: inline-flex;
        align-items: center;
    }
    .typing-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: #C08081;
        margin: 0 2px;
        animation: typing 1.4s infinite ease-in-out;
    }
    .typing-dot:nth-child(1) { animation-delay: 0s; }
    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }

    @keyframes typing {
        0%, 60%, 100% { transform: translateY(0); }
        30% { transform: translateY(-6px); }
    }

    /* Pour les transitions smooth */
    .fade-enter-active, .fade-leave-active {
        transition: opacity 0.3s;
    }
    .fade-enter, .fade-leave-to {
        opacity: 0;
    }

    /* Personnaliser la scrollbar */
    .messages-container::-webkit-scrollbar {
        width: 6px;
    }
    .messages-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .messages-container::-webkit-scrollbar-thumb {
        background: #ddd;
        border-radius: 10px;
    }
    .messages-container::-webkit-scrollbar-thumb:hover {
        background: #C08081;
    }

    /* Badge de notification */
    .unread-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #C08081;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 12px;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="flex flex-col md:flex-row h-[600px]">
                <!-- Sidebar avec liste des contacts -->
                <div class="w-full md:w-1/3 bg-white border-r border-gray-200 flex flex-col">
                    <!-- En-tête de la sidebar -->
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                        <h1 class="text-xl font-bold text-[#333333] font-display">Messagerie</h1>

                        @if(Auth::user()->isMariee())
                            <button id="new-conversation-btn" class="px-3 py-1 bg-[#FADADD] text-[#333333] rounded-lg text-sm hover:bg-[#C08081] hover:text-white transition-colors duration-200 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Nouveau
                            </button>
                        @endif
                    </div>

                    <!-- Recherche -->
                    <div class="p-4 border-b border-gray-200">
                        <div class="relative">
                            <input type="text" id="search-conversations" placeholder="Rechercher..." class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]">
                            <div class="absolute left-3 top-2.5 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Liste des conversations -->
                    <div id="conversations-list" class="flex-grow overflow-y-auto">
                        @forelse($conversations ?? [] as $conversation)
                            <div class="conversation-item p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors duration-200" data-id="{{ $conversation->id }}">
                                <div class="flex items-center">
                                    <div class="avatar bg-[#FADADD] text-[#C08081]">
                                        @if(Auth::user()->isMariee())
                                            {{ substr($conversation->traiteur->manager_name ?? 'T', 0, 1) }}
                                        @else
                                            {{ substr($conversation->mariee->groom_name ?? 'M', 0, 1) }}
                                        @endif
                                    </div>
                                    <div class="ml-3 flex-grow">
                                        <div class="flex justify-between items-center">
                                            <p class="font-medium text-gray-900">
                                                @if(Auth::user()->isMariee())
                                                    {{ $conversation->traiteur->manager_name ?? 'Traiteur' }}
                                                @else
                                                    {{ ($conversation->mariee->groom_name ?? 'Marié') . ' & ' . ($conversation->mariee->bride_name ?? 'Mariée') }}
                                                @endif
                                            </p>
                                            <span class="text-xs text-gray-500">
                                                {{ isset($conversation->last_message_at) ? \Carbon\Carbon::parse($conversation->last_message_at)->diffForHumans() : '' }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <p class="text-sm text-gray-500 truncate w-5/6">
                                                {{ $conversation->lastMessage ? Str::limit($conversation->lastMessage->content, 30) : 'Aucun message' }}
                                            </p>

                                            @php
                                                $unreadCount = isset($conversation->messages) ?
                                                    $conversation->messages->where('user_id', '!=', Auth::id())->where('read', false)->count() : 0;
                                            @endphp

                                            @if($unreadCount > 0)
                                                <span class="unread-badge">{{ $unreadCount }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                <p class="text-gray-500">
                                    @if(Auth::user()->isMariee())
                                        Vous n'avez pas encore contacté de traiteurs.
                                    @else
                                        Vous n'avez pas encore de messages de clients.
                                    @endif
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Zone de conversation principale -->
                <div class="w-full md:w-2/3 bg-white flex flex-col">
                    <!-- État vide (aucune conversation sélectionnée) -->
                    <div id="empty-state" class="flex-grow flex flex-col items-center justify-center p-6">
                        <div class="w-20 h-20 rounded-full bg-[#FADADD]/20 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-[#C08081]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-[#333333] mb-2">Vos messages</h3>
                        <p class="text-gray-500 text-center max-w-xs">
                            Sélectionnez une conversation pour commencer à discuter avec un
                            @if(Auth::user()->isMariee()) traiteur @else client @endif.
                        </p>
                    </div>

                    <!-- Contenu de la conversation (caché par défaut) -->
                    <div id="conversation-content" class="flex-grow flex flex-col hidden">
                        <!-- En-tête de la conversation -->
                        <div id="conversation-header" class="px-6 py-4 border-b border-gray-200 flex items-center">
                            <div class="avatar bg-[#FADADD] text-[#C08081] mr-3">
                                <span id="contact-initial">T</span>
                            </div>
                            <div>
                                <h2 id="conversation-title" class="font-semibold text-[#333333]">Nom du contact</h2>
                                <p id="typing-indicator" class="text-xs text-[#C08081] hidden">
                                    <span class="typing-indicator">
                                        <span class="typing-dot"></span>
                                        <span class="typing-dot"></span>
                                        <span class="typing-dot"></span>
                                    </span>
                                    En train d'écrire...
                                </p>
                            </div>
                        </div>

                        <!-- Zone des messages -->
                        <div id="messages-list" class="messages-container flex-grow bg-gray-50"></div>

                        <!-- Zone de saisie de message -->
                        <div id="message-form" class="px-4 py-3 border-t border-gray-200 bg-white">
                            <form id="send-message-form" class="flex items-center">
                                <input type="hidden" id="conversation_id" name="conversation_id" value="">

                                <div class="flex-grow relative">
                                    <input type="text" id="message_content" name="content" placeholder="Écrivez votre message..."
                                           class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FADADD] focus:border-[#FADADD]">
                                    <div class="absolute right-3 top-3 text-gray-400">
                                        <button type="button" id="emoji-btn" class="text-gray-400 hover:text-[#C08081]">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <button type="submit" class="ml-3 px-4 py-3 bg-[#FADADD] text-[#333333] rounded-lg hover:bg-[#C08081] hover:text-white transition-colors duration-200 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour créer une nouvelle conversation (pour les mariées) -->
@if(Auth::user()->isMariee())
<div id="new-conversation-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-[#333333]">Nouvelle conversation</h3>
            <button id="close-modal" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <p class="mb-4 text-sm text-gray-600">Sélectionnez un traiteur pour démarrer une conversation:</p>

        <div class="max-h-60 overflow-y-auto mb-4">
            @forelse($traiteurs ?? [] as $traiteur)
                <div class="traiteur-item p-3 rounded-lg hover:bg-gray-100 cursor-pointer" data-id="{{ $traiteur->id }}">
                    <div class="flex items-center">
                        <div class="avatar bg-[#FADADD] text-[#C08081]">
                            {{ substr($traiteur->manager_name ?? 'T', 0, 1) }}
                        </div>
                        <div class="ml-3">
                            <p class="font-medium text-gray-900">{{ $traiteur->manager_name }}</p>
                            <p class="text-sm text-gray-500">{{ $traiteur->city }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center p-4">Aucun nouveau traiteur disponible.</p>
            @endforelse
        </div>

        <div class="flex justify-end">
            <button id="cancel-new-conversation" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 mr-2 hover:bg-gray-50">
                Annuler
            </button>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Éléments DOM
    const conversationsList = document.getElementById('conversations-list');
    const conversationItems = document.querySelectorAll('.conversation-item');
    const emptyState = document.getElementById('empty-state');
    const conversationContent = document.getElementById('conversation-content');
    const messagesList = document.getElementById('messages-list');
    const sendMessageForm = document.getElementById('send-message-form');
    const conversationIdInput = document.getElementById('conversation_id');
    const messageContentInput = document.getElementById('message_content');
    const conversationTitle = document.getElementById('conversation-title');
    const contactInitial = document.getElementById('contact-initial');
    const searchInput = document.getElementById('search-conversations');
    const typingIndicator = document.getElementById('typing-indicator');

    // Configuration Pusher - utilisation directe comme dans la documentation Pusher
    Pusher.logToConsole = true; // Pour le débogage

    let pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
    });

    let currentChannel = null;
    let currentConversationId = null;
    let typingTimeout;

    // Fonction pour charger les messages d'une conversation
    function loadMessages(conversationId) {
        // Mise à jour de l'UI
        currentConversationId = conversationId;
        conversationIdInput.value = conversationId;
        emptyState.classList.add('hidden');
        conversationContent.classList.remove('hidden');

        // Mise à jour du titre de la conversation
        const selectedItem = document.querySelector(`.conversation-item[data-id="${conversationId}"]`);
        if (selectedItem) {
            const nameElement = selectedItem.querySelector('.font-medium');
            if (nameElement) {
                conversationTitle.textContent = nameElement.textContent.trim();
                contactInitial.textContent = nameElement.textContent.trim().charAt(0);
            }

            // Supprimer la classe active de tous les éléments
            conversationItems.forEach(item => {
                item.classList.remove('contact-active');
            });

            // Ajouter la classe active à l'élément sélectionné
            selectedItem.classList.add('contact-active');

            // Supprimer le badge de notification non lue
            const unreadBadge = selectedItem.querySelector('.unread-badge');
            if (unreadBadge) {
                unreadBadge.remove();
            }
        }

        // Afficher un indicateur de chargement
        messagesList.innerHTML = `
            <div class="flex justify-center items-center h-full">
                <div class="typing-indicator">
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
                </div>
            </div>
        `;

        // Récupérer les messages via AJAX
        fetch(`/conversations/${conversationId}/messages`)
            .then(response => response.json())
            .then(messages => {
                displayMessages(messages);

                // S'abonner au canal de la conversation
                subscribeToConversation(conversationId);
            })
            .catch(error => {
                console.error('Erreur lors du chargement des messages:', error);
                messagesList.innerHTML = `
                    <div class="flex justify-center items-center h-full text-red-500">
                        Une erreur est survenue lors du chargement des messages.
                    </div>
                `;
            });
    }

    // Fonction pour s'abonner au canal de la conversation - NOUVELLE VERSION SELON DOCUMENTATION PUSHER
    function subscribeToConversation(conversationId) {
        console.log("Abonnement au canal:", 'conversation-' + conversationId);

        // Se désabonner du canal précédent si nécessaire
        if (currentChannel) {
            pusher.unsubscribe('conversation-' + currentConversationId);
        }

        // S'abonner au nouveau canal - utilisation d'un canal simple (non-privé)
        currentChannel = pusher.subscribe('conversation-' + conversationId);

        // Écouter l'événement 'new-message' (nom d'événement utilisé dans le contrôleur)
        currentChannel.bind('new-message', function(data) {
            console.log('Message reçu via Pusher:', data);

            // Si ce n'est pas notre message, on l'ajoute à l'affichage
            if (data.user_id !== {{ Auth::id() }}) {
                receiveMessage(data);
            }
        });

        // Pour déboggage - confirmer l'abonnement réussi
        currentChannel.bind('pusher:subscription_succeeded', function() {
            console.log('Abonnement au canal réussi!');
        });

        // Pour déboggage - signaler les erreurs
        currentChannel.bind('pusher:subscription_error', function(status) {
            console.error('Erreur d\'abonnement au canal:', status);
        });
    }

    // Fonction pour afficher les messages
    function displayMessages(messages) {
        messagesList.innerHTML = '';

        if (messages.length === 0) {
            messagesList.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full p-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <p class="text-gray-500">Aucun message dans cette conversation.</p>
                    <p class="text-sm text-gray-400 mt-2">Soyez le premier à écrire !</p>
                </div>
            `;
            return;
        }

        let currentDate = null;

        messages.forEach(message => {
            // Vérifier si on doit afficher un séparateur de date
            const messageDate = new Date(message.created_at).toLocaleDateString();
            if (currentDate !== messageDate) {
                currentDate = messageDate;

                const dateSeparator = document.createElement('div');
                dateSeparator.className = 'text-center my-4';
                dateSeparator.innerHTML = `
                    <span class="text-xs bg-gray-200 text-gray-500 px-3 py-1 rounded-full">
                        ${formatDateHeader(message.created_at)}
                    </span>
                `;
                messagesList.appendChild(dateSeparator);
            }

            const messageElement = document.createElement('div');
            messageElement.classList.add('message-bubble');

            if (message.is_mine) {
                messageElement.classList.add('message-mine');
                messageElement.innerHTML = `
                    <div class="text-sm">
                        ${formatMessageContent(message.content)}
                    </div>
                    <div class="text-xs text-[#333333]/70 mt-1 flex items-center justify-end">
                        ${formatMessageTime(message.created_at)}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                `;
            } else {
                messageElement.classList.add('message-other');
                messageElement.innerHTML = `
                    <div class="text-sm">
                        ${formatMessageContent(message.content)}
                    </div>
                    <div class="text-xs text-gray-500 mt-1">
                        ${formatMessageTime(message.created_at)}
                    </div>
                `;
            }

            messagesList.appendChild(messageElement);
        });

        // Scroll vers le bas pour voir les derniers messages
        scrollToBottom();
    }

    // Fonction pour recevoir un nouveau message
    function receiveMessage(message) {
        // Vérifier si on doit ajouter un séparateur de date
        const lastDateSeparator = messagesList.querySelector('.text-center:last-of-type .text-xs');
        const messageDate = formatDateHeader(message.created_at);

        if (!lastDateSeparator || lastDateSeparator.textContent !== messageDate) {
            const dateSeparator = document.createElement('div');
            dateSeparator.className = 'text-center my-4';
            dateSeparator.innerHTML = `
                <span class="text-xs bg-gray-200 text-gray-500 px-3 py-1 rounded-full">
                    ${messageDate}
                </span>
            `;
            messagesList.appendChild(dateSeparator);
        }

        // Créer l'élément de message
        const messageElement = document.createElement('div');
        messageElement.classList.add('message-bubble', 'message-other');
        messageElement.innerHTML = `
            <div class="text-sm">
                ${formatMessageContent(message.content)}
            </div>
            <div class="text-xs text-gray-500 mt-1">
                ${formatMessageTime(message.created_at)}
            </div>
        `;

        // Ajouter une animation d'entrée
        messageElement.style.opacity = '0';
        messageElement.style.transform = 'translateY(10px)';
        messagesList.appendChild(messageElement);

        // Forcer un reflow
        messageElement.offsetHeight;

        // Lancer l'animation
        messageElement.style.transition = 'opacity 0.3s, transform 0.3s';
        messageElement.style.opacity = '1';
        messageElement.style.transform = 'translateY(0)';

        // Scroll vers le bas
        scrollToBottom();

        // Mettre à jour le dernier message dans la liste des conversations
        updateConversationLastMessage(currentConversationId, message.content);

        // Jouer un son de notification
        playNotificationSound();
    }

    // Fonction pour formater le contenu du message (remplacer les URLs, emojis, etc.)
    function formatMessageContent(content) {
        // Échapper le HTML pour éviter les injections XSS
        const escaped = escapeHtml(content);

        // Remplacer les URLs par des liens cliquables
        const withLinks = escaped.replace(
            /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig,
            '<a href="$1" target="_blank" class="text-blue-600 underline">$1</a>'
        );

        return withLinks;
    }

    // Fonction pour échapper le HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Fonction pour formater la date d'en-tête
    function formatDateHeader(dateString) {
        const date = new Date(dateString);
        const today = new Date();
        const yesterday = new Date();
        yesterday.setDate(yesterday.getDate() - 1);

        if (date.toDateString() === today.toDateString()) {
            return "Aujourd'hui";
        } else if (date.toDateString() === yesterday.toDateString()) {
            return "Hier";
        } else {
            const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
            return date.toLocaleDateString('fr-FR', options);
        }
    }

   // Fonction pour formater l'heure du message
   function formatMessageTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
    }

    // Fonction pour jouer un son de notification
    function playNotificationSound() {
        const audio = new Audio('/sounds/notification.mp3');
        audio.volume = 0.5;
        audio.play().catch(error => {
            // Les navigateurs bloquent souvent la lecture automatique du son
            console.log('Lecture du son bloquée par le navigateur');
        });
    }

    // Fonction pour envoyer un message - MODIFIÉE POUR SUIVRE LA DOCUMENTATION PUSHER
    // Fonction pour envoyer un message - MODIFIÉE POUR SUIVRE LA DOCUMENTATION PUSHER
   function sendMessage(event) {
       event.preventDefault();

       const content = messageContentInput.value.trim();
       if (!content) return;

       const conversationId = conversationIdInput.value;

       // Désactiver le bouton d'envoi pendant la requête
       const submitButton = sendMessageForm.querySelector('button[type="submit"]');
       submitButton.disabled = true;

       // Ajouter immédiatement le message dans l'interface (optimistic UI)
       // Vérifier si on doit ajouter un séparateur de date
       const now = new Date();
       const lastDateSeparator = messagesList.querySelector('.text-center:last-of-type .text-xs');
       const messageDate = formatDateHeader(now);

       if (!lastDateSeparator || lastDateSeparator.textContent !== messageDate) {
           const dateSeparator = document.createElement('div');
           dateSeparator.className = 'text-center my-4';
           dateSeparator.innerHTML = `
               <span class="text-xs bg-gray-200 text-gray-500 px-3 py-1 rounded-full">
                   ${messageDate}
               </span>
           `;
           messagesList.appendChild(dateSeparator);
       }

       // Création du message temporaire
       const tempMessageElement = document.createElement('div');
       tempMessageElement.classList.add('message-bubble', 'message-mine');
       tempMessageElement.innerHTML = `
           <div class="text-sm">
               ${formatMessageContent(content)}
           </div>
           <div class="text-xs text-[#333333]/70 mt-1 flex items-center justify-end">
               ${formatMessageTime(now)}
               <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
               </svg>
           </div>
       `;
       messagesList.appendChild(tempMessageElement);
       scrollToBottom();

       // Vider le champ de saisie
       messageContentInput.value = '';

       // Envoyer la requête
       fetch(`/conversations/${conversationId}/messages`, {
           method: 'POST',
           headers: {
               'Content-Type': 'application/json',
               'X-CSRF-TOKEN': '{{ csrf_token() }}'
           },
           body: JSON.stringify({ content })
       })
       .then(response => response.json())
       .then(data => {
           console.log('Réponse du serveur:', data);
           if (data.success) {
               // Mettre à jour la conversation dans la liste
               updateConversationLastMessage(conversationId, content);
           } else {
               // Afficher une erreur
               console.error('Erreur lors de l\'envoi du message:', data.error);
               alert('Erreur lors de l\'envoi du message: ' + (data.error || 'Une erreur est survenue'));

               // Supprimer le message temporaire
               tempMessageElement.remove();

               // Restaurer le contenu dans le champ de saisie
               messageContentInput.value = content;
           }
       })
       .catch(error => {
           console.error('Erreur lors de l\'envoi du message:', error);
           alert('Erreur lors de l\'envoi du message. Veuillez réessayer.');

           // Supprimer le message temporaire
           tempMessageElement.remove();

           // Restaurer le contenu dans le champ de saisie
           messageContentInput.value = content;
       })
       .finally(() => {
           // Réactiver le bouton d'envoi
           submitButton.disabled = false;
           messageContentInput.focus();
       });
   }

   // Fonction pour mettre à jour le dernier message dans la liste des conversations
   function updateConversationLastMessage(conversationId, content) {
       const conversationItem = document.querySelector(`.conversation-item[data-id="${conversationId}"]`);
       if (conversationItem) {
           const lastMessageElement = conversationItem.querySelector('.text-sm.text-gray-500');
           if (lastMessageElement) {
               const truncatedContent = content.length > 30 ? content.substring(0, 30) + '...' : content;
               lastMessageElement.textContent = truncatedContent;
           }

           // Mettre à jour l'heure du dernier message
           const timeElement = conversationItem.querySelector('.text-xs.text-gray-500');
           if (timeElement) {
               timeElement.textContent = 'à l\'instant';
           }

           // Déplacer la conversation en haut de la liste
           const conversationsContainer = conversationItem.parentElement;
           conversationsContainer.insertBefore(conversationItem, conversationsContainer.firstChild);
       }
   }

   // Fonction pour scroller en bas des messages
   function scrollToBottom() {
       messagesList.scrollTop = messagesList.scrollHeight;
   }

   // Fonction pour afficher l'indicateur de frappe
   function showTypingIndicator() {
       typingIndicator.classList.remove('hidden');
   }

   // Fonction pour cacher l'indicateur de frappe
   function hideTypingIndicator() {
       typingIndicator.classList.add('hidden');
   }

   // Gérer les clics sur les conversations
   conversationItems.forEach(item => {
       item.addEventListener('click', function() {
           const conversationId = this.getAttribute('data-id');
           loadMessages(conversationId);
       });
   });

   // Gérer l'envoi du formulaire de message
   sendMessageForm.addEventListener('submit', sendMessage);

   // Gérer l'événement de frappe pour signaler que l'utilisateur est en train d'écrire - MODIFIÉ POUR PUSHER
   messageContentInput.addEventListener('input', function() {
       if (currentChannel && this.value.trim().length > 0) {
           // Utilisation de client-events de Pusher pour les événements de typing
           currentChannel.trigger('client-typing', {
               user_id: {{ Auth::id() }}
           });
       }
   });

   // Gérer la recherche de conversations
   searchInput.addEventListener('input', function() {
       const searchTerm = this.value.toLowerCase();

       conversationItems.forEach(item => {
           const nameElement = item.querySelector('.font-medium');
           const name = nameElement ? nameElement.textContent.trim().toLowerCase() : '';

           if (name.includes(searchTerm)) {
               item.style.display = '';
           } else {
               item.style.display = 'none';
           }
       });
   });

   @if(Auth::user()->isMariee())
   // Code pour la création de nouvelles conversations (uniquement pour les mariées)
   const newConversationBtn = document.getElementById('new-conversation-btn');
   const newConversationModal = document.getElementById('new-conversation-modal');
   const closeModalBtn = document.getElementById('close-modal');
   const cancelNewConversationBtn = document.getElementById('cancel-new-conversation');
   const traiteurItems = document.querySelectorAll('.traiteur-item');

   // Ouvrir la modal
   newConversationBtn.addEventListener('click', function() {
       newConversationModal.classList.remove('hidden');
   });

   // Fermer la modal
   function closeModal() {
       newConversationModal.classList.add('hidden');
   }

   closeModalBtn.addEventListener('click', closeModal);
   cancelNewConversationBtn.addEventListener('click', closeModal);

   // Gérer les clics en dehors de la modal pour la fermer
   newConversationModal.addEventListener('click', function(e) {
       if (e.target === this) {
           closeModal();
       }
   });

   // Créer une nouvelle conversation
   traiteurItems.forEach(item => {
       item.addEventListener('click', function() {
           const traiteurId = this.getAttribute('data-id');

           // Afficher un état de chargement
           this.innerHTML += `
               <div class="absolute inset-0 bg-white bg-opacity-75 flex justify-center items-center">
                   <div class="typing-indicator">
                       <span class="typing-dot"></span>
                       <span class="typing-dot"></span>
                       <span class="typing-dot"></span>
                   </div>
               </div>
           `;

           fetch('/conversations', {
               method: 'POST',
               headers: {
                   'Content-Type': 'application/json',
                   'X-CSRF-TOKEN': '{{ csrf_token() }}'
               },
               body: JSON.stringify({ traiteur_id: traiteurId })
           })
           .then(response => response.json())
           .then(data => {
               if (data.success) {
                   // Fermer la modal
                   closeModal();

                   // Recharger la page pour voir la nouvelle conversation
                   window.location.reload();
               }
           })
           .catch(error => {
               console.error('Erreur lors de la création de la conversation:', error);

               // Enlever l'état de chargement
               const loadingDiv = this.querySelector('.absolute');
               if (loadingDiv) {
                   loadingDiv.remove();
               }

               // Afficher un message d'erreur
               alert('Une erreur est survenue lors de la création de la conversation.');
           });
       });
   });
   @endif

   // Gérer la touche Entrée dans le champ de saisie
   messageContentInput.addEventListener('keydown', function(e) {
       // Envoyer le message avec Entrée (mais permettre Shift+Entrée pour les sauts de ligne)
       if (e.key === 'Enter' && !e.shiftKey) {
           e.preventDefault();
           sendMessageForm.dispatchEvent(new Event('submit'));
       }
   });

   // Charger la première conversation si elle existe
   const firstConversation = document.querySelector('.conversation-item');
   if (firstConversation) {
       firstConversation.click();
   }

   // Gérer les erreurs Pusher
   pusher.connection.bind('error', function(err) {
       console.error('Pusher error:', err);
   });

   // Monitorer l'état de la connexion Pusher
   pusher.connection.bind('state_change', function(states) {
       console.log('État de la connexion Pusher:', states.current);
   });
});
</script>
@endsection
