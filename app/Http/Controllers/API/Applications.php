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
        $response = [];
        $responseData = ['code'=>200,'message'=>'Success!'];

        if(isset($request->package_id)){

            $dataObject = AppDetails::select()
                ->where('packageId',$request->package_id)
                ->orderBy('id','asc');

            if($dataObject->exists()){
                $dataObject = $dataObject->get();


                $app_detail_id = $dataObject[0]->id;


                $adsListObject = AdmobAds::where('app_detail_id',$app_detail_id)
                    ->get();


                $sponsorListObject = SponsorAds::where('app_detail_id',$app_detail_id)
                    ->get();


                foreach($adsListObject as $index => $arr){
                    $arr->isAdShow = (int) $arr->isAdShow;
                    $arr->isAdShow = getBoolean($arr->isAdShow);
                }

                $adsList = $adsListObject;

                foreach($sponsorListObject as $index => $arr1){

                    $arr1->isAdShow = (int) $arr1->isAdShow;
                    $arr1->isAdShow = getBoolean($arr1->isAdShow);
                }

                $sponsorList = $sponsorListObject;

                $dataObject[0]->suspendAppMessage = (!empty($dataObject[0]->suspendAppMessage)) ? $dataObject[0]->suspendAppMessage : "";
//                $responseData['data'] = $dataObject;

                foreach($dataObject as $index => $obj){

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

                    $responseData['data'] = $dataObject[0];
                    $responseData['data']->admobAds = $adsList;
                    $responseData['data']->sponsorAds = $sponsorList;

//                  $responseData['data'][0]->admobAds = $adsList;
//                  $responseData['data'][0]->sponsorAds = $sponsorList;

            }
            else{
                $responseData['code'] = 400;
                $responseData['message'] = 'Application not found!';
            }


        }
        else{
            $responseData['code'] = 400;
            $responseData['message'] = 'Package Id required!';
        }

//        dd($responseData['data']->admobAds[0]->adName);

        //        $response = $responseData;
        echo json_encode($responseData);
    }



}
