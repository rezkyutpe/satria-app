<?php
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\api\RegisterController;
use App\Http\Controllers\api\AppsController;
use App\Http\Controllers\api\approval\ApprovalController;
use App\Http\Controllers\api\pajak\PajakController;
use App\Http\Controllers\api\pononsap\PickingController;
  
use App\Http\Controllers\api\incentive\IncController;
use App\Http\Controllers\api\incentive\ReqController;
use App\Http\Controllers\api\qfd\QfdApprovalController;
use App\Http\Controllers\api\partstracking\PartsTrackingController;
use App\Http\Controllers\api\potracking\PotrackingController;
use App\Http\Controllers\api\ccr\CCRController;

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
  
Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);
Route::get('profile/{id}', [RegisterController::class, 'show']);

Route::post('apps-get', [AppsController::class, 'get']);
Route::post('info-get', [AppsController::class, 'getInfo']);

Route::post('approval-get', [ApprovalController::class, 'get']);
Route::post('approval-detail', [ApprovalController::class, 'detail']);

Route::get('picking-get', [PickingController::class, 'get']);
Route::post('picking-scan', [PickingController::class, 'show']);
Route::post('picking-received', [PickingController::class, 'received']);
Route::post('picking-finished', [PickingController::class, 'finished']);
Route::post('picking-update', [PickingController::class, 'update']);
Route::get('picking-search/{id}', [PickingController::class, 'search']);


Route::get('pajak-get', [PajakController::class, 'get']);
Route::post('pajak-scan', [PajakController::class, 'show']);
Route::get('pajak-search/{id}', [PajakController::class, 'search']);
     
Route::post('inc-get', [IncController::class, 'get']);
Route::post('inc-report', [IncController::class, 'show']);
Route::post('inc-update', [IncController::class, 'update']);

Route::post('increquest-get', [ReqController::class, 'get']);
Route::post('increquest-report', [ReqController::class, 'show']); 

Route::post('inc-approval-get', [ReqController::class, 'appget']);
Route::get('inc-approval-view/{id}/{token}', [ReqController::class, 'view']);
Route::post('inc-approval-accept', [ReqController::class, 'accept']);
Route::post('inc-approval-approve', [ReqController::class, 'approve']); 

Route::post('qfd-approval-get', [QfdApprovalController::class, 'get']);
Route::post('qfd-approval-detail', [QfdApprovalController::class, 'show']);
Route::get('qfd-approval-view/{id}/{token}', [QfdApprovalController::class, 'view']);
Route::post('qfd-approval-accept', [QfdApprovalController::class, 'accept']);
Route::post('qfd-approval-approve', [QfdApprovalController::class, 'approve']);


Route::post('parts-get', [PartsTrackingController::class, 'get']);
Route::get('lokasi-get', [PartsTrackingController::class, 'getLokasi']);
Route::get('kondisi-get', [PartsTrackingController::class, 'getKondisi']);
Route::post('parts-view', [PartsTrackingController::class, 'show']);
Route::post('parts-update', [PartsTrackingController::class, 'update']);

//PO Tracking
Route::post('po-tracking-securityscan', [PotrackingController::class, 'securityscan']);
Route::post('po-tracking-getTicketList', [PotrackingController::class, 'getTicketList']);
Route::post('po-tracking-getTicketDetail', [PotrackingController::class, 'getTicketDetail']);
Route::post('po-tracking-incomingticket', [PotrackingController::class, 'getIncomingTicket']);
Route::post('po-tracking-historyscanticket', [PotrackingController::class, 'getHistoryScanTicket']);

// CCR
Route::get('ccr-dataPRO', [CCRController::class, 'dataPRO']);
Route::get('ccr-dataSN', [CCRController::class, 'dataSN']);
Route::get('ccr-material-ongoing', [CCRController::class, 'dataMaterialOnGoing']);

Route::middleware('auth:api')->group( function () {
    // Route::resource('products', ProductController::class);
});