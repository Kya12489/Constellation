<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Photo
 * 
 * @property int $id
 * @property int $association_id
 * @property int $type_image_id
 * @property string $url
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Association $association
 * @property TypesImage $types_image
 *
 * @package App\Models
 */
class Photo extends Model
{
	protected $table = 'photos';

	protected $casts = [
		'association_id' => 'int',
		'type_image_id' => 'int'
	];

	protected $fillable = [
		'association_id',
		'type_image_id',
		'url'
	];

	public function association()
	{
		return $this->belongsTo(Association::class);
	}

	public function types_image()
	{
		return $this->belongsTo(TypesImage::class, 'type_image_id');
	}
}
