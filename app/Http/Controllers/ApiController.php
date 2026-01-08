<?php
namespace App\Http\Controllers;

use App\Models\Association;
use Illuminate\Http\Request;

class ApiController extends Controller
{
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