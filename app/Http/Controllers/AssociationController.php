<?php
namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Association;

class AssociationController extends Controller
{
    private Association $associationModel;
    function __construct()
    {
        $this->associationModel = new Association();
    }

    //créer l'association dans la base de données afin d'obtenir les données complete (president, commentaire, photos, etc) 
    // | erreur si ça existe déjà
    public function createAssociation(string $rnaId)
{
    try {
        $association = new Association();
        $association->rna_id = $rnaId;
        $association->is_verified = false;
        $association->save();
    } catch (\Exception $e) {
        if(str_contains($e->getMessage(), 'Cette association existe déjà')) {
            return redirect()->to('/association/' . $rnaId);
        }
        return back()->with('error', 'Erreur lors de la création de l\'association');
    }
    
    return redirect()->to('/association/' . $rnaId);
}

    public function detailAssociation(string $rnaId): Response
    {
        $association = $this->associationModel->where('rna_id', $rnaId)->firstOrFail();
        return Inertia::render('Associations/detail', [
            'association' => $association,
            'canLogin' => \Route::has('login'),
            'canRegister' => \Route::has('register'),
        ]);
    }

    public function listeAssociations(): Response
    {
        return Inertia::render('Associations/ListeAssociations', [
            'canLogin' => \Route::has('login'),
            'canRegister' => \Route::has('register'),
        ]);
    }
}