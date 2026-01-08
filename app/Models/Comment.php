<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Comment
 * 
 * Modèle représentant un commentaire avec évaluation d'une association
 * Chaque commentaire est lié à un utilisateur et une association
 * 
 * @property int $id Clé primaire
 * @property int $user_id ID de l'utilisateur auteur du commentaire
 * @property int $association_id ID de l'association commentée
 * @property string $content Contenu textuel du commentaire
 * @property Carbon|null $created_at Date de création
 * @property Carbon|null $updated_at Date de dernière mise à jour
 * @property string|null $deleted_at Date de suppression (soft delete)
 * @property int|null $rating Note numérique de 1 à 5
 * 
 * @property User $user Utilisateur auteur du commentaire
 * @property Association $association Association commentée
 *
 * @package App\Models
 */
class Comment extends Model
{
	use SoftDeletes;
	protected $table = 'comments';

	protected $casts = [
		'user_id' => 'int',
		'association_id' => 'int',
		'rating' => 'int'
	];

	protected $fillable = [
		'user_id',
		'association_id',
		'content',
		'rating'
	];

	/**
	 * Récupère l'utilisateur auteur du commentaire
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Relation vers l'utilisateur
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * Récupère l'association commentée
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Relation vers l'association
	 */
	public function association()
	{
		return $this->belongsTo(Association::class);
	}
}
