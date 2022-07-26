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
                                <div class="col-6 text-left">

                                    @if(auth()->user()->hasRole('super-admin') || auth()->user()->can('manage-sponsors'))
                                        <a class="btn btn-info" href="javascript:void(0)" id="addNew">
                                            Add New Amob Ad
                                        </a>
                                    @endif

                                </div>

                                <div class="col-6 text-right">

                                    @if(auth()->user()->hasRole('super-admin') || auth()->user()->can('manage-admob_ads'))
                                        <a class="btn btn-warning" href="javascript:window.location.reload()" id="">
                                            <i class="fa fa-spinner"></i> &nbsp; Refresh Screen
                                        </a>
                                    @endif

                                </div>
                            </div>


                            <div class="row">

                                <div class="col-sm-4 pt-4">
                                    <select class="form-control" id="filter_app_id" name="filter_app_id" >
                                        <option value="">   Select App </option>
                                        <option value="-1">   All </option>
                                        @foreach ($appsList as $obj)
                                            <option value="{{ $obj->id }}"  {{ (isset($obj->id) && old('id')) ? "selected":"" }}>{{ $obj->appName . ' - ' . $obj->packageId}}</option>
                                        @endforeach
                                    </select>

                                </div>

                                <div class="col-sm-2 pt-4">

                                    <button type="button" class="btn btn-primary" id="filter"> <i class="fa fa-filter"></i> Apply Filter </button>
                                </div>


                            </div>


                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover" id="DataTbl">
                                <thead>
                                <tr>
                                    <th scope="col" width="10px">#</th>
                                    <th scope="col">Application</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">adUId</th>
                                    <th scope="col">isAdShow</th>
                                    <th scope="col">Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->

        <!-- boostrap model -->
        <div class="modal fade" id="ajax-model" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-sm custom-fixed-popup right">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="ajaxheadingModel"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form action="javascript:void(0)" id="addEditForm" name="addEditForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" id="id">



                            <div class="form-group row">

                                <div class="col-sm-12">
                                    <label for="name" class="control-label">Application</label>
                                    <select class="form-control" id="app_detail_id" name="app_detail_id" required>
                                        <option value="">   Select App </option>
                                        @foreach ($appsList as $obj)
                                            <option value="{{ $obj->id }}"  {{ (isset($obj->id) && old('id')) ? "selected":"" }}>{{ $obj->appName . ' - ' . $obj->packageId}}</option>
                                        @endforeach
                                    </select>

                                    <span class="text-danger" id="sports_idError"></span>

                                </div>

                            </div>

                            <div class="form-group row">

                                <div class="col-sm-12">
                                    <label for="name" class="control-label">Name</label>
                                    <input type="text" class="form-control" id="adName" name="adName" placeholder="Enter Ads Name" value="" maxlength="50" required="">

                                    <span class="text-danger" id="adNameError"></span>

                                </div>

                            </div>


                            <div class="form-group row">

                                <div class="col-sm-12">
                                    <label for="league_icon" class="control-label d-block"> adUId </label>
                                    <input type="text" class="form-control" id="adUId" name="adUId" value="" required="">
                                    <span class="text-danger" id="adUIdError"></span>

                                </div>


                            </div>

                            <div class="form-group row">

                                <div class="col-sm-12">
                                    <label for="isAdShow" class="control-label d-block"> isAdShow </label>

                                    <label for="isAdShow1" class="cursor-pointer">
                                        <input type="radio" class="" id="isAdShow1" name="isAdShow" value="1"  />
                                        <span class="">Yes</span>
                                    </label>

                                    <label for="isAdShow0" class="cursor-pointer">
                                        <input type="radio" class="" id="isAdShow0" name="isAdShow" value="0" checked />
                                        <span class="">No</span>
                                    </label>


                                </div>


                            </div>


                            <div class="col-sm-12 text-center">
                                <button type="submit" class="btn btn-info full-width-button" id="btn-save" >
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
        <!-- end bootstrap model -->

    </section>
    <!-- /.content -->


@endsection

