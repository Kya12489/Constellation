<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Association
 * 
 * Modèle représentant une association française enregistrée
 * Contient les informations de base et les relations avec les commentaires, photos, etc.
 * 
 * @property int $id Clé primaire
 * @property string $rna_id Identifiant RNA unique de l'association
 * @property int|null $president_id ID du président de l'association
 * @property string|null $community_description Description communautaire de l'association
 * @property array|null $social_links Liens des réseaux sociaux (JSON)
 * @property string|null $contact_email Email de contact
 * @property string|null $contact_phone Téléphone de contact
 * @property string|null $website Site web officiel
 * @property bool|null $is_verified Statut de vérification
 * @property Carbon|null $created_at Date de création
 * @property Carbon|null $updated_at Date de dernière mise à jour
 * @property string|null $deleted_at Date de suppression (soft delete)
 * 
 * @property User|null $user Utilisateur (président) associé
 * @property Collection|Comment[] $comments Commentaires associés
 * @property Collection|Favorite[] $favorites Favoris associés
 * @property Collection|MembershipRequest[] $membership_requests Demandes d'adhésion
 * @property Collection|Photo[] $photos Photos associées
 *
 * @package App\Models
 */
class Association extends Model
{
	use SoftDeletes;
	protected $table = 'associations';

	protected $casts = [
		'president_id' => 'int',
		'social_links' => 'json',
		'is_verified' => 'bool'
	];

	protected $fillable = [
		'rna_id',
		'president_id',
		'community_description',
		'social_links',
		'contact_email',
		'contact_phone',
		'website',
		'is_verified'
	];

	/**
	 * Récupère l'utilisateur (président) associé à cette association
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Relation vers l'utilisateur
	 */
	public function user()
	{
		return $this->belongsTo(User::class, 'president_id');
	}

	/**
	 * Récupère tous les commentaires associés à cette association
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany Relation vers les commentaires
	 */
	public function comments()
	{
		return $this->hasMany(Comment::class);
	}

	/**
	 * Récupère tous les favoris associés à cette association
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany Relation vers les favoris
	 */
	public function favorites()
	{
		return $this->hasMany(Favorite::class);
	}

	/**
	 * Récupère toutes les demandes d'adhésion pour cette association
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany Relation vers les demandes d'adhésion
	 */
	public function membership_requests()
	{
		return $this->hasMany(MembershipRequest::class);
	}

	/**
	 * Récupère toutes les photos associées à cette association
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany Relation vers les photos
	 */
	public function photos()
	{
		return $this->hasMany(Photo::class);
	}
}
