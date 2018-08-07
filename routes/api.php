<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
Route::group(['as' => 'api.', 'namespace' => 'Api'],
    function () {
        /**
         * Lists
         */
        Route::apiResource('lists', 'ListsController');
        /**
         * Members
         */
        Route::apiResource('lists.members', 'MembersController');
        Route::post('lists/{listId}', 'ListsController@batchSubscribe');
    });