<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\Api\RnaAPI;

class AssociationController extends Controller
{
    public function listeAssociations(){

        $assoAPI = new RnaAPI();
        $assosJson = $assoAPI->searchAssociations(0,10,[]);
        

        return Inertia::render('Associations/ListeAssociations',[
            'canLogin' => \Route::has('login'),
            'canRegister' => \Route::has('register'),
            'assosData' => $assosJson['data'] ?? null,
        ]);
    }
}
