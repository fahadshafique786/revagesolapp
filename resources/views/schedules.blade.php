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
                                                Add Schedule
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>


                            <div class="row">

                                <div class="col-sm-2 pt-4">
                                    <select class="form-control" id="league_filter" name="league_filter" >
                                        <option value="">   Select League </option>
                                        @foreach ($leaguesList as $obj)
                                            <option value="{{ $obj->id }}"  {{ (isset($obj->id) && old('id')) ? "selected":"" }}>{{ $obj->name }}</option>
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
                                    <th scope="col">Label</th>
                                    <th scope="col">League</th>
                                    <th scope="col">Home</th>
                                    <th scope="col">Away</th>
                                    <th scope="col">Score</th>
                                    <th scope="col">Start(DateTime)</th>
                                    <th scope="col">Live</th>
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
                                    <label for="name" class="control-label">Label</label>
                                    <input type="text" class="form-control" id="label" name="label"  placeholder="Enter game label" required />

                                    <span class="text-danger" id="labelError"></span>

                                </div>

                            </div>

                            <div class="form-group row">

                                <div class="col-sm-12">
                                    <label for="name" class="control-label">Leagues</label>
                                    <select class="form-control" id="leagues_id" name="leagues_id" required onchange="getTeamsByLeagues(this.value);">
                                        <option value="">   Select League</option>
                                        @foreach ($leaguesList as $obj)
                                            <option value="{{ $obj->id }}"  {{ (isset($obj->id) && old('id')) ? "selected":"" }}>{{ $obj->name }}</option>
                                        @endforeach
                                    </select>

                                    <span class="text-danger" id="leaguesError"></span>

                                </div>

                            </div>

                            <div class="form-group row">

                                <div class="col-sm-12">
                                    <label for="name" class="control-label">Home Team</label>
                                    <select class="form-control" id="home_team_id" name="home_team_id" required
                                            onchange="verifyHomeAwayTeamDuplication('home',this.id,this.value)">
                                        <option value="">   Select Home Team </option>
{{--                                        @foreach ($teamsList as $team)--}}
{{--                                            <option value="{{ $team->id }}"  {{ (isset($team->id) && old('id')) ? "selected":"" }}>{{ $team->name }}</option>--}}
{{--                                        @endforeach--}}
                                    </select>

                                    <span class="text-danger" id="home_teamError"></span>

                                </div>

                            </div>

                            <div class="form-group row">

                                <div class="col-sm-12">
                                    <label for="name" class="control-label">Away Team</label>
                                    <select class="form-control" id="away_team_id" name="away_team_id" required
                                            onchange="verifyHomeAwayTeamDuplication('away',this.id,this.value)">
                                        <option value="">   Select Away Team </option>
{{--                                        @foreach ($teamsList as $team)--}}
{{--                                            <option value="{{ $team->id }}"  {{ (isset($team->id) && old('id')) ? "selected":"" }}>{{ $team->name }}</option>--}}
{{--                                        @endforeach--}}
                                    </select>

                                    <span class="text-danger" id="away_teamError"></span>

                                </div>
                            </div>



                            <div class="form-group">
                                <label for="start_time">Match Start(DateTime)</label>
                                <div class="input-group date" id="" data-target-input="nearest">
                                    <input type="text" autocomplete="off" class="form-control datetimepicker"  id="start_time" name="start_time"  required />
                                    <div class="input-group-append" data-target="" data-toggle="datetimepicker">
                                        <div class="input-group-text calendarIcon"><i class="fa fa-calendar"></i></div>
                                    </div>
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
            var league_filter = $('#league_filter').val();
            if(league_filter != '')
            {
                $('#DataTbl').DataTable().destroy();
                fetchData(league_filter);
            }
            else
            {
                alert('Select Filter Option');
                $('#DataTbl').DataTable().destroy();
                fetchData();
            }
        });


        function getTeamsByLeagues(leagues_id){

            $.ajax({
                type:"POST",
                url: "{{ url('admin/getTeamsByLeagueId') }}",
                data: { leagues_id: leagues_id},
                success: function(response){
                    $("#home_team_id").html(response);
                    $("#away_team_id").html(response);
                }
            });

        }

        function verifyHomeAwayTeamDuplication(type,currentId,currentValue){

            if(type == 'home') {
                $("#home_teamError").text('');
                if (currentValue == $("#away_team_id").val()) {
                    $("#home_teamError").text('Please select different team!');
                    $("#home_team_id").val('');
                }
            }

            if(type == 'away'){
                $("#away_teamError").text('');
                if(currentValue == $("#home_team_id").val()){
                    $("#away_teamError").text('Please select different team!');
                    $("#away_team_id").val('');
                }

            }
        }



        $(document).delegate('.isLiveStatusSwitch', 'switchChange.bootstrapSwitch', function(event,state){

            var schedule_id = $(this).attr('data-schedule-id');
            var is_live  = (state) ? 1 : 0;

            $.ajax({
                type:"POST",
                url: "{{ url('admin/update-schedule-live-status') }}",
                data: { schedule_id: schedule_id , is_live :  is_live},
                dataType: 'json',
                success: function(res){
                    fetchData();
                }
            });

        });


        var Table_obj = "";

        function fetchData(filter_league = "")
        {
            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            });

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
                    url:"{{ url('admin/fetch-schedules-data/'.$sports_id) }}",
                    type:"POST",
                    data:{
                        filter_league:filter_league
                    },
                    dataSrc: function ( json ) {
                        //Make your callback here.

                        setTimeout(function () {
                             $("input[data-bootstrap-switch]").each(function(){
                                $(this).bootstrapSwitch('state', $(this).prop('checked'));
                            });
                         },600);


                        return json.data;
                    }
                },
                columns: [
                    { data: 'srno', name: 'srno' },
                    { data: 'label', name: 'label'},
                    { data: 'league', name: 'league'},
                    { data: 'home_team_id', name: 'home_team_id'},
                    { data: 'away_team_id', name: 'away_team_id' },
                    { data: 'score', name: 'score' },
                    { data: 'start_time', name: 'start_time', render: function( data, type, full, meta,rowData ) {

                        return convertTime24to12(data)

                        }

                    },
                    { data: 'is_live', name: 'is_live', searchable:false , render: function( data, type, full, meta,rowData ) {

                            if(data=='Yes'){
                                return "<a href='javascript:void(0)' class='badge badge-success text-xs text-capitalize'>"+data+"</a>" +" ";
                            }
                            else{
                                return "<a href='javascript:void(0)' class='badge badge-danger text-xs text-capitalize'>"+data+"</a>" +" ";
                            }
                        },


                    },

                    {data: 'action', name: 'action', orderable: false , render: function( data, type, full, meta,rowData ) {
                            $("input[data-bootstrap-switch]").each(function(){
                                $(this).bootstrapSwitch('state', $(this).prop('checked'));
                            });
                            return data;


                        }
                    },
                ],
                order: [[6, 'asc']]
            });


            setTimeout(function(){


            },1500);
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
                $('#ajaxheadingModel').html("Add Schedule");
                $('#ajax-model').modal('show');
            });

            $('body').on('click', '.edit', function () {
                var id = $(this).data('id');
                $('#nameError').text('');
                $('#pointsError').text('');
                $('#team_iconError').text('');
                $('#leagues_idError').text('');

                $.ajax({
                    type:"POST",
                    url: "{{ url('admin/edit-schedule') }}",
                    data: { id: id },
                    dataType: 'json',
                    success: function(res){
                        console.log(res);
                        $("#password").prop("required",false);
                        $('#id').val("");
                        $('#addEditForm').trigger("reset");
                        $('#ajaxheadingModel').html("Edit Schedule");

                        $('#leagues_id').val(res.leagues_id);

                        getTeamsByLeagues(res.leagues_id)


                        $('#id').val(res.id);
                        $('#label').val(res.label);


                        $('#start_time').val(convertTime24to12(res.start_time));
                        setTimeout(function(){
                            $('#home_team_id').val(res.home_team_id);
                            $('#away_team_id').val(res.away_team_id);
                            $('#ajax-model').modal('show');
                        },1500);

                    }
                });
            });


            $('body').on('click', '.delete', function () {
                if (confirm("Are you sure you want to delete?") == true) {
                    var id = $(this).data('id');

                    $.ajax({
                        type:"POST",
                        url: "{{ url('admin/delete-schedule') }}",
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

                let start_time = convertTime12to24($("#start_time").val());
                Form_Data.set('start_time', start_time);


                $("#btn-save").html('Please Wait...');
                $("#btn-save"). attr("disabled", true);
                $('#labelError').text('');
                $('#home_teamError').text('');
                $('#away_teamError').text('');

                $.ajax({
                    type:"POST",
                    url: "{{ url('admin/add-update-schedules/'.$sports_id) }}",
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
                        $('#labelError').text(response.responseJSON.errors.label);
                        $('#home_teamError').text(response.responseJSON.errors.home_team_id);
                        $('#away_teamError').text(response.responseJSON.errors.away_team_id);
                    }
                });
            }));


        });

    </script>

@endpush
