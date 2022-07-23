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
                            <form action="javascript:void(0)" id="addEditForm" name="addEditForm" class="form-horizontal" method="POST" enctype="multipart/form-data">

                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-2 col-form-label">Sports</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="sports_id" id="sports_id" required>
                                            <option value="">   Select </option>
                                            @foreach($sportsList as $obj)
                                                <option value="{{$obj->id}}" {{($obj->id == $appData->sports_id) ? 'selected' : '' }}>   {{   $obj->name }}    </option>
                                            @endforeach
                                        </select>


                                     </div>


                                    <label for="staticEmail" class="col-sm-2 col-form-label" id="PackageIdLabel">PackageId</label>

                                    <div class="col-sm-4">
                                        <input type="text"  class="form-control" name="PackageId" id="PackageId" value="{{$appData->PackageId}}" onkeyup="$('#PackageIdError').text('')"   required />
                                        <span class="text-danger" id="PackageIdError"></span>
                                    </div>

                                </div>

                                <div class="form-group row">

                                    <label for="staticEmail" class="col-sm-2 col-form-label">App Name</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="appName" id="appName" value="{{$appData->appName}}" required>
                                    </div>



                                    <label for="staticEmail" class="col-sm-2 col-form-label">App Logo</label>
                                    <div class="col-sm-4">
                                        <input type="file" class="" name="appLogo" id="appLogo" value="{{$appData->appLogo}}"  {{ (!$appData->appLogo) ? 'required' : '' }} >

                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-2 col-form-label">adsIntervalTime</label>
                                    <div class="col-sm-4">
                                        <input type="number" class="form-control" name="adsIntervalTime" id="adsIntervalTime" value="{{$appData->adsIntervalTime}}" required>
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




                                    <label for="staticEmail" class="col-sm-2 col-form-label">admobAppId</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="admobAppId" id="admobAppId" value="{{$appData->admobAppId}}" required>
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
                                            <input type="radio" class="" id="isSponsorAdsShow1" name="isSponsorAdsShow" value="1"   {{($appData->isSponsorAdsShow) ? 'checked' : ''}}  />
                                            <span class="">Yes</span>
                                        </label>

                                        <label for="isSponsorAdsShow0" class="cursor-pointer">
                                            <input type="radio" class="" id="isSponsorAdsShow0" name="isSponsorAdsShow" value="0"  {{(!$appData->isSponsorAdsShow) ? 'checked' : ''}}  />
                                            <span class="">No</span>
                                        </label>


                                    </div>

                                    <button id="buttonxyze" type="button"> DONEEE </button>


                                    <label for="staticEmail" class="col-sm-2 col-form-label">isStartAppAdsShow</label>
                                    <div class="col-sm-4">

                                        <label for="isStartAppAdsShow1" class="cursor-pointer">
                                            <input type="radio" class="" id="isStartAppAdsShow1" name="isStartAppAdsShow" value="1"   {{($appData->isStartAppAdsShow) ? 'checked' : ''}}  />
                                            <span class="">Yes</span>
                                        </label>

                                        <label for="isStartAppAdsShow0" class="cursor-pointer">
                                            <input type="radio" class="" id="isStartAppAdsShow0" name="isStartAppAdsShow" value="0"  {{(!$appData->isStartAppAdsShow) ? 'checked' : ''}}  />
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

                                    <label for="staticEmail" class="col-sm-2 col-form-label">suspendApp</label>

                                    <div class="col-sm-4">

                                        <label for="suspendApp1" class="cursor-pointer">
                                            <input type="radio" class="" id="suspendApp1" name="suspendApp" value="1" {{($appData->isAdmobAdsShow) ? 'checked' : ''}}  />
                                            <span class="">Yes</span>
                                        </label>

                                        <label for="suspendApp0" class="cursor-pointer">
                                            <input type="radio" class="" id="suspendApp0" name="suspendApp" value="0" {{(!$appData->isAdmobAdsShow) ? 'checked' : ''}} />
                                            <span class="">No</span>
                                        </label>
                                    </div>



                                </div>

                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-2 col-form-label">suspendAppMessage</label>
                                    <div class="col-sm-4">
                                        <textarea class="form-control" name="suspendAppMessage" id="suspendAppMessage">{{$appData->suspendAppMessage}}</textarea>
                                    </div>



                                    <label for="staticEmail" class="col-sm-2 col-form-label">minimumVersionSupport</label>
                                    <div class="col-sm-4">
                                        <input type="number" class="form-control" name="minimumVersionSupport" id="minimumVersionSupport" value="{{$appData->minimumVersionSupport}}" required>
                                    </div>



                                </div>


                                <div class="form-group row">

                                    <div class="col-sm-12 text-right">
                                        <input type="submit" class="btn bg-dark vertical-bottom" name="submit" id=submitApp"  value="Update" />
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

            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $("#addEditForm").on('submit',(function(e) {

                e.preventDefault();

                var Form_Data = new FormData(this);

                $("input[type=submit]").html('Please Wait...');
                $("input[type=submit]").attr("disabled", true);

                $.ajax({
                    type: "POST",
                    url: "{{ url('admin/add-update-apps/'.$application_id) }}",
                    data: Form_Data,
                    mimeType: "multipart/form-data",
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'json',
                    success: function (res) {

                        $('#ajax-model').modal('hide');
                        $("input[type=submit]").html('Save');
                        $("input[type=submit]").attr("disabled", false);

                        Toast.fire({
                            icon: 'success',
                            title: 'Application has been updated successfully!'
                        })

                        setTimeout(function(){
                            window.location.href = "{{ url('admin/app/')}}";
                        },850);

                    },
                    error: function (response) {

                        $("input[type=submit]").html('Save');

                        $("input[type=submit]").attr("disabled", false);

                        var resp = response.responseJSON;

                        if(response.status == 422){

                            $('html, body').animate({
                                scrollTop: eval($("#PackageIdError").offset().top - 170)
                            }, 1000);

                            $("#PackageIdError").text(resp.errors.PackageId);
                        }
                        else{
                            Toast.fire({
                                icon: 'error',
                                title: 'Network Error!'
                            })

                        }



                    }
                });

            }));


        });



    </script>

@endpush
