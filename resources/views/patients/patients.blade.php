@extends('layouts.master')
@section('content')
@section('perticular_page_css')
<link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatable/fixedeader/dataTables.fixedcolumns.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatable/fixedeader/dataTables.fixedheader.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/sweetalert/sweetalert.css') }}"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<style>
    td.details-control {
        background: url('../assets/images/details_open.png') no-repeat center center;
        cursor: pointer;
    }
    tr.shown td.details-control {
        background: url('../assets/images/details_close.png') no-repeat center center;
    }

    .page-loader-wrapper {
        display: show !important;
    }
    .page-loader-wrapper {
        display: none; /* Hidden by default */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.8); /* Slight transparent overlay */
        z-index: 1051; /* Ensure it's above the modal (Bootstrap modals use z-index 1050) */
    }

    .page-loader-wrapper .loader {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }
    .modal-extra-lg {
        max-width: 90%; /* Adjust as needed */
    }

</style>
@endsection

<div id="main-content">
    <div class="container-fluid">
        @include('common.block-header')
        <div class="row clearfix">
            <div class="card">
                <div class="header">
                    {{-- <h2>{{$title ? $title : "User Management"}}</h2> --}}
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        {{-- <div class="d-flex align-items-center">
                            <select class="form-control m-1" name="" id="">
                                <option value="">Choose</option>
                                <option value="today">Today</option>
                                <option value="Yesterday">Yesterday</option>
                                <option value="This Week">This Week</option>
                                <option value="Last Month">Last Month</option>
                                <option value="Last 3 Month">Last 3 Month</option>
                                <option value="Last 6 Month">Last 6 Month</option>
                            </select>
                            <select class="form-control m-1" name="" id="">
                                <option value="">All</option>
                                <option value="booked">Booked</option>
                                <option value="arrived">Arrived</option>
                                <option value="Reviewed">Reviewed</option>
                            </select>
                            <input type="date" id="startDate" name="startDate" class="form-control m-1">
                            <button type="button" class="btn btn-sm btn-outline-primary m-1">All</button>
                            <button type="button" class="btn btn-sm btn-outline-primary m-1">Booked</button>
                            <button type="button" class="btn btn-sm btn-outline-primary m-1">Arrived</button>
                            <button type="button" class="btn btn-sm btn-outline-primary m-1">Reviewed</button>
                            <button type="button" class="btn btn-sm btn-success m-1">
                                <i class="fa fa-search fa-sm"></i>
                            </button>
                            
                        </div> --}}
                        <!-- Right Section: Add Patient Button -->
                        <div>
                            <button type="button" class="btn btn-success btn-sm btn-filter m-1" onclick="showPatients()">Add Patient</button>
                        </div>
                    </div>          
                    {{-- <hr> --}}
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-hover patients_table dataTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>Phone</th>
                                    <th>Status</th>
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

