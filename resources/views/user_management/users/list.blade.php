@extends('layouts.master')
@section('content')
@section('perticular_page_css')
<link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatable/fixedeader/dataTables.fixedcolumns.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatable/fixedeader/dataTables.fixedheader.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/sweetalert/sweetalert.css') }}"/>
<style>
    td.details-control {
        background: url('../assets/images/details_open.png') no-repeat center center;
        cursor: pointer;
    }
    tr.shown td.details-control {
        background: url('../assets/images/details_close.png') no-repeat center center;
    }
</style>
@endsection
<div id="main-content">
    <div class="container-fluid">
        @include('common.block-header')
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>{{$title ? $title : "User Management"}}</h2>
                        <button class="float-md-right btn btn-success btn-sm" onclick="showUsers()">Add User</button>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover users_table dataTable">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>UserName</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Role</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document"> <!-- Added modal-lg for a larger popup -->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="editModalLabel">Edit User Information</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- <div id="formErrors" class="text-danger"></div> --}}
            <form method="POST" action="{{ route('users_store') }}" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <div class="row clearfix">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="text" class="form-control" name="name" id="name" placeholder="Full Name">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select class="form-control show-tick" name="gender" id="gender">
                                    <option value="">- Gender -</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                    
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="text" data-provide="datepicker" data-date-autoclose="true" class="form-control" name="dob" id="dob" placeholder="Date of Birth">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select class="form-control show-tick" name="role_id" id="role_id">
                                    <option value="">-Designation / Role-</option>
                                    @foreach (getAllActiveRoles() as $role)
                                    <option value="{{$role->id}}">{{ucfirst($role->name)}}</option>
                                    @endforeach
                                </select>                                        
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="text" class="form-control" name="email" id="email" placeholder="Enter Your Email">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" id="password" placeholder="New Password">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control show-tick" name="speciality_id" id="speciality_id">
                                <option value="">-Speciality-</option>
                                @foreach (getAllActiveSpeciality() as $item)
                                <option value="{{$item->id}}">{{ucfirst($item->name)}}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="file" class="form-control" name="file" id="file">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group mt-3">
                                <textarea rows="4" class="form-control no-resize" name="signature_text" id="signature_text" placeholder="Signature Text"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Model Permission --}}
