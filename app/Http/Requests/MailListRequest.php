<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class MailListRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'contact' => 'required|array',
            'contact.company' => 'required|string',
            'contact.address1' => 'required|string',
            'contact.address2' => 'string',
            'contact.city' => 'required|string',
            'contact.state' => 'required|string',
            'contact.zip' => 'required|string',
            'contact.country' => 'required|string',
            'contact.phone' => 'string',
            'permission_reminder' => 'required|string',
            'use_archive_bar' => 'boolean',
            'campaign_defaults' => 'required|array',
            'campaign_defaults.from_name' => 'required|string',
            'campaign_defaults.from_email' => 'required|email',
            'campaign_defaults.subject' => 'required|string',
            'campaign_defaults.language' => 'required|string',
            'notify_on_subscribe' => 'email',
            'notify_on_unsubscribe' => 'email',
            'date_created' => 'date_format:"Y-m-d H:i:s"',
            'email_type_option' => 'required|boolean',
            'visibility' => Rule::in(['pub', 'prv']),
            'double_optin' => 'boolean',
            'marketing_permissions' => 'boolean',
        ];
    }
}
