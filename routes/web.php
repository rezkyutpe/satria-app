<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\View\VwPermissionAppsMenu;

/*
|--------------------------------------------------------------------------
| Web Routes SATRIA
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route::get('/clear-cache', function() {
//     $exitCode = Artisan::call('cache:clear');
//     $exitCodes = Artisan::call('config:clear');
//     return $exitCodes;
// });
Auth::routes(['register' => false]);
// Auth::routes(['verify' => true]);

Route::post('login-in', [App\Http\Controllers\Auth\LoginController::class, 'authenticate']);
Route::get('login-in', [App\Http\Controllers\HomeController::class, 'index']);
// ;->middleware("throttle:global");
Route::middleware('auth')->group(function() {
Route::get('/ems-redirect',  [App\Http\Controllers\EmsController::class, 'index'])->middleware('verified');
Route::get('/satria-mms',  [App\Http\Controllers\MmsController::class, 'index'])->middleware('verified');
Route::get('/home',  [App\Http\Controllers\HomeController::class, 'index'])->middleware('verified');
// Route::get('/dashboard',  [App\Http\Controllers\DashboardController::class, 'index'])->middleware('verified');
Route::get('/dashboard',  [App\Http\Controllers\DashboardMController::class, 'index'])->middleware('verified');
Route::get('/dashboard-maintenance',  [App\Http\Controllers\DashboardMController::class, 'index'])->middleware('verified');
// Route::get('/dashboard-maintenance-user',  [App\Http\Controllers\HomeController::class, 'DashboardUserNew'])->middleware('verified');
Route::get('/dashboard-user',  [App\Http\Controllers\DashboardMUserController::class, 'index'])->middleware('verified');
Route::get('/dashboard-head',  [App\Http\Controllers\DashboardHeadController::class, 'index'])->middleware('verified');
Route::get('get-attendant-ratio-grafik',  [App\Http\Controllers\DashboardHeadController::class, 'DashboardAttendanceRatio'])->middleware('verified');
Route::post('get-attendant-ratio-grafik',  [App\Http\Controllers\DashboardHeadController::class, 'DashboardAttendanceRatio'])->middleware('verified');
Route::get('approval-submission', [App\Http\Controllers\DashboardHeadController::class, 'TrxWfa']);
Route::get('approval-pr', [App\Http\Controllers\DashboardHeadController::class, 'TrxPR']);
Route::get('approval-ticket', [App\Http\Controllers\DashboardHeadController::class, 'TrxTicket']);
Route::post('/get-eletter-performance',  [App\Http\Controllers\DashboardController::class, 'generateEletterPerformance'])->middleware('verified');
Route::post('/get-eletter-competence',  [App\Http\Controllers\DashboardController::class, 'generateEletterCompetence'])->middleware('verified');
Route::get('/satria-dashboard',  [App\Http\Controllers\DashboardController::class, 'satriadashboard'])->middleware('verified');
Route::get('/detail-logs/{id}',  [App\Http\Controllers\DashboardController::class, 'getDetailLogs'])->middleware('verified');
Route::get('/resolve-percentage',  [App\Http\Controllers\DashboardController::class, 'resolvepercentage'])->middleware('verified');
Route::get('get-dashboard',  [App\Http\Controllers\DashboardController::class, 'getDashboard'])->middleware('verified');
Route::get('/home-satria',  [App\Http\Controllers\HomeController::class, 'home'])->middleware('verified');
Route::get('/about',  [App\Http\Controllers\HomeController::class, 'about'])->middleware('verified');
Route::get('/faq',  [App\Http\Controllers\HomeController::class, 'faq'])->middleware('verified');
Route::get('/welcome',  [App\Http\Controllers\HomeController::class, 'welcome'])->name('welcome')->middleware('verified');
Route::get('/search-location/{search}',  [App\Http\Controllers\RadarMapsController::class, 'searchAddresss'])->name('searchAddresss')->middleware('verified');
Route::get('/distance/{lat}/{long}',  [App\Http\Controllers\RadarMapsController::class, 'locationDistance'])->name('locationDistance')->middleware('verified');
Route::post('/post-submission',  [App\Http\Controllers\RadarMapsController::class, 'PostSubmission'])->name('PostSubmission')->middleware('verified');
Route::get('user-submission', [App\Http\Controllers\RadarMapsController::class, 'UserSubmission'])->name('Your Submission List');
Route::get('/user-submission-detail/{id}',  [App\Http\Controllers\RadarMapsController::class, 'UserSubmissionDetail'])->name('UserSubmissionDetail')->middleware('verified');
Route::post('wfa-approval-submit',  [App\Http\Controllers\DashboardHeadController::class, 'WfaActionApproval']);
Route::post('pr-approval-submit',  [App\Http\Controllers\DashboardHeadController::class, 'PrActionApproval']);
Route::post('ticket-approval-submit',  [App\Http\Controllers\DashboardHeadController::class, 'TicketActionApproval']);

Route::middleware('verified')->group(function() {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->middleware('verified');
    if (env('ENV') == 'ADMIN'){
        Route::get('apps-management',  [App\Http\Controllers\Cms\AppsManController::class, 'AppsMgmtInit']);
        Route::post('insert-apps', [App\Http\Controllers\Cms\AppsManController::class, 'AppsMgmtInsert']);
        Route::post('update-apps', [App\Http\Controllers\Cms\AppsManController::class, 'AppsMgmtUpdate']);
        Route::post('delete-apps', [App\Http\Controllers\Cms\AppsManController::class, 'AppsMgmtDelete']);

        Route::get('menu-management',  [App\Http\Controllers\Cms\MenuManController::class, 'MenuMgmtInit']);
        Route::get('get-menu/{app}',  [App\Http\Controllers\Cms\MenuManController::class, 'getMenu']);
        Route::post('insert-menu', [App\Http\Controllers\Cms\MenuManController::class, 'MenuMgmtInsert']);
        Route::post('update-menu', [App\Http\Controllers\Cms\MenuManController::class, 'MenuMgmtUpdate']);
        Route::post('delete-menu', [App\Http\Controllers\Cms\MenuManController::class, 'MenuMgmtDelete']);

        Route::get('user-dashboard',  [App\Http\Controllers\Cms\UserManController::class, 'UserMgmtDashboard']);
        Route::get('user-management',  [App\Http\Controllers\Cms\UserManController::class, 'UserMgmtInit']);
        Route::get('user-performance',  [App\Http\Controllers\Cms\UserPerformanceManController::class, 'UserPerformanceMgmtInit']);
        Route::post('preview-raw-performance',  [App\Http\Controllers\Cms\UserPerformanceManController::class, 'UserPerformanceMgmtPreview']);
        Route::post('insert-user-performance',  [App\Http\Controllers\Cms\UserPerformanceManController::class, 'UserPerformanceMgmtInsert']);
        Route::get('user-competence',  [App\Http\Controllers\Cms\UserComptenceManController::class, 'UserComptenceMgmtInit']);
        Route::post('preview-raw-competence',  [App\Http\Controllers\Cms\UserComptenceManController::class, 'UserComptenceMgmtPreview']);
        Route::post('insert-user-competence',  [App\Http\Controllers\Cms\UserComptenceManController::class, 'UserComptenceMgmtInsert']);
        Route::get('user-attendance-list',  [App\Http\Controllers\Cms\UserManController::class, 'UserMgmtAttendanceList']);
        Route::get('user-subcont',  [App\Http\Controllers\Cms\UserManController::class, 'UserMgmtSubcont']);
        Route::post('insert-subcont', [App\Http\Controllers\Cms\UserManController::class, 'UserMgmtSubcontInsert']);
        Route::post('update-subcont', [App\Http\Controllers\Cms\UserManController::class, 'UserMgmtSubcontUpdate']);
        Route::get('get-user-sf/{id}',  [App\Http\Controllers\Cms\UserManController::class, 'GetUserSfForDropdown']);
        Route::get('user-safety-hour',  [App\Http\Controllers\Cms\UserManController::class, 'UserMgmtSafetyHour']); 
        Route::post('user-safety-hour',  [App\Http\Controllers\Cms\UserManController::class, 'UserMgmtSafetyHour']); 
        Route::post('export-safety-hour',  [App\Http\Controllers\Cms\UserManController::class, 'ExportSafetyHour']);
        Route::get('user-attendance-ratio',  [App\Http\Controllers\Cms\UserManController::class, 'UserMgmtAttendanceRatio']);
        Route::post('user-attendance-ratio',  [App\Http\Controllers\Cms\UserManController::class, 'UserMgmtAttendanceRatio']);
        Route::get('get-departement/{id}',  [App\Http\Controllers\Cms\UserManController::class, 'GetDepartementForDropdown']);
        Route::post('export-attendance-ratio',  [App\Http\Controllers\Cms\UserManController::class, 'ExportAttendanceRatio']);
        Route::get('user-detail/{id}',  [App\Http\Controllers\Cms\UserManController::class, 'UserMgmtDetail']);
        Route::get('user-view/{id}',  [App\Http\Controllers\Cms\UserManController::class, 'UserMgmtView']);
        Route::post('insert-user', [App\Http\Controllers\Cms\UserManController::class, 'UserMgmtInsert']);
        Route::post('update-user', [App\Http\Controllers\Cms\UserManController::class, 'UserMgmtUpdate']);
        Route::post('reset-pass-user', [App\Http\Controllers\Cms\UserManController::class, 'UserMgmtResetPass']);
        Route::post('delete-user',  [App\Http\Controllers\Cms\UserManController::class, 'UserMgmtDelete']);
        Route::get('get-usersf/{id}', [App\Http\Controllers\HomeController::class, 'UserMgmtViewSF']);

        Route::get('rolegroup-management',  [App\Http\Controllers\Cms\RoleGroupController::class, 'RoleGroupMgmtInit']);
        Route::get('rolegroup-view/{id}',  [App\Http\Controllers\Cms\RoleGroupController::class, 'RoleGroupMgmtView']);
        Route::post('insert-menu-rolegroup', [App\Http\Controllers\Cms\RoleGroupController::class, 'RoleGroupMgmtPermissionAdd']);
        Route::post('insert-rolegroup', [App\Http\Controllers\Cms\RoleGroupController::class, 'RoleGroupMgmtInsert']);
        Route::post('insert-user-rolegroup', [App\Http\Controllers\Cms\RoleGroupController::class, 'RoleGroupMgmtAddUser']);
        Route::post('update-menu-rolegroup', [App\Http\Controllers\Cms\RoleGroupController::class, 'RoleGroupMgmtPermissionUpdate']);
        Route::post('delete-menu-rolegroup', [App\Http\Controllers\Cms\RoleGroupController::class, 'RoleGroupMgmtPermissionDelete']);
        Route::post('update-rolegroup', [App\Http\Controllers\Cms\RoleGroupController::class, 'RoleGroupMgmtUpdate']);
        Route::post('delete-rolegroup', [App\Http\Controllers\Cms\RoleGroupController::class, 'RoleGroupMgmtDelete']);
        Route::get('delete-user-rolegroup/{id}', [App\Http\Controllers\Cms\RoleGroupController::class, 'RoleGroupMgmtDeleteUser']);
        Route::get('get-rolegroup-view/{id}',  [App\Http\Controllers\Cms\RoleGroupController::class, 'GetUserRoleGroupView']);

        Route::get('config-app', [App\Http\Controllers\HomeController::class, 'UserMgmtConfigApp']);
        Route::post('update-config', [App\Http\Controllers\HomeController::class, 'UserMgmtUpdateConfig']);
        Route::get('profile', [App\Http\Controllers\HomeController::class, 'UserMgmtProfile']);
        Route::get('profile-admin', [App\Http\Controllers\HomeController::class, 'UserMgmtProfileAdmin']);
        Route::get('profile-password', [App\Http\Controllers\HomeController::class, 'UserMgmtProfilePassword']);
        Route::post('update-profile-user', [App\Http\Controllers\HomeController::class, 'UserMgmtUpdateProfile']);
        Route::post('change-password', [App\Http\Controllers\HomeController::class, 'UserMgmtChangePass']);


        Route::post('insert-appsmenu', [App\Http\Controllers\Cms\UserManController::class, 'UserMgmtInsertAppMenu']);
        Route::post('update-appsmenu', [App\Http\Controllers\Cms\UserManController::class, 'UserMgmtUpdateAppMenu']);
        Route::post('delete-appsmenu', [App\Http\Controllers\Cms\UserManController::class, 'UserMgmtDeleteAppMenu']);


        Route::get('komponen-management',  [App\Http\Controllers\Cms\PoNonSAP\KomponenManController::class, 'KomponenMgmtInit']);
        Route::get('get-komponen/{id}', [App\Http\Controllers\Cms\PoNonSAP\KomponenManController::class, 'getKomponen']);
        Route::post('insert-komponen', [App\Http\Controllers\Cms\PoNonSAP\KomponenManController::class, 'KomponenMgmtInsert']);
        Route::post('update-komponen', [App\Http\Controllers\Cms\PoNonSAP\KomponenManController::class, 'KomponenMgmtUpdate']);
        Route::post('delete-komponen', [App\Http\Controllers\Cms\PoNonSAP\KomponenManController::class, 'KomponenMgmtDelete']);

        Route::get('package-management',  [App\Http\Controllers\Cms\PoNonSAP\PackageManController::class, 'PackageMgmtInit']);
        Route::get('view-package/{package}', [App\Http\Controllers\Cms\PoNonSAP\PackageManController::class, 'PackageMgmtView']);
        Route::post('upload-package', [App\Http\Controllers\Cms\PoNonSAP\PackageManController::class, 'PackageUpload']);
        Route::post('insert-package', [App\Http\Controllers\Cms\PoNonSAP\PackageManController::class, 'PackageMgmtInsert']);
        Route::post('update-package', [App\Http\Controllers\Cms\PoNonSAP\PackageManController::class, 'PackageMgmtUpdate']);
        Route::post('delete-package', [App\Http\Controllers\Cms\PoNonSAP\PackageManController::class, 'PackageMgmtDelete']);

        Route::get('picking-management', [App\Http\Controllers\Cms\PoNonSAP\PoManController::class, 'PoMgmtInit']);
        Route::get('add-picking', [App\Http\Controllers\Cms\PoNonSAP\PoManController::class, 'PoMgmtAdd']);
        Route::post('add-picking-package', [App\Http\Controllers\Cms\PoNonSAP\PoManController::class, 'PoMgmtAddPackage']);
        Route::get('get-package/{name}', [App\Http\Controllers\Cms\PoNonSAP\PoManController::class, 'getPackage']);
        Route::get('view-picking/{nopo}', [App\Http\Controllers\Cms\PoNonSAP\PoManController::class, 'PoMgmtView']);
        Route::get('edit-picking/{nopo}', [App\Http\Controllers\Cms\PoNonSAP\PoManController::class, 'PoMgmtEdit']);
        Route::get('print-picking/{nopo}', [App\Http\Controllers\Cms\PoNonSAP\PoManController::class, 'PoMgmtPdf']);

        Route::post('insert-test-po', [App\Http\Controllers\Cms\PoNonSAP\PoManController::class, 'PoMgmtInsertTest']);
        Route::post('insert-picking', [App\Http\Controllers\Cms\PoNonSAP\PoManController::class, 'PoMgmtInsert']);
        Route::post('insert-picking-package', [App\Http\Controllers\Cms\PoNonSAP\PoManController::class, 'PoMgmtInsertPackage']);
        Route::post('update-picking', [App\Http\Controllers\Cms\PoNonSAP\PoManController::class, 'PoMgmtUpdate']);
        Route::post('delete-picking', [App\Http\Controllers\Cms\PoNonSAP\PoManController::class, 'PoMgmtDelete']);

        Route::get('get-pro/{id}',  [App\Http\Controllers\Cms\PoNonSAP\ProManController::class, 'getPro']);


        Route::get('history-transaction/{nopo}', [App\Http\Controllers\Cms\PoNonSAP\PoTrxController::class, 'PoHistory']);
        Route::get('receive-transaction', [App\Http\Controllers\Cms\PoNonSAP\PoTrxController::class, 'PoRcvInit']);
        Route::get('finished-transaction', [App\Http\Controllers\Cms\PoNonSAP\PoTrxController::class, 'PoFinishInit']);
        Route::get('receive-transaction/{nopo}', [App\Http\Controllers\Cms\PoNonSAP\PoTrxController::class, 'PoRcvView']);
        Route::post('receive-transaction', [App\Http\Controllers\Cms\PoNonSAP\PoTrxController::class, 'PoTrxReceive']);
        Route::post('close-transaction', [App\Http\Controllers\Cms\PoNonSAP\PoTrxController::class, 'PoTrxClose']);
        Route::post('invoice-transaction', [App\Http\Controllers\Cms\PoNonSAP\PoTrxController::class, 'PoTrxInvoiced']);
        Route::get('print-supplyed/{nopo}', [App\Http\Controllers\Cms\PoNonSAP\PoTrxController::class, 'PoSupplyPdf']);
        Route::get('export-picking/{nopo}', [App\Http\Controllers\Cms\PoNonSAP\PoTrxController::class, 'exportPicking']);
        Route::get('print-finished/{nopo}', [App\Http\Controllers\Cms\PoNonSAP\PoTrxController::class, 'PoFinishPdf']);


        Route::get('pajak-management',  [App\Http\Controllers\Cms\Pajak\FakturPajakManController::class, 'PajakMgmtInit']);
        Route::get('print-faktur/{id}', [App\Http\Controllers\Cms\Pajak\FakturPajakManController::class, 'FakturPrint']);
        Route::get('pajak-export',  [App\Http\Controllers\Cms\Pajak\FakturPajakManController::class, 'exportPajak']);
        Route::get('faktur-export/{id}',  [App\Http\Controllers\Cms\Pajak\FakturPajakManController::class, 'exportFaktur']);


        Route::get('transaction', [App\Http\Controllers\Cms\PoNonSAP\PoTrxController::class, 'PoTrxInit']);
        Route::get('view-transaction/{nopo}', [App\Http\Controllers\Cms\PoNonSAP\PoTrxController::class, 'PoTrxView']);
        Route::post('update-transaction', [App\Http\Controllers\Cms\PoNonSAP\PoTrxController::class, 'PoTrxUpdate']);


        Route::get('vendor-management',  [App\Http\Controllers\Cms\Pajak\VendorManController::class, 'VendorMgmtInit']);
        Route::post('insert-vendor', [App\Http\Controllers\Cms\Pajak\VendorManController::class, 'VendorMgmtInsert']);
        Route::post('update-vendor', [App\Http\Controllers\Cms\Pajak\VendorManController::class, 'VendorMgmtUpdate']);
        Route::post('delete-vendor', [App\Http\Controllers\Cms\Pajak\VendorManController::class, 'VendorMgmtDelete']);

        Route::get('incentive-management',  [App\Http\Controllers\Cms\Incentive\IncentiveManController::class, 'IncentiveMgmtInit']);
        Route::get('view-incentive/{inv}', [App\Http\Controllers\Cms\Incentive\IncentiveManController::class, 'IncentiveMgmtView']);
        Route::post('upload-incentive', [App\Http\Controllers\Cms\Incentive\IncentiveManController::class, 'IncentiveUpload']);
        Route::post('insert-incentive', [App\Http\Controllers\Cms\Incentive\IncentiveManController::class, 'IncentiveMgmtInsert']);
        Route::post('update-incentive', [App\Http\Controllers\Cms\Incentive\IncentiveManController::class, 'IncentiveMgmtUpdate']);
        Route::post('delete-incentive', [App\Http\Controllers\Cms\Incentive\IncentiveManController::class, 'IncentiveMgmtDelete']);


        Route::get('aging-management',  [App\Http\Controllers\Cms\Incentive\AgingManController::class, 'AgingMgmtInit']);
        Route::post('insert-aging', [App\Http\Controllers\Cms\Incentive\AgingManController::class, 'AgingMgmtInsert']);
        Route::post('update-aging', [App\Http\Controllers\Cms\Incentive\AgingManController::class, 'AgingMgmtUpdate']);
        Route::post('delete-aging', [App\Http\Controllers\Cms\Incentive\AgingManController::class, 'AgingMgmtDelete']);

        Route::get('custtype-management',  [App\Http\Controllers\Cms\Incentive\CustTypeManController::class, 'CustTypeMgmtInit']);
        Route::post('insert-custtype', [App\Http\Controllers\Cms\Incentive\CustTypeManController::class, 'CustTypeMgmtInsert']);
        Route::post('update-custtype', [App\Http\Controllers\Cms\Incentive\CustTypeManController::class, 'CustTypeMgmtUpdate']);
        Route::post('delete-custtype', [App\Http\Controllers\Cms\Incentive\CustTypeManController::class, 'CustTypeMgmtDelete']);

        Route::get('incef-management',  [App\Http\Controllers\Cms\Incentive\IncEfManController::class, 'IncEfMgmtInit']);
        Route::post('insert-incef', [App\Http\Controllers\Cms\Incentive\IncEfManController::class, 'IncEfMgmtInsert']);
        Route::post('update-incef', [App\Http\Controllers\Cms\Incentive\IncEfManController::class, 'IncEfMgmtUpdate']);
        Route::post('delete-incef', [App\Http\Controllers\Cms\Incentive\IncEfManController::class, 'IncEfMgmtDelete']);

        Route::get('gpm-management',  [App\Http\Controllers\Cms\Incentive\GpmManController::class, 'GpmMgmtInit']);
        Route::post('insert-gpm', [App\Http\Controllers\Cms\Incentive\GpmManController::class, 'GpmMgmtInsert']);
        Route::post('update-gpm', [App\Http\Controllers\Cms\Incentive\GpmManController::class, 'GpmMgmtUpdate']);
        Route::post('delete-gpm', [App\Http\Controllers\Cms\Incentive\GpmManController::class, 'GpmMgmtDelete']);

        Route::get('grade-management',  [App\Http\Controllers\Cms\Incentive\GradeManController::class, 'GradeMgmtInit']);
        Route::post('insert-grade', [App\Http\Controllers\Cms\Incentive\GradeManController::class, 'GradeMgmtInsert']);
        Route::post('update-grade', [App\Http\Controllers\Cms\Incentive\GradeManController::class, 'GradeMgmtUpdate']);
        Route::post('delete-grade', [App\Http\Controllers\Cms\Incentive\GradeManController::class, 'GradeMgmtDelete']);

        Route::get('sales-target-management',  [App\Http\Controllers\Cms\Incentive\SalesTargetManController::class, 'SalesTargetMgmtInit']);
        Route::post('insert-sales-target', [App\Http\Controllers\Cms\Incentive\SalesTargetManController::class, 'SalesTargetMgmtInsert']);
        Route::post('update-sales-target', [App\Http\Controllers\Cms\Incentive\SalesTargetManController::class, 'SalesTargetMgmtUpdate']);
        Route::post('delete-sales-target', [App\Http\Controllers\Cms\Incentive\SalesTargetManController::class, 'SalesTargetMgmtDelete']);

        Route::get('target-management',  [App\Http\Controllers\Cms\Incentive\TargetManController::class, 'TargetPercentMgmtInit']);
        Route::post('insert-target', [App\Http\Controllers\Cms\Incentive\TargetManController::class, 'TargetPercentMgmtInsert']);
        Route::post('update-target', [App\Http\Controllers\Cms\Incentive\TargetManController::class, 'TargetPercentMgmtUpdate']);
        Route::post('delete-target', [App\Http\Controllers\Cms\Incentive\TargetManController::class, 'TargetPercentMgmtDelete']);


        Route::get('sales-management',  [App\Http\Controllers\Cms\Incentive\SalesIncManController::class, 'SalesIncMgmtInit']);
        Route::post('submit-incentive', [App\Http\Controllers\Cms\Incentive\SalesIncManController::class, 'SalesIncMgmtSubmit']);
        Route::get('approval-management',  [App\Http\Controllers\Cms\Incentive\ApprovalManController::class, 'ApprovalMgmtInit']);
        Route::get('approval-view/{id}',  [App\Http\Controllers\Cms\Incentive\ApprovalManController::class, 'ApprovalMgmtView']);
        Route::post('adjustment-incentive', [App\Http\Controllers\Cms\Incentive\ApprovalManController::class, 'ApprovalMgmtAdjust']);
        Route::post('accept-request', [App\Http\Controllers\Cms\Incentive\ApprovalManController::class, 'ApprovalMgmtAccept']);
        Route::post('approve-request', [App\Http\Controllers\Cms\Incentive\ApprovalManController::class, 'ApprovalMgmtApprove']);
        Route::post('export-requests', [App\Http\Controllers\Cms\Incentive\ApprovalManController::class, 'ExportReq']);

        Route::get('requests-management',  [App\Http\Controllers\Cms\Incentive\RequestManController::class, 'RequestMgmtInit']);
        Route::get('requests-view/{id}',  [App\Http\Controllers\Cms\Incentive\RequestManController::class, 'RequestMgmtView']);

        Route::get('process-management',  [App\Http\Controllers\Cms\Qfd\ProcessManController::class, 'ProcessMgmtInit']);
        Route::post('insert-process', [App\Http\Controllers\Cms\Qfd\ProcessManController::class, 'ProcessMgmtInsert']);
        Route::post('update-process', [App\Http\Controllers\Cms\Qfd\ProcessManController::class, 'ProcessMgmtUpdate']);
        Route::post('delete-process', [App\Http\Controllers\Cms\Qfd\ProcessManController::class, 'ProcessMgmtDelete']);

        Route::get('qfd-management',  [App\Http\Controllers\Cms\Qfd\TrxMatManController::class, 'TrxMatMgmtInit']);
        Route::get('qfd-draft',  [App\Http\Controllers\Cms\Qfd\TrxMatManController::class, 'TrxMatMgmtDraft']);
        Route::get('get-sapmat/{id}',  [App\Http\Controllers\Cms\Qfd\TrxMatManController::class, 'getSapMat']);
        Route::get('get-component/{id}',  [App\Http\Controllers\Cms\Qfd\TrxMatManController::class, 'getSapComp']);
        Route::post('add-qfd', [App\Http\Controllers\Cms\Qfd\TrxMatManController::class, 'TrxMatMgmtAdd']);
        Route::get('copy-qfd/{id}',  [App\Http\Controllers\Cms\Qfd\TrxMatManController::class, 'TrxMatMgmtCopy']);
        Route::get('copy-qfd-draft/{id}',  [App\Http\Controllers\Cms\Qfd\TrxMatManController::class, 'TrxMatMgmtCopyDraft']);
        Route::get('detail-qfd/{id}',  [App\Http\Controllers\Cms\Qfd\TrxMatManController::class, 'TrxMatMgmtView']);
        Route::get('history-qfd/{id}',  [App\Http\Controllers\Cms\Qfd\TrxMatManController::class, 'TrxMatMgmtHistory']);
        Route::post('insert-qfd', [App\Http\Controllers\Cms\Qfd\TrxMatManController::class, 'TrxMatMgmtInsert']);
        Route::post('insert-draft-qfd', [App\Http\Controllers\Cms\Qfd\TrxMatManController::class, 'TrxMatMgmtInsertDraft']);
        Route::get('edit-qfd/{id}',  [App\Http\Controllers\Cms\Qfd\TrxMatManController::class, 'TrxMatMgmtEdit']);
        Route::get('edit-bom/{id}',  [App\Http\Controllers\Cms\Qfd\TrxMatManController::class, 'TrxMatMgmtEditBom']);
        Route::post('update-qfd', [App\Http\Controllers\Cms\Qfd\TrxMatManController::class, 'TrxMatMgmtUpdate']);
        Route::post('escalate-qfd', [App\Http\Controllers\Cms\Qfd\TrxMatManController::class, 'TrxMatMgmtEscalate']);
        Route::post('delete-qfd', [App\Http\Controllers\Cms\Qfd\TrxMatManController::class, 'TrxMatMgmtDelete']);
        Route::post('delete-qfd-draft', [App\Http\Controllers\Cms\Qfd\TrxMatManController::class, 'TrxMatMgmtDeleteDraft']);

        Route::get('qfd-approval',  [App\Http\Controllers\Cms\Qfd\QfdApproveManController::class, 'QfdApproveMgmtInit']);
        Route::get('detail-approval/{id}',  [App\Http\Controllers\Cms\Qfd\QfdApproveManController::class, 'QfdApproveMgmtView']);
        Route::get('qfd-attendance-email/{id}',  [App\Http\Controllers\Cms\Qfd\QfdApproveManController::class, 'QfdEmailAttandence']);
        Route::post('accept-qfd', [App\Http\Controllers\Cms\Qfd\QfdApproveManController::class, 'QfdApproveMgmtAccept']);
        Route::post('revice-qfd', [App\Http\Controllers\Cms\Qfd\QfdApproveManController::class, 'QfdApproveMgmtRevice']);
        Route::post('approve-qfd', [App\Http\Controllers\Cms\Qfd\QfdApproveManController::class, 'QfdApproveMgmtApprove']);
        Route::post('reject-qfd', [App\Http\Controllers\Cms\Qfd\QfdApproveManController::class, 'QfdApproveMgmtReject']);


        Route::get('approval-apps-management',  [App\Http\Controllers\Cms\Approval\ApprovalAppsController::class, 'ApprovalMgmtInit']);
        Route::post('insert-approval', [App\Http\Controllers\Cms\Approval\ApprovalAppsController::class, 'ApprovalMgmtInsert']);
        Route::post('update-approval', [App\Http\Controllers\Cms\Approval\ApprovalAppsController::class, 'ApprovalMgmtUpdate']);
        Route::post('delete-approval', [App\Http\Controllers\Cms\Approval\ApprovalAppsController::class, 'ApprovalMgmtDelete']);

        Route::get('approval-detail-management',  [App\Http\Controllers\Cms\Approval\ApprovalDetailController::class, 'ApprovalMgmtInit']);
        Route::post('insert-approval-detail', [App\Http\Controllers\Cms\Approval\ApprovalDetailController::class, 'ApprovalMgmtInsert']);
        Route::post('update-approval-detail', [App\Http\Controllers\Cms\Approval\ApprovalDetailController::class, 'ApprovalMgmtUpdate']);
        Route::post('delete-approval-detail', [App\Http\Controllers\Cms\Approval\ApprovalDetailController::class, 'ApprovalMgmtDelete']);


        Route::get('/sendemail/send', [App\Http\Controllers\SendEmailController::class, 'send']);


        Route::get('parts-transaction',  [App\Http\Controllers\Cms\PartsTracking\PartsTrxController::class, 'PartsTrxInit']);
        Route::get('parts-transaction-view/{id}',  [App\Http\Controllers\Cms\PartsTracking\PartsTrxController::class, 'PartsTrxView']);
        Route::get('parts-transaction-print/{id}',  [App\Http\Controllers\Cms\PartsTracking\PartsTrxController::class, 'PartsTrxPrint']);
        Route::get('parts-transaction-add',  [App\Http\Controllers\Cms\PartsTracking\PartsTrxController::class, 'PartsTrxAdd']);
        Route::post('parts-transaction-insert',  [App\Http\Controllers\Cms\PartsTracking\PartsTrxController::class, 'PartsTrxInsert']);
        Route::post('parts-transaction-export',  [App\Http\Controllers\Cms\PartsTracking\PartsTrxController::class, 'export']);

        Route::get('parts-fetch-mwp/{jhose}/{diameter}',  [App\Http\Controllers\Cms\PartsTracking\PartsTrxController::class, 'PartFetchMwp']);
        Route::get('parts-fetch-fitting/{fitting}',  [App\Http\Controllers\Cms\PartsTracking\PartsTrxController::class, 'PartFetchFitting']);
        Route::get('parts-fetch-ukuran-fitting/{ukuran}',  [App\Http\Controllers\Cms\PartsTracking\PartsTrxController::class, 'PartFetchUkuranfitting']);
        Route::get('parts-fetch-fitting-size/{fitting}/{diameter}',  [App\Http\Controllers\Cms\PartsTracking\PartsTrxController::class, 'PartFetchFittingsize']);

        Route::get('jhose-management',  [App\Http\Controllers\Cms\PartsTracking\JenisHoseController::class, 'JhoseMgmtInit']);
        Route::post('insert-jhose', [App\Http\Controllers\Cms\PartsTracking\JenisHoseController::class, 'JhoseMgmtInsert']);
        Route::post('update-jhose', [App\Http\Controllers\Cms\PartsTracking\JenisHoseController::class, 'JhoseMgmtUpdate']);
        Route::post('delete-jhose', [App\Http\Controllers\Cms\PartsTracking\JenisHoseController::class, 'JhoseMgmtDelete']);

        Route::get('aplikasi-management',  [App\Http\Controllers\Cms\PartsTracking\AplikasiController::class, 'AplikasiMgmtInit']);
        Route::post('insert-aplikasi', [App\Http\Controllers\Cms\PartsTracking\AplikasiController::class, 'AplikasiMgmtInsert']);
        Route::post('update-aplikasi', [App\Http\Controllers\Cms\PartsTracking\AplikasiController::class, 'AplikasiMgmtUpdate']);
        Route::post('delete-aplikasi', [App\Http\Controllers\Cms\PartsTracking\AplikasiController::class, 'AplikasiMgmtDelete']);

        Route::get('khose-management',  [App\Http\Controllers\Cms\PartsTracking\KonfHoseController::class, 'KhoseMgmtInit']);
        Route::post('insert-khose', [App\Http\Controllers\Cms\PartsTracking\KonfHoseController::class, 'KhoseMgmtInsert']);
        Route::post('update-khose', [App\Http\Controllers\Cms\PartsTracking\KonfHoseController::class, 'KhoseMgmtUpdate']);
        Route::post('delete-khose', [App\Http\Controllers\Cms\PartsTracking\KonfHoseController::class, 'KhoseMgmtDelete']);


        Route::get('jenisfitting-management',  [App\Http\Controllers\Cms\PartsTracking\JenisFittingController::class, 'JenisFittingMgmtInit']);
        Route::post('insert-jenisfitting', [App\Http\Controllers\Cms\PartsTracking\JenisFittingController::class, 'JenisFittingMgmtInsert']);
        Route::post('update-jenisfitting', [App\Http\Controllers\Cms\PartsTracking\JenisFittingController::class, 'JenisFittingMgmtUpdate']);
        Route::post('delete-jenisfitting', [App\Http\Controllers\Cms\PartsTracking\JenisFittingController::class, 'JenisFittingMgmtDelete']);

        Route::get('diameter-management',  [App\Http\Controllers\Cms\PartsTracking\DiameterController::class, 'DiameterMgmtInit']);
        Route::post('insert-diameter', [App\Http\Controllers\Cms\PartsTracking\DiameterController::class, 'DiameterMgmtInsert']);
        Route::post('update-diameter', [App\Http\Controllers\Cms\PartsTracking\DiameterController::class, 'DiameterMgmtUpdate']);
        Route::post('delete-diameter', [App\Http\Controllers\Cms\PartsTracking\DiameterController::class, 'DiameterMgmtDelete']);

        Route::get('mwp-management',  [App\Http\Controllers\Cms\PartsTracking\KondisiMwpController::class, 'MwpMgmtInit']);
        Route::post('insert-mwp', [App\Http\Controllers\Cms\PartsTracking\KondisiMwpController::class, 'MwpMgmtInsert']);
        Route::post('update-mwp', [App\Http\Controllers\Cms\PartsTracking\KondisiMwpController::class, 'MwpMgmtUpdate']);
        Route::post('delete-mwp', [App\Http\Controllers\Cms\PartsTracking\KondisiMwpController::class, 'MwpMgmtDelete']);

        Route::get('lifetime-management',  [App\Http\Controllers\Cms\PartsTracking\LifetimeController::class, 'LifetimeMgmtInit']);
        Route::post('insert-lifetime', [App\Http\Controllers\Cms\PartsTracking\LifetimeController::class, 'LifetimeMgmtInsert']);
        Route::post('update-lifetime', [App\Http\Controllers\Cms\PartsTracking\LifetimeController::class, 'LifetimeMgmtUpdate']);
        Route::post('delete-lifetime', [App\Http\Controllers\Cms\PartsTracking\LifetimeController::class, 'LifetimeMgmtDelete']);

        Route::get('lokasi-management',  [App\Http\Controllers\Cms\PartsTracking\LokasiController::class, 'LokasiMgmtInit']);
        Route::post('insert-lokasi', [App\Http\Controllers\Cms\PartsTracking\LokasiController::class, 'LokasiMgmtInsert']);
        Route::post('update-lokasi', [App\Http\Controllers\Cms\PartsTracking\LokasiController::class, 'LokasiMgmtUpdate']);
        Route::post('delete-lokasi', [App\Http\Controllers\Cms\PartsTracking\LokasiController::class, 'LokasiMgmtDelete']);

        Route::get('snunit-management',  [App\Http\Controllers\Cms\PartsTracking\SnUnitController::class, 'SnUnitMgmtInit']);
        Route::post('insert-snunit', [App\Http\Controllers\Cms\PartsTracking\SnUnitController::class, 'SnUnitMgmtInsert']);
        Route::post('update-snunit', [App\Http\Controllers\Cms\PartsTracking\SnUnitController::class, 'SnUnitMgmtUpdate']);
        Route::post('delete-snunit', [App\Http\Controllers\Cms\PartsTracking\SnUnitController::class, 'SnUnitMgmtDelete']);


        Route::get('assist-management',  [App\Http\Controllers\Cms\Elsa\AssistManController::class, 'AssistMgmtInit']);
        Route::post('insert-assist', [App\Http\Controllers\Cms\Elsa\AssistManController::class, 'AssistMgmtInsert']);
        Route::post('update-assist', [App\Http\Controllers\Cms\Elsa\AssistManController::class, 'AssistMgmtUpdate']);
        Route::post('delete-assist', [App\Http\Controllers\Cms\Elsa\AssistManController::class, 'AssistMgmtDelete']);

        Route::get('brand-management',  [App\Http\Controllers\Cms\Elsa\BrandManController::class, 'BrandMgmtInit']);
        Route::post('insert-brand', [App\Http\Controllers\Cms\Elsa\BrandManController::class, 'BrandMgmtInsert']);
        Route::post('update-brand', [App\Http\Controllers\Cms\Elsa\BrandManController::class, 'BrandMgmtUpdate']);
        Route::post('delete-brand', [App\Http\Controllers\Cms\Elsa\BrandManController::class, 'BrandMgmtDelete']);

        Route::get('inv-category-management',  [App\Http\Controllers\Cms\Elsa\CatInvManController::class, 'CatInvMgmtInit']);
        Route::post('insert-inv-category', [App\Http\Controllers\Cms\Elsa\CatInvManController::class, 'CatInvMgmtInsert']);
        Route::post('update-inv-category', [App\Http\Controllers\Cms\Elsa\CatInvManController::class, 'CatInvMgmtUpdate']);
        Route::post('delete-inv-category', [App\Http\Controllers\Cms\Elsa\CatInvManController::class, 'CatInvMgmtDelete']);

        Route::get('master-vendor',  [App\Http\Controllers\Cms\Elsa\VendorManController::class, 'VendorMgmtInit']);
        Route::post('insert-vendor', [App\Http\Controllers\Cms\Elsa\VendorManController::class, 'VendorMgmtInsert']);
        Route::post('update-vendor', [App\Http\Controllers\Cms\Elsa\VendorManController::class, 'VendorMgmtUpdate']);
        Route::post('delete-vendor', [App\Http\Controllers\Cms\Elsa\VendorManController::class, 'VendorMgmtDelete']);

        Route::get('inventory-management',  [App\Http\Controllers\Cms\Elsa\InventoryManController::class, 'InventoryMgmtInit']);
        Route::get('inventory-tracking/{id}',  [App\Http\Controllers\Cms\Elsa\InventoryManController::class, 'InventoryMgmtTracking']);
        Route::get('get-inventory/{id}',  [App\Http\Controllers\Cms\Elsa\InventoryManController::class, 'getInventory']);
        Route::post('add-income-inventory', [App\Http\Controllers\Cms\Elsa\InventoryManController::class, 'InventoryMgmtIncome']);
        Route::post('add-reduce-inventory', [App\Http\Controllers\Cms\Elsa\InventoryManController::class, 'InventoryMgmtReduce']);
        Route::post('insert-inventory', [App\Http\Controllers\Cms\Elsa\InventoryManController::class, 'InventoryMgmtInsert']);
        Route::post('update-inventory', [App\Http\Controllers\Cms\Elsa\InventoryManController::class, 'InventoryMgmtUpdate']);
        Route::post('delete-inventory', [App\Http\Controllers\Cms\Elsa\InventoryManController::class, 'InventoryMgmtDelete']);

        Route::get('pr-management',  [App\Http\Controllers\Cms\Elsa\PrManController::class, 'PrMgmtInit']);
        Route::get('pr-list',  [App\Http\Controllers\Cms\Elsa\PrManController::class, 'PrMgmtList'])->name('All PR List');
        Route::post('insert-pr', [App\Http\Controllers\Cms\Elsa\PrManController::class, 'PrMgmtInsert']);
        Route::post('update-pr', [App\Http\Controllers\Cms\Elsa\PrManController::class, 'PrMgmtUpdate']);
        Route::post('delete-pr', [App\Http\Controllers\Cms\Elsa\PrManController::class, 'PrMgmtDelete']);
        Route::post('add-pr-inventory-out', [App\Http\Controllers\Cms\Elsa\PrManController::class, 'PrMgmtPrInventoryOut']);
        Route::post('update-pr-inventory-out', [App\Http\Controllers\Cms\Elsa\PrManController::class, 'PrMgmtPrInventoryOutUpdate']);
        Route::post('delete-pr-inventory-out', [App\Http\Controllers\Cms\Elsa\PrManController::class, 'PrMgmtPrInventoryOutDelete']);
        Route::post('export-pr', [App\Http\Controllers\Cms\Elsa\PrManController::class, 'exportPr']);


        Route::get('assets-management',  [App\Http\Controllers\Cms\Elsa\AssetsManController::class, 'AssetsMgmtInit']);
        Route::get('get-assets',  [App\Http\Controllers\Cms\Elsa\AssetsManController::class, 'getAsset']);
        Route::get('search-assets/{id}',  [App\Http\Controllers\Cms\Elsa\AssetsManController::class, 'searchAsset']);
        Route::get('detail-assets/{id}',  [App\Http\Controllers\Cms\Elsa\AssetsManController::class, 'detailAsset']);
        Route::post('insert-assets', [App\Http\Controllers\Cms\Elsa\AssetsManController::class, 'AssetsMgmtInsert']);
        Route::post('update-assets', [App\Http\Controllers\Cms\Elsa\AssetsManController::class, 'AssetsMgmtUpdate']);
        Route::post('update-assets-detail', [App\Http\Controllers\Cms\Elsa\AssetsManController::class, 'AssetsMgmtUpdateDetail']);
        Route::post('delete-assets', [App\Http\Controllers\Cms\Elsa\AssetsManController::class, 'AssetsMgmtDelete']);

        Route::get('cat-maintenance',  [App\Http\Controllers\Cms\Elsa\CatMaintenanceController::class, 'CatMainMgmtInit']);
        Route::post('insert-cat-maintenance', [App\Http\Controllers\Cms\Elsa\CatMaintenanceController::class, 'CatMainMgmtInsert']);
        Route::post('update-cat-maintenance', [App\Http\Controllers\Cms\Elsa\CatMaintenanceController::class, 'CatMainMgmtUpdate']);
        Route::post('delete-cat-maintenance', [App\Http\Controllers\Cms\Elsa\CatMaintenanceController::class, 'CatMainMgmtDelete']);


        Route::get('schedule-maintenance',  [App\Http\Controllers\Cms\Elsa\ScheduleMaintenanceController::class, 'ScheduleMainMgmtInit']);
        Route::post('insert-schedule-maintenance', [App\Http\Controllers\Cms\Elsa\ScheduleMaintenanceController::class, 'ScheduleMainMgmtInsert']);
        Route::post('update-schedule-maintenance', [App\Http\Controllers\Cms\Elsa\ScheduleMaintenanceController::class, 'ScheduleMainMgmtUpdate']);
        Route::post('delete-schedule-maintenance', [App\Http\Controllers\Cms\Elsa\ScheduleMaintenanceController::class, 'ScheduleMainMgmtDelete']);

        Route::get('location-management',  [App\Http\Controllers\Cms\Elsa\LocationManController::class, 'LocationMgmtInit']);
        Route::post('insert-location', [App\Http\Controllers\Cms\Elsa\LocationManController::class, 'LocationMgmtInsert']);
        Route::post('update-location', [App\Http\Controllers\Cms\Elsa\LocationManController::class, 'LocationMgmtUpdate']);
        Route::post('delete-location', [App\Http\Controllers\Cms\Elsa\LocationManController::class, 'LocationMgmtDelete']);

        Route::get('room-management',  [App\Http\Controllers\Cms\Elsa\RoomManController::class, 'RoomMgmtInit']);
        Route::post('insert-room', [App\Http\Controllers\Cms\Elsa\RoomManController::class, 'RoomMgmtInsert']);
        Route::post('update-room', [App\Http\Controllers\Cms\Elsa\RoomManController::class, 'RoomMgmtUpdate']);
        Route::post('delete-room', [App\Http\Controllers\Cms\Elsa\RoomManController::class, 'RoomMgmtDelete']);


        Route::get('sla-management',  [App\Http\Controllers\Cms\Elsa\SlaManController::class, 'SlaMgmtInit']);
        Route::post('insert-sla', [App\Http\Controllers\Cms\Elsa\SlaManController::class, 'SlaMgmtInsert']);
        Route::post('update-sla', [App\Http\Controllers\Cms\Elsa\SlaManController::class, 'SlaMgmtUpdate']);
        Route::post('delete-sla', [App\Http\Controllers\Cms\Elsa\SlaManController::class, 'SlaMgmtDelete']);

        Route::get('ticket-management',  [App\Http\Controllers\Cms\Elsa\TicketManController::class, 'TicketMgmtInit']);
        Route::get('assist-ticket',  [App\Http\Controllers\Cms\Elsa\TicketManController::class, 'TicketMgmtAssist']);
        Route::get('filter-assist-ticket',  [App\Http\Controllers\Cms\Elsa\TicketManController::class, 'FilterTicketMgmtAssist']);
        Route::get('all-assist-ticket',  [App\Http\Controllers\Cms\Elsa\TicketManController::class, 'AllTicketMgmtAssist']);
        Route::get('filter-all-assist-ticket',  [App\Http\Controllers\Cms\Elsa\TicketManController::class, 'FilterAllTicketMgmtAssist']);
        Route::get('open-ticket',  [App\Http\Controllers\Cms\Elsa\TicketManController::class, 'TicketMgmtNew']);
        Route::get('filter-open-ticket',  [App\Http\Controllers\Cms\Elsa\TicketManController::class, 'FilterTicketMgmtNew']);
        Route::get('open-user-ticket',  [App\Http\Controllers\DashboardMUserController::class, 'TicketMgmtNewUser']);
        Route::get('filter-open-user-ticket',  [App\Http\Controllers\DashboardMUserController::class, 'FilterTicketMgmtNewUser']);
        Route::get('all-assist-user-ticket',  [App\Http\Controllers\DashboardMUserController::class, 'AllTicketMgmtAssistUser']);
        Route::get('filter-all-assist-user-ticket',  [App\Http\Controllers\DashboardMUserController::class, 'FilterAllTicketMgmtAssistUser']);
        Route::get('ticket-detail/{id}',  [App\Http\Controllers\Cms\Elsa\TicketManController::class, 'TicketMgmtView']);
        Route::post('insert-ticket', [App\Http\Controllers\Cms\Elsa\TicketManController::class, 'TicketMgmtInsert']);
        Route::post('update-ticket', [App\Http\Controllers\Cms\Elsa\TicketManController::class, 'TicketMgmtUpdate']);
        Route::post('delete-ticket', [App\Http\Controllers\Cms\Elsa\TicketManController::class, 'TicketMgmtDelete']);
        Route::post('export-ticket', [App\Http\Controllers\Cms\Elsa\TicketManController::class, 'exportTicket']);


        Route::post('asign-ticket', [App\Http\Controllers\Cms\Elsa\TicketManController::class, 'TicketMgmtAsign']);
        Route::post('move-ticket', [App\Http\Controllers\Cms\Elsa\TicketManController::class, 'TicketMgmtMove']);
        Route::post('proccess-ticket', [App\Http\Controllers\Cms\Elsa\TicketManController::class, 'TicketMgmtProccess']);
        Route::post('escalate-ticket', [App\Http\Controllers\Cms\Elsa\TicketManController::class, 'TicketMgmtEscalate']);
        Route::post('resolve-ticket', [App\Http\Controllers\Cms\Elsa\TicketManController::class, 'TicketMgmtResolve']);
        Route::post('cancel-ticket', [App\Http\Controllers\Cms\Elsa\TicketManController::class, 'TicketMgmtCancel']);
        Route::post('close-ticket', [App\Http\Controllers\Cms\Elsa\TicketManController::class, 'TicketMgmtClose']);
        Route::post('mutiple-close-ticket', [App\Http\Controllers\Cms\Elsa\TicketManController::class, 'TicketMgmtMultipleClose']);
        Route::post('request-approval-ticket', [App\Http\Controllers\Cms\Elsa\TicketManController::class, 'TicketMgmtRequestApproval']);

        Route::get('contract-management',  [App\Http\Controllers\Cms\Elsa\KontrakManController::class, 'KontrakMgmtInit']);
        Route::get('add-contract',  [App\Http\Controllers\Cms\Elsa\KontrakManController::class, 'KontrakMgmtAdd']);
        Route::get('detail-contract/{id}', [App\Http\Controllers\Cms\Elsa\KontrakManController::class, 'KontrakMgmtDetail']);
        Route::post('insert-contract', [App\Http\Controllers\Cms\Elsa\KontrakManController::class, 'KontrakMgmtInsert']);
        Route::post('update-contract', [App\Http\Controllers\Cms\Elsa\KontrakManController::class, 'KontrakMgmtUpdate']);
        Route::post('delete-contract', [App\Http\Controllers\Cms\Elsa\KontrakManController::class, 'KontrakMgmtDelete']);

        Route::get('manage-contract',  [App\Http\Controllers\Cms\Elsa\RenewKontrakManController::class, 'RenewKontrakMgmtInit']);
        Route::post('renew-contract', [App\Http\Controllers\Cms\Elsa\RenewKontrakManController::class, 'RenewKontrakMgmtInsert']);
        Route::get('get-counterpart/{id}', [App\Http\Controllers\Cms\Elsa\KontrakManController::class, 'getCounterPart']);

        Route::get('filter-ticket-req',  [App\Http\Controllers\DashboardMController::class, 'FilterTicketReq']);
        Route::get('filter-ticket-total',  [App\Http\Controllers\DashboardMController::class, 'FilterTicketTotal']);
        Route::get('filter-trends',  [App\Http\Controllers\DashboardMController::class, 'FilterTrends']);
        Route::get('filter-your-ticket-req',  [App\Http\Controllers\DashboardMController::class, 'FilterYourTicketReq']);
        Route::get('filter-list-ticket-open',  [App\Http\Controllers\DashboardMController::class, 'FilterListTicketOpen']);
        Route::get('filter-all-ticket-assign',  [App\Http\Controllers\DashboardMController::class, 'FilterAllTicketAssign']);
        Route::get('filter-ticket-req-user',  [App\Http\Controllers\DashboardMUserController::class, 'FilterTicketReq']);
        Route::get('filter-ticket-total-user',  [App\Http\Controllers\DashboardMUserController::class, 'FilterTicketTotal']);
        Route::get('filter-trends-user',  [App\Http\Controllers\DashboardMUserController::class, 'FilterTrends']);
        Route::get('filter-your-ticket-req-user',  [App\Http\Controllers\DashboardMUserController::class, 'FilterYourTicketReq']);
        Route::get('filter-list-ticket-open-user',  [App\Http\Controllers\DashboardMUserController::class, 'FilterListTicketOpen']);
        Route::get('filter-all-ticket-assign-user',  [App\Http\Controllers\DashboardMUserController::class, 'FilterAllTicketAssign']);
        Route::post('export-ticket-dashboard', [App\Http\Controllers\DashboardMController::class, 'ExportTicket']);

        Route::get('loc-mesin-management', [App\Http\Controllers\Cms\Elsa\LocationMesinManController::class, 'LocMesinIndex']);
        Route::get('get-location', [App\Http\Controllers\Cms\Elsa\LocationMesinManController::class, 'GetLocation']);
        Route::post('loc-mesin-insert', [App\Http\Controllers\Cms\Elsa\LocationMesinManController::class, 'LocMesinInsert']);
        Route::post('loc-mesin-update', [App\Http\Controllers\Cms\Elsa\LocationMesinManController::class, 'LocMesinUpdate']);
        Route::post('loc-mesin-delete', [App\Http\Controllers\Cms\Elsa\LocationMesinManController::class, 'LocMesinDelete']);

        Route::get('mesin-management', [App\Http\Controllers\Cms\Elsa\MesinManController::class, 'MesinIndex']);
        Route::get('get-detail-mesin', [App\Http\Controllers\Cms\Elsa\MesinManController::class, 'GetDetailMesin']);
        Route::get('mesin-detail/{id}', [App\Http\Controllers\Cms\Elsa\MesinManController::class, 'MesinDetail']);
        Route::get('view-file/{file_name}', [App\Http\Controllers\Cms\Elsa\MesinManController::class, 'MesinDetailViewFile']);
        Route::post('mesin-insert', [App\Http\Controllers\Cms\Elsa\MesinManController::class, 'MesinInsert']);
        Route::post('mesin-update', [App\Http\Controllers\Cms\Elsa\MesinManController::class, 'MesinUpdate']);
        Route::post('mesin-delete', [App\Http\Controllers\Cms\Elsa\MesinManController::class, 'MesinDelete']);
        Route::get('delete-spek-mesin/{id}', [App\Http\Controllers\Cms\Elsa\MesinManController::class, 'SpekMesinDelete']);

        
         // QRGAD FE

        Route::get('/show-schedule-by-date/{date}', [App\Http\Controllers\Cms\Qrgad\FeQrgadController::class, 'showRoomLoanByDate']);
        Route::post('/add-loan-action', [App\Http\Controllers\Cms\Qrgad\FeQrgadController::class, 'userLoanRequest']);
        Route::get('user-tms', [App\Http\Controllers\Cms\Qrgad\FeQrgadController::class, 'userTms']);
        Route::get('user-room', [App\Http\Controllers\Cms\Qrgad\FeQrgadController::class, 'userRoom']);
        Route::get('/detail-user-room/{id}', [App\Http\Controllers\Cms\Qrgad\FeQrgadController::class, 'detailRoom']);
        Route::get('/detail-user-tms/{id}', [App\Http\Controllers\Cms\Qrgad\FeQrgadController::class, 'detailTms']);
        Route::post('/check-room-booking', [App\Http\Controllers\Cms\Qrgad\FeQrgadController::class, 'validateRoomBooking']);
        Route::get('/show-facility/{id}', [App\Http\Controllers\Cms\Qrgad\FeQrgadController::class, 'showFacility']);
        
        // QRGAD jadwal ruangan
        

        // Ruangan
        Route::resource('/ruangan', App\Http\Controllers\Cms\Qrgad\RuanganController::class);
        Route::get('/ruangan-report', [App\Http\Controllers\Cms\Qrgad\RuanganController::class, 'report']);
        Route::get('/ruangan-get-by-day/{id}', [App\Http\Controllers\Cms\Qrgad\RuanganController::class, 'getByDay']);

        // fasilitas
        Route::resource('/fasilitas', App\Http\Controllers\Cms\Qrgad\FasilitasController::class);
        Route::get('/fasilitas-read', [App\Http\Controllers\Cms\Qrgad\FasilitasController::class, 'read']);
        Route::get('/fasilitas-delete/{id}', [App\Http\Controllers\Cms\Qrgad\FasilitasController::class, 'delete']);

        // lokasi
        Route::resource('/lokasi', App\Http\Controllers\Cms\Qrgad\LokasiController::class);
        Route::get('/lokasi-read', [App\Http\Controllers\Cms\Qrgad\LokasiController::class, 'read']);
        Route::get('/lokasi-delete/{id}', [App\Http\Controllers\Cms\Qrgad\LokasiController::class, 'delete']);

        // jadwal ruangan
        Route::resource('/jadwal-ruangan', App\Http\Controllers\Cms\Qrgad\JadwalRuanganController::class);
        Route::get('/jadwal-ruangan-get-by-day', [App\Http\Controllers\Cms\Qrgad\JadwalRuanganController::class, 'getByDay']);
        Route::get('/jadwal-ruangan-validate-date', [App\Http\Controllers\Cms\Qrgad\JadwalRuanganController::class, 'validateDate']);
        Route::get('/jadwal-ruangan-history', [App\Http\Controllers\Cms\Qrgad\JadwalRuanganController::class, 'history']);
        Route::get('/jadwal-ruangan-ticket/{id}', [App\Http\Controllers\Cms\Qrgad\JadwalRuanganController::class, 'ticket']);
        Route::get('/wa', [App\Http\Controllers\Cms\Qrgad\JadwalRuanganController::class, 'testWa']);
        
        // perusahaan
        Route::resource('/perusahaan', App\Http\Controllers\Cms\Qrgad\PerusahaanController::class);
        Route::get('/perusahaan-read', [App\Http\Controllers\Cms\Qrgad\PerusahaanController::class, 'read']);

        // trip
        Route::resource('/trip', App\Http\Controllers\Cms\Qrgad\TripController::class);
        Route::get('/trip-dashboard',[App\Http\Controllers\Cms\Qrgad\TripController::class,'indexAdmin']);
        Route::get('/trip-schedule',[App\Http\Controllers\Cms\Qrgad\TripController::class,'schedule']);
        Route::post('/trip-read',[App\Http\Controllers\Cms\Qrgad\TripController::class,'read']);
        Route::post('/trip-read-admin',[App\Http\Controllers\Cms\Qrgad\TripController::class,'readAdmin']);
        Route::get('/trip-show-admin/{id}',[App\Http\Controllers\Cms\Qrgad\TripController::class,'showAdmin']);
        Route::post('/trip-read-schedule',[App\Http\Controllers\Cms\Qrgad\TripController::class,'readSchedule']);
        Route::get('/trip-confirm-approve/{id}',[App\Http\Controllers\Cms\Qrgad\TripController::class,'confirmApprove']);
        Route::get('/trip-approve/{id}',[App\Http\Controllers\Cms\Qrgad\TripController::class,'approve']);
        Route::get('/trip-confirm-reject/{id}',[App\Http\Controllers\Cms\Qrgad\TripController::class,'confirmReject']);
        Route::post('/trip-reject/{id}',[App\Http\Controllers\Cms\Qrgad\TripController::class,'reject']);
        Route::get('/trip-confirm-cancel/{id}',[App\Http\Controllers\Cms\Qrgad\TripController::class,'confirmCancel']);
        Route::post('/trip-cancel/{id}',[App\Http\Controllers\Cms\Qrgad\TripController::class,'cancel']);
        Route::get('/trip-confirm-response/{id}',[App\Http\Controllers\Cms\Qrgad\TripController::class,'confirmResponse']);
        Route::get('/trip-response/{id}',[App\Http\Controllers\Cms\Qrgad\TripController::class,'response']);
        Route::get('/trip-pick-car/{id}',[App\Http\Controllers\Cms\Qrgad\TripController::class,'pickCar']);
        Route::post('/trip-confirm-set-trip/{id}',[App\Http\Controllers\Cms\Qrgad\TripController::class,'confirmSetTrip']);
        // Route::get('/testwa/{id}',[App\Http\Controllers\Cms\Qrgad\TripController::class,'sendWhatsappPemohonGrab']);
        Route::post('/trip-set-trip/{id}',[App\Http\Controllers\Cms\Qrgad\TripController::class,'setTrip']);
        Route::get('/trip-ticket/{id}',[App\Http\Controllers\Cms\Qrgad\TripController::class,'ticket']);
        Route::get('/trip-check',[App\Http\Controllers\Cms\Qrgad\TripController::class,'checkTrip']);
        Route::get('/trip-check-scan',[App\Http\Controllers\Cms\Qrgad\TripController::class,'checkTripScan']);
        Route::get('/trip-check-id-trip/{id}',[App\Http\Controllers\Cms\Qrgad\TripController::class,'checkTripIdTrip']);
        Route::get('/trip-check/{id}',[App\Http\Controllers\Cms\Qrgad\TripController::class,'checkTripById']);
        Route::post('/trip-filter',[App\Http\Controllers\Cms\Qrgad\TripController::class,'tripFilter']);
        // Route::get('/trip-view/{id}',[App\Http\Controllers\Cms\Qrgad\TripController::class,'viewTrip']);
        Route::post('/trip-check-out',[App\Http\Controllers\Cms\Qrgad\TripController::class,'checkOut']);
        Route::post('/trip-check-in',[App\Http\Controllers\Cms\Qrgad\TripController::class,'checkIn']);
        Route::get('/trip-schedule/{id}',[App\Http\Controllers\Cms\Qrgad\TripController::class,'showSchedule']);
        
        Route::resource('/kendaraan', App\Http\Controllers\Cms\Qrgad\KendaraanController::class);
        
        // supir
        Route::resource('/supir', App\Http\Controllers\Cms\Qrgad\SupirController::class);
        
        //Portal
        Route::get('user-attendance', [App\Http\Controllers\HomeController::class, 'UserAttendance']);
        Route::get('user-attendance-export', [App\Http\Controllers\HomeController::class, 'UserAttendanceExport']);
        Route::get('user-attendance-detail/{id}', [App\Http\Controllers\HomeController::class, 'UserAttendanceDetail']);
        Route::post('user-clock-in', [App\Http\Controllers\HomeController::class, 'UserCheckIn']);
        Route::post('user-clock-out', [App\Http\Controllers\HomeController::class, 'UserCheckOut']);
        Route::post('revice-attendance', [App\Http\Controllers\HomeController::class, 'UserAttendanceUpdate']);
        Route::post('add-request-action', [App\Http\Controllers\HomeController::class, 'UserHelpRequest']);
        Route::get('search-menu', [App\Http\Controllers\HomeController::class, 'SearchMenu']);
        Route::get('user-pr', [App\Http\Controllers\HomeController::class, 'UserPr'])->name('Your PR List');
        Route::get('user-ticket', [App\Http\Controllers\HomeController::class, 'UserTicket'])->name('Your Ticket List');
        Route::post('rate-ticket', [App\Http\Controllers\HomeController::class, 'RateTicket']);
        Route::post('rate-pr', [App\Http\Controllers\HomeController::class, 'RatePr']);
        Route::post('comment-ticket', [App\Http\Controllers\HomeController::class, 'CommentTicket']);
        Route::get('ticket-chat/{id}',  [App\Http\Controllers\HomeController::class, 'getChat']);
        Route::get('notif-read', [App\Http\Controllers\HomeController::class, 'NotifRead']);
        Route::get('notif-read-all', [App\Http\Controllers\HomeController::class, 'NotifReadAll']);
        Route::get('ticket-history/{id}',  [App\Http\Controllers\HomeController::class, 'getTicket']);
        Route::get('pr-history/{id}',  [App\Http\Controllers\HomeController::class, 'getPR']);
        Route::get('qfd-approval-get',  [App\Http\Controllers\HomeController::class, 'getQfdApproval']);
        Route::get('inc-approval-get',  [App\Http\Controllers\HomeController::class, 'getIncApproval']);
        Route::get('pr-approval-get',  [App\Http\Controllers\HomeController::class, 'getPrApproval']);
        Route::get('pr-approval-detail/{id}',  [App\Http\Controllers\HomeController::class, 'viewPrApproval']);
        Route::post('pr-approval-post',  [App\Http\Controllers\HomeController::class, 'PrApprovalAction']);
        Route::post('pr-approval-post-ajax',  [App\Http\Controllers\HomeController::class, 'PrApprovalActionPost']);
        Route::get('ticket-approval-get',  [App\Http\Controllers\HomeController::class, 'getTicketApproval']);
        Route::get('ticket-approval-detail/{id}',  [App\Http\Controllers\HomeController::class, 'viewTicketApproval']);
        Route::post('ticket-approval-post',  [App\Http\Controllers\HomeController::class, 'TicketApprovalAction']);
        Route::post('ticket-approval-post-ajax',  [App\Http\Controllers\HomeController::class, 'TicketApprovalActionPost']);
        Route::get('get-data-mesin/{id}',  [App\Http\Controllers\HomeController::class, 'GetDataMesin']);

        // COMPLETENESS COMPONENT bayu ke bawah
        // Route::get('dashboard-ccr',  [App\Http\Controllers\Cms\CompletenessComponent\DashboardController::class, 'Dashboard_v1']);
        // Route::get('completeness-component',  [App\Http\Controllers\Cms\CompletenessComponent\DashboardController::class, 'Dashboard']);
        // Route::get('detail-product-ccr',  [App\Http\Controllers\Cms\CompletenessComponent\DashboardController::class, 'GroupProductDetail']);
        // Route::get('detail-product-ccr-download',  [App\Http\Controllers\Cms\CompletenessComponent\DashboardController::class, 'GroupProductDownload']);

        // Route::get('inventory-component',  [App\Http\Controllers\Cms\CompletenessComponent\InventoryManagementController::class, 'InventoryManagementInit'])->name('inventory.data');
        // Route::get('inventory-ccr-download',  [App\Http\Controllers\Cms\CompletenessComponent\InventoryManagementController::class, 'InventoryDownload'])->name('inventory.download');
        // Route::get('detail-stock-ccr',  [App\Http\Controllers\Cms\CompletenessComponent\InventoryManagementController::class, 'detailStock']);
        // Route::get('material-detail/{id}',  [App\Http\Controllers\Cms\CompletenessComponent\InventoryManagementController::class, 'MaterialView']);
        
        // Route::get('material-ccr',  [App\Http\Controllers\Cms\CompletenessComponent\MaterialController::class, 'MaterialInit'])->name('material.data');
        
        // Route::get('status-material',  [App\Http\Controllers\Cms\CompletenessComponent\StatusController::class, 'StatusInit'])->name('status.data');
        
        // Route::get('ccr-get-komentar',  [App\Http\Controllers\Cms\CompletenessComponent\CommentController::class, 'getDataKomentar']);
        // Route::get('ccr-get-komentar-material',  [App\Http\Controllers\Cms\CompletenessComponent\CommentController::class, 'getKomentarMaterial']);
        // Route::get('ccr-get-komentar-detail-material',  [App\Http\Controllers\Cms\CompletenessComponent\CommentController::class, 'getKomentarDetailMaterial']);
        // Route::get('ccr-komentar',  [App\Http\Controllers\Cms\CompletenessComponent\CommentController::class, 'CommentInit']);
        // Route::get('comment-all',  [App\Http\Controllers\Cms\CompletenessComponent\CommentController::class, 'showAllComments']);
        // Route::get('mark-notification-read',  [App\Http\Controllers\Cms\CompletenessComponent\CommentController::class, 'MarkAllAsRead']);
        // Route::post('create-comment-pro',  [App\Http\Controllers\Cms\CompletenessComponent\CommentController::class, 'CreateCommentsPRO']);
        // Route::post('ccr-create-komentar',  [App\Http\Controllers\Cms\CompletenessComponent\CommentController::class, 'CreateComment']);
        // Route::post('ccr-edit-komentar',  [App\Http\Controllers\Cms\CompletenessComponent\CommentController::class, 'EditComment']);
        // Route::post('ccr-delete-komentar',  [App\Http\Controllers\Cms\CompletenessComponent\CommentController::class, 'DeleteComment']);
        // Route::post('comment',  [App\Http\Controllers\Cms\CompletenessComponent\CommentController::class, 'CreateCommentsbyMaterial']);
        // Route::get('cek-coment-potracking-ccr',  [App\Http\Controllers\Cms\CompletenessComponent\CommentController::class, 'CekCommentPoTracking']);
        // Route::post('insert-comment-potracking-ccr',  [App\Http\Controllers\Cms\CompletenessComponent\CommentController::class, 'InsertCommentPoTracking']);
       
        // Route::post('proses-ticket',  [App\Http\Controllers\Cms\CompletenessComponent\OnGoingController::class, 'prosesTicket']);
        // Route::get('production-order',  [App\Http\Controllers\Cms\CompletenessComponent\OnGoingController::class, 'ProductionOrderInit'])->name('pro.ongoing');Route::get('production-order-planning/{id}',  [App\Http\Controllers\Cms\CompletenessComponent\OnGoingController::class, 'ProductionOrderPlanning']);
        // Route::get('production-order-planning/{id}',  [App\Http\Controllers\Cms\CompletenessComponent\OnGoingController::class, 'ProductionOrderPlanning']);
        // Route::get('planning-unit/{id}',  [App\Http\Controllers\Cms\CompletenessComponent\OnGoingController::class, 'ProductNumber']);
        // Route::get('production-order-ongoing-download',  [App\Http\Controllers\Cms\CompletenessComponent\OnGoingController::class, 'ProductionOrderDownload']);
        // Route::get('production-order-ticket/{id}',  [App\Http\Controllers\Cms\CompletenessComponent\OnGoingController::class, 'ProductionOrderTicket']);
        // Route::get('detailMinusStockUnit',  [App\Http\Controllers\Cms\CompletenessComponent\OnGoingController::class, 'detailMinusUnit'])->name('detailMinusStockUnit');
        // Route::get('detail-minus-ccr',  [App\Http\Controllers\Cms\CompletenessComponent\OnGoingController::class, 'detailMinus']);
        // Route::get('ccr-detail-plotting-stock',  [App\Http\Controllers\Cms\CompletenessComponent\OnGoingController::class, 'detailPlottingStock']);
        
        // Route::get('production-order-history',  [App\Http\Controllers\Cms\CompletenessComponent\HistoryController::class, 'POHistoryInit'])->name('pro.history');
        // Route::get('production-order-planning-history/{id}',  [App\Http\Controllers\Cms\CompletenessComponent\HistoryController::class, 'ProductionOrderPlanningHistory']);
        // Route::get('planning-unit-history/{id}',  [App\Http\Controllers\Cms\CompletenessComponent\HistoryController::class, 'PlanningUnitHistory']);
        // Route::get('production-order-history-download',  [App\Http\Controllers\Cms\CompletenessComponent\HistoryController::class, 'POHistoryDownload']);
        // Route::get('detailPRO',  [App\Http\Controllers\Cms\CompletenessComponent\HistoryController::class, 'detailPRO'])->name('detailPRO');
        // Route::get('detailSN',  [App\Http\Controllers\Cms\CompletenessComponent\HistoryController::class, 'detailSN'])->name('detailSN');

        // // ticket Komponen PRO
        // Route::get('production-order-ticket/{id}',  [App\Http\Controllers\Cms\CompletenessComponent\TicketComponentController::class, 'CreateTicketPRO']);
        // Route::get('ticket-component-ccr',  [App\Http\Controllers\Cms\CompletenessComponent\TicketComponentController::class, 'TicketInit'])->name('ticket.index');
        // Route::get('list-component-ticket/{id}',  [App\Http\Controllers\Cms\CompletenessComponent\TicketComponentController::class, 'TicketReportDetail'])->name('ticket.listComponent');
        // Route::post('proses-ticket-ccr',  [App\Http\Controllers\Cms\CompletenessComponent\TicketComponentController::class, 'prosesCreateTicket']);
        // Route::post('proses-update-ticket-ccr',  [App\Http\Controllers\Cms\CompletenessComponent\TicketComponentController::class, 'prosesUpdateTicket'])->name('proses-ticket-ccr.update');
        
        // // Halaman Report CCR
        // Route::get('log-ccr',  [App\Http\Controllers\Cms\CompletenessComponent\Report\LogUserController::class, 'index'])->name('log_user.data');

        // Route::get('report-product',  [App\Http\Controllers\Cms\CompletenessComponent\Report\ProductFavoriteController::class, 'index'])->name('report.productFavorite');

        // Route::get('report-component-favorite',  [App\Http\Controllers\Cms\CompletenessComponent\Report\ComponentFavoriteController::class, 'index'])->name('report.componentFavorite');

        // Route::any('report-material-open',  [App\Http\Controllers\Cms\CompletenessComponent\Report\MaterialOpenShortlistController::class, 'index'])->name('report.materialShortlist');
        // Route::get('report-material-open-download',  [App\Http\Controllers\Cms\CompletenessComponent\Report\MaterialOpenShortlistController::class, 'materialOpenShortlistDownload']);
        // Route::get('option-material-open-shortlist',  [App\Http\Controllers\Cms\CompletenessComponent\Report\MaterialOpenShortlistController::class, 'MaterialOpenShortlistOption']);
        // Route::any('report-material-po',  [App\Http\Controllers\Cms\CompletenessComponent\Report\MaterialOpenShortlistController::class, 'shortlistPO']);
        // Route::get('report-material-open-po-download',  [App\Http\Controllers\Cms\CompletenessComponent\Report\MaterialOpenShortlistController::class, 'materialOpenShortlistPODownload']);
        // Route::get('option-material-open-po-shortlist',  [App\Http\Controllers\Cms\CompletenessComponent\Report\MaterialOpenShortlistController::class, 'materialOpenShortlistPOOption']);
        
        // Route::any('report-component-unit',  [App\Http\Controllers\Cms\CompletenessComponent\Report\ListComponent2ProductNumberController::class, 'index'])->name('report.componentListUnit');
        // Route::get('report-component-unit-download',  [App\Http\Controllers\Cms\CompletenessComponent\Report\ListComponent2ProductNumberController::class, 'componentListUnitDownload']);
        // Route::get('option-component-list',  [App\Http\Controllers\Cms\CompletenessComponent\Report\ListComponent2ProductNumberController::class, 'ComponentUnitOption'])->name('report.ComponentUnitOption');
        
        // Route::any('report-date-component',  [App\Http\Controllers\Cms\CompletenessComponent\Report\ReqDateComponentController::class, 'index']);
        // Route::get('report-date-component-download',  [App\Http\Controllers\Cms\CompletenessComponent\Report\ReqDateComponentController::class, 'reqDateComponentDownload']);
        
        // Route::any('report-gi-component',  [App\Http\Controllers\Cms\CompletenessComponent\Report\GIComponentController::class, 'index']);
        // Route::get('report-gi-component-detail-pro/{id}',  [App\Http\Controllers\Cms\CompletenessComponent\Report\GIComponentController::class, 'giComponentDetailPRO']);
        // Route::get('report-gi-component-detail-unit/{id}',  [App\Http\Controllers\Cms\CompletenessComponent\Report\GIComponentController::class, 'giComponentDetailUnit']);

        // Route::get('log-csv-ccr',  [App\Http\Controllers\Cms\CompletenessComponent\Report\LogCSVController::class, 'index']);
        // Route::get('log-error-ccr',  [App\Http\Controllers\Cms\CompletenessComponent\Report\LogErrorCCRController::class, 'index']);
        
        // END CCR

        // PO TRACKING

        Route::get('subcontdevuserroles',  [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'Subcontdevuserroles']);
        Route::get('carisubcontdevuserroles', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'carisubcontdevuserroles'])->name('carisubcontdevuserroles');
        Route::post('insert-subcontdevuserroles', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'subcontdevuserrolesInsert']);
        Route::post('update-subcontdevuserroles', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'subcontdevuserrolesUpdate']);
        Route::post('delete-subcontdevuserroles', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'subcontdevuserrolesDelete']);

        Route::get('checkpo',  [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'checkpo']);
        Route::get('searchpoall',[App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'searchpoall'])->name('searchpoall');

        Route::get('vendorssubcont',  [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'VendorsSubcont']);
        Route::get('carivendorssubcont',  [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'cariVendorsSubcont']);
        Route::post('insert-uservendor', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'UserVendorInsert']);
        Route::post('update-uservendor', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'UservendorUpdate']);
        Route::post('delete-uservendor', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'UservendorDelete']);

        Route::get('maintainvendorsubcont',  [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'maintainvendorsubcont']);
        Route::get('exportmaintainvendorsubcont',  [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'exportmaintainvendorsubcont']);
        Route::post('insert-maintainvendorsubcont', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'maintainvendorsubcontInsert']);
        Route::post('import-maintainvendorsubcont', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'maintainvendorsubcontImport']);

        Route::get('vendors',  [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'Vendors']);
        Route::get('carivendors', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'carivendors'])->name('carivendors');
        Route::post('insert-vendors', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'vendorsInsert']);
        Route::post('update-vendors', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'vendorsUpdate']);
        Route::post('delete-vendors', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'vendorsDelete']);
        Route::get('detail-datavendor/{VendorCode}',  [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'detailDataVendor']);

        Route::get('subcontdevvendor',  [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'SubcontDevVendor']);
        Route::get('carisubcontdevvendor', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'carisubcontdevvendor'])->name('carisubcontdevvendor');
        Route::get('carivwposubcont', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'carivwposubcont'])->name('carivwposubcont');
        Route::get('deletesubcontdevvendor', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'deletesubcontdevvendor'])->name('deletesubcontdevvendor');
        Route::post('insert-subcontdevvendor', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'subcontdevvendorInsert']);
        Route::post('update-subcontdevvendor', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'subcontdevvendorUpdate']);
        Route::post('delete-subcontdevvendor', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'subcontdevvendorDelete']);

        Route::get('uservendor',  [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'UserVendor']);
        Route::get('cariuservendor', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'cariuservendor'])->name('cariuservendor');
        Route::post('insert-uservendor', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'uservendorInsert']);
        Route::post('update-uservendor', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'uservendorUpdate']);
        Route::post('delete-uservendor', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'uservendorDelete']);
        Route::get('profile-vendor',  [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'ProfileVendor']);
        Route::get('profile-vendor-2',  [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'ProfileVendor']);
        Route::get('cekkota',  [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'CekKota']);
        Route::post('update-vendor', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'UpdateVendor']);
        Route::get('vendor/{status}/{file}', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'DownloadFileVendor']);
        Route::get('docpernyataan/{status}', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'DownloadDocPernyataan']);

        Route::get('delayreason',  [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'delayreason']);
        Route::get('caridelayreason', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'caridelayreason'])->name('caridelayreason');
        Route::post('insert-delayreason', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'delayreasonInsert']);
        Route::post('update-delayreason', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'delayreasonUpdate']);
        Route::post('delete-delayreason', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'delayreasonDelete']);

        Route::get('reasonsubcont',  [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'reasonsubcont']);
        Route::get('carireason', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'carireason'])->name('carireason');
        Route::post('insert-reasonsubcont', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'reasonsubcontInsert']);
        Route::post('update-reasonsubcont', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'reasonsubcontUpdate']);
        Route::post('delete-reasonsubcont', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'reasonsubcontDelete']);

        Route::get('userprocurementsuperior',  [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'userprocurementsuperior']);
        Route::get('cariuserprocurementsuperior',  [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'cariuserprocurementsuperior']);
        Route::post('insert-userprocurementsuperior', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'userprocurementsuperiorInsert']);
        Route::post('update-userprocurementsuperior', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'userprocurementsuperiorUpdate']);
        Route::post('delete-userprocurementsuperior', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'userprocurementsuperiorDelete']);

        Route::get('subcontleadtimemaster',  [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'Subcontleadtimemaster']);
        Route::get('carisubcontleadtimemaster', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'cariSubcontleadtimemaster'])->name('cariSubcontleadtimemaster');
        Route::post('insert-subcontleadtimemaster', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'SubcontleadtimemasterInsert']);
        Route::post('update-subcontleadtimemaster', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'SubcontleadtimemasterUpdate']);
        Route::post('delete-subcontleadtimemaster', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'SubcontleadtimemasterDelete']);
        Route::get('loghistory',  [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'LogHistory']);

        Route::get('parametersla',  [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'parametersla']);
        Route::get('cariparametersla', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'cariparametersla'])->name('cariparametersla');
        Route::post('update-parametersla', [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'parameterslaUpdate']);

        Route::get('user-plant-potracking',  [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'user_plant_potracking']);
        Route::post('add-user-plant-potracking',  [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'add_user_plant_potracking']);
        Route::post('delete-user-plant-potracking',  [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'delete_user_plant_potracking']);
        Route::get('view-user-plant-potracking',  [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'view_user_plant_potracking']);
        Route::post('edit-user-plant-potracking',  [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'edit_user_plant_potracking']);
        Route::get('get-detail-user-plant',  [App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'getDetailDataUser']);

        Route::get('view_disabled_date_fullcalendar',[App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'view_date_fullcalendar']);
        Route::post('update_disabled_date_fullcalendar',[App\Http\Controllers\Cms\PoTracking\PoTrackingMasterController::class, 'update_date_fullcalendar']);

        //poimport
        // Route::get('poimport',  [App\Http\Controllers\Cms\PoTracking\PoImportController::class, 'poimport']);
        // Route::get('poimportongoing',  [App\Http\Controllers\Cms\PoTracking\PoImportController::class, 'poimportongoing']);
        // Route::get('poimportnewpo',  [App\Http\Controllers\Cms\PoTracking\PoImportController::class, 'poimportnewpo']);
        // Route::get('poimporthistory',  [App\Http\Controllers\Cms\PoTracking\PoImportController::class, 'poimporthistory']);
        // Route::get('poimportreport',  [App\Http\Controllers\Cms\PoTracking\PoImportController::class, 'poimportreport']);
        // Route::post('caripoimport', [App\Http\Controllers\Cms\PoTracking\PoImportController::class, 'caripoimport']);
        // Route::post('caripoimportongoing', [App\Http\Controllers\Cms\PoTracking\PoImportController::class, 'caripoimportongoing']);
        // Route::post('caripoimportnewpo', [App\Http\Controllers\Cms\PoTracking\PoImportController::class, 'caripoimportnewpo']);
        // Route::post('caripoimporthistory', [App\Http\Controllers\Cms\PoTracking\PoImportController::class, 'caripoimporthistory']);
        // Route::post('insert-poimport', [App\Http\Controllers\Cms\PoTracking\PoImportController::class, 'poImportInsert']);
        // Route::post('update-poimport', [App\Http\Controllers\Cms\PoTracking\PoImportController::class, 'poImportUpdate']);
        // Route::get('viewpoimport', [App\Http\Controllers\Cms\PoTracking\PoImportController::class, 'viewpoimport'])->name('viewpoimport');
        // Route::get('viewcancelpoimport', [App\Http\Controllers\Cms\PoTracking\PoImportController::class, 'viewcancelpoimport'])->name('viewcancelpoimport');
        // Route::get('viewcaridataimport', [App\Http\Controllers\Cms\PoTracking\PoImportController::class, 'viewCariDataOngoing'])->name('viewcaridataimport');
        // Route::get('viewcaridataimportbyid', [App\Http\Controllers\Cms\PoTracking\PoImportController::class, 'viewCariDataOngoingById'])->name('caridata.id');
        // Route::get('datadetailpo', [App\Http\Controllers\Cms\PoTracking\PoImportController::class, 'dataDetailsPO'])->name('caridata.detailspo');
        // Route::post('uploadproforma', [App\Http\Controllers\Cms\PoTracking\PoImportController::class, 'uploadProforma'])->name('poimport.uploadproforma');
        // Route::post('verify_proforma', [App\Http\Controllers\Cms\PoTracking\PoImportController::class, 'verifyProforma'])->name('poimport.verify_proforma');
        // Route::post('detail_po', [App\Http\Controllers\Cms\PoTracking\PoImportController::class, 'detailPO'])->name('poimport.detail_po');
        // Route::post('shipmentDocumentImport', [App\Http\Controllers\Cms\PoTracking\PoImportController::class, 'documentShipment'])->name('poimport.shipmentDocumentImport');

       //subcount
         //newpo
         Route::get('subcontractornewpo',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\NewPoController::class, 'subcontractornewpo']);
         Route::get('subcontractornewpo-nonmanagement',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\NewPoController::class, 'subcontractornewpoNonManagement']);
         Route::get('subcontractornewpo-proc',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\NewPoController::class, 'subcontractornewpoProc']);
         Route::get('subcontractornewpo-vendor',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\NewPoController::class, 'subcontractornewpoVendor']);
         Route::get('subcontractornewpo-whs',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\NewPoController::class, 'subcontractornewpoWhs']);
         Route::post('insert-subcontractor', [App\Http\Controllers\Cms\PoTracking\POSubcontractor\NewPoController::class, 'poInsert']);
         Route::post('ConfirmPOSubcont', [App\Http\Controllers\Cms\PoTracking\POSubcontractor\NewPoController::class, 'ConfirmPOSubcont']);
         Route::get('view_negosiasiposubcontractor',[App\Http\Controllers\Cms\PoTracking\POSubcontractor\NewPoController::class, 'view_negosiasipo'])->name('view_negosiasipo');
         Route::post('update-posubcontractor', [App\Http\Controllers\Cms\PoTracking\POSubcontractor\NewPoController::class, 'poUpdate']);
         //ongoing
        Route::get('subcontractorongoing',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\OngoingController::class, 'subcontractorongoing']);
        Route::get('subcontractorongoing-nonmanagement',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\OngoingController::class, 'subcontractorongoingNonManagement']);
        Route::any('subcontractorongoing-proc',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\OngoingController::class, 'subcontractorongoingProc']);
        Route::any('subcontractorongoing-vendor',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\OngoingController::class, 'subcontractorongoingVendor']);
        Route::get('subcontractorongoing-whs',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\OngoingController::class, 'subcontractorongoingWhs']);
        Route::get('view_proformasubcontractor', [App\Http\Controllers\Cms\PoTracking\POSubcontractor\OngoingController::class, 'view_proformasubcontractor']);
        Route::post('proformasubcontractor', [App\Http\Controllers\Cms\PoTracking\POSubcontractor\OngoingController::class, 'proformasubcontractor']);
        Route::get('view_cekticketsubcont',[App\Http\Controllers\Cms\PoTracking\POSubcontractor\OngoingController::class, 'view_cekticket'])->name('view_cekticket');
        Route::post('subcontractorcreateticket', [App\Http\Controllers\Cms\PoTracking\POSubcontractor\OngoingController::class, 'subcontractorcreateticket']);
        Route::get('viewsubcontractorsequence', [App\Http\Controllers\Cms\PoTracking\POSubcontractor\OngoingController::class, 'viewsubcontractorsequence'])->name('viewsubcontractorsequence');
        Route::post('insertprogress-subcontractor', [App\Http\Controllers\Cms\PoTracking\POSubcontractor\OngoingController::class, 'subcontractorleadtime'])->name('insertprogress-subcontractor');
        Route::post('proses-subcontractorongoing', [App\Http\Controllers\Cms\PoTracking\POSubcontractor\OngoingController::class, 'prosessubcontractorongoing']);
        Route::post('skip-proformasubcontractor', [App\Http\Controllers\Cms\PoTracking\POSubcontractor\OngoingController::class, 'SkipProforma']);
        Route::get('historyparkingsubcontractor',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\OngoingController::class, 'Historyparking']);

        //update parking 11 agustus 2023 agam
        Route::get('historyparkingsubcontractor-proc',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\OngoingController::class, 'HistoryparkingProc']);
        Route::get('historyparkingsubcontractor-vendor',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\OngoingController::class, 'HistoryparkingVendor']);
        Route::get('historyparkingsubcontractor-nonmanagement',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\OngoingController::class, 'HistoryparkingnonManagement']);


        Route::post('parkinginvoicesubcontractor', [App\Http\Controllers\Cms\PoTracking\POSubcontractor\OngoingController::class, 'Parkinginvoice']);
        Route::post('updatedata-subcont', [App\Http\Controllers\Cms\PoTracking\POSubcontractor\OngoingController::class, 'UpdateData']);
        //plandelivery
        Route::get('subcontractorplandelivery',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\PlanDeliveryController::class, 'subcontractorPlandelivery']);
        Route::get('subcontractorplandelivery-nonmanagement',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\PlanDeliveryController::class, 'subcontractorPlandeliveryNonManagement']);
        Route::any('subcontractorplandelivery-whs',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\PlanDeliveryController::class, 'subcontractorPlandeliveryWhs']);
        Route::get('subcontractorplandelivery-proc',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\PlanDeliveryController::class, 'subcontractorPlandeliveryProc']);
        Route::get('subcontractorplandelivery-vendor',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\PlanDeliveryController::class, 'subcontractorPlandeliveryVendor']);
        Route::post('confirmticketsubcontractor', [App\Http\Controllers\Cms\PoTracking\POSubcontractor\PlanDeliveryController::class, 'Confirmticket']);
        Route::get('historyticketsubcont',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\PlanDeliveryController::class, 'Historyticket']);
        Route::get('historyticketsubcont-nonmanagement',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\PlanDeliveryController::class, 'HistoryticketNonManagement']);
        Route::get('historyticketsubcont-whs',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\PlanDeliveryController::class, 'HistoryticketWhs']);
        Route::get('historyticketsubcont-proc',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\PlanDeliveryController::class, 'HistoryticketProc']);
        Route::get('historyticketsubcont-vendor',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\PlanDeliveryController::class, 'HistoryticketVendor']);
        Route::post('prosesreverseticket-subcont', [App\Http\Controllers\Cms\PoTracking\POSubcontractor\PlanDeliveryController::class, 'ProsesUpdate']);
        //readytodelivery
        Route::get('subcontractorreadydelivery',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\ReadyDeliveryController::class, 'readyDelivery']);
        Route::get('subcontractorreadydelivery-nonmanagement',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\ReadyDeliveryController::class, 'readyDeliveryNonManagement']);
        Route::get('subcontractorreadydelivery-whs',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\ReadyDeliveryController::class, 'readyDeliveryWhs']);
        Route::get('subcontractorreadydelivery-proc',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\ReadyDeliveryController::class, 'readyDeliveryProc']);
        Route::any('subcontractorreadydelivery-vendor',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\ReadyDeliveryController::class, 'readyDeliveryVendor']);
        Route::post('print-ticketsubcont', [App\Http\Controllers\Cms\PoTracking\POSubcontractor\ReadyDeliveryController::class, 'TicketPdf']);
        Route::post('prosesreadydelivery-subcont', [App\Http\Controllers\Cms\PoTracking\POSubcontractor\ReadyDeliveryController::class, 'ProsesUpdate']);
        //history
        Route::get('subcontractorhistory',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\HistoryController::class, 'subcontractorhistory']);
        Route::get('subcontractorhistory-nonmanagement',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\HistoryController::class, 'subcontractorhistoryNonManagement']);
        Route::get('subcontractorhistory-proc',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\HistoryController::class, 'subcontractorhistoryProc']);
        Route::get('subcontractorhistory-vendor',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\HistoryController::class, 'subcontractorhistoryVendor']);
        Route::get('subcontractorhistory-whs',  [App\Http\Controllers\Cms\PoTracking\POSubcontractor\HistoryController::class, 'subcontractorhistoryWhs']);
        Route::post('poreturn-subcont', [App\Http\Controllers\Cms\PoTracking\POSubcontractor\HistoryController::class, 'Poreturn']);

        //PO LOCAL
        //newpo
        Route::get('polocalnewpo',  [App\Http\Controllers\Cms\PoTracking\POLocal\NewPoController::class, 'polocalnewpo']);
        Route::get('polocalnewpo-nonmanagement',  [App\Http\Controllers\Cms\PoTracking\POLocal\NewPoController::class, 'polocalnewpoNonManagement']);
        Route::get('polocalnewpo-proc',  [App\Http\Controllers\Cms\PoTracking\POLocal\NewPoController::class, 'polocalnewpoProc']);
        Route::get('polocalnewpo-vendor',  [App\Http\Controllers\Cms\PoTracking\POLocal\NewPoController::class, 'polocalnewpoVendor']);
        Route::get('polocalnewpo-whs',  [App\Http\Controllers\Cms\PoTracking\POLocal\NewPoController::class, 'polocalnewpoWhs']);
        Route::post('insert-polocal', [App\Http\Controllers\Cms\PoTracking\POLocal\NewPoController::class, 'poInsert']);
        Route::get('view_negosiasipolocal',[App\Http\Controllers\Cms\PoTracking\POLocal\NewPoController::class, 'view_negosiasipo'])->name('view_negosiasipo');
        Route::post('update-polocal', [App\Http\Controllers\Cms\PoTracking\POLocal\NewPoController::class, 'poUpdate']);
        //ongoing
        Route::get('polocalongoing',  [App\Http\Controllers\Cms\PoTracking\POLocal\OngoingController::class, 'polocalongoing']);
        Route::get('polocalongoing-nonmanagement',  [App\Http\Controllers\Cms\PoTracking\POLocal\OngoingController::class, 'polocalongoingNonManagement']);
        Route::any('polocalongoing-proc',  [App\Http\Controllers\Cms\PoTracking\POLocal\OngoingController::class, 'polocalongoingProc']);
        Route::any('polocalongoing-vendor',  [App\Http\Controllers\Cms\PoTracking\POLocal\OngoingController::class, 'polocalongoingVendor']);
        Route::get('polocalongoing-whs',  [App\Http\Controllers\Cms\PoTracking\POLocal\OngoingController::class, 'polocalongoingWhs']);
        Route::get('view_proforma',[App\Http\Controllers\Cms\PoTracking\POLocal\OngoingController::class, 'view_proforma'])->name('view_proforma');
        Route::get('view_cekticketlocal',[App\Http\Controllers\Cms\PoTracking\POLocal\OngoingController::class, 'view_cekticket'])->name('view_cekticket');
        Route::post('proses-polocalongoing', [App\Http\Controllers\Cms\PoTracking\POLocal\OngoingController::class, 'prosespolocalongoing']);
        Route::post('skip-proformalocal', [App\Http\Controllers\Cms\PoTracking\POLocal\OngoingController::class, 'SkipProforma']);
        Route::get('historyparkinglocal',  [App\Http\Controllers\Cms\PoTracking\POLocal\OngoingController::class, 'Historyparking']);
        Route::post('parkinginvoicelocal', [App\Http\Controllers\Cms\PoTracking\POLocal\OngoingController::class, 'Parkinginvoice']);
        Route::post('updatedata-local', [App\Http\Controllers\Cms\PoTracking\POLocal\OngoingController::class, 'UpdateData']);

        //update parking 11 agustus 2023 agam
        Route::get('historyparkinglocal-proc',  [App\Http\Controllers\Cms\PoTracking\POLocal\OngoingController::class, 'HistoryparkingProc']);
        Route::get('historyparkinglocal-vendor',  [App\Http\Controllers\Cms\PoTracking\POLocal\OngoingController::class, 'HistoryparkingVendor']);
        Route::get('historyparkinglocal-nonmanagement',  [App\Http\Controllers\Cms\PoTracking\POLocal\OngoingController::class, 'HistoryparkingnonManagement']);

        //plandelivery
        Route::get('polocalplandelivery',  [App\Http\Controllers\Cms\PoTracking\POLocal\PlanDeliveryController::class, 'polocalPlandelivery']);
        Route::get('polocalplandelivery-nonmanagement',  [App\Http\Controllers\Cms\PoTracking\POLocal\PlanDeliveryController::class, 'polocalPlandeliveryNonManagement']);
        Route::get('polocalplandelivery-proc',  [App\Http\Controllers\Cms\PoTracking\POLocal\PlanDeliveryController::class, 'polocalPlandeliveryProc']);
        Route::get('polocalplandelivery-vendor',  [App\Http\Controllers\Cms\PoTracking\POLocal\PlanDeliveryController::class, 'polocalPlandeliveryVendor']);
        Route::any('polocalplandelivery-whs',  [App\Http\Controllers\Cms\PoTracking\POLocal\PlanDeliveryController::class, 'polocalPlandeliveryWhs']);
        Route::post('confirmticketlocal', [App\Http\Controllers\Cms\PoTracking\POLocal\PlanDeliveryController::class, 'Confirmticket']);
        Route::get('historyticketlocal',  [App\Http\Controllers\Cms\PoTracking\POLocal\PlanDeliveryController::class, 'Historyticket']);
        Route::get('historyticketlocal-nonmanagement',  [App\Http\Controllers\Cms\PoTracking\POLocal\PlanDeliveryController::class, 'HistoryticketNonManagement']);
        Route::get('historyticketlocal-proc',  [App\Http\Controllers\Cms\PoTracking\POLocal\PlanDeliveryController::class, 'HistoryticketProc']);
        Route::get('historyticketlocal-vendor',  [App\Http\Controllers\Cms\PoTracking\POLocal\PlanDeliveryController::class, 'HistoryticketVendor']);
        Route::get('historyticketlocal-whs',  [App\Http\Controllers\Cms\PoTracking\POLocal\PlanDeliveryController::class, 'HistoryticketWhs']);
        Route::post('prosesreverseticket-local', [App\Http\Controllers\Cms\PoTracking\POLocal\PlanDeliveryController::class, 'ProsesUpdate']);
        //readytodelivery
        Route::get('polocalreadydelivery',  [App\Http\Controllers\Cms\PoTracking\POLocal\ReadyDeliveryController::class, 'readyDelivery']);
        Route::get('polocalreadydelivery-nonmanagement',  [App\Http\Controllers\Cms\PoTracking\POLocal\ReadyDeliveryController::class, 'readyDeliveryNonManagement']);
        Route::get('polocalreadydelivery-proc',  [App\Http\Controllers\Cms\PoTracking\POLocal\ReadyDeliveryController::class, 'readyDeliveryProc']);
        Route::any('polocalreadydelivery-vendor',  [App\Http\Controllers\Cms\PoTracking\POLocal\ReadyDeliveryController::class, 'readyDeliveryVendor']);
        Route::get('polocalreadydelivery-whs',  [App\Http\Controllers\Cms\PoTracking\POLocal\ReadyDeliveryController::class, 'readyDeliveryWhs']);
        Route::post('print-ticketlocal', [App\Http\Controllers\Cms\PoTracking\POLocal\ReadyDeliveryController::class, 'TicketPdf']);
        Route::post('prosesreadydelivery-local', [App\Http\Controllers\Cms\PoTracking\POLocal\ReadyDeliveryController::class, 'ProsesUpdate']);
        //history
        Route::get('polocalhistory',  [App\Http\Controllers\Cms\PoTracking\POLocal\HistoryController::class, 'Polocalhistory']);
        Route::get('polocalhistory-nonmanagement',  [App\Http\Controllers\Cms\PoTracking\POLocal\HistoryController::class, 'PolocalhistoryNonManagement']);
        Route::get('polocalhistory-proc',  [App\Http\Controllers\Cms\PoTracking\POLocal\HistoryController::class, 'PolocalhistoryProc']);
        Route::get('polocalhistory-vendor',  [App\Http\Controllers\Cms\PoTracking\POLocal\HistoryController::class, 'PolocalhistoryVendor']);
        Route::get('polocalhistory-whs',  [App\Http\Controllers\Cms\PoTracking\POLocal\HistoryController::class, 'PolocalhistoryWhs']);
        Route::post('poreturn-local', [App\Http\Controllers\Cms\PoTracking\POLocal\HistoryController::class, 'Poreturn']);
        
        // dashboard potraking
        Route::get('po-pdf/{id}/{status}', [App\Http\Controllers\Cms\PoTracking\DashboardController::class, 'PoPdf']);
        Route::post('cari-chartdashboard', [App\Http\Controllers\Cms\PoTracking\DashboardController::class, 'Carichartdashboard']);
        Route::get('view_negosiasipo',[App\Http\Controllers\Cms\PoTracking\DashboardController::class, 'view_negosiasipo'])->name('view_negosiasipo');
        Route::get('view_confirmpo',[App\Http\Controllers\Cms\PoTracking\DashboardController::class, 'view_confirmpo'])->name('view_confirmpo');
        Route::get('view_detailgr',[App\Http\Controllers\Cms\PoTracking\DashboardController::class, 'view_detailgr'])->name('view_detailgr');
    	// Route::get('view_cariparking',[App\Http\Controllers\Cms\PoTracking\DashboardController::class, 'viewcariparking'])->name('viewcariparking');
        // Route::get('view_cariparking_detail',[App\Http\Controllers\Cms\PoTracking\DashboardController::class, 'viewcariparking_detail']);
        Route::any('potracking', [App\Http\Controllers\Cms\PoTracking\DashboardController::class, 'index']);
        Route::any('potracking-dashboard', [App\Http\Controllers\Cms\PoTracking\DashboardController::class, 'dashboard']);
        Route::get('view_detailgrhistory',[App\Http\Controllers\Cms\PoTracking\DashboardController::class, 'view_detailgrhistory'])->name('view_detailgrhistory');
        Route::post('insert-po', [App\Http\Controllers\Cms\PoTracking\DashboardController::class, 'poInsert']);
        Route::post('update-po', [App\Http\Controllers\Cms\PoTracking\DashboardController::class, 'poUpdate']);
        Route::post('poreturn', [App\Http\Controllers\Cms\PoTracking\DashboardController::class, 'Poreturn']);
        Route::get('cek_coment',[App\Http\Controllers\Cms\PoTracking\DashboardController::class, 'cek_coment'])->name('cek_coment');
    	Route::get('cek_ticket',[App\Http\Controllers\Cms\PoTracking\DashboardController::class, 'cek_ticket'])->name('cek_ticket');
        Route::post('insert-comment', [App\Http\Controllers\Cms\PoTracking\DashboardController::class, 'InsertComment']);
        Route::post('skip-proforma', [App\Http\Controllers\Cms\PoTracking\DashboardController::class, 'SkipProforma']);
        Route::get('allnotification-potracking', [App\Http\Controllers\Cms\PoTracking\DashboardController::class, 'allnotification']);
	    Route::get('allmesengger-potracking', [App\Http\Controllers\Cms\PoTracking\DashboardController::class, 'allmesengger']);
	    // Route::post('parkinginvoice', [App\Http\Controllers\Cms\PoTracking\DashboardController::class, 'Parkinginvoice']);
        Route::get('view_cekparking',[App\Http\Controllers\Cms\PoTracking\DashboardController::class, 'view_cekparking'])->name('view_cekparking');
        Route::get('view_cekdelivery', [App\Http\Controllers\Cms\PoTracking\DashboardController::class, 'CekDelivery'])->name('CekDelivery');
        Route::get('view_cekmaterialvendor', [App\Http\Controllers\Cms\PoTracking\DashboardController::class, 'CekMaterialVendor'])->name('CekMaterialVendor');
        Route::post('cari-po', [App\Http\Controllers\Cms\PoTracking\DashboardController::class, 'CariPO']);
        Route::get('view_cekuser', [App\Http\Controllers\Cms\PoTracking\DashboardController::class, 'CekUser'])->name('CekUser');
        Route::get('detail-delivery/{id}',  [App\Http\Controllers\Cms\PoTracking\DashboardController::class, 'DetailDelivery']);

        // report potracking

        Route::get('view_detaildsgr',[App\Http\Controllers\Cms\PoTracking\ReportController::class, 'view_detaildsgr'])->name('view_detaildsgr');
        Route::any('report-pr-po', [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'reportPRCreateToPORelease']);
        Route::get('report-pr-po/{id}',  [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'reportPRCreateToPORelease']);
        Route::get('report-prpo-open-download',  [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'prpoDownload']);
        Route::any('report-delivery-gr', [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'reportDeliveryDateToGRDate']);
        Route::get('report-delivery-gr/{id}',  [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'reportDeliveryDateToGRDate']);
        Route::get('report-dsgr-open-download',  [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'dsgrDownload']);
        Route::any('report-po-cancel', [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'reportPOCancel']);
        Route::get('report-po-cancel/{id}',  [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'reportPOCancel']);
        Route::get('report-po-cancel-download',  [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'pocanceldownload']);
        Route::any('report-mt-fv', [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'reportMaterialFavorite']);
        Route::get('report-mt-fv/{id}',  [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'reportMaterialFavorite']);
        Route::get('report-mtfv-download',  [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'mtfvdownload']);
        Route::any('report-vn-fv', [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'reportVendorFavorite']);
        Route::get('report-vn-fv/{id}',  [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'reportVendorFavorite']);
        Route::get('report-vnfv-download',  [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'vnfvdownload']);
        Route::any('report-kunjungan-qc', [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'reportKunjunganQC']);
        Route::get('report-kunjungan-qc/{id}',  [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'reportKunjunganQC']);
        Route::get('report-kqc-download',  [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'kqcdownload']);
        Route::get('caridetailmaterial',[App\Http\Controllers\Cms\PoTracking\ReportController::class, 'caridetailmaterial'])->name('caridetailmaterial');
        Route::get('caridetailvendor',[App\Http\Controllers\Cms\PoTracking\ReportController::class, 'caridetailvendor'])->name('caridetailvendor');
        Route::get('caridetailpo',[App\Http\Controllers\Cms\PoTracking\ReportController::class, 'caridetailpo'])->name('caridetailpo');
        Route::get('caridetailpr',[App\Http\Controllers\Cms\PoTracking\ReportController::class, 'caridetailpr'])->name('caridetailpr');
        Route::any('report-dl-gd', [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'reportDeliveryGood']);
        Route::get('report-dl-gd/{id}',  [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'reportDeliveryGood']);
        Route::any('report-dlgd-download',  [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'dlgddownload']);
        Route::get('report-dlgd-download/{id}',  [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'dlgddownload']);
        Route::get('po-open-download/{type}/{status}',  [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'DownloadFilePo']);
	Route::any('jadwalpengiriman', [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'JadwalPengiriman']);
        Route::get('jadwalpengiriman/{id}',  [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'JadwalPengiriman']);
        Route::get('jadwalpengiriman-download',  [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'DownloadJadwalPengiriman']);
        Route::get('report-loghistory-download/{id}',  [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'loghistorydownload']);

        Route::get('kelengkapan-data-vendor',  [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'reportDataVendor']);
        Route::get('report-vndata-download',  [App\Http\Controllers\Cms\PoTracking\ReportController::class, 'vndatadownload']);

        //Parking Invoice Agm 30 Aug 2023
        Route::any('openparkinginvoice', [App\Http\Controllers\Cms\PoTracking\ParkingInvoiceController::class, 'open_parkinginvoice']);
        Route::get('onprocessparkinginvoice', [App\Http\Controllers\Cms\PoTracking\ParkingInvoiceController::class, 'onprocess_parkinginvoice']);
        Route::get('historyparkinginvoice', [App\Http\Controllers\Cms\PoTracking\ParkingInvoiceController::class, 'history_parkinginvoice']);
        Route::get('openparkinginvoicevendor', [App\Http\Controllers\Cms\PoTracking\ParkingInvoiceController::class, 'open_parkinginvoice_vendor']);
        Route::get('onprocessparkinginvoicevendor', [App\Http\Controllers\Cms\PoTracking\ParkingInvoiceController::class, 'onprocess_parkinginvoice_vendor']);
        Route::get('historyparkinginvoicevendor', [App\Http\Controllers\Cms\PoTracking\ParkingInvoiceController::class, 'history_parkinginvoice_vendor']);
        Route::get('view_cariparking',[App\Http\Controllers\Cms\PoTracking\ParkingInvoiceController::class, 'viewcariparking']);
        Route::get('view_cariparking_detail',[App\Http\Controllers\Cms\PoTracking\ParkingInvoiceController::class, 'viewcariparking_detail']);
        Route::post('parkinginvoice', [App\Http\Controllers\Cms\PoTracking\ParkingInvoiceController::class, 'parkinginvoice']);
        Route::get('logparkinginvoice', [App\Http\Controllers\Cms\PoTracking\ParkingInvoiceController::class, 'logparkinginvoice']);

        //FILE VENDOR
    Route::get('file/{id}', [App\Http\Controllers\Cms\PoTracking\FileVendorController::class, 'file']);
    Route::get('file-kbli/{id}', [App\Http\Controllers\Cms\PoTracking\FileVendorController::class, 'fileKbli']);
    Route::get('file-nib/{id}', [App\Http\Controllers\Cms\PoTracking\FileVendorController::class, 'fileNib']);
    Route::get('file-npwp/{id}', [App\Http\Controllers\Cms\PoTracking\FileVendorController::class, 'fileNpwp']);
    Route::get('file-skdp/{id}', [App\Http\Controllers\Cms\PoTracking\FileVendorController::class, 'fileSkdp']);
    Route::get('file-skt/{id}', [App\Http\Controllers\Cms\PoTracking\FileVendorController::class, 'fileSkt']);
    Route::get('file-skpkp/{id}', [App\Http\Controllers\Cms\PoTracking\FileVendorController::class, 'fileSkpkp']);
    Route::get('file-ahu/{id}', [App\Http\Controllers\Cms\PoTracking\FileVendorController::class, 'fileAhu']);
    Route::get('file-ktp-direksi/{id}', [App\Http\Controllers\Cms\PoTracking\FileVendorController::class, 'fileKtpDireksi']);
    Route::get('file-buku-rekening/{id}', [App\Http\Controllers\Cms\PoTracking\FileVendorController::class, 'fileBukuRekening']);
    Route::get('file-akte-pendirian/{id}', [App\Http\Controllers\Cms\PoTracking\FileVendorController::class, 'fileAktePendirian']);
    Route::get('file-anggaran-dasar/{id}', [App\Http\Controllers\Cms\PoTracking\FileVendorController::class, 'fileAnggaranDasar']);
    Route::get('file-izin-usaha/{id}', [App\Http\Controllers\Cms\PoTracking\FileVendorController::class, 'fileIzinUsaha']);
    Route::get('file-surat-agen/{id}', [App\Http\Controllers\Cms\PoTracking\FileVendorController::class, 'fileSuratAgen']);
    Route::get('file-pernyataan-rekening/{id}', [App\Http\Controllers\Cms\PoTracking\FileVendorController::class, 'filePernyataanRekening']);
    Route::get('file-pernyataan-pajak/{id}', [App\Http\Controllers\Cms\PoTracking\FileVendorController::class, 'filePernyataanPajak']);
    Route::get('file-etika-bertransaksi/{id}', [App\Http\Controllers\Cms\PoTracking\FileVendorController::class, 'fileEtikaBertransaksi']);
        // END PO TRACKING
        //init
        Route::get('cogs', [App\Http\Controllers\Cms\Cogs\DashboardController::class, 'Dashboard']);
       
        //MasterData 
        // Route::get('cogs-refresh-alldata', [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'RefreshAllData']);
        Route::get('cogs-import-kurs',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'ImportKurs'])->name('cogs-import-kurs');
        Route::get('cogs-view-kurs',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'Kurs']);
        Route::get('cogs-import-billofmaterial',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'ImportBillOfMaterial'])->name('cogs-import-billofmaterial');
        Route::get('cogs-view-billofmaterial',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'BillOfMaterial']);
        Route::get('cogs-import-activepricecalculationrequest',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'ImportActivePriceCalculationRequest'])->name('cogs-import-activepricecalculationrequest');
        Route::get('cogs-view-activepricecalculationrequest',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'ActivePriceCalculationRequest']);
        Route::get('cogs-import-purchasinginforecord',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'ImportPurchasingInfoRecord'])->name('cogs-import-purchasinginforecord');
        Route::get('cogs-view-confirmpurchaseorder',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'ConfirmPurchaseOrder']);
        Route::get('cogs-import-confirmpurchaseorder',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'ImportConfirmPurchaseOrder'])->name('cogs-import-confirmpurchaseorder');
        Route::get('cogs-search-pir', [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'SearchPir']);
        Route::get('cogs-view-purchasinginforecord',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'PurchasingInfoRecord']);
        Route::get('cogs-import-weightofmaterial',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'ImportWeightMaterial'])->name('cogs-import-weightmaterial');
        Route::get('cogs-view-weightofmaterial',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'WeightMaterial']);
        Route::get('cogs-import-masterprocess',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'ImportMasterProcess'])->name('cogs-import-masterprocess');
        Route::get('cogs-view-masterprocess',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'MasterProcess']);
        Route::get('cogs-view-productcategory',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'ProductCategory']);
        Route::get('cogs-search-productcategory',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'SearchProductCategory']);
        Route::post('cogs-insert-productcategory',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'InsertProductCategory']);
        Route::post('cogs-delete-productcategory',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'DeleteProductCategory']);
        Route::post('cogs-edit-productcategory',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'EditProductCategory']);
        Route::get('cogs-import-pn',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'ImportPN'])->name('cogs-import-pn');
        Route::get('cogs-view-pn',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'PN']);
        Route::get('cogs-import-apcr-api-data',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'ImportAPCRAPIData'])->name('cogs-import-apcr-api');
        Route::get('cogs-import-apcr-api-attachment',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'ImportAPCRAPIAttachment'])->name('cogs-import-apcr-attachment');

        
        // Route::get('cogs-view-role',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'Role']);
        // Route::get('cogs-search-role',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'SearchRole']);
        // Route::post('cogs-insert-role',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'InsertRole']);
        // Route::post('cogs-delete-role',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'DeleteRole']);
        // Route::post('cogs-edit-role',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'EditRole']);
        Route::get('cogs-view-rawmaterialprice',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'RawMaterialPrice']);
        Route::get('cogs-search-rawmaterialprice',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'SearchRawMaterialPrice']);
        Route::post('cogs-add-rawmaterialprice',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'InsertRawMaterialPrice']);
        Route::post('cogs-edit-rawmaterialprice',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'EditRawMaterialPrice']);
        Route::post('cogs-delete-rawmaterialprice',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'DeleteRawMaterialPrice']);
        Route::get('API_PCR',  [App\Http\Controllers\Cms\Cogs\MasterDataController::class, 'API_PCR']);
        
        //ProductClassification
        Route::get('cogs-product-classification', [App\Http\Controllers\Cms\Cogs\ProductClassificationController::class, 'ProductClassification'])->name('cogs-product-classification');
        Route::get('cogs-serch-product-classification', [App\Http\Controllers\Cms\Cogs\ProductClassificationController::class, 'SearchProductClassification']);
        Route::get('cogs-product-classification-detail/{category}', [App\Http\Controllers\Cms\Cogs\ProductClassificationController::class, 'ProductClassificationDetail']);
        
        //NewCOGS
        Route::get('cogs-search-product-category-manual', [App\Http\Controllers\Cms\Cogs\NewCOGSController::class, 'SearchProductCategoryManual']);
        Route::get('cogs-search-product-category-auto', [App\Http\Controllers\Cms\Cogs\NewCOGSController::class, 'SearchProductCategoryAuto']);
        Route::get('cogs-search-calculation-type', [App\Http\Controllers\Cms\Cogs\NewCOGSController::class, 'SearchCalculationType']);
        Route::get('cogs-search-bom', [App\Http\Controllers\Cms\Cogs\NewCOGSController::class, 'SearchBOM']);
        Route::get('cogs-search-cogs-header', [App\Http\Controllers\Cms\Cogs\NewCOGSController::class, 'SearchCogsHeader']);
        Route::post('cogs-delete-cogs', [App\Http\Controllers\Cms\Cogs\NewCOGSController::class, 'DeleteCOGS']);

        //PriceCalculationRequest 
        Route::get('cogs-price-calculation-request', [App\Http\Controllers\Cms\Cogs\PriceCalculationRequestController::class, 'PriceCalculationRequest'])->name('cogs-price-calculation-request');
        Route::get('cogs-refresh-apcr', [App\Http\Controllers\Cms\Cogs\PriceCalculationRequestController::class, 'RefreshAPCR']);
        Route::get('cogs-search-apcr', [App\Http\Controllers\Cms\Cogs\PriceCalculationRequestController::class, 'SearchAPCR']);

        //ConfirmPurchaseOrder
        Route::get('cogs-confirm-purchase-order', [App\Http\Controllers\Cms\Cogs\ConfirmPurchaseOrderController::class, 'ConfirmPurchaseOrder'])->name('cogs-confirm-purchase-order');
        Route::get('cogs-refresh-cpo', [App\Http\Controllers\Cms\Cogs\ConfirmPurchaseOrderController::class, 'RefreshCPO']);
        Route::get('cogs-search-cpo', [App\Http\Controllers\Cms\Cogs\ConfirmPurchaseOrderController::class, 'SearchCPO']);

        //FormCOGS
        Route::get('cogs-form-cogs/{cogsid}/{access}',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'FormCOGS']);
        Route::post('cogs-create-form-cogs',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'CreateFormCOGS']);
        Route::post('cogs-update-cogs-header',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'UpdateHeaderCOGS']);
        Route::get('cogs-import-kurs-form-cogs/{cogsid}',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'ImportKursFormCOGS']);
        Route::get('cogs-search-kurs-form-cogs',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'SearchKursFormCOGS']);
        Route::get('cogs-get-rawmaterial/{cogsid}',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'GetRawMaterial']);
        Route::get('cogs-get-sfcomponent/{cogsid}',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'GetSFComponent']);
        Route::get('cogs-get-consumables/{cogsid}',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'GetConsumables']);
        
        Route::get('cogs-search-rawmaterial',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'SearchRawMaterial']);
        Route::post('cogs-add-rawmaterial',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'AddRawMaterial']);
        Route::post('cogs-edit-rawmaterial',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'EditRawMaterial']);
        Route::post('cogs-delete-rawmaterial',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'DeleteRawMaterial']);

        Route::get('cogs-search-componentpir',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'SearchComponentPir']);
        Route::get('cogs-get-componentpir',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'GetDataComponentPir']);
        
        Route::get('cogs-search-sfcomponent',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'SearchSFComponent']);
        Route::post('cogs-add-sfcomponent',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'AddSFComponent']);
        Route::post('cogs-edit-sfcomponent',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'EditSFComponent']);
        Route::post('cogs-delete-sfcomponent',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'DeleteSFComponent']);
        
        Route::get('cogs-search-consumables',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'SearchConsumables']);
        Route::post('cogs-add-consumables',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'AddConsumables']);
        Route::post('cogs-edit-consumables',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'EditConsumables']);
        Route::post('cogs-delete-consumables',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'DeleteConsumables']);

        Route::get('cogs-search-process',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'SearchProcess']);
        Route::post('cogs-add-process',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'AddProcess']);
        Route::post('cogs-edit-process',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'EditProcess']);
        Route::post('cogs-delete-process',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'DeleteProcess']);

        Route::get('cogs-search-others',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'SearchOthers']);
        Route::post('cogs-add-others',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'AddOthers']);
        Route::post('cogs-edit-others',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'EditOthers']);
        Route::post('cogs-delete-others',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'DeleteOthers']);

        Route::get('cogs-search-masterprocess',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'SearchMasterProcess']);
        Route::get('cogs-get-masterprocess',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'GetMasterProcess']);
        Route::get('cogs-search-statuspricerawmaterial',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'SearchStatusPriceRawMaterial']);
        Route::post('cogs-edit-status-rawmaterial',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'EditStatusRawMaterial']);
        Route::post('cogs-update-calculated-header',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'UpdateCalculateHeader']);
        Route::get('cogs-search-calculated-header',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'SearchCalculateHeader']);

        Route::post('cogs-pdf',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'COGSPDF']);
        Route::get('cogs-save-new/{cogsid}',  [App\Http\Controllers\Cms\Cogs\FormCogsController::class, 'SaveNewCOGS']);

        //COGS List Update
        Route::get('cogs-list', [App\Http\Controllers\Cms\Cogs\FormCogsListController::class, 'CogsList'])->name('cogs-list');
        Route::get('cogs-serch-list', [App\Http\Controllers\Cms\Cogs\FormCogsListController::class, 'SearchCogsList']);
        Route::get('cogs-list-detail/{category}', [App\Http\Controllers\Cms\Cogs\FormCogsListController::class, 'CogsListDetail']);
        Route::get('cogs-search-cogspn', [App\Http\Controllers\Cms\Cogs\FormCogsListController::class, 'SearchCOGSPN']);

        // TSM
        Route::get('tsm', [App\Http\Controllers\Cms\Tsm\DashboardController::class, 'Dashboard']);
        Route::get('tsm-population', [App\Http\Controllers\Cms\Tsm\PopulationController::class, 'Population']);
        Route::get('tsm-sn', [App\Http\Controllers\Cms\Tsm\SNController::class, 'SN']);
        Route::get('tsm-import-sn',  [App\Http\Controllers\Cms\Tsm\SNController::class, 'ImportSN'])->name('tsm-import-sn');
        Route::get('tsm-area',  [App\Http\Controllers\Cms\Tsm\AreaController::class, 'Area']);
        Route::get('tsm-import-area',  [App\Http\Controllers\Cms\Tsm\AreaController::class, 'ImportArea'])->name('tsm-import-area');
        Route::get('tsm-get-serialnumber',  [App\Http\Controllers\Cms\Tsm\PopulationController::class, 'GetDataSerialNumber']);
        Route::get('tsm-search-serialnumber',  [App\Http\Controllers\Cms\Tsm\PopulationController::class, 'SearchSerialNumber']);
        Route::get('tsm-get-area',  [App\Http\Controllers\Cms\Tsm\PopulationController::class, 'GetDataArea']);
        Route::get('tsm-search-area',  [App\Http\Controllers\Cms\Tsm\PopulationController::class, 'SearchArea']);

        Route::post('tsm-add-population',  [App\Http\Controllers\Cms\Tsm\PopulationController::class, 'AddPopulation']);
        Route::get('tsm-search-population',  [App\Http\Controllers\Cms\Tsm\PopulationController::class, 'SearchPopulation']);
        Route::post('tsm-delete-population',  [App\Http\Controllers\Cms\Tsm\PopulationController::class, 'DeletePopulation']);

        Route::get('tsm-import-population',  [App\Http\Controllers\Cms\Tsm\PopulationController::class, 'ImportPopulation'])->name('tsm-import-population');
        Route::post('tsm-import-population-monitoring',  [App\Http\Controllers\Cms\Tsm\PopulationController::class, 'import']);
        
        Route::get('tsm-population-kalsel', [App\Http\Controllers\Cms\Tsm\PopulationKalselController::class, 'PK']);
        Route::get('tsm-import-pk', [App\Http\Controllers\Cms\Tsm\PopulationKalselController::class, 'ImportKALSEL']);

        Route::get('vw_count_sn', [App\Http\Controllers\Cms\Tsm\DashboardController::class, 'VwCountSN']);
        Route::get('vw_list_plant', [App\Http\Controllers\Cms\Tsm\DashboardController::class, 'VwListPlant']);
        
        Route::get('tsm-search-type-of-service',  [App\Http\Controllers\Cms\Cogs\PopulationController::class, 'SearchTypeOfService']);
    }
    });
});

Route::get('insertPRO',  [App\Http\Controllers\Cms\CompletenessComponent\Middleware\SAP_Insert::class, 'InsertPro']);
Route::get('insertMaterial',  [App\Http\Controllers\Cms\CompletenessComponent\Middleware\SAP_Insert::class, 'InsertMaterial']);

// PO TRACKING UPDATE Agam
Route::get('updateDataPotracking', [App\Http\Controllers\Cms\PoTracking\CSVController::class, 'allFunctionCSV']);

// CCR UPDATE DB bayu
// Route::get('updateInventory',  [App\Http\Controllers\Cms\CompletenessComponent\Middleware\SAP_Update_v2::class, 'inventory']);
// Route::get('updateProductionOrder',  [App\Http\Controllers\Cms\CompletenessComponent\Middleware\SAP_Update_v2::class, 'production_order']);
// Route::get('updateMaterialCSV',  [App\Http\Controllers\Cms\CompletenessComponent\Middleware\SAP_Update_v2::class, 'material']);
// Route::get('updateDateAPIMaterial',  [App\Http\Controllers\Cms\CompletenessComponent\Middleware\SAP_Update_v2::class, 'updateDateAPIMaterial']);
// Route::get('updateDataOlahanMaterial',  [App\Http\Controllers\Cms\CompletenessComponent\Middleware\SAP_Update_v2::class, 'dataOlahanMaterial']);
// Route::get('updatePersen',  [App\Http\Controllers\Cms\CompletenessComponent\Middleware\SAP_Update_v2::class, 'persen']);
// Route::get('UpdateMatListManual',  [App\Http\Controllers\Cms\CompletenessComponent\Middleware\SAP_Update_v2::class, 'UpdateMatListManual']);
