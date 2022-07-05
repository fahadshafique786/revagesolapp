@extends('layouts.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-12">
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 text-center">
                                    <h4 class="">Register New Application</h4>
                                </div>
                            </div>

                        </div>
                        <div class="card-body">
                            <form class="" method="post" action="">

                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-2 col-form-label">App Name</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="appName" id="appName" value="{{$appData->appName}}" required>
                                    </div>

                                    <label for="staticEmail" class="col-sm-2 col-form-label">admobAppId</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="admobAppId" id="admobAppId" value="{{$appData->admobAppId}}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-2 col-form-label">adsIntervalTime</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="adsIntervalTime" id="adsIntervalTime" value="{{$appData->adsIntervalTime}}" required>
                                    </div>

                                    <label for="staticEmail" class="col-sm-2 col-form-label">checkIpAddressApiUrl</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="checkIpAddressApiUrl" id="checkIpAddressApiUrl" value="{{$appData->checkIpAddressApiUrl}}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-2 col-form-label">newAppPackage</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="newAppPackage" id="newAppPackage" value="{{$appData->newAppPackage}}" required>
                                    </div>

                                    <label for="staticEmail" class="col-sm-2 col-form-label">ourAppPackage</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="ourAppPackage" id="ourAppPackage" value="{{$appData->ourAppPackage}}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-2 col-form-label">startAppId</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="startAppId" id="startAppId" value="{{$appData->startAppId}}" required>
                                    </div>

                                    <label for="staticEmail" class="col-sm-2 col-form-label">suspendApp</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="suspendApp" id="suspendApp" value="{{$appData->suspendApp}}" required>
                                    </div>
                                </div>


                                <div class="form-group row">

                                    <label for="staticEmail" class="col-sm-2 col-form-label">isAdmobAdsShow</label>
                                    <div class="col-sm-4">
                                        <label for="isAdmobAdsShow1" class="cursor-pointer">
                                            <input type="radio" class="" id="isAdmobAdsShow1" name="isAdmobAdsShow" value="1"  {{($appData->isAdmobAdsShow) ? 'checked' : ''}} />
                                            <span class="">Yes</span>
                                        </label>

                                        <label for="isAdmobAdsShow0" class="cursor-pointer">
                                            <input type="radio" class="" id="isAdmobAdsShow0" name="isAdmobAdsShow" value="0" {{(!$appData->isAdmobAdsShow) ? 'checked' : ''}} />
                                            <span class="">No</span>
                                        </label>

                                    </div>

                                    <label for="staticEmail" class="col-sm-2 col-form-label">isAdmobOnline</label>
                                    <div class="col-sm-4">

                                        <label for="isAdmobOnline1" class="cursor-pointer">
                                            <input type="radio" class="" id="isAdmobOnline1" name="isAdmobOnline" value="1" {{($appData->isAdmobOnline) ? 'checked' : ''}}  />
                                            <span class="">Yes</span>
                                        </label>

                                        <label for="isAdmobOnline0" class="cursor-pointer">
                                            <input type="radio" class="" id="isAdmobOnline0" name="isAdmobOnline" value="0"  {{(!$appData->isAdmobOnline) ? 'checked' : ''}}  />
                                            <span class="">No</span>
                                        </label>

                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-2 col-form-label">isAdsInterval</label>
                                    <div class="col-sm-4">

                                        <label for="isAdsInterval1" class="cursor-pointer">
                                            <input type="radio" class="" id="isAdsInterval1" name="isAdsInterval" value="1"  {{($appData->isAdsInterval) ? 'checked' : ''}}   />
                                            <span class="">Yes</span>
                                        </label>

                                        <label for="isAdsInterval0" class="cursor-pointer">
                                            <input type="radio" class="" id="isAdsInterval0" name="isAdsInterval" value="0"  {{(!$appData->isAdsInterval) ? 'checked' : ''}}  />
                                            <span class="">No</span>
                                        </label>

                                    </div>

                                    <label for="staticEmail" class="col-sm-2 col-form-label">isBannerPlayer</label>
                                    <div class="col-sm-4">

                                        <label for="isBannerPlayer1" class="cursor-pointer">
                                            <input type="radio" class="" id="isBannerPlayer1" name="isBannerPlayer" value="1"   {{($appData->isBannerPlayer) ? 'checked' : ''}}  />
                                            <span class="">Yes</span>
                                        </label>

                                        <label for="isBannerPlayer0" class="cursor-pointer">
                                            <input type="radio" class="" id="isBannerPlayer0" name="isBannerPlayer" value="0"  {{(!$appData->isBannerPlayer) ? 'checked' : ''}}  />
                                            <span class="">No</span>
                                        </label>

                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-2 col-form-label">isIpAddressApiCall</label>
                                    <div class="col-sm-4">


                                        <label for="isIpAddressApiCall1" class="cursor-pointer">
                                            <input type="radio" class="" id="isIpAddressApiCall1" name="isIpAddressApiCall" value="1"  {{($appData->isIpAddressApiCall) ? 'checked' : ''}}  />
                                            <span class="">Yes</span>
                                        </label>

                                        <label for="isIpAddressApiCall0" class="cursor-pointer">
                                            <input type="radio" class="" id="isIpAddressApiCall0" name="isIpAddressApiCall" value="0"  {{(!$appData->isIpAddressApiCall) ? 'checked' : ''}}  />
                                            <span class="">No</span>
                                        </label>

                                    </div>

                                    <label for="staticEmail" class="col-sm-2 col-form-label">isMessageDialogDismiss</label>
                                    <div class="col-sm-4">



                                        <label for="isMessageDialogDismiss1" class="cursor-pointer">
                                            <input type="radio" class="" id="isMessageDialogDismiss1" name="isMessageDialogDismiss" value="1"  {{($appData->isMessageDialogDismiss) ? 'checked' : ''}}  />
                                            <span class="">Yes</span>
                                        </label>

                                        <label for="isMessageDialogDismiss1" class="cursor-pointer">
                                            <input type="radio" class="" id="isMessageDialogDismiss0" name="isMessageDialogDismiss" value="0"  {{(!$appData->isMessageDialogDismiss) ? 'checked' : ''}}  />
                                            <span class="">No</span>
                                        </label>

                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-2 col-form-label">isSponsorAdsShow</label>
                                    <div class="col-sm-4">


                                        <label for="isSponsorAdsShow1" class="cursor-pointer">
                                            <input type="radio" class="" id="isSponsorAdsShow1" name="isSponsorAdsShow" value="1"  />
                                            <span class="">Yes</span>
                                        </label>

                                        <label for="isSponsorAdsShow0" class="cursor-pointer">
                                            <input type="radio" class="" id="isSponsorAdsShow0" name="isSponsorAdsShow" value="0" checked />
                                            <span class="">No</span>
                                        </label>


                                    </div>

                                    <label for="staticEmail" class="col-sm-2 col-form-label">isStartAppAdsShow</label>
                                    <div class="col-sm-4">

                                        <label for="isStartAppAdsShow1" class="cursor-pointer">
                                            <input type="radio" class="" id="isStartAppAdsShow1" name="isStartAppAdsShow" value="1"  />
                                            <span class="">Yes</span>
                                        </label>

                                        <label for="isStartAppAdsShow0" class="cursor-pointer">
                                            <input type="radio" class="" id="isStartAppAdsShow0" name="isStartAppAdsShow" value="0" checked />
                                            <span class="">No</span>
                                        </label>


                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-2 col-form-label">isStartAppOnline</label>
                                    <div class="col-sm-4">


                                        <label for="isStartAppOnline1" class="cursor-pointer">
                                            <input type="radio" class="" id="isStartAppOnline1" name="isStartAppOnline" value="1"  {{($appData->isStartAppOnline) ? 'checked' : ''}}/>
                                            <span class="">Yes</span>
                                        </label>

                                        <label for="isStartAppOnline0" class="cursor-pointer">
                                            <input type="radio" class="" id="isStartAppOnline0" name="isStartAppOnline" value="0" {{(!$appData->isStartAppOnline) ? 'checked' : ''}} />
                                            <span class="">No</span>
                                        </label>

                                    </div>

                                    <label for="staticEmail" class="col-sm-2 col-form-label">minimumVersionSupport</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="minimumVersionSupport" id="minimumVersionSupport" value="{{$appData->minimumVersionSupport}}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-2 col-form-label">suspendAppMessage</label>
                                    <div class="col-sm-4">
                                        <textarea class="form-control" name="suspendAppMessage" id="suspendAppMessage">{{$appData->suspendAppMessage}}</textarea>
                                    </div>

                                    <div class="col-sm-6 text-right">
                                        <button class="btn bg-dark vertical-bottom" name="submit" id=submitApp"> SUBMIT </button>
                                    </div>



                                </div>



                            </form>



                        </div>
                    </div>
                </div>

            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->


    </section>
    <!-- /.content -->


@endsection

@push('scripts')
    <script type="text/javascript">

        $(document).ready(function($){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        });

    </script>

@endpush
