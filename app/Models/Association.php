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
 * @property int $id
 * @property string $rna_id
 * @property int|null $president_id
 * @property string|null $community_description
 * @property array|null $social_links
 * @property string|null $contact_email
 * @property string|null $contact_phone
 * @property string|null $website
 * @property bool|null $is_verified
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User|null $user
 * @property Collection|Comment[] $comments
 * @property Collection|Favorite[] $favorites
 * @property Collection|MembershipRequest[] $membership_requests
 * @property Collection|Photo[] $photos
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

	public function user()
	{
		return $this->belongsTo(User::class, 'president_id');
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
		return $this->hasMany(MembershipRequest::class);
	}

	public function photos()
	{
		return $this->hasMany(Photo::class);
	}
}
