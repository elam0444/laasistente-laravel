<?php

namespace App\Models;

use App\Traits\HashableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ServiceCategory
 * @property string slug
 * @property string name
 * @property string description
 *
 * @package App\Models
 */
class ServiceCategory extends Model
{
    use HashableModel;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "service_categories";

    /**
     * This table doesn't have timestamps
     *
     * @var array
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'name',
        'description'
    ];

    /**
     * The attributes excluded from the model's JSON form and mass assignment.
     *
     * @var array
     */
    protected $guarded = ['id'];

}
