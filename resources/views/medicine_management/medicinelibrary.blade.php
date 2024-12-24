@extends('layouts.master')
@section('content')
@section('perticular_page_css')
<link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatable/fixedeader/dataTables.fixedcolumns.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatable/fixedeader/dataTables.fixedheader.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/sweetalert/sweetalert.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/multi-select/css/multi-select.css') }}">
<style>
    td.details-control {
        background: url('../assets/images/details_open.png') no-repeat center center;
        cursor: pointer;
    }
    tr.shown td.details-control {
        background: url('../assets/images/details_close.png') no-repeat center center;
    }
    .multiselect-container {
        width: 100% !important; /* Match the button width */
        max-width: none; /* Remove any maximum width restriction */
    }
    select option[value=""] {
        color: #999; /* Gray color for placeholder */
        font-style: italic; /* Italicize placeholder */
    }

    .multiselect-container .multiselect-search {
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 8px;
        margin-bottom: 10px;
        width: 100%;
        font-size: 14px;
    }

    .multiselect-container .multiselect-search {
        width: calc(100% - 20px); /* Adjust width dynamically to fit the dropdown */
        margin-right: 10px; /* Adjust margin */
        padding-right: 10px; /* Adjust padding */
    }

    .glyphicon-search {
        display: none;
    }
    .glyphicon-remove-circle {
        margin-right: 10px; /* Adjust this value for spacing on the right */
        margin-left: 5px;  /* Optional: Add spacing on the left */
        /* margin-top: 25px;   */
        padding: 5px;     
        font-size: 14px;   /* Adjust icon size */
        vertical-align: middle; /* Align icon properly with surrounding text/input */
    }
</style>

<style>


/* Customize the multiselect button to match form-control style */
.multiselect {
    background-color: #fff !important;
    border: 1px solid #ccc !important;
    padding: 10px;
    border-radius: 5px;
    width: 100%;
}


/* Optional: style the dropdown items */
.multiselect-container .multiselect-item {
    background-color: #fff;
    border-bottom: 1px solid #f0f0f0;
}

