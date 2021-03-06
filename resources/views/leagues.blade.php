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

                                        @if(auth()->user()->hasRole('super-admin') || auth()->user()->can('manage-leagues'))
                                            <a class="btn btn-info" href="javascript:void(0)" id="addNew">
                                                Add League
                                            </a>
                                        @endif

                                </div>

                                <div class="col-6 text-right">

                                    @if(auth()->user()->hasRole('super-admin') || auth()->user()->can('manage-leagues'))
                                        <a class="btn btn-warning" href="javascript:window.location.reload()" id="">
                                            <i class="fa fa-spinner"></i> &nbsp; Refresh Screen
                                        </a>
                                    @endif

                                </div>
                            </div>


                            <div class="row">

                                <div class="col-sm-2 pt-4">
                                    <select class="form-control" id="sports_filter" name="sports_filter" >
                                        <option value="">   Select Sports </option>
                                        <option value="-1" selected>   All </option>
                                        @foreach ($sports_list as $sport)
                                            <option value="{{ $sport->id }}"  {{ (isset($sport->id) && old('id')) ? "selected":"" }}>{{ $sport->name }}</option>
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
                                    <th scope="col">Icon</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Sport</th>
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
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">??</span></button>
                    </div>
                    <div class="modal-body">
                        <form action="javascript:void(0)" id="addEditForm" name="addEditForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" id="id">



                            <div class="form-group row">

                                <div class="col-sm-12">
                                    <label for="name" class="control-label">Sport</label>
                                    <select class="form-control" id="sports_id" name="sports_id" required>
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
                                    <label for="name" class="control-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" maxlength="50" required="">

                                    <span class="text-danger" id="nameError"></span>

                                </div>

                            </div>

                            <div class="form-group row">

                                <div class="col-sm-12">
                                    <label for="league_icon" class="control-label d-block">League Icon</label>

                                    <input type="file" class="" id="league_icon" name="league_icon" onchange="allowonlyImg(this)">
                                    <span class="text-danger" id="league_iconError"></span>

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
                if(sports_filter != '-1'){ // for all...
                    fetchData(sports_filter);
                }
                else{
                    fetchData();
                }
            }
            else {
                alert('Select  Filter Option');
            }
        });

        var Table_obj = "";

        function fetchData(filter_sports= '')
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
                    url:"{{ url('admin/fetch-leagues-data') }}",
                    type:"POST",
                    data:{
                        filter_sports:filter_sports
                    }
                },
                columns: [
                    { data: 'srno', name: 'srno' , searchable:false},
                    { data: 'icon', name: 'icon', searchable:false},
                    { data: 'name', name: 'name' },
                    { data: 'sport_name', name: 'sport_name' },
                    {data: 'action', name: 'action', orderable: false , searchable:false},
                ],
                order: [[0, 'asc']]
            });

        }

        function callDataTableWithFilters(){
            fetchData($('#sports_filter').val());
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
                $("#password").prop("required",true);
                $('#ajaxheadingModel').html("Add League");

                $('#ajax-model').modal('show');

                if($("#sports_filter").val() > 0){
                    $("#sports_id").val($("#sports_filter").val());
                }


            });

            $('body').on('click', '.edit', function () {
                var id = $(this).data('id');
                $('#nameError').text('');
                $('#emailError').text('');
                $.ajax({
                    type:"POST",
                    url: "{{ url('admin/edit-league') }}",
                    data: { id: id },
                    dataType: 'json',
                    success: function(res){
                        console.log(res);
                        $("#password").prop("required",false);
                        $('#id').val("");
                        $('#addEditForm').trigger("reset");
                        $('#ajaxheadingModel').html("Edit League");
                        $('#ajax-model').modal('show');
                        $('#id').val(res.id);
                        $('#name').val(res.name);
                        $('#sports_id').val(res.sports_id);

                    }
                });
            });
            $('body').on('click', '.delete', function () {
                if (confirm("Are you sure you want to delete?") == true) {
                    var id = $(this).data('id');

                    $.ajax({
                        type:"POST",
                        url: "{{ url('admin/delete-league') }}",
                        data: { id: id },
                        dataType: 'json',
                        success: function(res){
                            callDataTableWithFilters();
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
                $('#sports_typeError').text('');
                $('#multi_leagueError').text('');
                $('#image_requiredError').text('');

                $.ajax({
                    type:"POST",
                    url: "{{ url('admin/add-update-leagues') }}",
                    data: Form_Data,
                    mimeType: "multipart/form-data",
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'json',
                    success: function(res){


                        $('#ajax-model').modal('hide');
                        $("#btn-save").html('Save');
                        $("#btn-save"). attr("disabled", false);

                        callDataTableWithFilters();

                    },
                    error:function (response) {
                        $("#btn-save").html(' Save');
                        $("#btn-save"). attr("disabled", false);
                        $('#nameError').text(response.responseJSON.errors.name);
                        $('#sports_typeError').text(response.responseJSON.errors.sports_type);
                        $('#multi_leagueError').text(response.responseJSON.errors.multi_league);
                        $('#image_requiredError').text(response.responseJSON.errors.image_required);
                    }
                });
            }));
        });

    </script>

@endpush
