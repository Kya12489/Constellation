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
 * Modèle représentant une photo/image d'une association
 * Chaque photo est liée à une association et a un type spécifique
 * 
 * @property int $id Clé primaire
 * @property int $association_id ID de l'association propriétaire
 * @property int $type_image_id ID du type d'image
 * @property string $url URL d'accès à l'image
 * @property Carbon|null $created_at Date de création
 * @property Carbon|null $updated_at Date de dernière mise à jour
 * 
 * @property Association $association Association propriétaire
 * @property TypesImage $types_image Type d'image
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

	/**
	 * Récupère l'association propriétaire de cette photo
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Relation vers l'association
	 */
	public function association()
	{
		return $this->belongsTo(Association::class);
	}

	/**
	 * Récupère le type d'image associé à cette photo
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Relation vers le type d'image
	 */
	public function types_image()
	{
		return $this->belongsTo(TypesImage::class, 'type_image_id');
	}
}
