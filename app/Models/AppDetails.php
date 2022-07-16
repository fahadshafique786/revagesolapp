<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppDetails extends Model
{
    use HasFactory;

    protected $fillable = [

        'sports_id',
        'storePackageId',
        'appName',
        'appLogo',
        'admobAppId',
        'adsIntervalTime',
        'checkIpAddressApiUrl',
        'isAdmobAdsShow',
        'isAdmobOnline',
        'isAdsInterval',
        'isBannerPlayer',
        'isIpAddressApiCall',
        'isMessageDialogDismiss',
        'isSponsorAdsShow',
        'isStartAppAdsShow',
        'isStartAppOnline',
        'minimumVersionSupport',
        'startAppId',
        'newAppPackage',
        'ourAppPackage',
        'suspendApp',
        'suspendAppMessage'

    ];



}
