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
                                <div class="col-12 text-left">
                                    <div class="pull-left">

                                        @if(auth()->user()->hasRole('super-admin') || auth()->user()->can('manage-sports'))
                                            <a class="btn btn-info" href="javascript:void(0)" id="addNew">
                                                Add Server
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>


                            <div class="row">

                                <div class="col-sm-2 pt-4">
                                    <select class="form-control" id="sports_filter" name="sports_filter" onchange="getLeaguesOptionBySports(this.value,'leagues_filter')">
                                        <option value="">   Select Sports </option>
                                        @foreach ($sports_list as $obj)
                                            <option value="{{ $obj->id }}"  {{ (isset($obj->id) && old('id')) ? "selected":"" }}>{{ $obj->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-sm-2 pt-4">
                                    <select class="form-control" id="leagues_filter" name="leagues_filter" >
                                        <option value="">   Select League </option>
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
                                    <th scope="col">Name</th>
                                    <th scope="col">Sport</th>
                                    <th scope="col">League</th>
                                    <th scope="col">Link</th>
                                    <th scope="col">Headers</th>
                                    <th scope="col">Premium</th>
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
                                    <label for="name" class="control-label">Sport</label>
                                    <select class="form-control" id="sports_id" name="sports_id" required onchange="getLeaguesOptionBySports(this.value,'leagues_id')">
                                        <option value="">   Select Sport </option>
                                        @foreach ($sports_list as $sport)
                                            <option value="{{ $sport->id }}"  {{ (isset($sport->id) && old('id')) ? "selected":"" }}>{{ $sport->name }}</option>
                                        @endforeach
                                    </select>

                                    <span class="text-danger" id="sports_idError"></span>

                                </div>

                            </div>

                            <div class="form-group row">

                                <div class="col-sm-12">
                                    <label for="name" class="control-label">Leagues</label>
                                    <select class="form-control" id="leagues_id" name="leagues_id" required>
                                        <option value="">   Select League </option>
                                    </select>

                                    <span class="text-danger" id="leagues_idError"></span>

                                </div>

                            </div>

                            <div class="form-group row">

                                <div class="col-sm-12">
                                    <label for="name" class="control-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" maxlength="50" required="">

                                    <span class="text-danger" id="nameError"></span>

                                </div>

                            </div>

                            <div class="form-group row">

                                <div class="col-sm-12">
                                    <label for="name" class="control-label">Link</label>
                                    <input type="text" class="form-control" id="link" name="link" placeholder="Enter Link" value="">

                                    <span class="text-danger" id="linkError"></span>

                                </div>

                            </div>


                            <div class="form-group row">

                                <div class="col-sm-12">
                                    <label for="name" class="control-label d-block">Header</label>
                                    <label for="isHeaderYes" class="cursor-pointer">
                                        <input type="radio" class="" id="isHeaderYes" name="isHeader" value="1" />
                                        <span class="">Yes</span>
                                    </label>

                                    &nbsp;&nbsp;&nbsp;&nbsp;

                                    <label for="isHeaderNo" class="cursor-pointer">
                                        <input type="radio" class="" id="isHeaderNo" name="isHeader"  value="0" checked/>
                                        <span class="">No</span>
                                    </label>

                                    <span class="text-danger" id="isHeaderError"></span>
                                </div>

                            </div>



                            <div class="form-group row">

                                <div class="col-sm-12">
                                    <label for="name" class="control-label d-block">Premium</label>
                                    <label for="isPremiumYes" class="cursor-pointer">
                                        <input type="radio" class="" id="isPremiumYes" name="isPremium" value="1" />
                                        <span class="">Yes</span>
                                    </label>

                                    &nbsp;&nbsp;&nbsp;&nbsp;

                                    <label for="isPremiumNo" class="cursor-pointer">
                                        <input type="radio" class="" id="isPremiumNo" name="isPremium"  value="0" checked/>
                                        <span class="">No</span>
                                    </label>

                                    <span class="text-danger" id="isPremiumYesError"></span>
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
            var sports_filter = $('#sports_filter').val();
            if(sports_filter != '')
            {
                $('#DataTbl').DataTable().destroy();
                fetchData(sports_filter);
            }
            else
            {
                alert('Select Filter Option');
                $('#DataTbl').DataTable().destroy();
                fetchData();
            }
        });




        var Table_obj = "";

        function fetchData(filter_sports = "")
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
                processing: true,
                columnDefs: [
                    { targets: '_all',
                        orderable: true
                    },
                ],
                serverSide: true,
                "ajax" : {
                    url:"{{ url('admin/fetch-servers-data') }}",
                    type:"POST",
                    data:{
                        filter_sports:filter_sports
                    }
                },
                columns: [
                    { data: 'srno', name: 'srno' },
                    { data: 'name', name: 'name' },
                    { data: 'sport_name', name: 'sport_name' },
                    { data: 'league_name', name: 'league_name' },
                    { data: 'link', name: 'sport_name' },
                    { data: 'isHeader', name: 'isHeader' , render: function( data, type, full, meta,rowData ) {

                            if(data=='Yes'){
                                return "<a href='javascript:void(0)' class='badge badge-success text-xs text-capitalize'>"+data+"</a>" +" ";
                            }
                            else{
                                return "<a href='javascript:void(0)' class='badge badge-danger text-xs text-capitalize'>"+data+"</a>" +" ";
                            }
                        },

                    },

                    { data: 'isPremium', name: 'isPremium' , render: function( data, type, full, meta,rowData ) {

                            if(data=='Yes'){
                                return "<a href='javascript:void(0)' class='badge badge-success text-xs text-capitalize'>"+data+"</a>" +" ";
                            }
                            else{
                                return "<a href='javascript:void(0)' class='badge badge-danger text-xs text-capitalize'>"+data+"</a>" +" ";
                            }
                        },

                    },
                    {data: 'action', name: 'action', orderable: false},
                ],
                order: [[0, 'asc']]
            });

        }

        $(document).ready(function($){

            fetchData();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#addNew').click(function () {
                $('#id').val("");
                $('#addEditForm').trigger("reset");
                $('#ajaxheadingModel').html("Add Server");
                $('#nameError').text('');
                $('#leagues_idError').text('');
                $('#sports_idError').text('');
                $('#ajax-model').modal('show');
            });

            $('body').on('click', '.edit', function () {
                var id = $(this).data('id');
                $('#nameError').text('');
                $('#link').text('');

                $.ajax({
                    type:"POST",
                    url: "{{ url('admin/edit-server') }}",
                    data: { id: id },
                    dataType: 'json',
                    success: function(res){
                        console.log(res);
                        $('#id').val("");
                        $('#addEditForm').trigger("reset");
                        $('#ajaxheadingModel').html("Edit Server");
                        $('#ajax-model').modal('show');

                        $('#id').val(res.id);
                        $('#name').val(res.name);
                        $('#sports_id').val(res.sports_id);
                        $('#link').val(res.link);

                        if(res.isHeader == 1){
                            $("#isHeaderYes").prop("checked",true);
                        }
                        else{
                            $("#isHeaderNo").prop("checked",true);
                        }

                        if(res.isPremium == 1){
                            $("#isPremiumYes").prop("checked",true);
                        }
                        else{
                            $("#isPremiumNo").prop("checked",true);
                        }

                    }
                });
            });
            $('body').on('click', '.delete', function () {
                if (confirm("Are you sure you want to delete?") == true) {
                    var id = $(this).data('id');

                    $.ajax({
                        type:"POST",
                        url: "{{ url('admin/delete-server') }}",
                        data: { id: id },
                        dataType: 'json',
                        success: function(res){
                            fetchData();
                        }
                    });
                }
            });
            $("#addEditForm").on('submit',(function(e) {
                e.preventDefault();
                var Form_Data = new FormData(this);
                $("#btn-save").html('Please Wait...');
                $("#btn-save"). attr("disabled", true);

                $('#nameError').text('');
                $('#sports_idError').text('');
                $('#leagues_idError').text('');
                // $('#linkError').text('');
                // $('#isHeaderError').text('');
                // $('#isPremiumError').text('');

                $.ajax({
                    type:"POST",
                    url: "{{ url('admin/add-update-servers') }}",
                    data: Form_Data,
                    mimeType: "multipart/form-data",
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'json',
                    success: function(res){
                        fetchData();
                        $('#ajax-model').modal('hide');
                        $("#btn-save").html('Save');
                        $("#btn-save"). attr("disabled", false);
                    },
                    error:function (response) {
                        $("#btn-save").html(' Save');
                        $("#btn-save"). attr("disabled", false);

                        $('#nameError').text(response.responseJSON.errors.name);
                        $('#sports_idError').text(response.responseJSON.errors.sports_id);
                        $('#leagues_idError').text(response.responseJSON.errors.leagues_id);
                        // $('#linkError').text(response.responseJSON.errors.link);
                        // $('#isHeaderError').text(response.responseJSON.errors.isHeader);
                        // $('#isPremiumError').text(response.responseJSON.errors.isPremium);
                    }
                });
            }));
        });

    </script>

@endpush
