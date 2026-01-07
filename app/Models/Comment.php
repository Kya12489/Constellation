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
 * @property int $id
 * @property int $user_id
 * @property int $association_id
 * @property string $content
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $rating
 * 
 * @property User $user
 * @property Association $association
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

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function association()
	{
		return $this->belongsTo(Association::class);
	}
}
