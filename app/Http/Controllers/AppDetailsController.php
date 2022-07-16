<?php

namespace App\Http\Controllers;

use App\Models\SponsorAds;
use Illuminate\Http\Request;
use App\Models\AppDetails;
use App\Models\Sports;
use App\Models\SponsorAds;

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

        return view('application.index')
            ->with('apps_list',$appsList);
    }


    public function create()
    {
        $sportsList = Sports::all();
        return view('application.create')
        ->with('sportsList',$sportsList);
    }


    public function edit($application_id)
    {
        $appData = AppDetails::where('id',$application_id)->first();

        $sportsList = Sports::all();


        return view('application.edit')
            ->with('sportsList',$sportsList)
            ->with('appData',$appData)
            ->with('application_id',$application_id);
    }


    public function store(Request $request,$application_id = false)
    {
//        dd($request->all());
        if(!empty($application_id))
        {
            $this->validate($request, [
                'appName' => 'required|unique:app_details,appName,'.$application_id,
                'sports_id' => 'required',
                'admobAppId' => 'required',
                'adsIntervalTime' => 'required',
                'checkIpAddressApiUrl' => 'required',
                'minimumVersionSupport' => 'required',
                'startAppId' => 'required',
                'newAppPackage' => 'required',
                'ourAppPackage' => 'required',
            ]);
        }
        else
        {
            $this->validate($request, [
                'appName' => 'required|unique:app_details',
                'sports_id' => 'required',
                'admobAppId' => 'required',
                'adsIntervalTime' => 'required',
                'checkIpAddressApiUrl' => 'required',
                'minimumVersionSupport' => 'required',
                'startAppId' => 'required',
                'newAppPackage' => 'required',
                'ourAppPackage' => 'required',
            ]);
        }

        $input = [];
        $input = $request->all();


        if($request->hasFile('appLogo'))
        {
            $fileobj				= $request->file('appLogo');
            $file_extension_name 	= $fileobj->getClientOriginalExtension('appLogo');
            $file_unique_name 		= strtolower(str_replace(' ','-',$request->appName)).'-'.time().rand(1000,9999).'.'.$file_extension_name;
            $destinationPath		= public_path('/uploads/apps/');
            $fileobj->move($destinationPath,$file_unique_name);

            $input['appLogo'] = $file_unique_name;
        }

        $user   =   AppDetails::updateOrCreate(
            [
                'id' => $application_id
            ],
            $input);

        return response()->json(['success' => true]);
    }



    public function destroy(Request $request)
    {
        SponsorAds::where('app_detail_id',$request->id)->delete();
        AppDetails::where('id',$request->id)->delete();

        return response()->json(['success' => true]);
    }
}
