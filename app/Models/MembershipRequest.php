<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MembershipRequest
 * 
 * Modèle représentant une demande d'adhésion à une association
 * Permet aux utilisateurs de demander à rejoindre une association
 * Les présidents peuvent accepter ou refuser ces demandes
 * 
 * @property int $id Clé primaire
 * @property int $user_id ID de l'utilisateur demandeur
 * @property int $association_id ID de l'association ciblée
 * @property int $statut_id ID du statut de la demande
 * @property string|null $message Message de motivation du demandeur
 * @property string|null $phone Téléphone de contact du demandeur
 * @property array|null $availability Disponibilités du demandeur (JSON)
 * @property array|null $skills Compétences du demandeur (JSON)
 * @property string|null $response_message Message de réponse du président
 * @property Carbon|null $responded_at Date de réponse
 * @property int|null $responded_by ID de l'utilisateur ayant répondu
 * @property Carbon|null $created_at Date de création
 * @property Carbon|null $updated_at Date de dernière mise à jour
 * 
 * @property User|null $user Utilisateur demandeur
 * @property Association $association Association ciblée
 * @property StatutsDemande $statuts_demande Statut de la demande
 *
 * @package App\Models
 */
class MembershipRequest extends Model
{
	protected $table = 'membership_requests';

	protected $casts = [
		'user_id' => 'int',
		'association_id' => 'int',
		'statut_id' => 'int',
		'availability' => 'json',
		'skills' => 'json',
		'responded_at' => 'datetime',
		'responded_by' => 'int'
	];

	protected $fillable = [
		'user_id',
		'association_id',
		'statut_id',
		'message',
		'phone',
		'availability',
		'skills',
		'response_message',
		'responded_at',
		'responded_by'
	];

	/**
	 * Récupère l'utilisateur demandeur (à titre informatif)
	 * Note: Cette relation utilise responded_by au lieu de user_id
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Relation vers l'utilisateur
	 */
	public function user()
	{
		return $this->belongsTo(User::class, 'responded_by');
	}

	/**
	 * Récupère l'association ciblée par la demande d'adhésion
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Relation vers l'association
	 */
	public function association()
	{
		return $this->belongsTo(Association::class);
	}

	/**
	 * Récupère le statut de la demande d'adhésion
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Relation vers le statut
	 */
	public function statuts_demande()
	{
		return $this->belongsTo(StatutsDemande::class, 'statut_id');
	}
}