.multiselect-container .multiselect-item:hover {
    background-color: #f8f9fa;
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
                        <button class="float-md-right btn btn-success btn-sm" onclick="showMedicineLibrary()">Add Medicine Library</button>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover medicines_table dataTable">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Type</th>
                                        <th>Medicine Name</th>
                                        <th>Generic Name</th>
                                        <th>Dosage</th>
                                        <th>Administration</th>
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

{{-- Model Create --}}
<div class="modal fade" id="createModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document"> <!-- Added modal-lg for a larger popup -->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="createModalLabel">Add Medicine Library</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createMedicineForm" method="POST" action="#" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <div class="row clearfix">
                        <div class="col-sm-6">
                            <select class="form-control show-tick" name="medicine_type_id" id="medicine_type_id">
                                <option value="">-- Select Type --</option>
                                @foreach (getAllActiveMedicineType() as $item)
                                <option value="{{$item->id}}">{{ucfirst($item->name)}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select class="form-control show-tick" name="medicine_id" id="medicine_id">
                                    <option value="">-Select Medicine-</option>
                                    @foreach (getAllActiveMedicines() as $item)
                                    <option value="{{$item->id}}">{{ucfirst($item->title)}}</option>
                                    @endforeach
                                </select>                                        
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="col-md-12 row">
                                <div class="form-group d-flex align-items-center gap-2">
                                    <input type="number" class="form-control form-control-lg mr-1" name="dosage1" id="dosage1" placeholder="Dosage">
                                    <input type="number" class="form-control form-control-lg mr-1" name="dosage2" id="dosage2" placeholder="Dosage">
                                    <input type="number" class="form-control form-control-lg mr-1" name="dosage3" id="dosage3" placeholder="Dosage">
                                    {{-- <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></button> --}}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select class="form-control show-tick" name="medicine_administration_id" id="medicine_administration_id">
                                    <option value="">-Select Administration-</option>
                                    @foreach (getAllActiveMedicineAdministration() as $item)
                                    <option value="{{$item->id}}">{{ucfirst($item->name)}}</option>
                                    @endforeach
                                </select>                                        
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" name="unit" id="unit" placeholder="Unit">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control show-tick" name="time" id="time">
                                <option value="">-Select Time-</option>
                                <option value="5 mins">5 mins</option>
                                <option value="10 mins">10 mins</option>
                                <option value="15 mins">15 mins</option>
                                <option value="20 mins">20 mins</option>
                                <option value="30 mins">30 mins</option>
                                <option value="45 mins">45 mins</option>
                                <option value="1 hr">1 hr</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control show-tick" name="where" id="where">
                                <option value="">-Select Where-</option>
                                <option value="1">On Scalp</option>
                                <option value="2">On Face</option>
                                <option value="3">On Burns</option>
                                <option value="4">On Wounds</option>
                                <option value="5">On Warts</option>
                                <option value="6">On Pimples</option>
                                <option value="7">On Acne</option>
                                <option value="8">On Scare</option>
                                <option value="9">On Scabies</option>
                                <option value="10">On Itchy Areas</option>
                                <option value="11">On Rashes</option>
                                <option value="12">On Dark Areas</option>
                                <option value="13">On Dry Skin</option>
                                <option value="14">On Wet Skin</option>
                                <option value="15">On Ringworm</option>
                                <option value="16">On Wrinkles</option>
                                <option value="17">On Stretch Marks</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" name="generic_name" id="generic_name" placeholder="Generic Name">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control show-tick" name="frequency" id="frequency">
                                <option value="">-Select Frequency-</option>
                                <option value="Daily">Daily</option>
                                <option value="Alternate Day">Alternate Day</option>
                                <option value="Weekly">Weekly</option>
                                <option value="Weekly Twice">Weekly Twice</option>
                                <option value="Weekly Thrice">Weekly Thrice</option>
                                <option value="Fort Night">Fort Night</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control show-tick" name="duration" id="duration">
                                <option value="">-Select Duration-</option>
                                <option value="9 days">9 days</option>
                                <option value="9 weeks">9 weeks</option>
                                <option value="9 months">9 months</option>
                            </select>
                        </div>
                        <div class="col-sm-6 mt-3">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" name="quantity" id="quantity" placeholder="Quantity">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <textarea rows="3" class="form-control no-resize" name="notes" id="notes" placeholder="Notes"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="storeData()">Save</button>
                </div>
            </form>
            <div id="formErrors" class="text-danger"></div>
        </div>
    </div>
</div>

{{-- Model Edit --}}
<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document"> <!-- Added modal-lg for a larger popup -->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="editModalLabel">Add Medicine Library</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editMedicineForm" method="POST" action="#" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="medicinelibrary_id" id="medicinelibrary_id">
                    <div class="row clearfix">
                        <div class="col-sm-6">
                            <select class="form-control show-tick" name="medicine_type_id" id="edit_medicine_type_id">
                                <option value="">-- Select Type --</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select class="form-control show-tick" name="medicine_id" id="edit_medicine_id">
                                    <option value="">-Select Medicine-</option>
                                </select>                                        
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="col-md-12 row">
                                <div class="form-group d-flex align-items-center gap-2">
                                    <input type="number" class="form-control form-control-lg mr-1" name="dosage1" id="edit_dosage1" placeholder="Dosage1">
                                    <input type="number" class="form-control form-control-lg mr-1" name="dosage2" id="edit_dosage2" placeholder="Dosage2">
                                    <input type="number" class="form-control form-control-lg mr-1" name="dosage3" id="edit_dosage3" placeholder="Dosage3">
                                    {{-- <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></button> --}}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select class="form-control show-tick" name="medicine_administration_id" id="edit_medicine_administration_id">
                                    <option value="">-Select Administration-</option>
                                </select>                                        
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" name="unit" id="edit_unit" placeholder="Unit">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control show-tick" name="time" id="edit_time">
                                <option value="">-Select Time-</option>
                                <option value="5 mins">5 mins</option>
                                <option value="10 mins">10 mins</option>
                                <option value="15 mins">15 mins</option>
                                <option value="20 mins">20 mins</option>
                                <option value="30 mins">30 mins</option>
                                <option value="45 mins">45 mins</option>
                                <option value="1 hr">1 hr</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control show-tick" name="where" id="edit_where">
                                <option value="">-Select Where-</option>
                                <option value="1">On Scalp</option>
                                <option value="2">On Face</option>
                                <option value="3">On Burns</option>
                                <option value="4">On Wounds</option>
                                <option value="5">On Warts</option>
                                <option value="6">On Pimples</option>
                                <option value="7">On Acne</option>
                                <option value="8">On Scare</option>
                                <option value="9">On Scabies</option>
                                <option value="10">On Itchy Areas</option>
                                <option value="11">On Rashes</option>
                                <option value="12">On Dark Areas</option>
                                <option value="13">On Dry Skin</option>
                                <option value="14">On Wet Skin</option>
                                <option value="15">On Ringworm</option>
                                <option value="16">On Wrinkles</option>
                                <option value="17">On Stretch Marks</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" name="generic_name" id="edit_generic_name" placeholder="Generic Name">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control show-tick" name="frequency" id="edit_frequency">
                                <option value="">-Select Frequency-</option>
                                <option value="Daily">Daily</option>
                                <option value="Alternate Day">Alternate Day</option>
                                <option value="Weekly">Weekly</option>
                                <option value="Weekly Twice">Weekly Twice</option>
                                <option value="Weekly Thrice">Weekly Thrice</option>
                                <option value="Fort Night">Fort Night</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control show-tick" name="duration" id="edit_duration">
                                <option value="">-Select Duration-</option>
                                <option value="9 days">9 days</option>
                                <option value="9 weeks">9 weeks</option>
                                <option value="9 months">9 months</option>
                            </select>
                        </div>
                        <div class="col-sm-6 mt-3">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" name="quantity" id="edit_quantity" placeholder="Quantity">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group mt-2">
                                <textarea rows="3" class="form-control no-resize" name="notes" id="edit_notes" placeholder="Notes"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateData()">Update</button>
                </div>
            </form>
            <div id="formErrors1" class="text-danger"></div>
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
    
    <script src="{{ asset('assets/vendor/multi-select/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js') }}"></script>
    
    <script>
        var table;
        $(document).ready(function () {
            try{
                table = $('.medicines_table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ url('getmedicinelibraries') }}",
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
                            data: 'medicine_type_name', 
                            name: 'medicine_type_name',
                        },
                        { 
                            data:'medicine_name',
                            name:'medicine_name',
                        },
                        { 
                            data:'generic_name',
                            name:'generic_name',
                        },
                        { 
                            data:'dosage1',
                            name:'dosage1',
                            render: function(data, type, row){
                                return `<span class="badge badge-primary">${row.dosage1}</span>
                                        -
                                        <span class="badge badge-primary">${row.dosage2}</span>
                                        -
                                        <span class="badge badge-primary">${row.dosage3}</span>
                                        `
                            }
                        },
                        { 
                            data:'medicine_administration_name',
                            name:'medicine_administration_name',
                        },
                        /* { 
                            data:'dosage',
                            name:'dosage',
                            render: function(data, type, row){
                                return `<span class="badge badge-primary">${row.dosage1}</span>
                                        -
                                        <span class="badge badge-primary">${row.dosage2}</span>
                                        -
                                        <span class="badge badge-primary">${row.dosage3}</span>
                                        `
                            }
                        }, */
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
                                            onclick="changeStatus(${row.medicinelibrary_id}, 0)">
                                            <i class="fa fa-toggle-on"></i>
                                        </button>`;
                                } else {
                                    statusButton = `
                                        <button 
                                            title="Inactive" 
                                            class="btn btn-sm badge badge-danger" 
                                            onclick="changeStatus(${row.medicinelibrary_id}, 1)">
                                            <i class="fa fa-toggle-off"></i>
                                        </button>`;
                                }
                               

                               

                                return `
                                    <button title="Edit" class="btn btn-sm badge badge-success" onclick="editRow(${row.medicinelibrary_id}, 0)" ><i class="fa fa-pencil"></i></button>
                                    <button title="Delete" class="btn btn-sm badge badge-danger" onclick="deleteRow(${row.medicinelibrary_id})" ><i class="fa fa-trash"></i></button>

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

        function showMedicineLibrary(){
            $("#formErrors").html('')
            $("#createModalLabel").text("Add Medicine Library")
            $('#createMedicineForm')[0].reset(); // Reset the form
            $("#createModal").modal('show')
        }

        function storeData() {
            $("#formErrors").html(''); // Clear previous errors
            const formData = new FormData($('#createMedicineForm')[0]); // Get form data, including file input

            $.ajax({
                url: "{{ route('medicinelibraries_store') }}",
                method: "POST",
                data: formData,
                processData: false, // Required for FormData
                contentType: false, // Required for FormData
                success: function (response) {
                    if (response.success) {
                        toastr['success'](response.message); // Show success message
                        $('#createMedicineForm')[0].reset(); // Reset the form
                        $('#createModal').modal('hide'); // Hide modal
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
                url: "{{ url('medicinelibraries/change-status') }}"+`/${id}`,
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
            document.getElementById('formErrors1').innerHTML = '';
            $("#editModalLabel").text("Edit Medicine Library")
            $.ajax({
                url: "{{ url('medicinelibraries/edit') }}"+`/${id}`,
                method: 'GET',
                success: function(response) {
                    if (response.success) {   
                        const medicinelibrary = response.data;           
                        $('#medicinelibrary_id').val(id); 
                        $('#edit_dosage1').val(medicinelibrary.dosage1 || '')
                        $('#edit_dosage2').val(medicinelibrary.dosage2 || '')
                        $('#edit_dosage3').val(medicinelibrary.dosage3 || '')
                        $('#edit_unit').val(medicinelibrary.unit || '')
                        $('#edit_quantity').val(medicinelibrary.quantity || '')
                        $('#edit_generic_name').val(medicinelibrary.generic_name || '')
                        $('#edit_notes').val(medicinelibrary.notes || '')                          
                        getAllActiveMedicineType(response);
                        getAllActiveMedicines(response);
                        getAllActiveMedicineAdministration(response);               
                        $("#editModal").modal('show')
                    } else {
                        toastr['error'](response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + error);
                }
            });
        }

        function getAllActiveMedicineType(response){
            const itemDropdown = $('#edit_medicine_type_id');
            // Clear and destroy existing multiselect
            // if (itemDropdown.data('multiselect')) {
            //     itemDropdown.multiselect('destroy');
            // }
            // Clear existing options
            itemDropdown.empty();
            // Add a default "placeholder" option
            itemDropdown.append('<option value="">-- Select Type --</option>');

            // Extract specialities from the response
            const items = response.getAllActiveMedicineType; // Assuming response contains this array

            // Iterate over the items and populate the dropdown
            items.forEach(function(item) {
                // Create the option element dynamically
                const option = `<option value="${item.id}" ${item.id == response.data.medicine_type_id ? 'selected' : ''}>${item.name.charAt(0).toUpperCase() + item.name.slice(1)}</option>`;

                // Append the option to the dropdown
                itemDropdown.append(option);
            });

             // Reinitialize multiselect
            itemDropdown.multiselect({
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                maxHeight: 500,
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: '-- Select Type --',
                onDropdownShow: function () {
                    const buttonWidth = $('#edit_medicine_type_id').siblings('.multiselect').outerWidth();
                    $('.multiselect-container').css('width', buttonWidth + 'px');
                }
            });
        }

        function getAllActiveMedicines(response){
            const itemDropdown = $('#edit_medicine_id');
            // Clear existing options
            itemDropdown.empty();
            // Add a default "placeholder" option
            itemDropdown.append('<option value="">-- Select Medicine --</option>');

            // Extract specialities from the response
            const items = response.getAllActiveMedicines; // Assuming response contains this array

            // Iterate over the items and populate the dropdown
            items.forEach(function(item) {
                // Create the option element dynamically
                const option = `<option value="${item.id}" ${item.id == response.data.medicine_id ? 'selected' : ''}>${item.title.charAt(0).toUpperCase() + item.title.slice(1)}</option>`;

                // Append the option to the dropdown
                itemDropdown.append(option);
            });
            // Reinitialize multiselect
            itemDropdown.multiselect({
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                maxHeight: 500,
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: '-- Select Type --',
                onDropdownShow: function () {
                    const buttonWidth = $('#edit_medicine_id').siblings('.multiselect').outerWidth();
                    $('.multiselect-container').css('width', buttonWidth + 'px');
                }
            });
        }

        function getAllActiveMedicineAdministration(response){
            const itemDropdown = $('#edit_medicine_administration_id');
            // Clear existing options
            itemDropdown.empty();
            // Add a default "placeholder" option
            itemDropdown.append('<option value="">-- Select Medicine Administration --</option>');

            // Extract specialities from the response
            const items = response.getAllActiveMedicineAdministration; // Assuming response contains this array

            // Iterate over the items and populate the dropdown
            items.forEach(function(item) {
                // Create the option element dynamically
                const option = `<option value="${item.id}" ${item.id == response.data.medicine_administration_id ? 'selected' : ''}>${item.name.charAt(0).toUpperCase() + item.name.slice(1)}</option>`;
                // Append the option to the dropdown
                itemDropdown.append(option);
            });
            // Reinitialize multiselect
            itemDropdown.multiselect({
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                maxHeight: 500,
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: '-- Select Type --',
                onDropdownShow: function () {
                    const buttonWidth = $('#edit_medicine_administration_id').siblings('.multiselect').outerWidth();
                    $('.multiselect-container').css('width', buttonWidth + 'px');
                }
            });
        }


        function updateData() {
            $("#formErrors1").html(''); // Clear previous errors
            const formData = new FormData($('#editMedicineForm')[0]); // Get form data, including file input

            $.ajax({
                url: "{{ route('medicinelibraries_update') }}",
                method: "POST",
                data: formData,
                processData: false, // Required for FormData
                contentType: false, // Required for FormData
                success: function (response) {
                    if (response.success) {
                        toastr['success'](response.message); // Show success message
                        $('#editModal').modal('hide'); // Hide modal
                        $('#editMedicineForm')[0].reset(); // Reset the form
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
                url: "{{ url('medicinelibraries/delete') }}"+`/${id}`,
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

        $(function () {
            $('#medicine_type_id').multiselect({
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                maxHeight: 500,
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: '-- Select Type --', // Placeholder text
                // allSelectedText: 'All Roles Selected',
                onDropdownShow: function () {
                    // Dynamically adjust dropdown width to match the button
                    const buttonWidth = $('#medicine_type_id').siblings('.multiselect').outerWidth();
                    $('.multiselect-container').css('width', buttonWidth + 'px');
                }
            });
            
            $('#medicine_id').multiselect({
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                maxHeight: 500,
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: '-- Select Medicine --', // Placeholder text
                // allSelectedText: 'All Roles Selected',
                onDropdownShow: function () {
                    // Dynamically adjust dropdown width to match the button
                    const buttonWidth = $('#medicine_id').siblings('.multiselect').outerWidth();
                    $('.multiselect-container').css('width', buttonWidth + 'px');
                }
            });

            $('#medicine_administration_id').multiselect({
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                maxHeight: 500,
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: '-- Select Administration --', // Placeholder text
                // allSelectedText: 'All Roles Selected',
                onDropdownShow: function () {
                    // Dynamically adjust dropdown width to match the button
                    const buttonWidth = $('#medicine_administration_id').siblings('.multiselect').outerWidth();
                    $('.multiselect-container').css('width', buttonWidth + 'px');
                }
            });

            $('#time').multiselect({
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                maxHeight: 500,
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: '-- Select Time --', // Placeholder text
                // allSelectedText: 'All Roles Selected',
                onDropdownShow: function () {
                    // Dynamically adjust dropdown width to match the button
                    const buttonWidth = $('#time').siblings('.multiselect').outerWidth();
                    $('.multiselect-container').css('width', buttonWidth + 'px');
                }
            });

            $('#where').multiselect({
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                maxHeight: 500,
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: '-- Select where --', // Placeholder text
                // allSelectedText: 'All Roles Selected',
                onDropdownShow: function () {
                    // Dynamically adjust dropdown width to match the button
                    const buttonWidth = $('#where').siblings('.multiselect').outerWidth();
                    $('.multiselect-container').css('width', buttonWidth + 'px');
                }
            });

            $('#frequency').multiselect({
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                maxHeight: 500,
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: '-- Select frequency --', // Placeholder text
                // allSelectedText: 'All Roles Selected',
                onDropdownShow: function () {
                    // Dynamically adjust dropdown width to match the button
                    const buttonWidth = $('#frequency').siblings('.multiselect').outerWidth();
                    $('.multiselect-container').css('width', buttonWidth + 'px');
                }
            });

            $('#duration').multiselect({
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                maxHeight: 500,
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: '-- Select duration --', // Placeholder text
                // allSelectedText: 'All Roles Selected',
                onDropdownShow: function () {
                    // Dynamically adjust dropdown width to match the button
                    const buttonWidth = $('#duration').siblings('.multiselect').outerWidth();
                    $('.multiselect-container').css('width', buttonWidth + 'px');
                }
            });
        });
    </script>
@endsection
@endsection