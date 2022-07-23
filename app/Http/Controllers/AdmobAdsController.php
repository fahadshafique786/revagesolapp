<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppDetails;
use App\Models\AdmobAds;
use Response;

class AdmobAdsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role_or_permission:super-admin|view-admob_ads', ['only' => ['index','fetchAdmobAdsList']]);
        $this->middleware('role_or_permission:super-admin|manage-admob_ads',['only' => ['edit','store','destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $appsList = AppDetails::all();
        return view('admob_ads')
            ->with('appsList',$appsList);
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
//            $this->validate($request, [
//                'adName' => 'required|unique:admob_ads,adName,'.$request->id,
//                'app_detail_id' => 'required',
//                'adUId' => 'required',
//            ]);

            $validation = AdmobAds::where('adName',$request->adName)
                ->where('app_detail_id',$request->app_detail_id)
                ->where('id','!=',$request->id);

            $validationResponse = [];

            if($validation->exists()){
                $validationResponse = [];
                $validationResponse['message'] = "The given data was invalid.";
                $validationResponse['errors']['adName'] = "The ad name already exists with current App!";

                return Response::json($validationResponse,422);
            }

        }
        else
        {
//            $this->validate($request, [
//                'adName' => 'required|unique:admob_ads,adName',
//                'app_detail_id' => 'required',
//                'adUId' => 'required',
//            ]);

            $validation = AdmobAds::where('adName',$request->adName)
                ->where('app_detail_id',$request->app_detail_id);

            $validationResponse = [];

            if($validation->exists()){
                $validationResponse = [];
                $validationResponse['message'] = "The given data was invalid.";
                $validationResponse['errors']['adName'] = "The ad name already exists with current App!";

                return Response::json($validationResponse,422);
            }


        }

        $input = array();
        $input['adName'] = $request->adName;
        $input['app_detail_id'] = $request->app_detail_id;
        $input['adUId'] = $request->adUId;
        $input['isAdShow'] = $request->isAdShow;


        $user   =   AdmobAds::updateOrCreate(
            [
                'id' => $request->id
            ],
            $input);

        return response()->json(['success' => true]);
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
        $data  = AdmobAds::where($where)->first();
        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request)
    {
        AdmobAds::where('id',$request->id)->delete();
        return response()->json(['success' => true]);
    }

    public function fetchAdmobAdsList(Request  $request)
    {

        if(request()->ajax()) {

            $response = array();
            $Filterdata = AdmobAds::select('admob_ads.*','app_details.appName','app_details.PackageId as PackageId');


            if(isset($request->filter_app_id) && !empty($request->filter_app_id) && ($request->filter_app_id != '-1')){
                $Filterdata = $Filterdata->where('admob_ads.app_detail_id',$request->filter_app_id);
            }

            $Filterdata = $Filterdata->join('app_details', function ($join) {
                $join->on('app_details.id', '=', 'admob_ads.app_detail_id');
            })->orderBy('admob_ads.id','asc')->get();



            if(!empty($Filterdata))
            {
                $i = 0;
                foreach($Filterdata as $index => $obj)
                {

                    $response[$i]['srno'] = $i + 1;
                    $response[$i]['appName'] = $obj->appName . ' - ' . $obj->PackageId;
                    $response[$i]['name'] = $obj->adName;
                    $response[$i]['adUId'] = $obj->adUId;
                    $response[$i]['isAdShow'] = getBooleanStr($obj->isAdShow,true);
                    if(auth()->user()->hasRole('super-admin') || auth()->user()->can('manage-admob_ads'))
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
