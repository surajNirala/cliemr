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
                        <a href="{{ route('permissions_create') }}" class="float-md-right btn btn-success btn-sm">Add Permission</a>
                    </div>
                    <div class="body">
                        <div class="table-responsive table_middel">
                            <table class="table m-b-0">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>User Name</th>
                                        <th>Permission Name</th>
                                        <th>Permission Detail</th>
                                        <th>Created At</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($permissions) && count($permissions)>0)
                                        @foreach ($permissions as $key => $permission)
                                        <tr>
                                            <td>{{$permissions->firstItem() + $key}}</td>
                                            <td>{{$permission->person_name}}</td>
                                            <td>{{$permission->permission_name}}</td>
                                            <td>{{Str::limit($permission->permission_detail, 40)}}</td>
                                            <td>{{ date("M d, Y", strtotime($permission->created_at)) }}</td>
                                            <td>
                                                @if ($permission->status == 1)
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
                                            <td colspan="10" style="text-align: center;">permissions not found</td>
                                        </tr>
                                    @endif
                                    
                                </tbody>
                            </table>
                            @if(isset($permissions) && count($permissions)>0)
                                {{ $permissions->appends(request()->query())->links('common.pagination') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection