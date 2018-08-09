<?php

namespace App\Entity;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ListMember extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'email_address',
        'unique_email_id',
        'email_type',
        'status',
        'unsubscribe_reason',
        'merge_fields',
        'interests',
        'stats',
        'ip_signup',
        'timestamp_signup',
        'ip_opt',
        'timestamp_opt',
        'member_rating',
        'last_changed',
        'language',
        'vip',
        'email_client',
        'location',
        'marketing_permissions',
        'list_id',
    ];

    protected $casts = [
        'merge_fields' => 'array',
        'interests' => 'array',
        'stats' => 'array',
        'location' => 'array',
        'marketing_permissions' => 'array',
        'id' => 'string'
    ];

    protected $attributes = [
        'unsubscribe_reason' => '',
        'interests' => '',
        'marketing_permissions' => '',
    ];

    public function setMergeFieldsAttribute($value)
    {
        $this->attributes['merge_fields'] = json_encode($value);
    }

    public function setInterestsAttribute($value)
    {
        $this->attributes['interests'] = json_encode($value);
    }

    public function setStatsAttribute($value)
    {
        $this->attributes['stats'] = json_encode($value);
    }

    public function setLocationAttribute($value)
    {
        $this->attributes['location'] = json_encode($value);
    }

    public function setMarketingPermissionsAttribute($value)
    {
        $this->attributes['marketing_permissions'] = json_encode($value);
    }

    public function setLastChangedAttribute($value)
    {
        $this->attributes['last_changed'] = Carbon::parse($value)->toDateTimeString();
    }

    public function setTimestampOptAttribute($value)
    {
        $this->attributes['timestamp_opt'] = Carbon::parse($value)->toDateTimeString();
    }

}