{{-- Create Modal --}}
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document"> <!-- Added modal-lg for a larger popup -->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="title modal-title text-white" id="createModalLabel">Add Patient Information</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">                
                <h6>Patient Information</h6>
                <div id="formErrors" class="text-danger"></div>
                <form id="createForm" method="POST" action="#" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="row clearfix">
                            <div class="col-sm-4">
                                <label for="name">Name<span class="text-danger">*</span> </label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <select class="form-control" name="title" id="title">
                                            {{-- <option value=""></option> --}}
                                            @foreach (titleBeforName() as $key => $item)
                                                <option value="{{$key}}">{{$item}}</option>                                            
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="name">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="gender">Gender<span class="text-danger">*</span> </label>
                                <div class="form-group">
                                    <select class="form-control show-tick" name="gender" id="gender">
                                        <option value="">- Gender -</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="others">Others</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="age">Age/DOB<span class="text-danger">*</span> </label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" name="age" id="age" placeholder="Age">
                                    <div class="input-group-prepend">
                                        <select class="form-control" name="age_type" id="age_type">
                                            @foreach (ageType() as $key => $item)
                                                <option value="{{$item}}">{{$item}}</option>                                            
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="text" data-provide="datepicker" data-date-autoclose="true" class="form-control" name="dob" id="dob" placeholder="DOB">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="phone">Phone<span class="text-danger">*</span> </label>
                                <div class="form-group">
                                    <input type="number" class="form-control" name="phone" id="phone" placeholder="Phone">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="email">Email<span class="text-danger">*</span> </label>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                                </div>
                            </div>
                            
                            <div class="col-sm-4">
                                <label for="blood_group">Bloog Group</label>
                                <select class="form-control show-tick" name="blood_group" id="blood_group">
                                    <option value="">-Blood Group-</option>
                                    @foreach (bloodGroups() as $item)
                                    <option value="{{$item}}">{{ucfirst($item)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="language_id">Preffered Language</label>
                                <select class="form-control show-tick" name="language_id" id="language_id">
                                    <option value="">-Language-</option>
                                    @foreach (getAllActiveLanguage() as $key => $item)
                                    <option value="{{$item->id}}">{{ucfirst($item->name)}}</option>
                                    @endforeach
                                </select>
                            </div>
    
                            <div class="col-sm-4">
                                <label for="address">Address</label>
                                <div class="form-group">
                                    <textarea name="address" id="addres" cols="10" rows="2" class="form-control" placeholder="Address"></textarea>                                       
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="city">City</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="city" id="city" placeholder="City">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="pincode">Pincode</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="pincode" id="pincode" placeholder="Area/pincode">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="file">Image</label>
                                <div class="form-group">
                                    <input type="file" class="form-control" name="file" id="file">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h6>Bill Information <span class="text-danger">*</span> </h6>
                    <div class="modal-body">
                        <div class="row clearfix">
                            <div class="col-sm-3">
                                <label for="service">Service</label>
                                <select class="form-control show-tick" name="service_id" id="edit_service_id" onchange="serviceChange()">
                                    <option value="">-Service-</option>
                                    @foreach (getAllActiveService() as $key => $item)
                                    <option value="{{$item->id}}">{{ucfirst($item->service)}}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="service_name" id="service_name">
                            </div>
                            <div class="col-sm-3">
                                <label for="unit_price">Unit Price</label>
                                <input type="text" readonly class="form-control" name="unit_price" id="unit_price">
                            </div>
                            <div class="col-sm-3">
                                <label for="discount">Discount</label>
                                <input type="text" readonly class="form-control" name="discount" id="discount" value="0">
                            </div>
                            <div class="col-sm-3">
                                <label for="mode">Mode</label>
                                <select class="form-control show-tick" name="mode" id="edit_mode">
                                    <option value="">-Mode-</option>
                                    <option value="case">Case</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h6>Appointment Information <span class="text-danger">*</span> </h6>
                    <div class="modal-body">
                        <div class="row clearfix">
                            <div class="col-sm-3">
                                <label for="doctor">Doctor</label>
                                <select class="form-control show-tick" name="doctor_id" id="edit_doctor_id" onchange="doctorChange()">
                                    @foreach (getAllActiveUsers() as $key => $item)
                                    @if ($item->id == 2)
                                    <option value="{{$item->id}}">{{ucfirst($item->name)}}</option>    
                                    @endif
                                    @endforeach
                                </select>
                                <input type="hidden" name="doctor_name" id="doctor_name">
                            </div>
                            <div class="col-sm-3">
                                <label for="date_time">Date</label>
                                <div class="input-group date" data-date-autoclose="true" data-date-format="dd/mm/yyyy" data-provide="datepicker">
                                    <input type="text" class="form-control" readonly name="date" id="date">
                                    <div class="input-group-append">                                            
                                        <button class="btn btn-outline-secondary" type="button"><i class="fa fa-calendar"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label for="time">Time</label>
                                <div class="input-group date" data-date-autoclose="true">
                                    <input type="text" class="form-control" name="time" id="time_only" placeholder="Select time">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button">
                                            <i class="fa fa-clock-o"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label for="duration">Duration</label>
                                <select class="form-control show-tick" name="duration" id="edit_duration">
                                    @foreach (getAllDurations() as $key => $item)
                                        <option value="{{$item}}" {{$key == 2 ? "selected" : ""}} >{{$item}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label for="status">Status</label>
                                <select class="form-control show-tick" name="status" id="edit_status">
                                    @foreach (getAllPatientStatus() as $key => $item)
                                        <option value="{{$item}}">{{$item}}</option>
                                    @endforeach
                                </select>
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
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document"> <!-- Added modal-lg for a larger popup -->
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="title modal-title text-white" id="editModalLabel">Edit Patient Information</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="formErrors1" class="text-danger"></div>
            <div class="card patient_details">                
                
            </div>
        </div>
    </div>
</div>

{{-- Bills Modal --}}
<div class="modal fade" id="billModal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-extra-lg" role="document"> <!-- Added modal-lg for a larger popup -->
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="title modal-title text-white" id="billModalLabel">Bill Patient Information</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="formErrors2" class="text-danger"></div>
            <div class="card patient_bills">                
                
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
                table = $('.patients_table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ url('getpatients') }}",
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
                            data: 'image',
                            name: 'image',
                            render: function(data, type, row){
                                if(row.image){
                                    return `<a href="{{baseURL()}}${row.image}"><img src="{{baseURL()}}${row.image}" class="patients-img width30 m-r-15" alt="profile-image" width="50"></a>`
                                }else{
                                    return `<a href="{{baseURL()}}custom_data/default-image.jpg"><img src="{{baseURL()}}custom_data/default-image.jpg" class="patients-img width30 m-r-15" alt="profile-image"></a>`
                                }
                            },
                            
                        },
                        { 
                            data: 'name', 
                            name: 'name',
                            render: function(data, type, row){
                                return `<span class="text-success">${row.name}</span>`
                            }
                         },
                        { data: 'age', name: 'age' },
                        { 
                            data: 'gender', 
                            name: 'gender',
                            render: function(data, type, row){
                                return `${row.gender.charAt(0).toUpperCase() + row.gender.slice(1)}`
                            }
                        },
                        { 
                            data:'phone',
                            name:'phone',
                            // render: function(data, type, row){
                            //     return `<span class="badge badge-primary">${row.phone}</span>`
                            // }
                        },
                        { 
                            data:'flag',
                            name:'flag',
                            render: function(data, type, row){
                                let flagButton = '';
                                if (row.flag == 1) {
                                    flagButton = `
                                        <button 
                                            title="Active" 
                                            class="btn btn-sm btn-outline-primary" 
                                            onclick="changeFlag(${row.patient_id}, ${row.flag})">
                                            Booked
                                        </button>`;
                                }else if (row.flag == 2) {
                                    flagButton = `
                                        <button 
                                            title="Active" 
                                            class="btn btn-sm btn-outline-warning" 
                                            onclick="changeFlag(${row.patient_id}, ${row.flag})">
                                            Arrived
                                        </button>`;
                                } else {
                                    flagButton = `
                                        <button 
                                            title="Inactive" 
                                            class="btn btn-sm btn-outline-success" 
                                            onclick="changeFlag(${row.patient_id}, ${row.flag})">
                                            Reviewed
                                        </button>`;
                                }
                                return `${flagButton}`
                            }
                        },
                        { 
                            data: 'created_at', 
                            name: 'created_at',
                            orderable: true,
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
                                let appointmentButton = `
                                        <button 
                                            title="Appointment" 
                                            class="btn btn-sm btn-outline-warning" 
                                            onclick="appointmentButton(${row.patient_id}, 1)">
                                            <i class="fa icon-clock"></i>
                                        </button>`;
                                
                                let billButton = `
                                            <button 
                                                title="Bills" 
                                                class="btn btn-sm btn-outline-danger" 
                                                onclick="billButton(${row.patient_id}, 1)">
                                                <i class="fa icon-calculator"></i>
                                            </button>`;
                                if (row.status == 1) {
                                    statusButton = `
                                        <button disabled
                                            title="Active" 
                                            class="btn btn-sm btn-outline-success" 
                                            onclick="changeStatus(${row.patient_id}, 0)">
                                            <i class="fa fa-toggle-on"></i>
                                        </button>`;
                                } else {
                                    statusButton = `
                                        <button disabled
                                            title="Inactive" 
                                            class="btn btn-sm btn-outline-danger" 
                                            onclick="changeStatus(${row.patient_id}, 1)">
                                            <i class="fa fa-toggle-off"></i>
                                        </button>`;
                                }    
                                // <button title="View" class="btn btn-sm btn-outline-primary" onclick="showRow(${row.patient_id})" ><i class="fa fa-eye"></i></button>
                                                           
                                // ${appointmentButton}
                                return `
                                    ${billButton}
                                    <button title="Edit" class="btn btn-sm btn-outline-success" onclick="editRow(${row.patient_id})" ><i class="fa fa-pencil"></i></button>
                                    ${statusButton}
                                    <button disabled title="Delete" class="btn btn-sm btn-outline-danger" onclick="deleteRow(${row.patient_id})" ><i class="fa fa-trash"></i></button>
                                `;
                            }
                        },
                    ],
                    rowCallback: function (row, data, index) {
                        // Check the condition for danger class
                        // if (data.status != 1) {
                        //     $(row).addClass('table-danger');
                        // }
                        if (data.bill_count == 0) {
                            $(row).addClass('table-warning');
                        }
                    },
                });
            }catch (error) {
                console.error('Error initializing DataTable:', error);
            }

            flatpickr("#time_only", {
                enableTime: true,       // Enables time picker
                noCalendar: true,       // Hides the calendar
                dateFormat: "h:i K",    // Format for time (12-hour with AM/PM)
                time_24hr: false,       // Ensures 12-hour format
            });
        });

        $(document).ready(function (){            
            $('#createModal').on('hidden.bs.modal', function () {
            const hash = window.location.hash; 
            if (hash === '#Add' || hash === '#Bills' || hash === '#Appointment' || hash === '#Paid' || hash === '#Visits' ) {
                    history.pushState('', document.title, window.location.pathname); 
                }
            });
        })

        function showPatients(){
            $("#createModal").modal('show')
        }

        function storeData() {
            $("#formErrors").html(''); // Clear previous errors
            const formData = new FormData($('#createForm')[0]); // Get form data, including file input
            $.ajax({
                url: "{{ route('patients_store') }}",
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
                        // setTimeout(function () {
                        //     editRow($response.patient_id)
                        // }, 100); // Delay in milliseconds
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
                url: "{{ url('notes/change-status') }}"+`/${id}`,
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
            document.getElementById('formErrors1').innerHTML = '';
            $.ajax({
                url: "{{ url('patients/edit') }}"+`/${id}`,
                method: 'GET',
                success: function(response) {
                    if (response.success) {                       
                        console.log(response.data);
                        const patient = response.data
                        if(patient.image){
                            image_url = `<a href="{{baseURL()}}${patient.image}"><img src="{{baseURL()}}${patient.image}" class="patients-img width30 m-r-15" alt="profile-image" width="50"></a>`
                        }else{
                            image_url = `<a href="{{baseURL()}}custom_data/default-image.jpg"><img src="{{baseURL()}}custom_data/default-image.jpg" class="patients-img width30 m-r-15" alt="profile-image"></a>`
                        }
                       $("#editModalLabel").html(`${image_url} ${patient.name || 'Unknown Patient'} 
                       Information`);   
                        $('.patient_details').html(response.patient_details)                                           
                        $("#editModal").modal('show')
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

        function updateData() {
            $("#formErrors1").html(''); // Clear previous errors
            const formData = new FormData($('#editForm')[0]); // Get form data, including file input
            $.ajax({
                url: "{{ route('patients_update') }}",
                method: "POST",
                data: formData,
                processData: false, // Required for FormData
                contentType: false, // Required for FormData
                success: function (response) {
                    if (response.success) {
                        toastr['success'](response.message); 
                        table.ajax.reload(); 
                        $("#editModal").modal('hide')
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
                url: "{{ url('notes/delete') }}"+`/${id}`,
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

        function serviceChange() {
            const serviceId = document.getElementById("edit_service_id").value;
            if (serviceId) {
                // Make AJAX request
                fetch( "{{url('patients/service-details')}}"+`/${serviceId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Populate the unit_price and discount fields
                        document.getElementById("unit_price").value = data.unit_price || 0;
                        document.getElementById("discount").value = data.discount || 0;
                        document.getElementById("service_name").value = data.service || "";
                        
                    })
                    .catch(error => console.error("Error fetching service details:", error));
            } else {
                // Reset fields if no service is selected
                document.getElementById("unit_price").value = 0;
                document.getElementById("discount").value = 0;
                document.getElementById("service_name").value = "";
            }
        }

        function billButton(id) { 
            document.getElementById('formErrors2').innerHTML = '';
            $.ajax({
                url: "{{ url('patients/bills') }}"+`/${id}`,
                method: 'GET',
                success: function(response) {
                    if (response.success) {                       
                        const patient = response.data
                        const bills = response.bills
                        if(patient.image){
                            image_url = `<a href="{{baseURL()}}${patient.image}"><img src="{{baseURL()}}${patient.image}" class="patients-img width30 m-r-15" alt="profile-image" width="50"></a>`
                        }else{
                            image_url = `<a href="{{baseURL()}}custom_data/default-image.jpg"><img src="{{baseURL()}}custom_data/default-image.jpg" class="patients-img width30 m-r-15" alt="profile-image"></a>`
                        }
                       $("#billModalLabel").html(`${image_url} ${patient.name || 'Unknown Patient'} 
                       Bill Information`);   
                        $('.patient_bills').html(response.patient_bills)                                           
                        $("#billModal").modal('show')
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
    </script>
@endsection
@endsection