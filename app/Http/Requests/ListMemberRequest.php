<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class ListMemberRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                    'email_address' =>'required|email',
                    'email_type' =>['string', Rule::in(['html', 'text'])],
                    'status' =>['required','string', Rule::in(['subscribed', 'unsubscribed','cleaned','pending'])],
                    'merge_fields' =>'array',
                    'interests' =>'array',
                    'language' =>'string',
                    'vip' =>'boolean',
                    'location' =>'array',
                    'location.latitude' =>'integer',
                    'location.longitude' =>'integer',
                    'ip_signup' =>'string',
                    'timestamp_signup' =>'date_format:"Y-m-d H:i:s"',
                    'ip_opt' =>'string',
                    'timestamp_opt' =>'date_format:"Y-m-d H:i:s"',
        ];
    }
}
