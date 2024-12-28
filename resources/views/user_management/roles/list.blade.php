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
                        <button class="float-md-right btn btn-success btn-sm" onclick="showRoles()">Add Role</button>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover roles_table dataTable">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Designation / Role</th>
                                        <th>User Name</th>
                                        <th>Created At</th>
                                        <th>Status</th>
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

{{-- Model Create --}}
<div class="modal fade" id="createModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="createModalLabel">Add Role</h4>
            </div>
            <div id="formErrors"></div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Role Name</label>
                    <input type="text" class="form-control" name="name" id="name" required="">
                    <input type="hidden" class="form-control" name="role_id" id="role_id">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
                <button type="button" class="btn btn-primary" onclick="storeData()">SAVE</button>
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
                table = $('.roles_table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ url('getroles') }}",
                    // order: [[3, 'desc']],
                    //'role_id', 'person_name', 'role_name', 'status', 'created_at'
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
                        // { data: 'id', name: 'id',visible: "{{ Auth::user()->id == 1 ? true : false }}" },
                        { 
                            data:'role_name',
                            name:'role_name',
                            render: function(data, type, row){
                                return `<span class="badge badge-primary">${row.role_name}</span>`
                            }
                        },
                        { data: 'person_name', name: 'person_name' },
                        { 
                            data: 'created_at', 
                            name: 'created_at',
                            render: function (data, type, row) {
                                return moment(data).format('MMM DD, YYYY'); // "Dec 12, 2024"
                            }
                        },
                        { 
                            data: 'status', 
                            name: 'status',
                            visible:false,
                            render: function (data, type, row) {
                                return data == 1 ? 
                                `<button data-id="${row.role_id}" class="btn btn-sm badge badge-success" onclick="changeStatus(${row.role_id}, 0)"><i class="fa fa-toggle-on"></button>`:
                                `<button data-id="${row.role_id}" class="btn btn-sm badge badge-danger" onclick="changeStatus(${row.role_id}, 1)"><i class="fa fa-toggle-off"></button>`;
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
                                            onclick="changeStatus(${row.role_id}, 0)">
                                            <i class="fa fa-toggle-on"></i>
                                        </button>`;
                                } else {
                                    statusButton = `
                                        <button 
                                            title="Inactive" 
                                            class="btn btn-sm badge badge-danger" 
                                            onclick="changeStatus(${row.role_id}, 1)">
                                            <i class="fa fa-toggle-off"></i>
                                        </button>`;
                                }
                               

                               

                                return `
                                    <button title="Edit" class="btn btn-sm badge badge-success" onclick="editRow(${row.role_id}, 0)" ><i class="fa fa-pencil"></i></button>
                                    <button title="Delete" class="btn btn-sm badge badge-danger" onclick="deleteRow(${row.role_id})" ><i class="fa fa-trash"></i></button>

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

        function showRoles(){
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
                    let errorMessage = "Something went wrong. Please try again.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    toastr['error'](errorMessage);
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
                    let errorMessage = "Something went wrong. Please try again.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    toastr['error'](errorMessage);
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
                    let errorMessage = "Something went wrong. Please try again.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    toastr['error'](errorMessage);
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
                    let errorMessage = "Something went wrong. Please try again.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    swal("Error!", errorMessage, "error");
                }
            });
        }
    </script>
@endsection
@endsection