<?php

namespace App\Services;

use App\Entity\MailList;
use App\Jobs\DeleteMailList;
use App\Jobs\SynchronizeMailLists;
use App\Jobs\UpdateMailList;
use DrewM\MailChimp\MailChimp;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MailListService
{
    private $mailChimp;

    /**
     * MailListService constructor.
     * @param MailChimp $mailChimp
     */
    public function __construct(MailChimp $mailChimp)
    {
            $this->mailChimp = $mailChimp;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        SynchronizeMailLists::dispatch();
        return MailList::all()->toArray();
    }

    /**
     * @param String $listId
     * @return array
     */
    public function getOne(String $listId)
    {
        $list = MailList::where('id', 'like', $listId)->first();
        if (!$list) {
            $result = $this->mailChimp->get("lists/{$listId}");
            if (!empty($result['status']) && $result['status'] === 404) {
                throw new ModelNotFoundException();
            }
            $list = MailList::create($result);
        }
        return $list->toArray();
    }

    /**
     * @param array $data
     * @return array
     */
    public function store(Array $data)
    {
        $response = $this->mailChimp->post("lists", $data);
        if (!empty($response['status']) && $response['status'] === 400) {
            throw new \DomainException($response['detail']);
        }
        return MailList::create($response)->toArray();
    }

    /**
     * @param String $listId
     * @param array $data
     * @return array
     */
    public function update(String $listId, Array $data)
    {
        $list = MailList::where('id', 'like', $listId)->first();
        if (!$list) {
            throw new ModelNotFoundException();
        }
        UpdateMailList::dispatch($listId, $data);
        $list->update($data);
        return $list->toArray();
    }

    /**
     * @param String $listId
     * @return null
     * @throws \Exception
     */
    public function delete(String $listId)
    {
        $list = MailList::where('id', 'like', $listId)->first();
        if (!$list) {
            throw new ModelNotFoundException();
        }
        deleteMailList::dispatch($listId);
        $list->delete();
        return null;
    }
}