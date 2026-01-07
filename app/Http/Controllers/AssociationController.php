<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\Api\RnaAPI;

class AssociationController extends Controller
{
    public function listeAssociations(): Response
    {
        $page = max(1, (int) request()->get('page', 1)); 
        
        $assoAPI = new RnaAPI();
        $assosJson = $assoAPI->searchAssociations($page - 1, 10, []);
        
        return Inertia::render('Associations/ListeAssociations', [
            'canLogin' => \Route::has('login'),
            'canRegister' => \Route::has('register'),
            'assos' => $assosJson['results'] ?? [],
            'currentPage' => $page, 
            'total' => $assosJson['total_count'] ?? 0, 
        ]);
    }
}