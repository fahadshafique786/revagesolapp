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
                                    @if(auth()->user()->hasRole('super-admin') || auth()->user()->can('manage-apps'))
                                        <a class="btn btn-info" href="{{route('app.create')}}">
                                            Add Application
                                        </a>

                                    @endif

                                </div>

                                <div class="col-6 text-right">
                                    @if(auth()->user()->hasRole('super-admin') || auth()->user()->can('manage-apps'))
                                        <a class="btn btn-warning" href="javascript:window.location.reload()" id="">
                                            <i class="fa fa-spinner"></i> &nbsp; Refresh Screen
                                        </a>
                                    @endif

                                </div>


                            </div>

                        </div>
                        <div class="card-body row">

                            @foreach($apps_list as $obj)
                            <div class="col-xl-3 col-md-3" id="application{{$obj->id}}">
                                <div class="card custom text-center">
                                    <div class="card-header">
                                        <div class="col-md-12">
                                            <div class="text-center">
                                                <h6>{{$obj->appName}}</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-block text-center">
                                        <div style="height: 92px; " class="col-md-12">
                                            <div class="text-center">
                                                <img style="height: 72px; width: 72px" src="{{asset('uploads/apps/appLogo.png')}}" alt="App Logo">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <a class="ik ik-settings f-18 text-green" href="{{url('/admin/app/'.$obj->id)}}"><i class="fa fa-cog"></i> </a>
                                            </div>
                                            <div class="col-6 border-left">
                                                <a class="ik ik-trash-2 f-18 text-red" id="1" href="" data-toggle="modal" data-target="#applicationDelete">
                                                    <i class="fa fa-trash"></i>

                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

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
                        orderable: true
                    },
                ],
                serverSide: true,
                ajax: "{{ url('admin/fetchsportsdata') }}",
                columns: [
                    { data: 'srno', name: 'srno' , searchable:false},
                    { data: 'icon', name: 'icon', searchable:false},
                    { data: 'name', name: 'name' },
                    { data: 'sports_type', name: 'sports_type' },
                    { data: 'image_required', name: 'image_required' , searchable:false , render: function( data, type, full, meta,rowData ) {

                            if(data=='Yes'){
                                return "<a href='javascript:void(0)' class='badge badge-success text-xs text-capitalize'>"+data+"</a>" +" ";
                            }
                            else{
                                return "<a href='javascript:void(0)' class='badge badge-danger text-xs text-capitalize'>"+data+"</a>" +" ";
                            }
                        },
                    },
                    { data: 'multi_league', name: 'multi_league' , searchable:false , render: function( data, type, full, meta,rowData ) {
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
                order: [[0, 'asc']],
            });

        }

        $(document).ready(function($){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        });

    </script>

@endpush
