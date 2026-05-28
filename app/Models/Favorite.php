<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Favorite
 * 
 * Modèle représentant un favori d'un utilisateur pour une association
 * Permet aux utilisateurs de marquer leurs associations préférées
 * 
 * @property int $id Clé primaire
 * @property int $user_id ID de l'utilisateur
 * @property int $association_id ID de l'association
 * @property Carbon|null $created_at Date de création
 * @property Carbon|null $updated_at Date de dernière mise à jour
 * 
 * @property User $user Utilisateur propriétaire du favori
 * @property Association $association Association favorie
 *
 * @package App\Models
 */
class Favorite extends Model
{
	protected $table = 'favoris';

	protected $casts = [
		'user_id' => 'int',
		'association_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'association_id'
	];

	/**
	 * Récupère l'utilisateur propriétaire du favori
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Relation vers l'utilisateur
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * Récupère l'association favorie
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Relation vers l'association
	 */
	public function association()
	{
		return $this->belongsTo(Association::class);
	}
}
