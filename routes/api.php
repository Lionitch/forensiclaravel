<?php

use Illuminate\Http\Request;
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
// Route::group([
//     'prefix' => 'auth'
// ], function () {
//     Route::post('/login', 'AuthController@Login');
//     Route::post('/signup', 'AuthController@Signup');
  
//     Route::group([
//       'middleware' => 'auth:api'
//     ], function() {
//         Route::get('/logout', 'AuthController@logout');
//         Route::get('/user', 'AuthController@user');
//     });
// });

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', 'ApiController@Login' );

Route::post('/signup', 'ApiController@SignUp' );

Route::post('/forget', 'ApiController@forget' );

Route::get('/test', 'ApiController@Test' );

Route::get('/verifying', 'ApiController@Verifying' );

Route::post('/approve', 'ApiController@Approve' );

Route::post('/deny', 'ApiController@Deny' );

Route::post('/newform', 'ApiController@Newform' );

Route::post('/evidence/{caseNo}', 'ApiController@evidence' );

Route::get('/getFile/{caseNo}', 'ApiController@getFile' );

Route::post('/pdf', 'ApiController@Pdf' );

Route::get('/verifyingPdf', 'ApiController@VerifyingPdf' );

Route::post('/approvePdf', 'ApiController@ApprovePdf' );

Route::post('/denyPdf', 'ApiController@DenyPdf' );

Route::post('/seePdf', 'ApiController@seePdf' );

Route::get('/verifiedPdf', 'ApiController@VerifiedPdf' );

Route::post('/madePdf', 'ApiController@madePdf' );

Route::post('/search', 'ApiController@search' );

//Route::post('/report', 'ApiController@Report' );