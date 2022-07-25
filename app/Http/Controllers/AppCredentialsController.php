<?php

namespace App\Http\Controllers;

use App\Models\AppCredentials;
use Illuminate\Http\Request;
use App\Models\AppDetails;

class AppCredentialsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
//        $this->middleware('role_or_permission:super-admin|view-app_credentials', ['only' => ['index','fetchAppCredentialsList']]);
//        $this->middleware('role_or_permission:super-admin|manage-app_credentials',['only' => ['edit','store','destroy']]);
    }
    public function index()
    {
        $appsList = AppDetails::all();
        return view('credentials.index')
            ->with('appsList',$appsList);
    }


    public function store(Request $request)
    {
        if(!empty($request->id))
        {
            $validation = AppCredentials::where('secret_key',$request->secret_key)
                ->where('app_detail_id',$request->app_detail_id)
                ->where('id','!=',$request->id);

            $validationResponse = [];

            if($validation->exists()){
                $validationResponse = [];
                $validationResponse['message'] = "The given data was invalid.";
                $validationResponse['errors']['secret_key'] = "The ad name already exists with current App!";

                return Response::json($validationResponse,422);
            }

        }
        else
        {

            $validation = AppCredentials::where('secret_key',$request->secret_key)
                ->where('app_detail_id',$request->app_detail_id);

            $validationResponse = [];

            if($validation->exists()){
                $validationResponse = [];
                $validationResponse['message'] = "The given data was invalid.";
                $validationResponse['errors']['secret_key'] = "The ad name already exists with current App!";

                return Response::json($validationResponse,422);
            }

        }

        $input = array();
        $input['secret_key'] = $request->adName;
        $input['stream_key'] = $request->stream_key;
        $input['app_detail_id'] = $request->app_detail_id;

        $user   =   AppCredentials::updateOrCreate(
            [
                'id' => $request->id
            ],
            $input);

        return response()->json(['success' => true]);
    }


    public function edit(Request $request)
    {
        $where = array('id' => $request->id);
        $data  = AppCredentials::where($where)->first();
        return response()->json($data);
    }

    public function destroy(Request $request)
    {
        AppCredentials::where('id',$request->id)->delete();
        return response()->json(['success' => true]);
    }

    public function fetchAppCredentialsList(Request  $request)
    {

        if(request()->ajax()) {

            $response = array();
            $Filterdata = AppCredentials::select('app_credentials.*','app_details.appName','app_details.PackageId as PackageId');


            if(isset($request->filter_app_id) && !empty($request->filter_app_id) && ($request->filter_app_id != '-1')){
                $Filterdata = $Filterdata->where('app_credentials.app_detail_id',$request->filter_app_id);
            }

            $Filterdata = $Filterdata->join('app_details', function ($join) {
                $join->on('app_details.id', '=', 'app_credentials.app_detail_id');
            })->orderBy('app_credentials.id','asc')->get();



            if(!empty($Filterdata))
            {
                $i = 0;
                foreach($Filterdata as $index => $obj)
                {


                    $response[$i]['srno'] = $i + 1;
                    $response[$i]['appName'] = $obj->appName . ' - ' . $obj->PackageId;
                    $response[$i]['secret_key'] = $obj->secret_key;
                    $response[$i]['stream_key'] = $obj->stream_key;
                    if(auth()->user()->hasRole('super-admin') || auth()->user()->can('manage-app_credentials'))
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
                ->rawColumns(['action'])
                ->make(true);
        }
    }

}
