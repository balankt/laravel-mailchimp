<?php

namespace App\Http\Controllers\Api;

use App\Entity\MailList;
use App\Http\Requests\MailListRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use DrewM\MailChimp\MailChimp;

class ListsController extends Controller
{
    private $mailChimpApi;

    public function __construct()
    {
        $this->mailChimpApi = new MailChimp(env('MAILCHIMP_APIKEY'));
    }

    public function index()
    {
        $resultApi = $this->mailChimpApi->get("lists");
      /*  Log::channel('debug')->debug([
            '$resultApi'=>$resultApi,
      ]);*/

        //TODO: move to new method synchronize
        foreach ($resultApi['lists'] as $item){
            $list = MailList::where('id', 'like', $item['id'])->first();
            if ($list) {
                $list->update($item);
            } else {
                MailList::create($item);
            }
        }
        $mailList = MailList::all()->toArray();
        return $this->sendResponse([$mailList]);
    }

    public function show(string $listId)
    {
        //TODO: move to new method synchronizeOne if not found
       $mailList = MailList::where('id','like',$listId)->first();
       if (!$mailList){
           $result = $this->mailChimpApi->get("lists/{$listId}");
            if (!empty($result['status']) && $result['status'] === 404){
                return $this->sendError('The requested resource could not be found');
            }
          $mailList= MailList::create($result);
       }
        return $this->sendResponse($mailList);
    }

    public function store(Request $request)
    {
        $list = new MailListRequest();
        $validator = Validator::make($request->all(), $list->rules());
        if ($validator->fails()) {
            return response()->json(['success'=> false, 'error'=> $validator->messages(), 'data'=>[]]);
        }
        $result = $this->mailChimpApi->post("lists", $request->all());
        $savedList = MailList::create($result);
        return $this->sendResponse($savedList, 'created',201);
    }

    public function update(Request $request, string $listId)
    {
        $list = new MailListRequest();
        $validator = Validator::make($request->all(), $list->rules());
        if ($validator->fails()) {
            return response()->json(['success'=> false, 'error'=> $validator->messages(), 'data'=>[]]);
        }
        $result = $this->mailChimpApi->patch("lists/{$listId}", $request->all());
        $mailList = MailList::where('id','like',$listId)->first();
        $mailList->update($request->all());
        return $this->sendResponse([$result, $mailList], 'updated',200);
    }

    public function batchSubscribe(Request $request, string $listId)
        {
            $result = $this->mailChimpApi->post("lists/{$listId}", $request->all());
            return $this->sendResponse($result, 'created',200);
        }

    public function destroy(string $listId)
    {
        $mailList = MailList::where('id','like',$listId)->first();
        $mailList->delete();
        $this->mailChimpApi->delete("lists/{$listId}");
        return $this->sendResponse(null, 'deleted',204);
    }
}
