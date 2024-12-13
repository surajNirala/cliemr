@extends('layouts.master')
@section('content')
<div id="main-content">
    <div class="container-fluid">
        @include('common.block-header')
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>User Management</h2>
                        <a href="{{ route('users_create') }}" class="float-md-right btn btn-success btn-sm">Add User</a>
                    </div>
                    <div class="body">
                        <div class="table-responsive table_middel">
                            <table class="table m-b-0">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>UserName</th>
                                        <th>CRN</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($users) && count($users)>0)
                                        @foreach ($users as $key => $user)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td><img src="../assets/images/xs/avatar3.jpg" class="rounded-circle width30 m-r-15" alt="profile-image"><span>{{ucwords($user->name)}}</span></td>
                                            <td><span class="text-info">{{$user->username}}</span></td>
                                            <td><span class="badge badge-success">{{$user->crn}}</span></td>
                                            <td>{{$user->email}}</td>
                                            <td>
                                                @if ($user->status == 1)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ date("M d, Y", strtotime($user->created_at)) }}</td>
                                            <td>
                                                <a href="#" title="Edit" class="badge badge-success"><i class="fa fa-pencil"></i></a>
                                                <a href="#" title="Delete" class="badge badge-danger"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr> 
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="10" style="text-align: center;">Users not found</td>
                                        </tr>
                                    @endif
                                    
                                </tbody>
                            </table>
                            @if(isset($users) && count($users)>0)
                                {{ $users->appends(request()->query())->links('common.pagination') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection