<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\AppDetails;
use App\Models\SponsorAds;
use App\Models\AdmobAds;
use Illuminate\Routing\Controller as BaseController;

class ApiAppDetails extends BaseController
{
    public function __construct()
    {
//        dd("RUN");
    }

    public function index()
    {
        $dataObject = AppDetails::select()
            ->orderBy('id','asc')
            ->limit(1)
            ->get();

        $app_detail_id = $dataObject[0]->id;


        $ads_list = AdmobAds::where('app_detail_id',$app_detail_id)
            ->get();


        $sponsor_list = SponsorAds::where('app_detail_id',$app_detail_id)
        ->get();


        foreach($ads_list as $index => $arr){
            $arr->isAdShow = (int) $arr->isAdShow;
            $arr->isAdShow = getBoolean($arr->isAdShow);
        }

        foreach($sponsor_list as $index => $arr1){

            $arr1->isAdShow = (int) $arr1->isAdShow;
            $arr1->isAdShow = getBoolean($arr1->isAdShow);
        }

        // unset($dataArray[0]->id);
        $dataObject[0]->suspendAppMessage = $dataObject[0]->suspendAppMessage = "";
        $response['AppDetails'] = $dataObject[0];
        $response['AppDetails']->admobAds = $ads_list;
        $response['AppDetails']->sponsorAdsList = $sponsor_list;


        foreach($response as $index => $obj){

            $obj->adsIntervalTime = (int)($obj->adsIntervalTime);
            $obj->minimumVersionSupport = (int)($obj->minimumVersionSupport);

            $obj->isAdmobAdsShow = getBoolean($obj->isAdmobAdsShow);
            $obj->isAdmobOnline = getBoolean($obj->isAdmobOnline);
            $obj->isAdsInterval = getBoolean($obj->isAdsInterval);
            $obj->isBannerPlayer = getBoolean($obj->isBannerPlayer);
            $obj->isIpAddressApiCall = getBoolean($obj->isIpAddressApiCall);
            $obj->isMessageDialogDismiss = getBoolean($obj->isMessageDialogDismiss);
            $obj->isSponsorAdsShow = getBoolean($obj->isSponsorAdsShow);
            $obj->isStartAppAdsShow = getBoolean($obj->isStartAppAdsShow);
            $obj->isStartAppOnline = getBoolean($obj->isStartAppOnline);
            $obj->suspendApp = getBoolean($obj->suspendApp);

        }

        echo json_encode($response);
    }



}
