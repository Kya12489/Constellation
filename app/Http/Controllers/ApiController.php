<?php
namespace App\Http\Controllers;

use App\Models\Association;
use Illuminate\Http\Request;

/**
 * ApiController - Contrôleur API pour les associations
 * 
 * Gère les requêtes API liées aux associations, notamment la récupération des évaluations
 */
class ApiController extends Controller
{
    /**
     * Récupère la note moyenne et le nombre total d'évaluations pour une association
     *
     * @param string $rnaId Identifiant RNA de l'association
     * @return \Illuminate\Http\JsonResponse Réponse JSON contenant averageRating et totalRatings
     */
    public function getRateOf(string $rnaId)
    {
        // Trouver l'association par RNA ID
        $association = Association::where('rna_id', $rnaId)->first();
        
        if (!$association) {
            return response()->json([
                'averageRating' => null,
                'totalRatings' => 0
            ]);
        }
        
        // Calculer la note moyenne
        $averageRating = $association->comments()
            ->whereNotNull('rating')
            ->avg('rating');
        
        // Calculer le nombre total de notes
        $totalRatings = $association->comments()
            ->whereNotNull('rating')
            ->count();
        
        return response()->json([
            'averageRating' => $averageRating ? round($averageRating, 1) : null,
            'totalRatings' => $totalRatings
        ]);
    }
}