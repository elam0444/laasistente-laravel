<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Company
 * @property string name
 * @property string activity
 *
 * @package App\Models
 */
class Company extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "companies";

    /**
     * This table doesn't have timestamps
     *
     * @var array
     */
    // public $timestamps = false;

    /**
     * The attributes that are dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'activity'
    ];

    /**
     * The attributes excluded from the model's JSON form and mass assignment.
     *
     * @var array
     */
    protected $guarded = ['id'];

}
