<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\MailListRequest;
use App\Services\MailListService;
use App\Http\Controllers\Controller;


class MailListsController extends Controller
{
    private $service;

    /**
     * ListsController constructor.
     * @param MailListService $service
     */
    public function __construct(MailListService $service)
    {
        $this->service = $service;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendResponse(['lists' => $this->service->getAll()]);
    }

    /**
     * @param string $listId
     * @return \Illuminate\Http\Response
     */
    public function show(string $listId)
    {
        return $this->sendResponse($this->service->getOne($listId));
    }

    /**
     * @param MailListRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(MailListRequest $request)
    {
        return $this->sendResponse($this->service->store($request->all()), 'created',201);
    }

    /**
     * @param MailListRequest $request
     * @param string $listId
     * @return \Illuminate\Http\Response
     */
    public function update(MailListRequest $request, string $listId)
    {
        return $this->sendResponse($this->service->update($listId, $request->all()), 'updated',200);
    }

    /**
     * @param string $listId
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $listId)
    {
        return $this->sendResponse($this->service->delete($listId), 'deleted',204);
    }
}
