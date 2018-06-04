<?php

namespace App\Models;

use App\Traits\HashableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Requirement
 * @property integer user_id
 * @property string description
 * @property integer address_id
 * @property integer additional_address_id
 * @property integer is_accepted
 *
 * @package App\Models
 */
class Requirement extends Model
{
    use SoftDeletes, HashableModel;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "requirements";

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
        'user_id',
        'description',
        'address_id',
        'additional_address_id',
        'is_accepted'
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
     **/
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requirementService()
    {
        return $this->hasMany(RequirementService::class, 'requirement_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function address()
    {
        return $this->hasOne(Address::class, 'id', 'address_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function additionalAddress()
    {
        return $this->hasOne(Address::class, 'id', 'additional_address_id');
    }
}
