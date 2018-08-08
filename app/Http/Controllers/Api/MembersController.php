<?php

namespace App\Http\Controllers\Api;

use App\Entity\ListMember;
use App\Http\Requests\ListMemberRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DrewM\MailChimp\MailChimp;
use Log;
use Validator;

class MembersController extends Controller
{
    private $mailChimpApi;

    public function __construct()
    {
        $this->mailChimpApi = new MailChimp(env('MAILCHIMP_APIKEY'));
    }

    public function index(string $listId)
    {
        $resultApi = $this->mailChimpApi->get("lists/{$listId}/members");
        /*  Log::channel('debug')->debug([
              '$resultApi'=>$resultApi,
        ]);*/

        //TODO: move to new method synchronize
        foreach ($resultApi['members'] as $item){
            $list = ListMember::where('id', 'like', $item['id'])->first();
            if ($list) {
                $list->update($item);
            } else {
                ListMember::create($item);
            }
        }
        $mailList = ListMember::all()->toArray();
        return $this->sendResponse([$mailList]);
    }

    public function show(string $listId, string $memberHash)
    {
        //TODO: move to new method synchronizeOne if not found
        $member = ListMember::where('list_id','like',$listId)->where('id', 'like', $memberHash)->first();
        if (!$member){
            $result = $this->mailChimpApi->get("lists/{$listId}/members/{$memberHash}");
            if (!empty($result['status']) && $result['status'] === 404){
                return $this->sendError('The requested resource could not be found');
            }
            $member= ListMember::create($result);
        }
        return $this->sendResponse($member);
    }

    public function store(Request $request, string $listId)
    {
        $list = new ListMemberRequest();
        $validator = Validator::make($request->all(), $list->rules());
        if ($validator->fails()) {
            return response()->json(['success'=> false, 'error'=> $validator->messages(), 'data'=>[]]);
        }
        $result = $this->mailChimpApi->post("lists/{$listId}/members", $request->all());
        if (!empty($result['status']) && $result['status'] === 400){
            return $this->sendError($result['detail'],[],400);
        }
        $member = ListMember::create($result);
        return $this->sendResponse($member, 'created',201);
    }

    public function update(Request $request, string $listId, string $memberHash)
    {
        $list = new ListMemberRequest();
        $validator = Validator::make($request->all(), $list->rules());
        if ($validator->fails()) {
            return response()->json(['success'=> false, 'error'=> $validator->messages(), 'data'=>[]]);
        }
        $result = $this->mailChimpApi->patch("lists/{$listId}/members/{$memberHash}", $request->all());
        if (!empty($result['status']) && $result['status'] === 404){
            return $this->sendError($result['detail']);
        }
        $member = ListMember::where('list_id','like',$listId)->where('id', 'like', $memberHash)->first();
        $member->update($request->all());
        return $this->sendResponse($member, 'updated',200);
    }


    public function destroy(string $listId, string $memberHash)
    {
        $mailList = ListMember::where('list_id','like',$listId)->where('id', 'like', $memberHash)->first();
        $mailList->delete();
        $this->mailChimpApi->delete("lists/{$listId}/members/{$memberHash}");
        return $this->sendResponse(null, 'deleted',204);
    }
}
