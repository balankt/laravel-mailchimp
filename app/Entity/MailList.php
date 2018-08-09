<?php

namespace App\Entity;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MailList extends Model
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
        'web_id',
        'name',
        'contact_id',
        'contact',
        'permission_reminder',
        'use_archive_bar',
        'campaign_defaults',
        'notify_on_subscribe',
        'notify_on_unsubscribe',
        'date_created',
        'list_rating',
        'email_type_option',
        'subscribe_url_short',
        'subscribe_url_long',
        'beamer_address',
        'visibility',
        'double_optin',
        'marketing_permissions',
        'stats',
    ];

    protected $casts = [
        'contact' => 'array',
        'campaign_defaults' => 'array',
        'stats' => 'array',
        'id' => 'string'
    ];

    public function setContactAttribute($value)
    {
        $this->attributes['contact'] = json_encode($value);
    }

    public function setCampaignDefaultsAttribute($value)
    {
        $this->attributes['campaign_defaults'] = json_encode($value);
    }

    public function setStatsAttribute($value)
    {
        $this->attributes['stats'] = json_encode($value);
    }

    public function setDateCreatedAttribute($value)
    {
        $this->attributes['date_created'] = Carbon::parse($value)->toDateTimeString();
    }
}
