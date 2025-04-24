<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfService
{
    /**
     * Génère un PDF à partir d'une vue
     *
     * @param string $view Nom de la vue
     * @param array $data Données à passer à la vue
     * @param string $filename Nom du fichier à télécharger
     * @return \Illuminate\Http\Response
     */
    public function generatePdf($view, $data, $filename)
    {
        $pdf = PDF::loadView($view, $data);

        // Définir le papier au format A4 et en mode portrait
        $pdf->setPaper('a4', 'portrait');

        // Définir les options pour le PDF (facultatif)
        $pdf->setOptions([
            'defaultFont' => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => false
        ]);

        // Retourner le PDF en téléchargement forcé
        return $pdf->download($filename);
    }
}
