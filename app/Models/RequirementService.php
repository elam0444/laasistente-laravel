<?php

namespace App\Models;

use App\Traits\HashableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Requirement
 * @property integer requirement_id
 * @property integer service_id
 * @property integer associate_user_id
 * @property integer requirement_service_status_id
 * @property string delivery_datetime
 * @property integer qty
 * @property float total_cost
 *
 * @package App\Models
 */
class RequirementService extends Model
{
    use SoftDeletes, HashableModel;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "requirement_services";

    /**
     * This table doesn't have timestamps
     *
     * @var array
     */
    //public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'requirement_id',
        'service_id',
        'associate_user_id',
        'requirement_service_status_id',
        'delivery_date_time',
        'total_cost',
        'qty'
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function requirement()
    {
        return $this->belongsTo(Requirement::class, 'id', 'requirement_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function associate()
    {
        return $this->hasOne(User::class, 'id', 'associate_user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function status()
    {
        return $this->hasOne(RequirementServiceStatus::class, 'id', 'requirement_service_status_id');
    }

}
