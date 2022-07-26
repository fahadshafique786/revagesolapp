<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\AppDetails;
use App\Models\SponsorAds;
use App\Models\AdmobAds;
use Illuminate\Routing\Controller as BaseController;

class Applications extends BaseController
{
    public function __construct()
    {
//        verifyToken();
    }

    public function index(Request $request)
    {
        $response = ['code'=>200,'message'=>'Success!'];
        $responseData = null;

        if(isset($request->package_id)){

            $dataObject = AppDetails::select()
                ->where('packageId',$request->package_id)
                ->orderBy('id','asc');

            if($dataObject->exists()){
                $dataObject = $dataObject->get();


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
                $dataObject[0]->suspendAppMessage = (!empty($dataObject[0]->suspendAppMessage)) ? $dataObject[0]->suspendAppMessage : "";
                $responseData['AppDetails'] = $dataObject[0];
                $responseData['AppDetails']->admobAds = $ads_list;
                $responseData['AppDetails']->sponsorAds = $sponsor_list;


                foreach($responseData as $index => $obj){

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
                    $obj->isSuspendApp = getBoolean($obj->isSuspendApp);

                }

            }
            else{
                $response['code'] = 400;
                $response['message'] = 'Application not found!';
            }


        }
        else{
            $response['code'] = 400;
            $response['message'] = 'Package Id required!';
        }
        $response['data'] = $responseData;
        echo json_encode($response);
    }



}
