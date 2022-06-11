  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; @php echo date('Y') @endphp <a href="javascript:void(0)">{{ config('app.name', 'Laravel') }}</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.0.5
    </div>
  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery -->

  <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Bootstrap 4 -->

<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->


  {{--<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
  <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>--}}

  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>

<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('dist/js/demo.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

<!-- DataTables -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>

<script>
    function getFormattedDate(date) {
        var day = date.getDate();
        var month = date.getMonth() + 1;
        var year = date.getFullYear().toString().slice(2);
        return day + '-' + month + '-' + year;
    }

    $(document).ready(function() {
        $('#start_time').datetimepicker({format: 'm/d/Y h:m'});

        $('.js-example-basic-multiple').select2({
            placeholder: "Please Select an Option ",
        });
        $(document).ready(function() {
            $('.js-example-basic-single').select2({
                placeholder: "Please Select an Option ",
            });
        });

    });
  $(function () {
    $('.dataTables').DataTable({
      "paging": false,
      "lengthChange": false,
      "searching": true,
      "ordering": false,
      "info": false,
      "autoWidth": false,
      "responsive": true,
    });
  });


function checkFileFormat(e){

	var id = e.id;
	if(id == '')
		return false;

	var files = $('#'+id)[0].files[0];
	if(files)
	{
		var filename = files.name;
		var fileNameExt = filename.substr(filename.lastIndexOf('.') + 1).toLowerCase();
		var validExtensions = ['jpeg','jpg','png','tiff','bmp','mp4','3gp','wmv','avi','mov']; //array of valid extensions
		if ($.inArray(fileNameExt, validExtensions) == -1)
		{
		   alert("Invalid file type");
		   $("#"+id).val('');
		   return false;
		}
	}
}

function allowonlyImg(e){
	var id = e.id;
	if(id == '')
		return false;

	var files = $('#'+id)[0].files[0];
	if(files)
	{
		var filename = files.name;
		var filesize = files.size;
		if( filesize <= 1048576 ){
			var fileNameExt = filename.substr(filename.lastIndexOf('.') + 1).toLowerCase();
			var validExtensions = ['jpeg','jpg','png','tiff','bmp']; //array of valid extensions
			if ($.inArray(fileNameExt, validExtensions) == -1)
			{
			   alert("Invalid file type");
			   $("#"+id).val('');
			   return false;
			}
		}else{
			alert("Your file size should not be greater than 1 MB");
		   $("#"+id).val('');
		   return false;
		}
	}
}

function allowonlyVideo(e){

	var id = e.id;
	if(id == '')
		return false;

	var files = $('#'+id)[0].files[0];
	if(files)
	{
		var filename = files.name;
		var fileNameExt = filename.substr(filename.lastIndexOf('.') + 1).toLowerCase();
		var validExtensions = ['mp4','3gp','wmv','avi','mov','flv']; //array of valid extensions
		if ($.inArray(fileNameExt, validExtensions) == -1)
		{
		   alert("Invalid file type");
		   $("#"+id).val('');
		   return false;
		}
	}
}

function allowonlyImportCodeFiles(e){

	var id = e.id;
	if(id == '')
		return false;

	var files = $('#'+id)[0].files[0];
	if(files)
	{
		var filename = files.name;
		var fileNameExt = filename.substr(filename.lastIndexOf('.') + 1).toLowerCase();
		var validExtensions = ['xlsx']; //array of valid extensions
		if ($.inArray(fileNameExt, validExtensions) == -1)
		{
		   alert("Invalid file type");
		   $("#"+id).val('');
		   return false;
		}
	}
}

$(function () {

/*	$(".select2").select2({
		width: "100%",
	});*/

	$('.decimal_only').keypress(function(event) {
		if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
			event.preventDefault();
		}
	});

	$('.number_only').keyup(function () {
	   this.value = this.value.replace(/[^0-9]/g,'');
	});

	$('.phone_number').keyup(function () {
	   this.value = this.value.replace(/[^0-9+-]/g,'');
	});

});

$(document).ready(function($){
	$.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

	$('body').on('click', '.profile', function () {
        $('#profile_user_nameError').text('');
		$('#profile_emailError').text('');
        $.ajax({
            type:"POST",
            url: "{{ url('admin/edit-profile') }}",
            dataType: 'json',
            success: function(res){
			  $("#profile_password").prop("required",false);
			  $('#profile_addEditForm').trigger("reset");
              $('#profile_ajaxheadingModel').html("Profile");
              $('#profile_ajax-model').modal('show');
              $('#profile_name').val(res.name);
			  $('#profile_user_name').val(res.user_name);
			  $('#profile_email').val(res.email);
			  $('#profile_phone').val(res.phone);
           }
        });
    });

	$("#profile_addEditForm").on('submit',(function(e) {
		e.preventDefault();
		var Form_Data = new FormData(this);
        $("#profile_btn-save").html('Please Wait...');
        $("#profile_btn-save"). attr("disabled", true);
        $('#profile_user_nameError').text('');
		$('#profile_emailError').text('');

        $.ajax({
            type:"POST",
            url: "{{ url('admin/update-profile') }}",
            data: Form_Data,
			mimeType: "multipart/form-data",
		    contentType: false,
		    cache: false,
		    processData: false,
            dataType: 'json',
            success: function(res){
				$('#profile_ajax-model').modal('hide');
				$("#profile_btn-save").html('<i class="fa fa-save"></i> Save');
				$("#profile_btn-save"). attr("disabled", false);
           },
		   error:function (response) {
				$("#profile_btn-save").html('<i class="fa fa-save"></i> Save');
				$("#profile_btn-save"). attr("disabled", false);
				$('#profile_user_nameError').text(response.responseJSON.errors.user_name);
				$('#profile_emailError').text(response.responseJSON.errors.email);
			}
        });
    }));

});

</script>

	@stack('scripts')

	 <!-- boostrap model -->
    <div class="modal fade" id="profile_ajax-model" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="profile_ajaxheadingModel"></h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body">
            <form action="javascript:void(0)" id="profile_addEditForm" name="addEditForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
              <div class="form-group row">
                <div class="col-sm-6">
					<label for="name" class="control-label">Name</label>
					<input type="text" class="form-control" id="profile_name" name="name" placeholder="Enter Name" value="" maxlength="50" required="">
                </div>
				<div class="col-sm-6">
					<label for="name" class="control-label">User Name</label>
					<input type="text" class="form-control" id="profile_user_name" name="user_name" placeholder="Enter User Name" value="" maxlength="50" required="">
					<span class="text-danger" id="profile_user_nameError"></span>
                </div>
              </div>
			  <div class="form-group row">
                <div class="col-sm-6">
					<label for="name" class="control-label">Email</label>
					<input type="email" class="form-control" id="profile_email" name="email" placeholder="Enter Email" value="" maxlength="50" required="">
					<span class="text-danger" id="profile_emailError"></span>
                </div>
				<div class="col-sm-6">
					<label for="name" class="control-label">Password</label>
					<input type="text" class="form-control" id="profile_password" name="password" placeholder="Enter Password" value="" minlength="8" maxlength="50" required="">
                </div>
              </div>
			  <div class="form-group row">
                <div class="col-sm-6">
					<label for="name" class="control-label">Phone</label>
					<input type="text" class="form-control phone_number" id="profile_phone" name="phone" placeholder="Enter Phone" value="" maxlength="50" required="">
                </div>
              </div>
              <div class="col-sm-offset-2 col-sm-12 text-right">
                <button type="submit" class="btn btn-dark" id="profile_btn-save" >
                    <i class="fa fa-save"></i>&nbsp; Save
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
