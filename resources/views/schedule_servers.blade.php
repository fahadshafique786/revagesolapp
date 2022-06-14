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

                                    @if(auth()->user()->hasRole('super-admin') || auth()->user()->can('manage_servers'))
                                    <a class="btn btn-info" href="javascript:void(0)" id="addNew">
                                        New Server
                                    </a>
                                    <a class="btn btn-success" href="javascript:void(0)" id="linkServer">
                                        Link Server
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
                                <th scope="col" width="10px">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Sport</th>
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
                                <label for="name" class="control-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" maxlength="50" required="">

                                <span class="text-danger" id="nameError"></span>

                            </div>

                        </div>

                        <div class="form-group row">

                            <div class="col-sm-12">
                                <label for="name" class="control-label">URL</label>
                                <input type="text" class="form-control" id="link" name="link" placeholder="Enter URL" value="">

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

    <!-- boostrap model -->
    <div class="modal fade" id="attachServerModal" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-sm custom-fixed-popup right">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ajaxheadingModal-LinkServer"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="addEditForm-LinkServer" name="addEditForm-LinkServer" class="form-horizontal" method="POST" enctype="multipart/form-data">

                        <div class="form-group row">

                            <div class="col-sm-12">
                                <label for="name" class="control-label">Name</label>
                                <select class="form-control" id="server_id" name="server_id" required="">

                                    <option value="">  Select Server     </option>

                                    @foreach ($servers_list as $obj)
                                        <option value="{{ $obj->id }}"  {{ (isset($obj->id) && old('id')) ? "selected":"" }}>{{ $obj->name }}</option>
                                    @endforeach

                                </select>

                                <span class="text-danger" id="serversError"></span>

                            </div>

                        </div>

                        <div class="col-sm-12 text-center">
                            <button type="submit" class="btn btn-info full-width-button" id="btn-save-LinkServer" >
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
            ajax: "{{ url('admin/fetch-servers-data/'.$schedule_id) }}",
            columns: [
                { data: 'srno', name: 'srno' },
                { data: 'name', name: 'name' },
                { data: 'sport_name', name: 'sport_name' },
                { data: 'link', name: 'sport_name' },
                { data: 'isHeader', name: 'isHeader' },
                { data: 'isPremium', name: 'isPremium' },
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
            $('#ajaxheadingModel').html("New Server");
            $('#ajax-model').modal('show');
        });

        $('#linkServer').click(function () {
            $('#id').val("");
            $('#addEditForm-LinkServer').trigger("reset");
            $('#ajaxheadingModal-LinkServer').html("Attach Server");
            $('#attachServerModal').modal('show');
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
            $('#linkError').text('');
            $('#isHeaderError').text('');
            $('#isPremiumError').text('');

            $.ajax({
                type:"POST",
                url: "{{ url('admin/add-update-servers/'.$schedule_id) }}",
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
                    $('#linkError').text(response.responseJSON.errors.link);
                    $('#isHeaderError').text(response.responseJSON.errors.isHeader);
                    $('#isPremiumError').text(response.responseJSON.errors.isPremium);
                }
            });
        }));


        $("#addEditForm-LinkServer").on('submit',(function(e) {
            e.preventDefault();
            var Form_Data = new FormData(this);
            $("#btn-save-LinkServer").html('Please Wait...');
            $("#btn-save-LinkServer").attr("disabled", true);

            $.ajax({
                type:"POST",
                url: "{{ url('admin/attach-servers/'.$schedule_id) }}",
                data: Form_Data,
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function(res){
                    fetchData();
                    $('#attachServerModal').modal('hide');
                    $("#btn-save-LinkServer").html('Save');
                    $("#btn-save-LinkServer"). attr("disabled", false);
                },
                error:function (response) {
                    $("#btn-save-LinkServer").html(' Save');
                    $("#btn-save-LinkServer"). attr("disabled", false);
                    $('#server_idError').text(response.responseJSON.errors.server_id);
                }
            });
        }));



    });

</script>

@endpush