<div class="modal fade" id="permissionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document"> <!-- Added modal-lg for a larger popup -->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="permissionModalLabel">User Permissions</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- <div id="formErrors" class="text-danger"></div> --}}
            <div class="modal-body">
                <div class="row">
                    @forelse (getAllActivePermissions() as $p => $permission)
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input 
                                    type="checkbox" 
                                    class="form-check-input" 
                                    id="permission-{{ $permission->id }}" 
                                    name="permissions[]" 
                                    value="{{ $permission->id }}">
                                <label class="form-check-label" for="permission-{{ $permission->id }}">
                                    {{ $permission->name }}
                                </label>
                                <p class="small mb-0">{{ $permission->description }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <p class="text-danger">Please contact the admin.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Permission Save</button>
            </div>
        </div>
    </div>
</div>


@section('perticular_page_scripts')
    {{-- <script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>     --}}
    <script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatablescripts.bundle.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-datatable/buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-datatable/buttons/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-datatable/buttons/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-datatable/buttons/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-datatable/buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/sweetalert/sweetalert.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script>
        var table;
        $(document).ready(function () {
            try{
                table = $('.users_table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ url('getusers') }}",
                    order: [[7, 'desc']],
                    columns: [
                        { 
                            data: null, 
                            name: 'serial_no', 
                            render: function (data, type, row, meta) {
                                return meta.settings._iDisplayStart + meta.row + 1;
                            },
                            searchable: false,
                            orderable: false
                        },
                        { 
                            data:'image',
                            name:'image',
                            render: function(data, type, row){
                                return `<img src="../assets/images/xs/avatar3.jpg" class="rounded-circle width30 m-r-15" alt="profile-image">`
                            }
                        },
                        { 
                            data: 'person_name', 
                            name: 'person_name' 
                        },
                        { 
                            data: 'username', 
                            name: 'username' 
                        },
                        { 
                            data: 'email', 
                            name: 'email' 
                        },
                        { 
                            data: 'phone', 
                            name: 'phone' 
                        },
                        {
                            data:'role_name',
                            name:'role_name',
                            render: function(data, type, row){
                                return `<span class="badge badge-success">${row.role_name}</span>`
                            }
                        },
                        { 
                            data: 'created_at', 
                            name: 'created_at',
                            render: function (data, type, row) {
                                return moment(data).format('MMM DD, YYYY'); // "Dec 12, 2024"
                            }
                        },
                        { 
                            data: null, 
                            name:'action',
                            searchable: false,
                            orderable: false,
                            render: function (data, type, row) {                                
                                let statusButton = '';
                                if (row.status == 1) {
                                    statusButton = `
                                        <button 
                                            title="Active" 
                                            class="btn btn-sm badge badge-success" 
                                            onclick="changeStatus(${row.user_id}, 0)">
                                            <i class="fa fa-toggle-on"></i>
                                        </button>`;
                                } else {
                                    statusButton = `
                                        <button 
                                            title="Inactive" 
                                            class="btn btn-sm badge badge-danger" 
                                            onclick="changeStatus(${row.user_id}, 1)">
                                            <i class="fa fa-toggle-off"></i>
                                        </button>`;
                                }
                                return `
                                    <button title="Edit" class="btn btn-sm badge badge-success" onclick="showEditRow(${row.user_id}, 0)" ><i class="fa fa-pencil"></i></button>
                                    <button title="Permissions" class="btn btn-sm badge badge-primary" onclick="showPermissionRow(${row.user_id})" ><i class="fa fa-lock"></i></button>
                                    ${statusButton}
                                `;
                            }
                        },
                    ],
                    rowCallback: function (row, data, index) {
                        // Check the condition for danger class
                        if (data.status != 1) {
                            $(row).addClass('table-danger');
                        }
                    },
                });
            }catch (error) {
                console.error('Error initializing DataTable:', error);
            }
        });

        function showUsers(){
            document.getElementById('name').value = '';  
            document.getElementById('role_id').value = ''; 
            document.getElementById('formErrors').innerHTML = '';
            $("#createModalLabel").text("Add Role")
            $("#createModal").modal('show')
        }

        function storeData() {
            $("#formErrors").html('')
            const name = $('#name').val();
            const role_id = $('#role_id').val();
            $.ajax({
                url: "{{ url('roles/store') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}", // CSRF token for security
                    name: name,
                    role_id:role_id
                },
                success: function(response) {
                    if (response.success) {                        
                        toastr['success'](response.message);
                        $('#createModal').modal('hide'); 
                        table.ajax.reload();
                    } else {
                        toastr['error'](response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        // Validation error
                        const errors = xhr.responseJSON.errors;
                        let errorMessages = '';

                        for (const field in errors) {
                            errorMessages += `<div class="alert alert-danger alert-dismissible" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                    <i class="fa fa-times-circle"></i> ${errors[field][0]}
                                                </div>`;
                        }

                        // Display errors in a designated div inside the modal
                        $('#formErrors').html(errorMessages).show();
                    }
                    if(xhr.status == 500){
                        $('#formErrors').html(`<div class="alert alert-danger alert-dismissible" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                    <i class="fa fa-times-circle"></i> Internal Server Error.
                                                </div>`);
                    }
                }
            });
        }

        function changeStatus(id, status) {
            toastr.options = {
                "timeOut": "10000",
                "closeButton": true,
                // positionClass: "toast-bottom-right",
            };
            $.ajax({
                url: "{{ url('roles/change-status') }}"+`/${id}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // Add CSRF token
                    status: status
                },
                success: function(response) {
                    if (response.success) {                        
                        toastr['success'](response.message);
                        table.ajax.reload();
                    } else {
                        toastr['error'](response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + error);
                }
            });
        }

        function editRow(id) {
            document.getElementById('name').value = ''; 
            document.getElementById('role_id').value = id; 
            document.getElementById('formErrors').innerHTML = '';
            $("#createModalLabel").text("Edit Role")
            $.ajax({
                url: "{{ url('roles/edit') }}"+`/${id}`,
                method: 'GET',
                success: function(response) {
                    if (response.success) {                       
                        console.log(response.data);
                        $('#name').val(response.data.name);                       
                        $("#createModal").modal('show')
                    } else {
                        toastr['error'](response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + error);
                }
            });
        }

        function deleteRow(id) {
            swal({
                title: "Are you sure?",
                text: "You want to delete this item.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel Please!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    swal.close()
                    deleteRecord(id)
                } else {
                    swal.close()
                    // swal("Cancelled", "Your imaginary file is safe :)", "error");
                }
            });
        }

        function deleteRecord(id){
            toastr.options = {
                "timeOut": "10000",
                "closeButton": true,
                // positionClass: "toast-bottom-right",
            };
            $.ajax({
                url: "{{ url('roles/delete') }}"+`/${id}`,
                method: 'GET',
                success: function(response) {
                if (response.success) {
                        // swal("Deleted!", response.message, "success");
                        toastr['success'](response.message);
                        setTimeout(() => {
                            table.ajax.reload();
                        }, 1000);
                    } else {
                        swal("Error!", response.message, "error");
                    }
                },
                error: function (xhr, status, error) {
                    swal("Error!", 'Something went wrong. Please try again.', "error");
                }
            });
        }

        function showEditRow(id){
            $("#editModal").modal('show')
        }

        function showPermissionRow(id){
            $("#permissionModal").modal('show')
        }
    </script>
@endsection
@endsection