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
    public function edit(Request $request)
    {
        $where = array('id' => $request->id);
        $data  = SponsorAds::where($where)->first();
        return response()->json($data);
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
      * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request)
    {
        SponsorAds::where('id',$request->id)->delete();
        return response()->json(['success' => true]);
    }

    public function fetchSponsorAdsList(Request  $request)
    {

        if(request()->ajax()) {

            $response = array();
            $Filterdata = SponsorAds::select('sponsor_ads.*','app_details.appName');


            if(isset($request->filter_sports) && !empty($request->filter_sports)){
                $Filterdata = $Filterdata->where('leagues.sports_id',$request->filter_sports);
            }

            $Filterdata = $Filterdata->join('app_details', function ($join) {
                $join->on('app_details.id', '=', 'sponsor_ads.app_detail_id');
            })->orderBy('sponsor_ads.id','asc')->get();



            if(!empty($Filterdata))
            {
                $i = 0;
                foreach($Filterdata as $index => $obj)
                {

                    $images =  (!empty($obj->adUrlImage)) ? '<img class="dataTable-image" src="'.url("/uploads/sponsor_ads/").'/'.$obj->adUrlImage.'" />' : '<a href="javascript:void(0)" class="" ><i class="fa fa-image text-xl"></i></a>';

                    $response[$i]['srno'] = $i + 1;
                    $response[$i]['appName'] = $obj->appName;
                    $response[$i]['name'] = $obj->adName;
                    $response[$i]['adUrlImage'] = $images;
                    $response[$i]['url'] = $obj->clickAdToGo;
                    $response[$i]['isAdShow'] = getBooleanStr($obj->isAdShow,true);
                    if(auth()->user()->hasRole('super-admin') || auth()->user()->can('manage-sponsors'))
                    {
                        $response[$i]['action'] = '<a href="javascript:void(0)" class="btn edit" data-id="'. $obj->id .'"><i class="fa fa-edit  text-info"></i></a>
											<a href="javascript:void(0)" class="btn delete " data-id="'. $obj->id .'"><i class="fa fa-trash-alt text-danger"></i></a>';
                    }
                    else
                    {
                        $response[$i]['action'] = "-";
                    }
                    $i++;
                }
            }

            return datatables()->of($response)
                ->addIndexColumn()
                ->rawColumns(['adUrlImage','action'])
                ->make(true);
        }
    }


}
