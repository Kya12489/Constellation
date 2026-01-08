<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\Api\RnaAPI;
use App\Services\Api\WebAPI;

class AssociationController extends Controller
{
    public function listeAssociations(): Response
    {
        $page = max(1, (int) request()->get('page', 1)); 
        
        $assoAPI = new RnaAPI();
        $assosJson = $assoAPI->searchAssociations($page - 1, 10, []);

        foreach($assosJson["results"] as &$asso){
            if(isset($asso['website']) && !empty($asso['website'])){
                $url = $asso['website'];
                if(isset($url) && !empty($url)){
                    if(WebAPI::isUrlValid($url)){
                        $asso['website'] = $url;
                    } else {
                        $asso['website'] = "Invalid";
                    }
                }
            }
        }
        
        return Inertia::render('Associations/ListeAssociations', [
            'canLogin' => \Route::has('login'),
            'canRegister' => \Route::has('register'),
            'assos' => $assosJson['results'] ?? [],
            'currentPage' => $page, 
            'total' => $assosJson['total_count'] ?? 0, 
        ]);
    }
}