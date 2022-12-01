<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FileReportController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'user', 'controller' => AuthController::class], function () {
    Route::post('/login',  'login');
    Route::post('/register',  'register');
});



Route::group(['middleware' => ['auth:api']], function () {

    Route::group(['prefix' => 'user', 'controller' => AuthController::class], function () {
        Route::get('/profile',  'getUserProfile');
        Route::post('/logout',  'logout');
        Route::post('/change-password',  'changePassword');
    });
    Route::group(['prefix' => 'user', 'controller' => UserController::class], function () {
        Route::get('/all',  'getAllUsers');
    });
    Route::group(['prefix' => 'file', 'controller' => FileController::class], function () {
        Route::post('/upload',  'upload');
        Route::post('/moveFileToNewGroup',  'moveFileToNewGroup');
        Route::post('/copyFileToNewGroup',  'copyFileToNewGroup');
        Route::post('/edit',  'editFile');
        Route::post('/rename',  'renameFile');
        Route::post('/reserve',  'reserveFiles');
        Route::post('/cancelReserve/{file_id}',  'cancelReserveFile');

        Route::post('/delete/{file_id}',  'delete');
        /////////////////get api's////////////////
        // Route::get('/user-files',  'getAllUserFiles');
        Route::get('/group-files/{group_id}',  'getGroupFiles')->name('getGroupFiles');
    });
    Route::group(['prefix' => 'file', 'controller' => FileReportController::class], function () {

        /////////////////get api's////////////////
        Route::get('/reports/{file_id}',  'getFileReport');
    });

    Route::group(['prefix' => 'group', 'controller' => GroupController::class], function () {
        Route::post('/create',  'create');
        Route::post('/add-user',  'addUserToGroup');
        Route::post('/delete-user',  'deleteUserFromGroup');
        Route::post('/leftGroup/{group_id}',  'leftGroup');
        Route::post('/delete/{group_id}',  'deleteGroup');
        /////////////////get api's////////////////
        Route::get('/user-groups',  'getUserGroups');
        Route::get('/getGroupsByUser/{user_id}',  'getGroupsByUser');
    });
});



#########################################Test#########################
Route::group(['controller' => Controller::class], function () {
    Route::get('/test',  'test');
});
