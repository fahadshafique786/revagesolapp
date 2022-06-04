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
										<a class="btn btn-info" href="javascript:void(0)" id="addNew">
                                            Add Sports
                                        </a>
									</div>
								</div>
							</div>
						</div>
						<div class="card-body">
							<table class="table table-bordered table-hover" id="DataTbl">
								<thead>
									<tr>
										<th scope="col">#</th>
										<th scope="col">Icon</th>
										<th scope="col">Name</th>
										<th scope="col">Type</th>
										<th scope="col">Image Required</th>
										<th scope="col">Multi League</th>
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
                </div>

              </div>

                <div class="form-group row">

                    <div class="col-sm-12">
                        <label for="name" class="control-label d-block">  Sports Type </label>
                        <label for="multi_league">
                            <input type="radio" class="" id="sports_type_yes" name="sports_type" value="1" />
                            <span class="">Yes</span>
                        </label>
                        <label for="multi_league">
                            <input type="radio" class="" id="sports_type_no" name="sports_type"  value="0"/>
                            <span class="">No</span>
                        </label>

                        <span class="text-danger" id="multi_leagueError"></span>
                    </div>

                </div>

              <div class="form-group row">

                  <div class="col-sm-12">
                      <label for="name" class="control-label d-block">Multi League</label>
                      <label for="multi_league">
                          <input type="radio" class="" id="multi_league_yes" name="multi_league" value="1" />
                          <span class="">Yes</span>
                      </label>
                      <label for="multi_league">
                          <input type="radio" class="" id="multi_league_no" name="multi_league"  value="0"/>
                          <span class="">No</span>
                      </label>

                      <span class="text-danger" id="multi_leagueError"></span>
                  </div>

              </div>



              <div class="form-group row">

                  <div class="col-sm-12">
                      <label for="name" class="control-label d-block">Image Required</label>
                      <label for="image_required">
                          <input type="radio" class="" id="image_required_yes" name="image_required" value="1">
                          <span class="">Yes</span>
                      </label>
                      <label for="image_required">
                          <input type="radio" class="" id="image_required_no" name="image_required" value="0">
                          <span class="">No</span>
                      </label>
                      <span class="text-danger" id="image_requiredError"></span>

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
		serverSide: true,
		ajax: "{{ url('admin/fetchsportsdata') }}",
		columns: [
		{ data: 'srno', name: 'srno' },
		{ data: 'name', name: 'name' },
		{ data: 'sport_name', name: 'sport_name' },
		{ data: 'email', name: 'email' },
		{ data: 'phone', name: 'phone' },
		{ data: 'status', name: 'status' },
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
        $('#ajaxheadingModel').html("Add Sports");
        $('#ajax-model').modal('show');
    });

    $('body').on('click', '.edit', function () {
        var id = $(this).data('id');
        $('#sport_nameError').text('');
		$('#emailError').text('');
        $.ajax({
            type:"POST",
            url: "{{ url('admin/edit-Sport') }}",
            data: { id: id },
            dataType: 'json',
            success: function(res){
			  $("#password").prop("required",false);
			  $('#id').val("");
			  $('#addEditForm').trigger("reset");
              $('#ajaxheadingModel').html("Edit Sports");
              $('#ajax-model').modal('show');
              $('#id').val(res.id);
              $('#name').val(res.name);
			  $('#sport_name').val(res.sport_name);
			  $('#email').val(res.email);
			  $('#phone').val(res.phone);
              $('#is_status').val(res.is_status);
           }
        });
    });
    $('body').on('click', '.delete', function () {
       if (confirm("Delete Record?") == true) {
        var id = $(this).data('id');

        $.ajax({
            type:"POST",
            url: "{{ url('admin/delete-Sport') }}",
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
        $('#sport_nameError').text('');
		$('#emailError').text('');

        $.ajax({
            type:"POST",
            url: "{{ url('admin/add-update-Sport') }}",
            data: Form_Data,
			mimeType: "multipart/form-data",
		    contentType: false,
		    cache: false,
		    processData: false,
            dataType: 'json',
            success: function(res){
				fetchData();
				$('#ajax-model').modal('hide');
				$("#btn-save").html('<i class="fa fa-save"></i> Save');
				$("#btn-save"). attr("disabled", false);
           },
		   error:function (response) {
				$("#btn-save").html('<i class="fa fa-save"></i> Save');
				$("#btn-save"). attr("disabled", false);
				$('#sport_nameError').text(response.responseJSON.errors.sport_name);
				$('#emailError').text(response.responseJSON.errors.email);
			}
        });
    }));
});

</script>

@endpush
