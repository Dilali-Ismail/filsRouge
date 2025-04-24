<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Réservation #{{ $reservation->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
        }
        .title {
            font-size: 22px;
            color: #C08081;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 16px;
            color: #888;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .info-grid {
            display: block;
            margin-bottom: 20px;
        }
        .info-row {
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 180px;
        }
        .info-value {
            display: inline-block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #888;
        }
        .total {
            font-weight: bold;
            font-size: 16px;
            text-align: right;
            margin-top: 20px;
        }
        .total-value {
            color: #C08081;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Détails de la Réservation #{{ $reservation->id }}</div>
        <div class="subtitle">Généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</div>
    </div>

    <div class="section">
        <div class="section-title">Informations Générales</div>
        <div class="info-grid">
            <div class="info-row">
                <span class="info-label">Date de l'événement:</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($reservation->event_date)->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nombre d'invités:</span>
                <span class="info-value">{{ $reservation->nombre_invites }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nombre de tables:</span>
                <span class="info-value">{{ $reservation->nombre_tables }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Statut:</span>
                <span class="info-value">{{ ucfirst($reservation->status) }}</span>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Informations du Couple</div>
        <div class="info-grid">
            <div class="info-row">
                <span class="info-label">Nom du marié:</span>
                <span class="info-value">{{ $reservation->mariee->groom_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nom de la mariée:</span>
                <span class="info-value">{{ $reservation->mariee->bride_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $reservation->mariee->user->email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Ville:</span>
                <span class="info-value">{{ $reservation->mariee->city ?: 'Non spécifiée' }}</span>
            </div>
        </div>
    </div>

     <!-- Section Services Réservés -->
    <!-- Section Services Réservés -->
<div class="section">
    <div class="section-title">Services Réservés</div>

    @if(!empty($organizedServices))
        @foreach($organizedServices as $category => $services)
            <div style="margin-bottom: 15px;">
                <h3 style="color: #C08081; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 5px;">
                    {{ ucfirst($category) }}
                </h3>

                <ul style="list-style-type: none; padding-left: 10px;">
                    @foreach($services as $service)
                        <li style="margin-bottom: 8px;">
                            <strong>{{ $service['nom'] }}</strong>

                            @if(isset($service['catégorie']))
                                - {{ ucfirst($service['catégorie']) }}
                            @endif

                            @if(isset($service['style']))
                                ({{ ucfirst($service['style']) }})
                            @endif

                            <span style="float: right; color: #C08081;">
                                {{ number_format($service['prix'], 2) }} MAD
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach

        <div class="total">
            Total: <span class="total-value">{{ number_format($reservation->total_amount, 2) }} MAD</span>
        </div>
    @else
        <p>Aucun service réservé.</p>
    @endif
</div>

    <div class="footer">
        Ce document est un récapitulatif de réservation et ne constitue pas une facture officielle.
        <br>
        © {{ date('Y') }} Alf Mabrouk - Tous droits réservés.
    </div>
</body>
</html>
