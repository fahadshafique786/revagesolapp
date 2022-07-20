<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Sports;
use Illuminate\Routing\Controller as BaseController;

class SportsApi extends BaseController
{
    public function __construct()
    {
//        dd("RUN");
    }

    public function index()
    {
        $data = Sports::select()
            ->orderBy('id','asc')
            ->get();
        $response = [];
        $response['SportsList'] = $data;


        echo json_encode($response);
        exit();
    }



}
