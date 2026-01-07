<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StatutsDemande
 * 
 * @property int $id
 * @property string $libelle
 * 
 * @property Collection|MembershipRequest[] $membership_requests
 *
 * @package App\Models
 */
class StatutsDemande extends Model
{
	protected $table = 'statuts_demande';
	public $timestamps = false;

	protected $fillable = [
		'libelle'
	];

	public function membership_requests()
	{
		return $this->hasMany(MembershipRequest::class, 'statut_id');
	}
}
