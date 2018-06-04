<?php

namespace App\Models;

use App\Traits\HashableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Service
 * @property string name
 * @property integer user_id
 * @property integer lat
 * @property integer lng
 * @property string country
 * @property string state
 * @property string city
 * @property string address
 * @property string zip_code
 *
 * @package App\Models
 */
class Address extends Model
{
    use SoftDeletes, HashableModel;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "addresses";

    /**
     * This table doesn't have timestamps
     *
     * @var array
     */
    // public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'lat',
        'lng',
        'country',
        'state',
        'city',
        'address',
        'zip_code',
        'description'
    ];

    /**
     * The attributes excluded from the model's JSON form and mass assignment.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

}
