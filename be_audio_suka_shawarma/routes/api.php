<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AudioWebRTCController;
use App\Http\Controllers\Auth\OperatorAuthController;
use App\Http\Controllers\Auth\OutletAuthController;
use App\Http\Controllers\Outlet\OutletController;
use App\Http\Controllers\Audio\AudioFileController;
use App\Http\Controllers\Broadcast\BroadcatsController;
use App\Http\Controllers\WebRTCController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {

    // Operator
    // Login Operator
    Route::post('/operator-login', [
        OperatorAuthController::class,
        'login'
    ]);


    // Outlet
    Route::post(
        '/outlet-connect',
        [OutletAuthController::class, 'connect']
    );

    // Protected routes
    Route::middleware('auth:api')->group(function () {

        Route::post('/logout', [
            OperatorAuthController::class,
            'logout'
        ]);
    });

    Route::post('/outlet-logout', [OutletAuthController::class, 'logout'])->middleware('jwt.outlet');
});


/*
|--------------------------------------------------------------------------
| Outlet
|--------------------------------------------------------------------------
*/

Route::prefix('outlet')->group(function () {

    Route::get('/', [OutletAuthController::class, 'index']);
    Route::get('/{id}', [OutletAuthController::class, 'show']);


    Route::post(
        '/heartbeat-online',
        [OutletController::class, 'heartBeat']
    )->middleware('jwt.outlet');

    Route::post(
        '/heartbeat-offline',
        [OutletController::class, 'disconnect']
    )->middleware('jwt.outlet');

    Route::post(
        '/fcm-token',
        [OutletAuthController::class, 'updateFcmToken']
    );

    Route::post(
        '/presence',
        [OutletController::class, 'updatePresence']
    );
});


/*
|--------------------------------------------------------------------------
| Audio
|--------------------------------------------------------------------------
*/

Route::prefix('audio')->middleware('jwt.auth')->group(function () {
    Route::post('/upload', [AudioFileController::class, 'upload']);
    Route::get('/', [AudioFileController::class, 'index']);
    Route::get('/{id}', [AudioFileController::class, 'show']);
    Route::delete('/{id}', [AudioFileController::class, 'destroy']);
});
Route::get(
    '/audio-stream/{filename}',
    [AudioFileController::class, 'stream']
);

/*
    |--------------------------------------------------------------------------
    | Broadcast
    |--------------------------------------------------------------------------
    */

Route::prefix('broadcast')->middleware('jwt.auth')->group(function () {
    Route::post('/start', [BroadcatsController::class, 'start']);
    Route::post('/{broadcastId}/end', [BroadcatsController::class, 'end']);
});

// Dipanggil outlet (bukan operator, tidak pakai jwt.auth) untuk
// cek broadcast live yang sedang berlangsung saat app baru dibuka.
Route::get('/broadcast/active', [BroadcatsController::class, 'active']);

/*
|--------------------------------------------------------------------------
| WEBRTC
|--------------------------------------------------------------------------
*/

Route::prefix('webrtc')->group(function () {

    // ============================================================
    // OPERATOR → OUTLET
    // ============================================================

    Route::post('/offer', [WebRTCController::class, 'offer']);
    Route::post('/answer', [WebRTCController::class, 'answer']);
    Route::post('/ice', [WebRTCController::class, 'ice']);
    Route::post('/operator-ice', [WebRTCController::class, 'operatorIceForBroadcast']);
    Route::post('/ready', [WebRTCController::class, 'receiverReady']);

    // ============================================================
    // OUTLET → OPERATOR
    // ============================================================

    Route::post('/outlet/mic/start', [WebRTCController::class, 'outletMicStar']);
    Route::post('/outlet/mic/stop', [WebRTCController::class, 'outletMicStop']);
    Route::post('/outlet/thumbs-up', [WebRTCController::class, 'thumbsUp']);
    Route::post('/outlet/offer', [WebRTCController::class, 'outletOffer']);
    Route::post('/operator/answer', [WebRTCController::class, 'operatorAnswer']);
    Route::post('/outlet/ice', [WebRTCController::class, 'outletIce']);
    Route::post('/operator/ice', [WebRTCController::class, 'operatorIce']);
});

/*
|--------------------------------------------------------------------------
| WEBRTC AUDIO
|--------------------------------------------------------------------------
*/


Route::prefix('audio/webrtc')->group(function () {

    Route::post(
        '/audio/broadcast',
        [AudioWebRTCController::class, 'audioBroadcast']
    );

    Route::post(
        '/audio/broadcast/end',
        [AudioWebRTCController::class, 'audioBroadcastEnd']
    );

    Route::post(
        '/offer',
        [AudioWebRTCController::class, 'offer']
    );

    Route::post(
        '/operator-ice',
        [AudioWebRTCController::class, 'operatorIce']
    );

    Route::post(
        '/answer',
        [AudioWebRTCController::class, 'answer']
    );

    Route::post(
        '/ice',
        [AudioWebRTCController::class, 'ice']
    );
});
