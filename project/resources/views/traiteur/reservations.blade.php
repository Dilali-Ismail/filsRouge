@extends('layouts.app')

@section('title', 'Mes réservations')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css">
<style>
    /* Style pour le calendrier */
    .fc-day-today {
        background-color: rgba(250, 218, 221, 0.1) !important;
    }
    .fc-day-disabled {
        background-color: #f5f5f5 !important;
        text-decoration: line-through;
        cursor: not-allowed;
    }
    .fc-day-unavailable {
        background-color: rgba(255, 200, 200, 0.3) !important;
    }
    /* Style pour les boutons du calendrier */
    .fc-button-primary {
        background-color: #FADADD !important;
        border-color: #C08081 !important;
        color: #333333 !important;
    }
    .fc-button-primary:hover {
        background-color: #C08081 !important;
        color: white !important;
    }
    /* Style pour le tableau */
    .reservation-table th {
        position: sticky;
        top: 0;
        background-color: white;
        z-index: 10;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-[#333333] mb-6 font-display">Gestion de mes réservations</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Section avec deux colonnes : Calendrier et Réservations -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Calendrier de disponibilités -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold text-[#333333] mb-4">Calendrier de disponibilité</h2>
                    <p class="text-gray-600 mb-4">
                        Cliquez sur une date pour la marquer comme disponible ou indisponible. Les dates marquées en rouge sont indisponibles.
                    </p>

                    <div id="calendar" class="mt-4"></div>
                </div>

                <!-- Liste des réservations -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold text-[#333333] mb-4">Mes réservations confirmées</h2>

                    @if($reservations->isEmpty())
                        <p class="text-gray-500 text-center py-8">Vous n'avez aucune réservation confirmée.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full reservation-table">
                                <thead>
                                    <tr class="border-b">
                                        <th class="py-3 px-4 text-left">Date</th>
                                        <th class="py-3 px-4 text-left">Mariés</th>
                                        <th class="py-3 px-4 text-left">Invités</th>
                                        <th class="py-3 px-4 text-left">Montant</th>
                                        <th class="py-3 px-4 text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservations as $reservation)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="py-3 px-4">
                                                {{ \Carbon\Carbon::parse($reservation->event_date)->format('d/m/Y') }}
                                            </td>
                                            <td class="py-3 px-4">
                                                {{ $reservation->mariee->groom_name }} & {{ $reservation->mariee->bride_name }}
                                            </td>
                                            <td class="py-3 px-4">
                                                {{ $reservation->nombre_invites }}
                                                <span class="text-xs text-gray-500">({{ $reservation->nombre_tables }} tables)</span>
                                            </td>
                                            <td class="py-3 px-4 font-medium text-[#C08081]">
                                                {{ number_format($reservation->total_amount, 2) }} MAD
                                            </td>
                                            <td class="py-3 px-4">
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
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales-all.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Liste des dates désactivées
        const disabledDates = @json($disabledDates);

        // Initialisation du calendrier
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'fr',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek'
            },
            buttonText: {
                today: 'Aujourd\'hui',
                month: 'Mois',
                week: 'Semaine'
            },
            firstDay: 1, // Lundi comme premier jour
            dayMaxEvents: true,
            selectable: true,
            unselectAuto: false,

            // Personnalisation des jours
            dayCellClassNames: function(arg) {
                // Si la date est dans la liste des dates désactivées
                if (disabledDates.includes(arg.date.toISOString().split('T')[0])) {
                    return ['fc-day-unavailable'];
                }
                return [];
            },

            // Gestion du clic sur une date
            dateClick: function(info) {
                const clickedDate = info.dateStr;
                const isUnavailable = disabledDates.includes(clickedDate);

                // Vérifier si la date est dans le passé
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                if (new Date(clickedDate) < today) {
                    alert('Vous ne pouvez pas modifier la disponibilité des dates passées.');
                    return;
                }

                // Confirmer l'action
                let message = isUnavailable
                    ? 'Voulez-vous marquer cette date comme disponible ?'
                    : 'Voulez-vous marquer cette date comme indisponible ?';

                if (confirm(message)) {
                    toggleAvailability(clickedDate, isUnavailable);
                }
            }
        });

        calendar.render();

        // Fonction pour basculer la disponibilité d'une date
        function toggleAvailability(date, currentlyUnavailable) {
            // Appel AJAX pour marquer la date comme disponible/indisponible
            fetch('{{ route('traiteur.availability.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    date: date,
                    available: currentlyUnavailable // Si actuellement indisponible, on le marque comme disponible, et vice-versa
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Erreur: ' + data.error);
                    return;
                }

                // Mettre à jour la liste des dates désactivées
                const index = disabledDates.indexOf(date);
                if (data.available) {
                    // Si la date est maintenant disponible, on la retire de la liste
                    if (index !== -1) {
                        disabledDates.splice(index, 1);
                    }
                } else {
                    // Si la date est maintenant indisponible, on l'ajoute à la liste
                    if (index === -1) {
                        disabledDates.push(date);
                    }
                }

                // Rafraîchir le calendrier
                calendar.refetchEvents();
                calendar.render();

                // Afficher un message
                alert(data.message);
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de la modification de la disponibilité.');
            });
        }
    });
</script>
@endsection
