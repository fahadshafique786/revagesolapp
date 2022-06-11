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
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover" id="DataTbl">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
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
                                    <label for="name" class="control-label">Home Team</label>
                                    <select class="form-control" id="home_team_id" name="home_team_id" required>
                                        <option value="">   Select Leagues </option>
                                        @foreach ($teamsList as $team)
                                            <option value="{{ $team->id }}"  {{ (isset($team->id) && old('id')) ? "selected":"" }}>{{ $team->name }}</option>
                                        @endforeach
                                    </select>

                                    <span class="text-danger" id="home_teamError"></span>

                                </div>

                            </div>

                            <div class="form-group row">

                                <div class="col-sm-12">
                                    <label for="name" class="control-label">Away Team</label>
                                    <select class="form-control" id="away_team_id" name="away_team_id" required>
                                        <option value="">   Select Leagues </option>
                                        @foreach ($teamsList as $team)
                                            <option value="{{ $team->id }}"  {{ (isset($team->id) && old('id')) ? "selected":"" }}>{{ $team->name }}</option>
                                        @endforeach
                                    </select>

                                    <span class="text-danger" id="away_teamError"></span>

                                </div>

                            </div>



                            <div class="form-group">
                                <label for="start_time">Match Start(DateTime)</label>
                                <div class="input-group date" id="" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker"  id="start_time" name="start_time"  required />
                                    <div class="input-group-append" data-target="" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
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
        var Table_obj = "";

        function fetchData()
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
                        orderable: false
                    },
                ],
                serverSide: true,
                ajax: "{{ url('admin/fetch-schedules-data/'.$sports_id) }}",
                columns: [
                    { data: 'srno', name: 'srno' },
                    { data: 'home_team_id', name: 'home_team_id'},
                    { data: 'away_team_id', name: 'away_team_id' },
                    { data: 'score', name: 'score' },
                    { data: 'start_time', name: 'start_time' },
                    { data: 'is_live', name: 'is_live' },
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
                        $('#ajax-model').modal('show');

                        $('#id').val(res.id);
                        $('#label').val(res.label);
                        $('#home_team_id').val(res.home_team_id);
                        $('#away_team_id').val(res.away_team_id);
                        $('#start_time').val(res.start_time);

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
