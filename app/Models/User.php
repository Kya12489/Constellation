<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Association[] $associations
 * @property Collection|Comment[] $comments
 * @property Collection|Favorite[] $favorites
 * @property Collection|MembershipRequest[] $membership_requests
 *
 * @package App\Models
 */
class User extends Model
{
	protected $table = 'users';

	protected $casts = [
		'email_verified_at' => 'datetime'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'email',
		'email_verified_at',
		'password',
		'remember_token'
	];

	public function associations()
	{
		return $this->hasMany(Association::class, 'president_id');
	}

	public function comments()
	{
		return $this->hasMany(Comment::class);
	}

	public function favorites()
	{
		return $this->hasMany(Favorite::class);
	}

	public function membership_requests()
	{
		return $this->hasMany(MembershipRequest::class, 'responded_by');
	}
}
