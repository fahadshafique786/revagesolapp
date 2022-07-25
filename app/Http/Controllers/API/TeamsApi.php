<?php

namespace App\Http\Controllers\API;

use App\Models\Teams;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;

class TeamsApi extends BaseController
{
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $response = ['code'=>200,'message'=>'Success!'];
        $responseData = null;

        if(isset($request->league_id)){

            $data = Teams::where('leagues_id',$request->league_id)
                ->select(DB::raw('id,name,sports_id,leagues_id,points,IFNULL(icon,"") AS icon'));

            if($data->exists()){
                $data = $data->get();
                $responseData['TeamsList'] = $data;
            }
            else{
                $response['code'] = 400;
                $response['message'] = 'Teams data not found!';
            }

        }
        else{
            $response['code'] = 400;
            $response['message'] = 'League Id required!';
        }



        $response['data'] = $responseData;
        echo json_encode($response);
        exit();
    }

}
