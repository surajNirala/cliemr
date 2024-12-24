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

{{-- Create Modal --}}
<div class="modal fade" id="createModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document"> <!-- Added modal-lg for a larger popup -->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="createModalLabel">Add User Information</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="formErrors" class="text-danger"></div>
            <form id="createForm" method="POST" action="#" enctype="multipart/form-data">
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
                    <button type="button" class="btn btn-primary" onclick="storeData()">Save</button>
                </div>
            </form>
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
            <div id="formErrors1" class="text-danger"></div>
            <form id="editForm" method="POST" action="#" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <div class="row clearfix">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="text" class="form-control" name="name" id="edit_name" placeholder="Full Name">
                                <input type="hidden" class="form-control" name="user_id" id="edit_user_id" placeholder="user_id">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select class="form-control show-tick" name="gender" id="edit_gender">
                                    <option value="">- Gender -</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                    
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="text" data-provide="datepicker" data-date-autoclose="true" class="form-control" name="dob" id="edit_dob" placeholder="Date of Birth">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select class="form-control show-tick" name="role_id" id="edit_role_id">
                                    <option value="">-Designation / Role-</option>
                                </select>                                        
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="text" class="form-control" name="email" id="edit_email" placeholder="Enter Your Email">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="text" class="form-control" name="phone" id="edit_phone" placeholder="Phone">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" id="edit_password" placeholder="New Password">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control show-tick" name="speciality_id" id="edit_speciality_id">
                                <option value="">-Speciality-</option>
                            </select>
                        </div>
                        
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="file" class="form-control" name="file" id="edit_file">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group mt-3">
                                <textarea rows="4" class="form-control no-resize" name="signature_text" id="edit_signature_text" placeholder="Signature Text"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary"  onclick="updateData()" >Update</button>
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
            <div class="modal-body">
                <input type="hidden" id="permission_user_id">
                <div class="row permission_append">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="savePermissions()">Permission Save</button>
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
                    order: [[8, 'desc']],
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
                                // console.log("row.image   ====  ",row.image);
                                
                                if(row.image){
                                    return `<img src="{{baseURL()}}${row.image}" class="rounded-circle m-r-15" alt="profile-image" width="50">`
                                }else{

                                    return `<img src="../assets/images/xs/avatar3.jpg" class="rounded-circle width30 m-r-15" alt="profile-image">`
                                }
                            }
                        },
                        { 
                            data: 'person_name', 
                            name: 'person_name' 
                        },
                        { 
                            data: 'username', 
                            name: 'username' ,
                            visible:false
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
                                let deleteButton = '';
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

                                if (!row.role_name){
                                    deleteButton = `<button title="Delete" class="btn btn-sm badge badge-danger" onclick="deleteRow(${row.user_id})" ><i class="fa fa-trash"></i></button>`
                                }
                                return `
                                    <button title="Edit" class="btn btn-sm badge badge-success" onclick="showEditRow(${row.user_id}, 0)" ><i class="fa fa-pencil"></i></button>
                                    <button title="Permissions" class="btn btn-sm badge badge-primary" onclick="showPermissionRow(${row.user_id})" ><i class="fa fa-lock"></i></button>
                                    ${deleteButton}
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
            $("#createModal").modal('show')
        }

        function storeData() {
            $("#formErrors").html(''); // Clear previous errors
            const formData = new FormData($('#createForm')[0]); // Get form data, including file input

            $.ajax({
                url: "{{ route('users_store_new') }}",
                method: "POST",
                data: formData,
                processData: false, // Required for FormData
                contentType: false, // Required for FormData
                success: function (response) {
                    if (response.success) {
                        toastr['success'](response.message); // Show success message
                        $('#createModal').modal('hide'); // Hide modal
                        $('#createForm')[0].reset(); // Reset the form
                        table.ajax.reload(); // Reload DataTable
                    } else {
                        toastr['error'](response.message); // Show error message
                    }
                },
                error: function (xhr) {
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

                        // Display errors in the modal
                        $('#formErrors').html(errorMessages).show();
                    }
                    if (xhr.status == 500) {
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
                url: "{{ url('users/change-status') }}"+`/${id}`,
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
                url: "{{ url('users/delete') }}"+`/${id}`,
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
            $("#formErrors1").html('')
            $.ajax({
                url: "{{ url('users/edit') }}"+`/${id}`,
                type: 'GET',
                success: function(response) {
                    if (response.status) {
                        const user = response.data;                    
                        // Populate the form fields with the fetched data
                        $('#edit_name').val(user.person_name || '');
                        $('#edit_user_id').val(user.user_id || '');
                        $('#edit_gender').val(user.gender || '');
                        $('#edit_dob').val(user.dob || '');
                        $('#edit_email').val(user.email || '');
                        $('#edit_phone').val(user.phone || '');
                        $('#edit_signature_text').val(user.signature_text || '');
                        // $('#edit_speciality_id').val(user.speciality_id || '');
                        // $('#edit_role_id').val(user.role_id || '');
                        populateRolesDropdown(response);
                        populateSpecialityDropdown(response);
                        // Show the modal
                        $("#editModal").modal('show');
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Failed to fetch user data. Please try again.');
                }
            });
        }

        function populateRolesDropdown(response) {
            const roleDropdown = $('#edit_role_id');
            // Clear existing options
            roleDropdown.empty();
            // Add a default "placeholder" option
            roleDropdown.append('<option value="">--Designation / Role--</option>');
            
            // Extract specialities from the response
            const roles = response.getAllActiveRoles; // Assuming response contains this array

            // Iterate over the roles and populate the dropdown
            roles.forEach(function(item) {
                console.log("Item role ID:", item.id);
                console.log("Response role ID:", response.data.role_id);

                // Create the option element dynamically
                const option = `<option value="${item.id}" ${item.id == response.data.role_id ? 'selected' : ''}>${item.name.charAt(0).toUpperCase() + item.name.slice(1)}</option>`;

                // Append the option to the dropdown
                roleDropdown.append(option);
            });
        }

        function populateSpecialityDropdown(response) {
            const specialityDropdown = $('#edit_speciality_id');
            
            // Clear existing options
            specialityDropdown.empty();
            
            // Add a default "placeholder" option
            specialityDropdown.append('<option value="">--Speciality--</option>');
            
            // Extract specialities from the response
            const specialities = response.getAllActiveSpeciality; // Assuming response contains this array

            // Iterate over the specialities and populate the dropdown
            specialities.forEach(function(item) {
                // console.log("Item ID:", item.id);
                // console.log("Response Speciality ID:", response.data.speciality_id);

                // Create the option element dynamically
                const option = `<option value="${item.id}" ${item.id == response.data.speciality_id ? 'selected' : ''}>${item.name.charAt(0).toUpperCase() + item.name.slice(1)}</option>`;

                // Append the option to the dropdown
                specialityDropdown.append(option);
            });

            // Optional: Set the selected value explicitly if `response.selected_speciality_id` exists
            // if (response.selected_speciality_id) {
            //     specialityDropdown.val(response.selected_speciality_id);
            // }
        }

        function updateData(){
            let formData = new FormData($('#editForm')[0]);
            $.ajax({
                url: "{{ route('users_update') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.success) {
                        toastr['success'](response.message); // Show success message
                        $('#editModal').modal('hide'); // Hide modal
                        $('#editForm')[0].reset(); // Reset the form
                        table.ajax.reload(); // Reload DataTable
                    } else {
                        toastr['error'](response.message); // Show error message
                    }
                },
                error: function (xhr) {
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

                        // Display errors in the modal
                        $('#formErrors1').html(errorMessages).show();
                    }
                    if (xhr.status == 500) {
                        $('#formErrors1').html(`<div class="alert alert-danger alert-dismissible" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                    <i class="fa fa-times-circle"></i> Internal Server Error.
                                                </div>`);
                    }
                }
            });
        };
    
        function showPermissionRow(id){
            $.ajax({
                url: "{{ url('users/permissions') }}"+`/${id}`,
                type: 'GET',
                success: function(response) {
                    if (response.status) {
                        $("#permission_user_id").val(id)
                        const permissionList = $('.permission_append');
                            // Clear existing options
                            permissionList.empty();
                            // Extract specialities from the response
                            const permissionLists = response.getAllActivePermissions; 
                            const permission_ids = response.getAllPermissionIds;
                            // Iterate over the permissionLists and populate the dropdown
                            permissionLists.forEach(function(permission) {
                            const html =  `<div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input 
                                                    type="checkbox" 
                                                    class="form-check-input" 
                                                    id="permission-${permission.id}" 
                                                    name="permissions[]" ${permission_ids.includes(permission.id) ? "checked" : ""}                                                     value="${permission.id}">
                                                    <label class="form-check-label" for="permission-${permission.id}">
                                                        ${permission.name}
                                                    </label>
                                                    <p class="small mb-0">${permission.description}</p>    
                                                </div>
                                            </div>`
                                // Append the option to the dropdown
                                permissionList.append(html);
                            });
                        // Show the modal
                        $("#permissionModal").modal('show');
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Failed to fetch user data. Please try again.');
                }
            });
        }

        function savePermissions() {
            const userId = $('#permission_user_id').val(); // Get user ID
            const selectedPermissions = [];            
            // Collect selected permissions
            $('input[name="permissions[]"]:checked').each(function () {
                selectedPermissions.push($(this).val());
            });
            // Send AJAX request
            $.ajax({
                url: "{{ url('users/permissions/save') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // Add CSRF token
                    user_id: userId,
                    permissions: selectedPermissions,
                },
                success: function (response) {
                    if (response.success) {
                        toastr['success'](response.message); // Show success message
                        $('#permissionModal').modal('hide'); // Hide modal
                        table.ajax.reload(); // Reload DataTable
                    } else {
                        toastr['error'](response.message); // Show error message
                        alert('Failed to save permissions. Please try again.');
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    alert('An error occurred while saving permissions.');
                }
            });
        }
    </script>
@endsection
@endsection