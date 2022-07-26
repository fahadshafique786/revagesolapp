<?php

namespace App\Http\Controllers\API;

use App\Models\Leagues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;

class LeaguesApi extends BaseController
{
    public function __construct()
    {
//        verifyToken();
    }

    public function index(Request $request)
    {
        $response = ['code'=>200,'message'=>'Success!'];
        $responseData = null;

        if(isset($request->sport_id)){

            $data = Leagues::where('sports_id',$request->sport_id)
                ->select(DB::raw('id,name,sports_id,IFNULL(icon,"") AS icon'));

            if($data->exists()){
                $data = $data->get();
                $responseData['LeaguesList'] = $data;
            }
            else{
                $response['code'] = 400;
                $response['message'] = 'Leagues data not found!';
            }


        }
        else{
            $response['code'] = 400;
            $response['message'] = 'Sport Id required!';
        }




        $response['data'] = $responseData;
        echo json_encode($response);
        exit();
    }

}
