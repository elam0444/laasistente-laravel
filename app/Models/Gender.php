<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Gender
 * @property string name
 *
 * @package App\Models
 */
class Gender extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "gender";

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
        'name'
    ];

    /**
     * The attributes excluded from the model's JSON form and mass assignment.
     *
     * @var array
     */
    protected $guarded = ['id'];

}
