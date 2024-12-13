@extends('layouts.master')
@section('content')
<div id="main-content">
    <div class="container-fluid">
        @include('common.block-header')
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="body">
                        <ul class="nav nav-tabs">
                            <li class="nav-item"><a class="nav-link show active" data-toggle="tab" href="#staff">Staff</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#referral_doctor">Referral Doctor</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#vendors">Vendors</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane show active" id="staff">
                                <div class="table-responsive m-t-20">
                                    <table class="table table-filter table-hover m-b-0">                                
                                        <tbody>
                                            @if (isset($staffs) && count($staffs)>0)
                                                @foreach ($staffs as $key => $staff)
                                                    <tr onclick="fetchInfo({{$staff->id}})">
                                                        <td>{{$key+1}}</td>
                                                        <td><div class="media-object"><img src="../assets/images/xs/avatar1.jpg" alt="" width="35px" class="rounded-circle"></div></td>
                                                        <td>{{$staff->name}}</td>
                                                        <td>
                                                            <span class="text-danger">
                                                                {{$staff->phone}}
                                                            </span>
                                                            <br>
                                                            <span class="text-info">
                                                                {{$staff->email}}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-success">{{getRole($staff->id)}}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td>Record not found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="referral_doctor">
                                <h6>Referral Doctors</h6>
                                <div class="table-responsive m-t-20">
                                    <table class="table table-filter table-hover m-b-0">                                
                                        <tbody>
                                            <tr data-status="approved" style="">
                                                <td>1</td>
                                                <td><div class="media-object"><img src="../assets/images/xs/avatar1.jpg" alt="" width="35px" class="rounded-circle"></div></td>
                                                <td>jacob</td>
                                                <td>jacob@gnail.com</td>
                                                <td width="250px">
                                                    <div class="progress progress-xs">
                                                        <div class="progress-bar l-green" role="progressbar" aria-valuenow="87" aria-valuemin="0" aria-valuemax="100" style="width: 87%;"></div>
                                                    </div>
                                                </td>
                                                <td><span class="badge badge-success">Approved</span></td>
                                            </tr>
                                            <tr data-status="suspended" style="">
                                                <td>2</td>
                                                <td><div class="media-object"><img src="../assets/images/xs/avatar2.jpg" alt="" width="35px" class="rounded-circle"></div></td>
                                                <td>charlotte</td>
                                                <td>a.charlotte@gnail.com</td>
                                                <td>
                                                    <div class="progress progress-xs">
                                                        <div class="progress-bar l-amber" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%;"></div>
                                                    </div>
                                                </td>
                                                <td><span class="badge badge-warning">Suspended</span></td>
                                            </tr>
                                            <tr data-status="blocked" style="">
                                                <td>3</td>
                                                <td><div class="media-object"><img src="../assets/images/xs/avatar3.jpg" alt="" width="35px" class="rounded-circle"></div></td>
                                                <td>grayson</td>
                                                <td>grayson@yahoo.com</td>
                                                <td>
                                                    <div class="progress progress-xs">
                                                        <div class="progress-bar l-coral" role="progressbar" aria-valuenow="16" aria-valuemin="0" aria-valuemax="100" style="width: 16%;"></div>
                                                    </div>
                                                </td>
                                                <td><span class="badge badge-danger">Blocked</span></td>
                                            </tr>
                                            <tr data-status="approved" style="">
                                                <td>4</td>
                                                <td><div class="media-object"><img src="../assets/images/xs/avatar4.jpg" alt="" width="35px" class="rounded-circle"></div></td>
                                                <td>jacob</td>
                                                <td>jacob@gnail.com</td>
                                                <td>
                                                    <div class="progress progress-xs">
                                                        <div class="progress-bar l-green" role="progressbar" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100" style="width: 67%;"></div>
                                                    </div>
                                                </td>
                                                <td><span class="badge badge-success">Approved</span></td>
                                            </tr>
                                            <tr data-status="approved" style="">
                                                <td>5</td>
                                                <td><div class="media-object"><img src="../assets/images/xs/avatar5.jpg" alt="" width="35px" class="rounded-circle"></div></td>
                                                <td>amelia</td>
                                                <td>amelia@gnail.com</td>
                                                <td>
                                                    <div class="progress progress-xs">
                                                        <div class="progress-bar l-green" role="progressbar" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100" style="width: 72%;"></div>
                                                    </div>
                                                </td>
                                                <td><span class="badge badge-success">Approved</span></td>
                                            </tr>
                                            <tr data-status="pending" style="">
                                                <td>6</td>
                                                <td><div class="media-object"><img src="../assets/images/xs/avatar6.jpg" alt="" width="35px" class="rounded-circle"></div></td>
                                                <td>michael</td>
                                                <td>michael@gmail.com</td>
                                                <td>
                                                    <div class="progress progress-xs">
                                                        <div class="progress-bar l-blue" role="progressbar" aria-valuenow="32" aria-valuemin="0" aria-valuemax="100" style="width:32%;"></div>
                                                    </div>
                                                </td>
                                                <td><span class="badge badge-info">Pending</span></td>
                                            </tr>
                                            <tr data-status="pending" style="">
                                                <td>7</td>
                                                <td><div class="media-object "><img src="../assets/images/xs/avatar7.jpg" alt="" width="35px" class="rounded-circle"></div></td>
                                                <td>michael</td>
                                                <td>michael@gmail.com</td>
                                                <td>
                                                    <div class="progress progress-xs">
                                                        <div class="progress-bar l-blue" role="progressbar" aria-valuenow="68" aria-valuemin="0" aria-valuemax="100" style="width: 68%;"></div>
                                                    </div>
                                                </td>
                                                <td><span class="badge badge-info">Pending</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="vendors">
                                <h6>Vendors</h6>
                                <div class="table-responsive m-t-20">
                                    <table class="table table-filter table-hover m-b-0">                                
                                        <tbody>
                                            <tr data-status="approved" style="">
                                                <td>1</td>
                                                <td><div class="media-object"><img src="../assets/images/xs/avatar1.jpg" alt="" width="35px" class="rounded-circle"></div></td>
                                                <td>jacob</td>
                                                <td>jacob@gnail.com</td>
                                                <td width="250px">
                                                    <div class="progress progress-xs">
                                                        <div class="progress-bar l-green" role="progressbar" aria-valuenow="87" aria-valuemin="0" aria-valuemax="100" style="width: 87%;"></div>
                                                    </div>
                                                </td>
                                                <td><span class="badge badge-success">Approved</span></td>
                                            </tr>
                                            <tr data-status="suspended" style="">
                                                <td>2</td>
                                                <td><div class="media-object"><img src="../assets/images/xs/avatar2.jpg" alt="" width="35px" class="rounded-circle"></div></td>
                                                <td>charlotte</td>
                                                <td>a.charlotte@gnail.com</td>
                                                <td>
                                                    <div class="progress progress-xs">
                                                        <div class="progress-bar l-amber" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%;"></div>
                                                    </div>
                                                </td>
                                                <td><span class="badge badge-warning">Suspended</span></td>
                                            </tr>
                                            <tr data-status="blocked" style="">
                                                <td>3</td>
                                                <td><div class="media-object"><img src="../assets/images/xs/avatar3.jpg" alt="" width="35px" class="rounded-circle"></div></td>
                                                <td>grayson</td>
                                                <td>grayson@yahoo.com</td>
                                                <td>
                                                    <div class="progress progress-xs">
                                                        <div class="progress-bar l-coral" role="progressbar" aria-valuenow="16" aria-valuemin="0" aria-valuemax="100" style="width: 16%;"></div>
                                                    </div>
                                                </td>
                                                <td><span class="badge badge-danger">Blocked</span></td>
                                            </tr>
                                            <tr data-status="approved" style="">
                                                <td>4</td>
                                                <td><div class="media-object"><img src="../assets/images/xs/avatar4.jpg" alt="" width="35px" class="rounded-circle"></div></td>
                                                <td>jacob</td>
                                                <td>jacob@gnail.com</td>
                                                <td>
                                                    <div class="progress progress-xs">
                                                        <div class="progress-bar l-green" role="progressbar" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100" style="width: 67%;"></div>
                                                    </div>
                                                </td>
                                                <td><span class="badge badge-success">Approved</span></td>
                                            </tr>
                                            <tr data-status="approved" style="">
                                                <td>5</td>
                                                <td><div class="media-object"><img src="../assets/images/xs/avatar5.jpg" alt="" width="35px" class="rounded-circle"></div></td>
                                                <td>amelia</td>
                                                <td>amelia@gnail.com</td>
                                                <td>
                                                    <div class="progress progress-xs">
                                                        <div class="progress-bar l-green" role="progressbar" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100" style="width: 72%;"></div>
                                                    </div>
                                                </td>
                                                <td><span class="badge badge-success">Approved</span></td>
                                            </tr>
                                            <tr data-status="pending" style="">
                                                <td>6</td>
                                                <td><div class="media-object"><img src="../assets/images/xs/avatar6.jpg" alt="" width="35px" class="rounded-circle"></div></td>
                                                <td>michael</td>
                                                <td>michael@gmail.com</td>
                                                <td>
                                                    <div class="progress progress-xs">
                                                        <div class="progress-bar l-blue" role="progressbar" aria-valuenow="32" aria-valuemin="0" aria-valuemax="100" style="width:32%;"></div>
                                                    </div>
                                                </td>
                                                <td><span class="badge badge-info">Pending</span></td>
                                            </tr>
                                            <tr data-status="pending" style="">
                                                <td>7</td>
                                                <td><div class="media-object "><img src="../assets/images/xs/avatar7.jpg" alt="" width="35px" class="rounded-circle"></div></td>
                                                <td>michael</td>
                                                <td>michael@gmail.com</td>
                                                <td>
                                                    <div class="progress progress-xs">
                                                        <div class="progress-bar l-blue" role="progressbar" aria-valuenow="68" aria-valuemin="0" aria-valuemax="100" style="width: 68%;"></div>
                                                    </div>
                                                </td>
                                                <td><span class="badge badge-info">Pending</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card">                                        
                    <div class="loader" style="display: none">
                        <img src="{{ asset('assets/images/loader.gif') }}" alt="Loading..." />
                    </div> 
                    <div class="header user_details_html">                       
                        <div class="tab-content">
                            <div class="tab-pane active">
                                <div class="row clearfix">
                                    <div class="text-center">
                                        <a href="{{ route('users_create') }}" class="btn btn-success btn-lg"> 
                                        <i class="fa fa-plus"></i> Add user</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
    </div> 
</div>
<script>
    function fetchInfo(user_id){
        $(".user_details_html").html('')
        $(".loader").show();
        $.ajax({
            url:"{{route('fetch_user')}}",
            type: "POST",
            data: {
                user_id: user_id,
                _token: '{{csrf_token()}}'
            },
            success: function(result){
                console.log(result.user_details_html);
                $('.user_details_html').html(result.user_details_html)  
            },
            error: function(xhr, status, error) {
                console.error("An error occurred: ", error);
            },
            complete: function() {
                // Hide the loader once the request completes
                $(".loader").hide();
            }
        })
    }
</script>
@endsection