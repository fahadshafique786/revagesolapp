<?php

namespace App\Http\Controllers;

use App\Models\AppDetails;
use App\Models\SponsorAds;
use Illuminate\Http\Request;

class SponsorsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
//        $this->middleware('role_or_permission:super-admin|view-sponsors', ['only' => ['index','FetchSponsorsData']]);
//        $this->middleware('role_or_permission:super-admin|manage-sponsors',['only' => ['edit','store','destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $appsList = AppDetails::all();
        return view('sponsors')
            ->with('appsList',$appsList);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        if(!empty($request->id))
        {
            $this->validate($request, [
                'adName' => 'required|unique:sponsor_ads,adName,'.$request->id,
                'app_detail_id' => 'required',
            ]);
        }
        else
        {
            $this->validate($request, [
                'adName' => 'required|unique:sponsor_ads,adName,'.$request->id,
                'app_detail_id' => 'required',
            ]);
        }

        $input = array();
        $input['adName'] = $request->adName;
        $input['app_detail_id'] = $request->app_detail_id;
        $input['clickAdToGo'] = $request->clickAdToGo;
        $input['isAdShow'] = $request->isAdShow;


        if($request->hasFile('adUrlImage'))
        {
            $fileobj				= $request->file('adUrlImage');
            $file_extension_name 	= $fileobj->getClientOriginalExtension('adUrlImage');
            $file_unique_name 		= strtolower($request->adName).'-'.time().rand(1000,9999).'.'.$file_extension_name;
            $destinationPath		= public_path('/uploads/sponsor_ads/');
            $fileobj->move($destinationPath,$file_unique_name);

            $input['adUrlImage'] = $file_unique_name;
        }

        $user   =   SponsorAds::updateOrCreate(
            [
                'id' => $request->id
            ],
            $input);

        return response()->json(['success' => true]);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\sponsors  $sponsors
     * @return \Illuminate\Http\Response
     */
    public function show(sponsors $sponsors)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\sponsors  $sponsors
     * @return \Illuminate\Http\Response
     */
    public function edit(sponsors $sponsors)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\sponsors  $sponsors
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, sponsors $sponsors)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\sponsors  $sponsors
     * @return \Illuminate\Http\Response
     */
    public function destroy(sponsors $sponsors)
    {
        //
    }
}
