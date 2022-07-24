<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermissionsController;

use App\Http\Controllers\SportsController;
use App\Http\Controllers\LeaguesController;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ServersController;
use App\Http\Controllers\AppDetailsController;
use App\Http\Controllers\SponsorsController;
use App\Http\Controllers\AdmobAdsController;
use \Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CustomAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    if(!empty(Auth::user())){
        return redirect()->route('dashboard');
    }
    else{
        return redirect()->route('login');
//        view('auth.login');
    }
});


//Route::get('/', ['middleware' => 'guest', function()
//{
//    return redirect()->route('dashboard');
//    // Redirected If Authenticated
//}]);

//Auth::routes();


#Remember me functionality in Laravel
Route::get('/user-register',[CustomAuthController::class,'registerform'])->name('user.register');
Route::post('/post-registration',[CustomAuthController::class,'postRegistration'])->name('post.register');

Route::get('/login',[CustomAuthController::class,'loginform'])->name('login');
Route::post('/verify-login',[CustomAuthController::class,'checklogin'])->name('post.login');

Route::post('logout', [CustomAuthController::class, 'logout'])->name('logout');


Route::group(
    [
        'middleware' => ['auth'],
        'prefix' => 'admin/',
    ],
    function() {

        /******* User Module ***********/

        Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/fetchusersdata', [UserController::class, 'fetchusersdata']);
        Route::post('/add-update-user', [UserController::class, 'store']);
        Route::post('/edit-user', [UserController::class, 'edit']);
        Route::post('/delete-user', [UserController::class, 'destroy']);
        Route::post('/edit-profile', [UserController::class, 'editProfile']);
        Route::post('/update-profile', [UserController::class, 'updateProfile']);
        Route::post('/update-password', [UserController::class, 'changePassword']);

        /******* Sports Module ***********/

        Route::get('/sports', [SportsController::class, 'index']);
        Route::get('/fetchsportsdata', [SportsController::class, 'fetchsportsdata']);
        Route::post('/add-update-Sport', [SportsController::class, 'store']);
        Route::post('/edit-Sport', [SportsController::class, 'edit']);
        Route::post('/delete-sport', [SportsController::class, 'destroy']);

        /******* RBAC Management ***********/

        Route::get('/roles', [RolesController::class, 'index']);;
        Route::get('/fetchrolesdata', [RolesController::class, 'fetchRolesdata']);
        Route::post('/add-update-role', [RolesController::class, 'store']);
        Route::post('/edit-role', [RolesController::class, 'edit']);
        Route::post('/update-role', [RolesController::class, 'updateRole']);
        Route::post('/delete-role', [RolesController::class, 'destroy']);
        Route::get('/permissions', [PermissionsController::class, 'index']);
        Route::get('/fetchpermissionsdata', [PermissionsController::class, 'fetchpermissionsdata']);
        Route::post('/edit-permission', [PermissionsController::class, 'edit']);
        Route::post('/add-update-permission', [PermissionsController::class, 'store']);
        Route::post('/delete-permission', [PermissionsController::class, 'destroy']);


        /******* LeaguesApi Module ***********/
        Route::get('/leagues', [LeaguesController::class, 'index']);
        Route::post('/fetch-leagues-data', [LeaguesController::class, 'fetchleaguesdata']);
        Route::post('/add-update-leagues', [LeaguesController::class, 'store']);
        Route::post('/edit-league', [LeaguesController::class, 'edit']);
        Route::post('/delete-league', [LeaguesController::class, 'destroy']);
        Route::post('/leagueslistbysport', [LeaguesController::class, 'getLeaguesOptionBySports']);

        /******* Teams Module ***********/
        Route::get('/teams/{sports_id}', [TeamsController::class, 'index']);
        Route::post('/fetch-teams-data/{sports_id}', [TeamsController::class, 'fetchteamsdata']);
        Route::post('/add-update-teams/{sports_id}', [TeamsController::class, 'store']);
        Route::post('/delete-team', [TeamsController::class, 'destroy']);
        Route::post('/edit-team', [TeamsController::class, 'edit']);
        Route::post('/getTeamsByLeagueId', [TeamsController::class, 'getTeamsByLeagueId']);


        /******* Schedules Module ***********/
        Route::get('/schedules/{sports_id}', [ScheduleController::class, 'index']);
        Route::post('/fetch-schedules-data/{sports_id}', [ScheduleController::class, 'fetchschedulesdata']);
        Route::post('/add-update-schedules/{sports_id}', [ScheduleController::class, 'store']);
        Route::post('/delete-schedule', [ScheduleController::class, 'destroy']);
        Route::post('/edit-schedule', [ScheduleController::class, 'edit']);
        Route::post('/update-schedule-live-status', [ScheduleController::class, 'updateScheduleLiveStatus']);


        /******* Servers Module ***********/
        Route::get('/servers', [ServersController::class, 'index']);
        Route::post('/fetch-servers-data', [ServersController::class, 'fetchserversdata']);
        Route::post('/add-update-servers', [ServersController::class, 'store']);
        Route::post('/delete-server', [ServersController::class, 'destroy']);
        Route::post('/edit-server', [ServersController::class, 'edit']);

        /******* Schedule Servers  ***********/
        Route::get('/servers/{schedule_id}', [ServersController::class, 'fetchScheduleServersView']);;
        Route::get('/fetch-servers-data/{schedule_id}', [ServersController::class, 'fetchserversdata']);
        Route::post('/add-update-servers/{schedule_id}', [ServersController::class, 'store']);
        Route::post('/attach-servers/{schedule_id}', [ServersController::class, 'attachServers']);
        Route::post('/delete-server/{schedule_id}', [ServersController::class, 'destroy']);



        /******* Application Module  ***********/
        Route::get('/app', [AppDetailsController::class, 'index']);;
        Route::get('/app/create', [AppDetailsController::class, 'create'])->name('app.create');
        Route::get('/app/{app_id}', [AppDetailsController::class, 'edit'])->name('app.edit');
        Route::post('/add-update-apps', [AppDetailsController::class, 'store']);
        Route::post('/add-update-apps/{app_id}', [AppDetailsController::class, 'store']);
        Route::post('/delete-app', [AppDetailsController::class, 'destroy']);


        /******* Sponsor Ads Module  ***********/
        Route::get('/sponsors', [SponsorsController::class, 'index']);;
        Route::post('/add-update-sponsorads', [SponsorsController::class, 'store']);
        Route::post('/fetch-sponsor-data/', [SponsorsController::class, 'fetchSponsorAdsList']);
        Route::post('/edit-sponsor-ads', [SponsorsController::class, 'edit']);
        Route::post('/delete-sponsor-ads', [SponsorsController::class, 'destroy']);


        /******* Sponsor Ads Module  ***********/
        Route::get('/admob_ads', [AdmobAdsController::class, 'index']);;
        Route::post('/add-update-admob_ads', [AdmobAdsController::class, 'store']);
        Route::post('/fetch-admob_ads-data/', [AdmobAdsController::class, 'fetchAdmobAdsList']);
        Route::post('/edit-admob-ads', [AdmobAdsController::class, 'edit']);
        Route::post('/delete-admob-ads', [AdmobAdsController::class, 'destroy']);


//        Route::get('/app/create', [SponsorsController::class, 'create'])->name('app.create');
//        Route::get('/app/{app_id}', [SponsorsController::class, 'edit'])->name('app.edit');
//        Route::post('/add-update-apps', [SponsorsController::class, 'store']);
//        Route::post('/add-update-apps/{app_id}', [SponsorsController::class, 'store']);
//        Route::post('/delete-app', [SponsorsController::class, 'destroy']);

    });
