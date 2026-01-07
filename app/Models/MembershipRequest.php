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
 * @property int $id
 * @property int $user_id
 * @property int $association_id
 * @property int $statut_id
 * @property string|null $message
 * @property string|null $phone
 * @property array|null $availability
 * @property array|null $skills
 * @property string|null $response_message
 * @property Carbon|null $responded_at
 * @property int|null $responded_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User|null $user
 * @property Association $association
 * @property StatutsDemande $statuts_demande
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

	public function user()
	{
		return $this->belongsTo(User::class, 'responded_by');
	}

	public function association()
	{
		return $this->belongsTo(Association::class);
	}

	public function statuts_demande()
	{
		return $this->belongsTo(StatutsDemande::class, 'statut_id');
	}
}
