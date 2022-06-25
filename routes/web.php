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
    return view('auth.login');
});

Auth::routes();

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

        /******* Sports Module ***********/

        Route::get('/sports', [SportsController::class, 'index']);
        Route::post('/fetchsportsdata', [SportsController::class, 'fetchsportsdata']);
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


        /******* Leagues Module ***********/
        Route::get('/leagues', [LeaguesController::class, 'index']);
        Route::get('/fetch-leagues-data', [LeaguesController::class, 'fetchleaguesdata']);
        Route::post('/add-update-leagues', [LeaguesController::class, 'store']);
        Route::post('/edit-league', [LeaguesController::class, 'edit']);
        Route::post('/delete-league', [LeaguesController::class, 'destroy']);


        /******* Teams Module ***********/
        Route::get('/teams/{sports_id}', [TeamsController::class, 'index']);
        Route::get('/fetch-teams-data/{sports_id}', [TeamsController::class, 'fetchteamsdata']);
        Route::post('/add-update-teams/{sports_id}', [TeamsController::class, 'store']);
        Route::post('/delete-team', [TeamsController::class, 'destroy']);
        Route::post('/edit-team', [TeamsController::class, 'edit']);


        /******* Schedules Module ***********/
        Route::get('/schedules/{sports_id}', [ScheduleController::class, 'index']);
        Route::get('/fetch-schedules-data/{sports_id}', [ScheduleController::class, 'fetchschedulesdata']);
        Route::post('/add-update-schedules/{sports_id}', [ScheduleController::class, 'store']);
        Route::post('/delete-schedule', [ScheduleController::class, 'destroy']);
        Route::post('/edit-schedule', [ScheduleController::class, 'edit']);


        /******* Servers Module ***********/
        Route::get('/servers', [ServersController::class, 'index']);
        Route::get('/fetch-servers-data', [ServersController::class, 'fetchserversdata']);
        Route::post('/add-update-servers', [ServersController::class, 'store']);
        Route::post('/delete-server', [ServersController::class, 'destroy']);
        Route::post('/edit-server', [ServersController::class, 'edit']);

        /******* Schedule Servers  ***********/
        Route::get('/servers/{schedule_id}', [ServersController::class, 'fetchScheduleServersView']);;
        Route::get('/fetch-servers-data/{schedule_id}', [ServersController::class, 'fetchserversdata']);
        Route::post('/add-update-servers/{schedule_id}', [ServersController::class, 'store']);
        Route::post('/attach-servers/{schedule_id}', [ServersController::class, 'attachServers']);

    });
