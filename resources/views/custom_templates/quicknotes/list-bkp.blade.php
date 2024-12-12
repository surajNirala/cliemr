@extends('layouts.master')
@section('content')



<div id="main-content">
    <div class="container-fluid">
        @include('common.block-header')
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>{{$title ? $title : "User Management"}}</h2>
                        <a href="{{ route('quicknotes_create') }}" class="float-md-right btn btn-success btn-sm">Add Quick Note</a>
                    </div>
                    <div class="body">
                        <div class="table-responsive table_middel">
                            <table class="table m-b-0 js-basic-example">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Created At</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($quicknotes) && count($quicknotes)>0)
                                        @foreach ($quicknotes as $key => $item)
                                        <tr>
                                            {{-- <td>{{$quicknotes->firstItem() + $key}}</td> --}}
                                            <td>{{$key}}</td>
                                            <td>{{$item->title}}</td>
                                            <td>{{$item->description}}</td>
                                            <td>{{ date("M d, Y", strtotime($item->created_at)) }}</td>
                                            <td>
                                                @if ($item->status == 1)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="#" title="Edit" class="badge badge-success"><i class="fa fa-pencil"></i></a>
                                                <a href="#" title="Delete" class="badge badge-danger"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr> 
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="10" style="text-align: center;">quicknotes not found</td>
                                        </tr>
                                    @endif
                                    
                                </tbody>
                            </table>
                            @if(isset($quicknotes) && count($quicknotes)>0)
                                {{-- {{ $quicknotes->appends(request()->query())->links('common.pagination') }} --}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>    
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>

{{-- <script src="{{ asset('assets/bundles/datatablescripts.bundle.js') }}"></script> --}}
<script src="{{ asset('assets/vendor/jquery-datatable/buttons/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery-datatable/buttons/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery-datatable/buttons/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery-datatable/buttons/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery-datatable/buttons/buttons.print.min.js') }}"></script>

{{-- <script src="../assets/vendor/sweetalert/sweetalert.min.js"></script> <!-- SweetAlert Plugin Js -->  --}}


{{-- <script src="assets/bundles/mainscripts.bundle.js"></script> --}}
<script src="{{ asset('assets/js/pages/tables/jquery-datatable.js') }}"></script>
<script>
    $(function () {
        $('.js-basic-example').DataTable();
        //Exportable table
        $('.js-exportable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>
@endsection