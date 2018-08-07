<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mailchimp;

class MembersController extends Controller
{
    private $mailChimp;

    public function __construct()
    {
        $this->mailChimp = new Mailchimp(env('MAILCHIMP_APIKEY'));
    }

    public function index(string $listId)
    {
        $result = $this->mailChimp->lists($listId)->members()->get();
        return $this->sendResponse($result);
    }

    public function show(string $listId, string $memberHash)
    {
        $result = $this->mailChimp->lists($listId)->members($memberHash)->get();
        return $this->sendResponse($result);
    }

    public function store(Request $request, string $listId)
    {
        $result = $this->mailChimp->lists($listId)->members()->post($request->all());
        return $this->sendResponse($result, 'created',201);
    }

    public function update(Request $request, string $listId, string $memberHash)
    {
        $result = $this->mailChimp->lists($listId)->members($memberHash)->patch($request->all());
        return $this->sendResponse($result, 'updated',200);
    }

    public function destroy(string $listId, string $memberHash)
    {
        $this->mailChimp->lists($listId)->members($memberHash)->delete();
        return $this->sendResponse(null, 'deleted',204);
    }
}