@push('scripts')
    <script type="text/javascript">


        $('#filter').click(function(){
            var filter_app_id = $('#filter_app_id').val();
            if(filter_app_id != '')
            {
                $('#DataTbl').DataTable().destroy();
                if(filter_app_id != '-1'){ // for all...
                    fetchData(filter_app_id);
                }
                else{
                    fetchData();
                }
            }
            else
            {
                alert('Select  Filter Option');
                $('#DataTbl').DataTable().destroy();
                fetchData();
            }
        });



        var Table_obj = "";

        function fetchData(filter_app_id= '')
        {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            if(Table_obj != '' && Table_obj != null)
            {
                $('#DataTbl').dataTable().fnDestroy();
                $('#DataTbl tbody').empty();
                Table_obj = '';
            }


            Table_obj = $('#DataTbl').DataTable({
                "processing" : true,
                "serverSide" : true,
                "order" : [],
                "searching" : true,
                "paging": false,
                columnDefs: [{
                    "defaultContent": "-",
                    "targets": "_all"
                }],
                serverSide: true,
                "ajax" : {
                    url:"{{ url('admin/fetch-admob_ads-data/') }}",
                    type:"POST",
                    data:{
                        filter_app_id:filter_app_id
                    }
                },
                columns: [
                    { data: 'srno', name: 'srno' , searchable:false},
                    { data: 'appName', name: 'appName' },
                    { data: 'name', name: 'name' },
                    { data: 'adUId', name: 'adUId' },
                    { data: 'isAdShow', name: 'isAdShow', searchable:false , render: function( data, type, full, meta,rowData ) {

                            if(data=='Yes'){
                                return "<a href='javascript:void(0)' class='badge badge-success text-xs text-capitalize'>"+data+"</a>" +" ";
                            }
                            else{
                                return "<a href='javascript:void(0)' class='badge badge-danger text-xs text-capitalize'>"+data+"</a>" +" ";
                            }
                        },


                    },

                    {data: 'action', name: 'action', orderable: false , searchable:false},
                ],
                order: [[0, 'asc']]
            });

        }


        function callDataTableWithFilters(){
            fetchData($('#filter_app_id').val());
        }


        $(document).ready(function($){

            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });



            fetchData();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#addNew').click(function () {

                $('#id').val("");
                $('#addEditForm').trigger("reset");
                $("#password").prop("required",true);
                $('#ajaxheadingModel').html("Add Admob Ad");
                $("form#addEditForm")[0].reset();


                if($("#filter_app_id").val() > 0){
                    $("#app_detail_id").val($("#filter_app_id").val());
                }

                $('#ajax-model').modal('show');

            });

            $('body').on('click', '.edit', function () {

                var id = $(this).data('id');
                $('#nameError').text('');
                $('#adNameError').text(' ');
                $('#emailError').text('');
                $.ajax({
                    type:"POST",
                    url: "{{ url('admin/edit-admob-ads') }}",
                    data: { id: id },
                    dataType: 'json',
                    success: function(res){
                        console.log(res);
                        $("#password").prop("required",false);
                        $('#id').val("");
                        $('#addEditForm').trigger("reset");
                        $('#ajaxheadingModel').html("Edit Admob Ad");
                        $('#id').val(res.id);

                        $('#app_detail_id').val(res.app_detail_id);
                        $('#adName').val(res.adName);
                        $('#adUId').val(res.adUId);
                        $('#isAdShow'+res.isAdShow).prop('checked',true);
                        $('#ajax-model').modal('show');

                    }
                });
            });


            $('body').on('click', '.delete', function () {
                if (confirm("Are you sure you want to delete?") == true) {
                    var id = $(this).data('id');

                    $.ajax({
                        type:"POST",
                        url: "{{ url('admin/delete-admob-ads') }}",
                        data: { id: id },
                        dataType: 'json',
                        success: function(res){
                            Toast.fire({
                                icon: 'success',
                                title: 'Admob Ad has been removed!'
                            });

                            callDataTableWithFilters();

                        },
                        error:function (response) {

                            Toast.fire({
                                icon: 'error',
                                title: 'Network Error Occured!'
                            });
                        }
                    });
                }
            });

            $("#addEditForm").on('submit',(function(e) {
                e.preventDefault();
                var Form_Data = new FormData(this);
                $("#btn-save").html('Please Wait...');
                $("#btn-save"). attr("disabled", true);

                $.ajax({
                    type:"POST",
                    url: "{{ url('admin/add-update-admob_ads') }}",
                    data: Form_Data,
                    mimeType: "multipart/form-data",
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'json',
                    success: function(res){

                        callDataTableWithFilters();


                        $('#ajax-model').modal('hide');
                        $("#btn-save").html('Save');
                        $("#btn-save"). attr("disabled", false);

                        Toast.fire({
                            icon: 'success',
                            title: 'Admob Ad has been saved successfully!'
                        });

                        $("form#addEditForm")[0].reset();

                    },
                    error:function (response) {
                        if(response.status == 422){
                            Toast.fire({
                                icon: 'error',
                                title: response.responseJSON.message
                            });
                        }
                        else{
                            Toast.fire({
                                icon: 'error',
                                title: 'Network Error Occured!'
                            });
                        }


                        $("#btn-save").html(' Save');
                        $("#btn-save"). attr("disabled", false);
                        $('#adNameError').text(response.responseJSON.errors.adName);
                        $('#adUIdError').text(response.responseJSON.errors.adUId);
                        $('#isAdShowError').text(response.responseJSON.errors.isAdShow);
                    }
                });
            }));
        });

    </script>

@endpush
