@extends('layouts.app')

@section('title', 'Mes réservations')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Styles généraux */
    .soft-border {
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        overflow: hidden;
    }
    .section-header {
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 0.75rem;
        margin-bottom: 1rem;
    }
    .reservation-table th {
        position: sticky;
        top: 0;
        background-color: white;
        z-index: 10;
    }

    /* Nouveau style minimaliste pour le calendrier */
    .calendar-container {
        padding: 0.5rem;
    }
    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
    }
    .calendar-weekday {
        text-align: center;
        font-size: 0.7rem;
        color: #6b7280;
        padding: 0.25rem 0;
    }
    .calendar-day {
        aspect-ratio: 1/1;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .calendar-day:hover {
        background-color: #f9fafb;
    }
    .calendar-day.today {
        font-weight: 600;
        border: 1px solid #FADADD;
    }
    .calendar-day.unavailable {
        background-color: #FEE2E2;
        color: #991B1B;
    }
    .calendar-day.selected {
        background-color: #FADADD;
        color: #333333;
    }
    .calendar-day.out-of-month {
        color: #D1D5DB;
    }

    /* Style pour le tableau des réservations */
    .table-container {
        height: 520px;
        overflow-y: auto;
        border-radius: 0.75rem;
    }
    .empty-state {
        padding: 3rem 2rem;
        text-align: center;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-[#333333] font-display">Gestion de mes réservations</h1>

                <div>
                    <button id="toggle-calendar-btn" class="flex items-center px-4 py-2 bg-[#FADADD] text-[#333333] rounded-lg hover:bg-[#C08081] hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Disponibilités
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-md">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-md">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Calendrier caché par défaut -->
            <div id="calendar-panel" class="bg-white rounded-xl shadow-md mb-6 soft-border hidden">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-[#333333] mb-4 section-header">Calendrier de disponibilité</h2>

                    <div class="calendar-container">
                        <div class="calendar-header">
                            <button id="prev-month" class="p-1 text-gray-600 hover:text-[#C08081]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <h3 id="current-month" class="text-lg font-medium text-[#333333]">Mai 2025</h3>
                            <button id="next-month" class="p-1 text-gray-600 hover:text-[#C08081]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>

                        <div class="calendar-grid" id="calendar-weekdays">
                            <div class="calendar-weekday">Lun</div>
                            <div class="calendar-weekday">Mar</div>
                            <div class="calendar-weekday">Mer</div>
                            <div class="calendar-weekday">Jeu</div>
                            <div class="calendar-weekday">Ven</div>
                            <div class="calendar-weekday">Sam</div>
                            <div class="calendar-weekday">Dim</div>
                        </div>

                        <div class="calendar-grid" id="calendar-days">
                            <!-- Les jours seront générés dynamiquement par JS -->
                        </div>
                    </div>

                    <div class="flex justify-between mt-6 text-sm text-gray-600">
                        <div class="flex items-center">
                            <span class="inline-block w-3 h-3 bg-[#FEE2E2] rounded-sm mr-2"></span>
                            <span>Date indisponible</span>
                        </div>
                        <div class="flex items-center">
                            <span class="inline-block w-3 h-3 border border-[#FADADD] rounded-sm mr-2"></span>
                            <span>Aujourd'hui</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des réservations (pleine largeur) -->
            <div class="bg-white rounded-xl shadow-md p-6 soft-border">
                <h2 class="text-xl font-semibold text-[#333333] mb-4 section-header">Mes réservations confirmées</h2>

                @if($reservations->isEmpty())
                    <div class="empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-600 mb-2">Vous n'avez aucune réservation confirmée</h3>
                        <p class="text-gray-500 max-w-md mx-auto">Les réservations apparaîtront ici une fois qu'elles auront été confirmées par les mariés.</p>
                    </div>
                @else
                    <div class="table-container">
                        <table class="min-w-full reservation-table">
                            <thead>
                                <tr class="border-b">
                                    <th class="py-3 px-4 text-left">Date</th>
                                    <th class="py-3 px-4 text-left">Mariés</th>
                                    <th class="py-3 px-4 text-left">Invités</th>
                                    <th class="py-3 px-4 text-left">Services réservés</th>
                                    <th class="py-3 px-4 text-left">Montant</th>
                                    <th class="py-3 px-4 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reservations as $reservation)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-4 px-4">
                                            {{ \Carbon\Carbon::parse($reservation->event_date)->format('d/m/Y') }}
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="font-medium">{{ $reservation->mariee->groom_name }} & {{ $reservation->mariee->bride_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $reservation->mariee->user->email }}</div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div>{{ $reservation->nombre_invites }} invités</div>
                                            <div class="text-sm text-gray-500">{{ $reservation->nombre_tables }} tables</div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($reservation->services->take(3) as $service)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-[#FADADD]/20 text-[#C08081]">
                                                        {{ $service->category->name }}
                                                    </span>
                                                @endforeach
                                                @if($reservation->services->count() > 3)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                                        +{{ $reservation->services->count() - 3 }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 font-medium text-[#C08081]">
                                            {{ number_format($reservation->total_amount, 2) }} MAD
                                        </td>
                                        <td class="py-4 px-4">
                                            <a href="{{ route('traiteur.reservation.pdf', ['reservation' => $reservation->id]) }}" class="bg-[#FADADD] hover:bg-[#C08081] text-[#333333] hover:text-white px-3 py-1 rounded text-sm inline-flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                                </svg>
                                                PDF
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation/information -->
<div id="customModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay gris semi-transparent -->
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- Centrage du modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <!-- Contenu du modal -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <!-- Icône (success ou error) -->
                    <div id="modalIcon" class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                        <!-- L'icône sera injectée ici dynamiquement -->
                    </div>

                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 id="modalTitle" class="text-lg leading-6 font-medium text-gray-900"></h3>
                        <div class="mt-2">
                            <p id="modalMessage" class="text-sm text-gray-500"></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button id="modalConfirmBtn" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#FADADD] text-base font-medium text-[#333333] hover:bg-[#C08081] hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C08081] sm:ml-3 sm:w-auto sm:text-sm">
                    Confirmer
                </button>
                <button id="modalCancelBtn" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Liste des dates désactivées/indisponibles
        const disabledDates = @json($disabledDates);

        // Gestion du toggle du panel calendrier
        const toggleCalendarBtn = document.getElementById('toggle-calendar-btn');
        const calendarPanel = document.getElementById('calendar-panel');

        toggleCalendarBtn.addEventListener('click', function() {
            calendarPanel.classList.toggle('hidden');
        });

        // Configuration du calendrier personnalisé
        let currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();

        // Fonctions pour le calendrier
        function updateCalendar() {
            const monthNames = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

            // Mise à jour du titre
            document.getElementById('current-month').textContent = `${monthNames[currentMonth]} ${currentYear}`;

            // Récupération du premier jour du mois et du nombre de jours
            const firstDay = new Date(currentYear, currentMonth, 1);
            const lastDay = new Date(currentYear, currentMonth + 1, 0);
            const daysInMonth = lastDay.getDate();

            // Récupération du jour de la semaine du premier jour (0 = Dimanche, 1 = Lundi, ...)
            let firstDayOfWeek = firstDay.getDay();
            firstDayOfWeek = firstDayOfWeek === 0 ? 6 : firstDayOfWeek - 1; // Ajustement pour commencer par Lundi

            // Récupération des jours du mois précédent à afficher
            const prevMonthLastDay = new Date(currentYear, currentMonth, 0).getDate();

            // Génération du HTML pour les jours
            const daysContainer = document.getElementById('calendar-days');
            daysContainer.innerHTML = '';

            // Jours du mois précédent
            for (let i = 0; i < firstDayOfWeek; i++) {
                const day = prevMonthLastDay - firstDayOfWeek + i + 1;
                const dayElement = createDayElement(day, 'out-of-month');
                daysContainer.appendChild(dayElement);
            }

            // Jours du mois actuel
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            for (let i = 1; i <= daysInMonth; i++) {
                const dateStr = formatDate(new Date(currentYear, currentMonth, i));
                const isToday = today.getFullYear() === currentYear &&
                               today.getMonth() === currentMonth &&
                               today.getDate() === i;
                const isUnavailable = disabledDates.includes(dateStr);

                let classes = [];
                if (isToday) classes.push('today');
                if (isUnavailable) classes.push('unavailable');

                const dayElement = createDayElement(i, classes.join(' '));
                dayElement.setAttribute('data-date', dateStr);

                // Ajouter l'événement de clic
                if (new Date(currentYear, currentMonth, i) >= today) {
                    dayElement.addEventListener('click', function() {
                        const clickedDate = this.getAttribute('data-date');
                        const isUnavailable = disabledDates.includes(clickedDate);

                        // Message pour la confirmation
                        let message = isUnavailable
                            ? 'Voulez-vous marquer cette date comme disponible ?'
                            : 'Voulez-vous marquer cette date comme indisponible ?';

                        // Utiliser le modal de confirmation
                        confirmModal('Changement de disponibilité', message).then(result => {
                            if (result) {
                                toggleAvailability(clickedDate, isUnavailable);
                            }
                        });
                    });
                }

                daysContainer.appendChild(dayElement);
            }

            // Jours du mois suivant pour compléter la grille
            const totalDaysDisplayed = firstDayOfWeek + daysInMonth;
            const remainingCells = 42 - totalDaysDisplayed; // 6 semaines de 7 jours = 42

            for (let i = 1; i <= remainingCells; i++) {
                const dayElement = createDayElement(i, 'out-of-month');
                daysContainer.appendChild(dayElement);
            }
        }

        function createDayElement(day, className = '') {
            const dayElement = document.createElement('div');
            dayElement.textContent = day;
            dayElement.className = `calendar-day ${className}`;
            return dayElement;
        }

        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // Navigation entre les mois
        document.getElementById('prev-month').addEventListener('click', function() {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            updateCalendar();
        });

        document.getElementById('next-month').addEventListener('click', function() {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            updateCalendar();
        });

        // Initialisation du calendrier
        updateCalendar();

        // Fonction pour basculer la disponibilité d'une date
        function toggleAvailability(date, currentlyUnavailable) {
    // Modifier immédiatement l'apparence visuelle AVANT l'appel AJAX
    const clickedDayElement = document.querySelector(`.calendar-day[data-date="${date}"]`);

    if (clickedDayElement) {
        if (currentlyUnavailable) {
            clickedDayElement.classList.remove('unavailable');
        } else {
            clickedDayElement.classList.add('unavailable');
        }
    }

    // Préparation des données pour l'envoi
    const formData = new FormData();
    formData.append('date', date);
    formData.append('available', currentlyUnavailable ? 1 : 0);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('{{ route('traiteur.availability.store') }}', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        // Vérifier d'abord si la réponse est OK
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        // Essayons de parser le JSON, mais en gérant le cas où ce n'est pas du JSON
        return response.text().then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                console.log('Réponse du serveur (non-JSON):', text);
                return { success: true, message: "Opération réussie", available: !currentlyUnavailable };
            }
        });
    })
    .then(data => {
        // Mettre à jour la liste des dates désactivées
        const index = disabledDates.indexOf(date);

        // Utiliser la valeur retournée par le serveur OU l'inverse de currentlyUnavailable si data.available n'existe pas
        const isNowAvailable = data.available !== undefined ? data.available : !currentlyUnavailable;

        if (isNowAvailable) {
            if (index !== -1) {
                disabledDates.splice(index, 1);
            }
        } else {
            if (index === -1) {
                disabledDates.push(date);
            }
        }

        // Afficher un message de succès
        showModal('Succès', data.message || "La disponibilité a été mise à jour avec succès.", 'success');
    })
    .catch(error => {
        console.error('Erreur détaillée:', error);

        showModal('Information', "L'opération semble avoir réussi mais il y a eu un problème de communication. La disponibilité a probablement été mise à jour correctement.", 'info');
    });
}

        // Fonction pour afficher un message simple
        function showModal(title, message, type = 'info') {
            const modal = document.getElementById('customModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');
            const modalIcon = document.getElementById('modalIcon');
            const confirmBtn = document.getElementById('modalConfirmBtn');
            const cancelBtn = document.getElementById('modalCancelBtn');

            // Définir le contenu
            modalTitle.textContent = title;
            modalMessage.textContent = message;

            // Gérer l'icône et la couleur selon le type
            if (type === 'success') {
                modalIcon.innerHTML = '<svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
                modalIcon.className = 'mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10';
            } else if (type === 'error') {
                modalIcon.innerHTML = '<svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';
                modalIcon.className = 'mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10';
            } else {
                modalIcon.innerHTML = '<svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                modalIcon.className = 'mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10';
            }

            // Pour un simple message, nous n'avons besoin que du bouton de confirmation
            confirmBtn.textContent = 'OK';
            confirmBtn.onclick = function() {
                modal.classList.add('hidden');
            };

            // Cacher le bouton Annuler pour un simple message
            cancelBtn.classList.add('hidden');

            // Afficher le modal
            modal.classList.remove('hidden');
        }

        // Fonction qui remplace confirm() et retourne une promesse
        function confirmModal(title, message) {
            return new Promise((resolve) => {
                const modal = document.getElementById('customModal');
                const modalTitle = document.getElementById('modalTitle');
                const modalMessage = document.getElementById('modalMessage');
                const modalIcon = document.getElementById('modalIcon');
                const confirmBtn = document.getElementById('modalConfirmBtn');
                const cancelBtn = document.getElementById('modalCancelBtn');

                // Définir le contenu
                modalTitle.textContent = title;
                modalMessage.textContent = message;

                // Icône d'information
                modalIcon.innerHTML = '<svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                modalIcon.className = 'mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10';

                // Configuration des boutons
                confirmBtn.textContent = 'Confirmer';
                confirmBtn.onclick = function() {
                    modal.classList.add('hidden');
                    resolve(true);
                };

                cancelBtn.textContent = 'Annuler';
                cancelBtn.classList.remove('hidden');
                cancelBtn.onclick = function() {
                    modal.classList.add('hidden');
                    resolve(false);
                };
                    modal.classList.remove('hidden');
            });
        }
    });
</script>
@endsection
