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
        $data = Teams::where('leagues_id',$request->league_id)
            ->select(DB::raw('id,name,sports_id,leagues_id,points,IFNULL(icon,"") AS icon'))

            ->get();

        $response = [];
        $response['TeamsList'] = $data;


        echo json_encode($response);
        exit();
    }

}
