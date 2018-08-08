<?php

namespace App\Services;

use App\Entity\ListMember;
use DrewM\MailChimp\MailChimp;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ListMemberService
{
    private $mailChimp;

    public function __construct()
    {
        try {
            $this->mailChimp = new MailChimp(env('MAILCHIMP_APIKEY'));
        } catch (\Exception $exception) {

        }
    }

    public function getAll(String $listId)
    {
        $data = $this->mailChimp->get("lists/{$listId}/members");
        foreach ($data['members'] as $item) {
            $member = ListMember::where('id', 'like', $item['id'])->first();
            if ($member) {
                $member->update($item);
            } else {
                ListMember::create($item);
            }
        }
        return ListMember::all()->toArray();
    }

    public function getOne(String $listId, String $memberHash)
    {
        $member = ListMember::where('list_id', 'like', $listId)->where('id', 'like', $memberHash)->first();
        if (!$member) {
            $result = $this->mailChimp->get("lists/{$listId}/members/{$memberHash}");
            if (!empty($result['status']) && $result['status'] === 404) {
                throw new ModelNotFoundException();
            }
            $member = ListMember::create($result);
        }
        return $member->toArray();
    }

    public function store(String $listId, Array $data)
    {
        $response = $this->mailChimp->post("lists/{$listId}/members", $data);
        if (!empty($response['status']) && $response['status'] === 400) {
            throw new \DomainException($response['detail']);
        }
        return ListMember::create($response);
    }

    public function update(String $listId, String $memberHash, Array $data)
    {
        $member = ListMember::where('list_id', 'like', $listId)->where('id', 'like', $memberHash)->first();
        if (!$member) {
            throw new ModelNotFoundException();
        }
        $response = $this->mailChimp->patch("lists/{$listId}/members/{$memberHash}", $data);
        if (!empty($response['status']) && $response['status'] === 404) {
            throw new ModelNotFoundException();
        }
        $member->update($data);
        return $member->toArray();
    }

    public function delete(String $listId, String $memberHash)
    {
        $member = ListMember::where('list_id', 'like', $listId)->where('id', 'like', $memberHash)->first();
        if (!$member) {
            throw new ModelNotFoundException();
        };
        $member->delete();
        $this->mailChimp->delete("lists/{$listId}/members/{$memberHash}");
        return null;
    }
}