<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\Api\RnaAPI;

class AssociationController extends Controller
{
    public function listeAssociations()
    {
        $assoAPI = new RnaAPI();
        $assosJson = $assoAPI->searchAssociations(0, 10, []);
        
        // Debug: Ajoutez ceci pour voir ce qui est retourné
        \Log::info('Associations data:', ['data' => $assosJson]);
        
        return Inertia::render('Associations/ListeAssociations', [
            'canLogin' => \Route::has('login'),
            'canRegister' => \Route::has('register'),
            // Changé 'assosData' en 'assos' pour correspondre à Vue
            // Et récupérez 'results' au lieu de 'data'
            'assos' => $assosJson['results'] ?? [],
        ]);
    }
}