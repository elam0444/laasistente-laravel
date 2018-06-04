<?php

namespace App\Models;

use App\Traits\HashableModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RequirementServiceStatus
 * @property string id
 * @property string name
 *
 * @package App\Models
 */
class RequirementServiceStatus extends Model
{
    use HashableModel;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "requirement_service_status";

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
        'name',
        'slug',
    ];

    /**
     * The attributes excluded from the model's JSON form and mass assignment.
     *
     * @var array
     */
    protected $guarded = ['id'];

}
