<?php

namespace App\Services;

use App\Entity\MailList;
use DrewM\MailChimp\MailChimp;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MailListService
{
    private $mailChimp;

    public function __construct()
    {
        try {
            $this->mailChimp = new MailChimp(env('MAILCHIMP_APIKEY'));
        } catch (\Exception $exception) {

        }
    }

    public function getAll()
    {
        $data = $this->mailChimp->get("lists");
        foreach ($data['lists'] as $list) {
            $mailList = MailList::where('id', 'like', $list['id'])->first();
            if ($mailList) {
                $mailList->update($list);
            } else {
                MailList::create($list);
            }
        }
        return MailList::all()->toArray();
    }

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

    public function store(Array $data)
    {
        $response = $this->mailChimp->post("lists", $data);
        if (!empty($response['status']) && $response['status'] === 400) {
            throw new \DomainException($response['detail']);
        }
        return MailList::create($response);
    }

    public function update(String $listId, Array $data)
    {
        $list = MailList::where('id', 'like', $listId)->first();
        if (!$list) {
            throw new ModelNotFoundException();
        }
        $this->mailChimp->patch("lists/{$listId}", $data);
        if (!empty($result['status']) && $result['status'] === 404) {
            throw new ModelNotFoundException();
        }
        $list->update($data);
        return $list->toArray();
    }

    public function delete(String $listId)
    {
        $list = MailList::where('id', 'like', $listId)->first();
        if (!$list) {
            throw new ModelNotFoundException();
        }
        $list->delete();
        $this->mailChimp->delete("lists/{$listId}");
        return null;
    }
}