<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TypesImage
 * 
 * @property int $id
 * @property string $libelle
 * 
 * @property Collection|Photo[] $photos
 *
 * @package App\Models
 */
class TypesImage extends Model
{
	protected $table = 'types_images';
	public $timestamps = false;

	protected $fillable = [
		'libelle'
	];

	public function photos()
	{
		return $this->hasMany(Photo::class, 'type_image_id');
	}
}
