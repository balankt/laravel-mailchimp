<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ListMemberRequest;
use App\Http\Controllers\Controller;
use App\Services\ListMemberService;


class ListMembersController extends Controller
{
    private $service;

    /**
     * MembersController constructor.
     * @param ListMemberService $service
     */
    public function __construct(ListMemberService $service)
    {
        $this->service = $service;
    }

    /**
     * @param String $listId
     * @return \Illuminate\Http\Response
     */
    public function index(String $listId)
    {
        return $this->sendResponse(['members' => $this->service->getAll($listId)]);
    }

    /**
     * @param String $listId
     * @param String $memberHash
     * @return \Illuminate\Http\Response
     */
    public function show(String $listId, String $memberHash)
    {
        return $this->sendResponse($this->service->getOne($listId, $memberHash));
    }

    /**
     * @param ListMemberRequest $request
     * @param String $listId
     * @return \Illuminate\Http\Response
     */
    public function store(ListMemberRequest $request, String $listId)
    {
        return $this->sendResponse($this->service->store($listId, $request->all()), 'created', 201);
    }

    /**
     * @param ListMemberRequest $request
     * @param String $listId
     * @param String $memberHash
     * @return \Illuminate\Http\Response
     */
    public function update(ListMemberRequest $request, String $listId, String $memberHash)
    {
        return $this->sendResponse($this->service->update($listId, $memberHash, $request->all()), 'updated', 200);
    }

    /**
     * @param String $listId
     * @param String $memberHash
     * @return \Illuminate\Http\Response
     */
    public function destroy(String $listId, String $memberHash)
    {
        return $this->sendResponse($this->service->delete($listId, $memberHash), 'deleted', 204);
    }
}
