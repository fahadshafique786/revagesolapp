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
    }

    public function index(Request $request)
    {
        $data = Leagues::where('sports_id',$request->sport_id)
            ->select(DB::raw('id,name,sports_id,IFNULL(icon,"") AS icon'))
            ->get();

        $response = [];

        $response['LeaguesList'] = $data;


        echo json_encode($response);
        exit();
    }

}
