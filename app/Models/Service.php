<?php

namespace App\Models;

use App\Traits\HashableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 * Class Service
 * @property integer company_id
 * @property integer service_category_id
 * @property string name
 * @property string description
 * @property string units
 * @property integer units_min
 * @property float cost_per_unit
 *
 * @package App\Models
 */
class Service extends Model
{
    use SoftDeletes, HashableModel;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "services";

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
        'company_id',
        'service_category_id',
        'name',
        'description',
        'units',
        'units_min',
        'cost_per_unit'
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function category()
    {
        return $this->hasOne(ServiceCategory::class, 'id', 'service_category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }


}
