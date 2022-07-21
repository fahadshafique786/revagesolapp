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
        $response = ['code'=>'200','message'=>'Success!'];
        $responseData = null;

        $data = Sports::select()
            ->orderBy('id','asc');

        if($data->exists()) {
            $data = $data->get();
            $responseData['SportsList'] = $data;
        }
        else{
            $response['code'] = 400;
            $response['message'] = 'Sports List not found!';
        }

        $response['data'] = $responseData;

        echo json_encode($response);
        exit();
    }



}
