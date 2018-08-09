<?php

namespace App\Services;

use App\Entity\ListMember;
use App\Jobs\DeleteListMember;
use App\Jobs\UpdateListMember;
use DrewM\MailChimp\MailChimp;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ListMemberService
{
    private $mailChimp;

    /**
     * ListMemberService constructor.
     * @param MailChimp $mailChimp
     */
    public function __construct(MailChimp $mailChimp)
    {
        $this->mailChimp = $mailChimp;
    }

    /**
     * @param String $listId
     * @return array
     */
    public function getAll(String $listId)
    {
        return ListMember::where('list_id', 'like', $listId)->get()->toArray();
    }

    /**
     * @param String $listId
     * @param String $memberHash
     * @return array
     */
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

    /**
     * @param String $listId
     * @param array $data
     * @return array
     */
    public function store(String $listId, Array $data)
    {
        $response = $this->mailChimp->post("lists/{$listId}/members", $data);
        if (!empty($response['status']) && $response['status'] === 400) {
            throw new \DomainException($response['detail']);
        }
        return ListMember::create($response)->toArray();
    }

    /**
     * @param String $listId
     * @param String $memberHash
     * @param array $data
     * @return array
     */
    public function update(String $listId, String $memberHash, Array $data)
    {
        $member = ListMember::where('list_id', 'like', $listId)->where('id', 'like', $memberHash)->first();
        if (!$member) {
            throw new ModelNotFoundException();
        }
        UpdateListMember::dispatch($listId, $memberHash, $data);
        $member->update($data);
        return $member->toArray();
    }

    /**
     * @param String $listId
     * @param String $memberHash
     * @return null
     * @throws \Exception
     */
    public function delete(String $listId, String $memberHash)
    {
        $member = ListMember::where('list_id', 'like', $listId)->where('id', 'like', $memberHash)->first();
        if (!$member) {
            throw new ModelNotFoundException();
        };
        DeleteListMember::dispatch($listId,$memberHash);
        $member->delete();
        return null;
    }
}