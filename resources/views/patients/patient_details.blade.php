<ul class="nav nav-tabs">
    <li class="nav-item"><a class="nav-link show active" data-toggle="tab" href="#Edit">Edit</a></li>
    <li class="nav-item bills_load"><a class="nav-link" data-toggle="tab" href="#Bills">Bills</a></li>
    {{-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Appointment">Appointment</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Paid">Paid</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Visits">Visits</a></li> --}}
</ul>
<div class="tab-content">
    <div class="tab-pane show active" id="Edit">
        <form id="editForm" method="POST" action="#" enctype="multipart/form-data">
            <div class="modal-body">
                @csrf
                <input type="hidden" name="patient_id" value="{{$patient->id}}" id="edit_patient_id">
                <div class="row clearfix">
                    <div class="col-sm-6">
                        <label for="name">Name</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <select class="form-control" name="title" value="{{$patient->title}}" id="edit_title">
                                    {{-- <option value=""></option> --}}
                                    @foreach (titleBeforName() as $key => $item)
                                        <option value="{{$key}}" {{ $patient->title == $key ? 'selected' : ''}}>{{$item}}</option>                                            
                                    @endforeach
                                </select>
                            </div>
                            <input type="text" class="form-control" name="name" value="{{$patient->name}}" id="edit_name" placeholder="name">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label for="gender">Gender</label>
                        <div class="form-group">
                            <select class="form-control show-tick" name="gender" id="edit_gender">
                                <option value="">- Gender -</option>
                                <option {{ $patient->gender == 'male' ? 'selected' : ''}} value="male">Male</option>
                                <option {{ $patient->gender == 'female' ? 'selected' : ''}} value="female">Female</option>
                                <option {{ $patient->gender == 'others' ? 'selected' : ''}} value="others">Others</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label for="age">Age/DOB</label>
                        <div class="input-group mb-3">
                            <input type="number" class="form-control" name="age" value="{{$patient->age}}" id="edit_age" placeholder="Age">
                            <div class="input-group-prepend">
                                <select class="form-control" name="age_type" id="edit_age_type">
                                    @foreach (ageType() as $key => $item)
                                        <option value="{{$item}}" {{ $patient->age_type == $item ? 'selected' : ''}} >{{$item}}</option>                                            
                                    @endforeach
                                </select>
                            </div>
                            <input type="text" data-provide="datepicker" data-date-autoclose="true" class="form-control" name="dob" value="{{$patient->dob}}" id="edit_dob" placeholder="Date of Birth">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label for="phone">Phone</label>
                        <div class="form-group">
                            <input type="number" class="form-control" name="phone" value="{{$patient->phone}}" id="edit_phone" placeholder="Phone">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label for="email">Email</label>
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" value="{{$patient->email}}" id="edit_email" placeholder="Email">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <label for="address">Address</label>
                        <div class="form-group">
                            <textarea name="address" value="{{$patient->address}}" id="edit_address" cols="10" rows="2" class="form-control" placeholder="Address"></textarea>                                       
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label for="city">City</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="city" value="{{$patient->city}}" id="edit_city" placeholder="City">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label for="pincode">Pincode</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="pincode" value="{{$patient->pincode}}" id="edit_pincode" placeholder="Area/pincode">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label for="blood_group">Bloog Group</label>
                        <select class="form-control show-tick" name="blood_group" id="edit_blood_group">
                            <option value="">-Blood Group-</option>
                            @foreach (bloodGroups() as $item)
                            <option value="{{$item}}" {{$patient->blood_group == $item ? 'selected' : ''}} >{{ucfirst($item)}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label for="language_id">Preffered Language</label>
                        <select class="form-control show-tick" name="language_id" id="edit_language_id">
                            <option value="">-Language-</option>
                            @foreach (getAllActiveLanguage() as $key => $item)
                            <option value="{{$item->id}}" {{$patient->language_id == $item->id ? 'selected' : ''}}>{{ucfirst($item->name)}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="updateData()">Update</button>
            </div>
        </form>
    </div>
    <div class="tab-pane" id="Bills">
        <button class="btn btn-success collapsed" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            New Bill <i class="fa fa-plus"></i>
        </button>
        <div class="collapse" id="collapseExample" style="">
            <div class="card card-body">
                <div id="billformErrors" class="text-danger"></div>
                <form id="addBillForm" method="POST" action="#" enctype="multipart/form-data">
                        @csrf
                        <div class="row clearfix">
                            <div class="col-sm-6">
                                <label for="service">Service</label>
                                <select class="form-control show-tick" name="service_id" id="edit_service_id" onchange="serviceChange()">
                                    <option value="">-Service-</option>
                                    @foreach (getAllActiveService() as $key => $item)
                                    <option value="{{$item->id}}">{{ucfirst($item->service)}}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="service_name" id="service_name">
                            </div>
                            <div class="col-sm-6">
                                <label for="unit_price">Unit Price</label>
                                <input type="text" readonly class="form-control" name="unit_price" id="unit_price">
                            </div>
                            <div class="col-sm-6">
                                <label for="discount">Discount</label>
                                <input type="text" readonly class="form-control" name="discount" id="discount" value="0">
                            </div>
                            <div class="col-sm-6">
                                <label for="mode">Mode</label>
                                <select class="form-control show-tick" name="mode" id="edit_mode" onchange="serviceChange()">
                                    <option value="">-Mode-</option>
                                    <option value="case">Case</option>
                                </select>
                            </div>
                            <div class="col-sm-6 mt-4">
                                <button type="button" class="btn btn-primary" onclick="addBill()">Add Bill</button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
        <div class="table-responsive mt-3">
            <table class="table table-hover bills_list1 dataTable table-custom">
                <thead class="thead-dark">
                    <tr>
                        <th>Date</th>
                        <th>Invoice</th>
                        <th>Service Name</th>
                        <th>Unit Price</th>
                        <th>Discount</th>
                        <th>Due</th>
                        <th>Refund</th>
                        <th>Paid</th>
                        <th>GST</th>
                        <th>Mode</th>
                        {{-- <th>Action</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bills as $key => $bill)
                    <tr>
                        <td>{{ date("M d, Y", strtotime($bill->created_at)) }}</td>
                        <td>{{$bill->invoice}}</td>
                        <td>{{$bill->service}}</td>
                        <td>{{$bill->unit_price}}</td>
                        <td>{{$bill->discount}}</td>
                        <td>{{$bill->due ?? 0}}</td>
                        <td>0</td>
                        <td>{{$bill->unit_price}}</td>
                        <td>{{$bill->gst ?? '-'}}</td>
                        <td>{{strtoupper($bill->mode)}}</td>
                        {{-- <td>
                            <button title="Edit" class="btn btn-sm btn-outline-primary"><i class="fa fa-pencil"></i></button>
                            <button title="Delete" class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>       
                        </td> --}}
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
    <div class="tab-pane" id="Appointment">
        <h6>Contact</h6>
        <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS.</p>
    </div>
    <div class="tab-pane" id="Paid">
        <h6>Paid</h6>
        <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS.</p>
    </div>
    <div class="tab-pane" id="Visits">
        <h6>Vistis</h6>
        <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS.</p>
    </div>
</div>

<script>
    var table;
    $(document).ready(function () {
        try {
            table = $('.bills_list1').DataTable()
        } catch (error) {
            console.error('Error initializing DataTable:', error);
        }
    })

    // var table1;

    // $(document).ready(function () {
    //     $(".bills_load").off('click').on('click', function () {
    //         alert('Loading bills...');

    //         // Ensure edit_patient_id is defined
    //         if (!edit_patient_id) {
    //             alert('Error: Patient ID is not defined.');
    //             return;
    //         }

    //         // Destroy the DataTable if it already exists
    //         if ($.fn.dataTable.isDataTable('.bills_list1')) {
    //             $('.bills_list1').DataTable().destroy(); // Destroy existing table
    //             $('.bills_list1').empty(); // Clear table contents
    //         }

    //         try {
    //             // Initialize DataTable
    //             table1 = $('.bills_list1').DataTable({
    //                 processing: true,
    //                 serverSide: true,
    //                 ajax: {
    //                     url: "{{url('getbills')}}",
    //                     method: 'GET',
    //                     data: function (d) {
    //                         d.patient_id = edit_patient_id; // Pass additional parameter
    //                     },
    //                     dataSrc: function (json) {
    //                         console.log('Server response:', json); // Debugging: log the response
    //                         return json.data;
    //                     },
    //                 },
    //                 columns: [
    //                     { data: 'created_at', title: 'Date' },
    //                     { data: 'invoice', title: 'Invoice' },
    //                 ],
    //                 drawCallback: function () {
    //                     console.log('Table redrawn');
    //                 },
    //                 error: function (xhr, error, code) {
    //                     console.error('Error fetching data:', error);
    //                 },
    //             });
    //         } catch (error) {
    //             console.error('Error initializing DataTable:', error);
    //         }
    //     });
    // });




    function addBill() {
        // table.ajax.reload();
        // return 
            let edit_patient_id = $("#edit_patient_id").val()
            // alert(edit_patient_id)
            // return
            $("#billformErrors").html(''); // Clear previous errors
            const formData = new FormData($('#addBillForm')[0]); // Get form data, including file input
            formData.append('patient_id', edit_patient_id);
            $.ajax({
                url: "{{ route('bill_store') }}",
                method: "POST",
                data: formData,
                processData: false, // Required for FormData
                contentType: false, // Required for FormData
                success: function (response) {
                    if (response.success) {
                        toastr['success'](response.message); 
                        $('#addBillForm')[0].reset(); // Reset the form
                        // table1.ajax.reload(); 
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
                        $('#billformErrors').html(errorMessages).show();
                    }
                    if (xhr.status == 500) {
                        $('#billformErrors').html(`<div class="alert alert-danger alert-dismissible" role="alert">
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
</script>