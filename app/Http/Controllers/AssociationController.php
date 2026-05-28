<?php
namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Association;
use App\Models\Comment;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

/**
 * AssociationController - Contrôleur principal pour la gestion des associations
 * 
 * Gère l'affichage, la création et la suppression des associations, ainsi que
 * la gestion des commentaires et évaluations associées
 */
class AssociationController extends Controller
{
    private Association $associationModel;
    private Favorite $favoriteModel;
    
    /**
     * Constructeur - Initialise le modèle Association
     */
    function __construct()
    {
        $this->associationModel = new Association();
        $this->favoriteModel = new Favorite();
    }

    /**
     * Crée une nouvelle association avec un RNA ID donné
     *
     * @param string $rnaId Identifiant RNA unique de l'association
     * @return \Illuminate\Http\RedirectResponse Redirection vers la page de l'association
     */
    public function createAssociation(string $rnaId)
    {
        try {
            // Vérifier si l'association existe déjà
            $existing = Association::where('rna_id', $rnaId)->first();
            
            if ($existing) {
                return redirect()->to('/association/' . $rnaId)
                    ->with('info', 'Cette association existe déjà');
            }
            
            $association = new Association();
            $association->rna_id = $rnaId;
            $association->is_verified = false;
            $association->save();
            
            return redirect()->to('/association/' . $rnaId)
                ->with('success', 'Association créée avec succès');
                
        } catch (\Exception $e) {
            Log::error('Erreur création association: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la création de l\'association');
        }
    }

    /**
     * Affiche les détails d'une association avec ses commentaires et évaluations
     *
     * @param string $rnaId Identifiant RNA de l'association
     * @return Response Vue Inertia avec les données de l'association
     */
    public function detailAssociation(string $rnaId): Response
    {
        $association = $this->associationModel->where('rna_id', $rnaId)->firstOrFail();
        
        // Charger les commentaires avec les utilisateurs et trier par date décroissante
        $comments = $association->comments()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $nbFavoris = Favorite::where('idAssociation', $association->id)->count();

        $isUserFavoris = null;
        if(Auth::check()){
            $isUserFavoris = Favorite::where('idAssociation', $association->id)
                                    ->where('idUser', Auth::id())
                                    ->exists(); // retourne true/false
        }
        // Calculer la note moyenne
        $averageRating = $association->comments()
            ->whereNotNull('rating')
            ->avg('rating');
        
        // Calculer le nombre total de notes
        $totalRatings = $association->comments()
            ->whereNotNull('rating')
            ->count();
        
        // Vérifier si l'utilisateur connecté a déjà commenté
        $userComment = null;
        if (Auth::check()) {
            $userComment = $association->comments()
                ->where('user_id', Auth::id())
                ->first();
        }
        
        return Inertia::render('Associations/detail', [
            'association' => $association,
            'comments' => $comments,
            'favorites'=>["nbFavoris"=>$nbFavoris,"isFavoris"=>$isUserFavoris],
            'averageRating' => $averageRating ? round($averageRating, 1) : null,
            'totalRatings' => $totalRatings,
            'userComment' => $userComment,
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }

    /**
     * Ajoute un nouveau commentaire et une évaluation à une association
     *
     * @param Request $request Requête HTTP contenant le contenu et la note
     * @param string $rnaId Identifiant RNA de l'association
     * @return \Illuminate\Http\RedirectResponse Redirection avec message de succès/erreur
     */
    public function storeComment(Request $request, string $rnaId)
    {
        // Vérifier que l'utilisateur est connecté
        if (!Auth::check()) {
            return back()->with('error', 'Vous devez être connecté pour commenter');
        }
        
        $association = $this->associationModel->where('rna_id', $rnaId)->firstOrFail();
        
        // Valider les données
        $validated = $request->validate([
            'content' => 'required|string|min:10|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);
        
        // Vérifier si l'utilisateur a déjà commenté
        $existingComment = Comment::where('association_id', $association->id)
            ->where('user_id', Auth::id())
            ->first();
        
        if ($existingComment) {
            return back()->with('error', 'Vous avez déjà commenté cette association');
        }
        
        // Créer le commentaire
        Comment::create([
            'association_id' => $association->id,
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'rating' => $validated['rating'],
        ]);
        
        return back()->with('success', 'Commentaire ajouté avec succès');
    }

    /**
     * Met à jour un commentaire existant
     *
     * @param Request $request Requête HTTP contenant les données mises à jour
     * @param string $rnaId Identifiant RNA de l'association
     * @param int $commentId ID du commentaire à mettre à jour
     * @return \Illuminate\Http\RedirectResponse Redirection avec message de succès/erreur
     */
    public function updateComment(Request $request, string $rnaId, int $commentId)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Vous devez être connecté');
        }
        
        $comment = Comment::findOrFail($commentId);
        
        // Vérifier que c'est bien l'auteur du commentaire
        if ($comment->user_id !== Auth::id()) {
            return back()->with('error', 'Vous ne pouvez modifier que vos propres commentaires');
        }
        
        $validated = $request->validate([
            'content' => 'required|string|min:10|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);
        
        $comment->update($validated);
        
        return back()->with('success', 'Commentaire modifié avec succès');
    }

    /**
     * Supprime un commentaire existant
     *
     * @param string $rnaId Identifiant RNA de l'association
     * @param int $commentId ID du commentaire à supprimer
     * @return \Illuminate\Http\RedirectResponse Redirection avec message de succès/erreur
     */
    public function deleteComment(string $rnaId, int $commentId)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Vous devez être connecté');
        }
        
        $comment = Comment::findOrFail($commentId);
        
        // Vérifier que c'est bien l'auteur du commentaire
        if ($comment->user_id !== Auth::id()) {
            return back()->with('error', 'Vous ne pouvez supprimer que vos propres commentaires');
        }
        
        $comment->delete();
        
        return back()->with('success', 'Commentaire supprimé avec succès');
    }

    /**
     * Affiche la liste de toutes les associations
     *
     * @return Response Vue Inertia avec la liste des associations
     */
    public function listeAssociations(): Response
    {
        return Inertia::render('Associations/ListeAssociations', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }
}