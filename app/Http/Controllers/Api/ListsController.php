<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mailchimp;

class ListsController extends Controller
{
    private $mailChimp;

    public function __construct()
    {
        $this->mailChimp = new Mailchimp(env('MAILCHIMP_APIKEY'));
    }

    public function index()
    {
        $result = $this->mailChimp->lists()->get();
        return $this->sendResponse($result);
    }

    public function show(string $listId)
    {
        $result = $this->mailChimp->lists($listId)->get();
        return $this->sendResponse($result);
    }

    public function store(Request $request)
    {
        $result = $this->mailChimp->lists()->post($request->all());
        return $this->sendResponse($result, 'created',201);
    }

    public function update(Request $request, string $listId)
    {
        $result = $this->mailChimp->lists($listId)->patch($request->all());
        return $this->sendResponse($result, 'updated',200);
    }

    public function batchSubscribe(Request $request, string $listId)
        {
            $result = $this->mailChimp->lists($listId)->BATCH_SUB($request->all(), true);
            return $this->sendResponse($result, 'created',200);
        }

    public function destroy(string $listId)
    {
        $this->mailChimp->lists($listId)->delete();
        return $this->sendResponse(null, 'deleted',204);
    }
}
