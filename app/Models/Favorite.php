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
 * @property int $id
 * @property int $user_id
 * @property int $association_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Association $association
 *
 * @package App\Models
 */
class Favorite extends Model
{
	protected $table = 'favorites';

	protected $casts = [
		'user_id' => 'int',
		'association_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'association_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function association()
	{
		return $this->belongsTo(Association::class);
	}
}
