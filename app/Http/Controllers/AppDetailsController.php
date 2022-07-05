<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppDetails;

class AppDetailsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
//        $this->middleware('role_or_permission:super-admin|view-sports', ['only' => ['index','fetchsportsdata']]);
//        $this->middleware('role_or_permission:super-admin|manage-sports',['only' => ['edit','store','editProfile','updateRole','destroy']]);
    }


    public function index(Request $request)
    {
        $appsList = AppDetails::select('id','appName','appLogo')->get();
//        dd($appsList);
        return view('application.index')
            ->with('apps_list',$appsList);
    }


    public function create()
    {
//        $appsList = AppDetails::select('id','appName','appLogo')->get();
        return view('application.create');
    }


    public function edit($application_id)
    {
        $appData = AppDetails::where('id',$application_id)->first();
//        dd($appData);
        return view('application.edit')
            ->with('appData',$appData);
    }

}
