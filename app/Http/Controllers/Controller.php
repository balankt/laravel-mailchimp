<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * success response method.
     *
     * @param $result
     * @param int $code
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $code = 200)
    {
        return response()->json(['success' => true, 'data' => $result], $code);
    }
}
