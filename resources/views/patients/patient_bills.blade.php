<div class="modal-body">
    <div id="billformErrors2" class="text-danger"></div>
    <form id="addBillForm" method="POST" action="#" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="patient_id" value="{{$patient->id}}" id="edit_patient_id">
            <div class="row clearfix">
                <div class="col-sm-3">
                    <label for="service">Service</label>
                    <select class="form-control show-tick" name="service_id" id="bill_service_id" onchange="serviceChange()">
                        <option value="">-Service-</option>
                        @foreach (getAllActiveService() as $key => $item)
                        <option value="{{$item->id}}">{{ucfirst($item->service)}}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="service_name" id="bill_service_name">
                </div>
                <div class="col-sm-3">
                    <label for="unit_price">Unit Price</label>
                    <input type="text" readonly class="form-control" name="unit_price" id="bill_unit_price">
                </div>
                <div class="col-sm-3">
                    <label for="discount">Discount</label>
                    <input type="text" readonly class="form-control" name="discount" id="bill_discount" value="0">
                </div>
                <div class="col-sm-3">
                    <label for="mode">Mode</label>
                    <select class="form-control show-tick" name="mode" id="bill_edit_mode">
                        {{-- <option value="">-Mode-</option> --}}
                        <option value="case">Case</option>
                    </select>
                </div>
                <div class="col-sm-3 mt-4">
                    <button type="button" class="btn btn-sm btn-success" onclick="addBill()">Add Bill</button>
                    <button title="refresh" type="button" class="btn btn-sm btn-primary" onclick="refresh()"><i class="fa fa-refresh"></i></button>
                </div>
            </div>
    </form>
    <div class="row">
        <div class="col-md-8">
            <div class="table-responsive mt-3">
                <table class="table table-hover bills_list1 dataTable table-custom">
                    <thead class="thead-dark">
                        <tr>
                            <th>Date</th>
                            <th>Bill#</th>
                            <th>Billed</th>
                            <th>Due</th>
                            <th>Refund</th>
                            <th>Service</th>
                            <th>Price</th>
                            <th>GST</th>
                            <th>Discount</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-4 bill_invoice" style="display: none">
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">Gross Bill Amount<span>14</span></li>
                <li class="list-group-item d-flex justify-content-between align-items-center">Discount<span>2</span></li>
                <li class="list-group-item d-flex justify-content-between align-items-center">Tax Amount<span>1</span></li>
                <li class="list-group-item d-flex justify-content-between align-items-center"><strong>Net Amount</strong><span>14</span></li>
                <li class="list-group-item d-flex justify-content-between align-items-center">Collected Amount<span>2</span></li>
                <li class="list-group-item d-flex justify-content-between align-items-center"><strong>Net Paid Amount</strong><span>14</span></li>
                <li class="list-group-item d-flex justify-content-between align-items-center"><strong>Balance Amount</strong><span>14</span></li>
            </ul>
            <ul class="nav nav-tabs-new2">
                <li class="nav-item"><a class="nav-link show active" data-toggle="tab" href="#Home-new2">DEPOSIT</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Profile-new2">DISCOUNT</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Contact-new2">REFUND</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane show active" id="Home-new2">
                    <form id="addNewAmountForm" method="POST" action="#" enctype="multipart/form-data">
                        @csrf
                        <div class="row clearfix">
                            <div class="col-md-6">
                                <label for="amount">Amount</label>
                                <input type="text" class="form-control" name="amount" id="add_amount" value="0">
                            </div>
                            <div class="col-md-6">
                                <label for="mode">Payment Mode</label>
                                <select class="form-control show-tick" name="mode" id="bill_edit_mode">
                                    <option value="case">Case</option>
                                </select>
                            </div>
                            <div class="col-md-12 mt-4">
                                <button type="button" class="btn btn-success btn-block" onclick="addAmountBill()">Add Deposit</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" id="Profile-new2">
                    <form id="discountForm" method="POST" action="#" enctype="multipart/form-data">
                        @csrf
                        <div class="row clearfix">
                            <div class="col-md-12">
                                <label for="amount">Amount</label>
                                <input type="text" class="form-control" name="amount" id="add_amount" value="0">
                            </div>
                            <div class="col-md-12 mt-4">
                                <button type="button" class="btn btn-success btn-block" onclick="discountAmount()">Edit Discount</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" id="Contact-new2">
                    <form id="addNewAmountForm" method="POST" action="#" enctype="multipart/form-data">
                        @csrf
                        <div class="row clearfix">
                            <div class="col-md-12">
                                <label class="control-inline fancy-checkbox">
                                    <input type="checkbox" checked name="checkbox2" required="" data-parsley-mincheck="2" data-parsley-errors-container="#error-checkbox2" data-parsley-multiple="checkbox2">
                                    <span>Give Refund & Add To Discount</span>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label for="amount">Amount</label>
                                <input type="text" class="form-control" name="amount" id="add_amount" value="0">
                            </div>
                            <div class="col-md-6">
                                <label for="mode">Refund Mode</label>
                                <select class="form-control show-tick" name="mode" id="bill_edit_mode">
                                    <option value="case">Case</option>
                                </select>
                            </div>
                            <div class="col-md-12 mt-4">
                                <button type="button" class="btn btn-success btn-block" onclick="addRefund()">Add Refund</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var table1;
    $(document).ready(function () {
        try {
            // Initialize DataTable
            table1 = $('.bills_list1').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{url('getbills')}}",
                    method: 'GET',
                    // data: function (d) {
                    //     d.patient_id = 3; // Pass additional parameter
                    // },
                    dataSrc: function (json) {
                        console.log('Server response:', json); // Debugging: log the response
                        return json.data;
                    },
                },
                columns: [
                    {
                        data: 'created_at',
                        title: 'Date',
                        name: 'created_at',
                        render: function (data, type, row) {
                            if (!data || data === null || data === "") {
                                return ""; 
                            }
                            return moment(data).format('MMM DD, YYYY');
                        }
                    },
                    { 
                        data: 'invoice', 
                        title: 'Bill #',
                        render:function(data,type,row){
                            return  `C-${row.invoice}`
                        } 
                    },
                    { 
                        data: 'unit_price', 
                        name: 'unit_price',
                        render: function(data, type, row){
                            return `<i class="fa icon-printer text-primary"></i> <span onclick="bill_invoice(${row.id})" class="btn btn-sm btn-outline-success">${row.unit_price}</span>`
                        }
                    },
                    {
                        data: 'due',
                        name: 'due',
                        render: function(data, type, row) {
                            // If 'due' doesn't exist or is null/undefined, default to 0
                            return data ? data : 0;
                        }
                    },
                    {
                        data: 'refund',
                        name: 'refund',
                        render: function(data, type, row) {
                            // If 'due' doesn't exist or is null/undefined, default to 0
                            return data ? data : 0;
                        }
                    },
                    {
                        data: 'service',
                        name: 'service',
                        render: function(data, type, row) {
                            // If 'due' doesn't exist or is null/undefined, default to 0
                            return data ? data : 0;
                        }
                    },
                    {
                        data: 'unit_price',
                        name: 'unit_price',
                        render: function(data, type, row) {
                            // If 'due' doesn't exist or is null/undefined, default to 0
                            return data ? data : 0;
                        }
                    },
                    {
                        data: 'gst',
                        name: 'gst',
                        render: function(data, type, row) {
                            // If 'due' doesn't exist or is null/undefined, default to 0
                            return data ? data : "";
                        }
                    },
                    {
                        data: 'discount',
                        name: 'discount',
                        render: function(data, type, row) {
                            // If 'due' doesn't exist or is null/undefined, default to 0
                            return data ? data : 0;
                        }
                    }
                ],
                drawCallback: function () {
                    console.log('Table redrawn');
                },
                error: function (xhr, error, code) {
                    console.error('Error fetching data:', error);
                },
            });
        } catch (error) {
            console.error('Error initializing DataTable:', error);
        }
    });

    function addBill() {
        // table.ajax.reload();
        // return 
            let edit_patient_id = $("#edit_patient_id").val()
            // alert(edit_patient_id)
            // return
            $("#billformErrors2").html(''); // Clear previous errors
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
                        table1.ajax.reload(); 
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
                        $('#billformErrors2').html(errorMessages).show();
                    }
                    if (xhr.status == 500) {
                        $('#billformErrors2').html(`<div class="alert alert-danger alert-dismissible" role="alert">
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
    
    function refresh(){
        table1.ajax.reload();
        $(".bill_invoice").hide()
    }

    function serviceChange() {
        const serviceId = document.getElementById("bill_service_id").value;
        if (serviceId) {
            // Make AJAX request
            fetch( "{{url('patients/service-details')}}"+`/${serviceId}`)
                .then(response => response.json())
                .then(data => {
                    // Populate the unit_price and discount fields
                    document.getElementById("bill_unit_price").value = data.unit_price || 0;
                    document.getElementById("bill_discount").value = data.discount || 0;
                    document.getElementById("bill_service_name").value = data.service || "";
                    
                })
                .catch(error => console.error("Error fetching service details:", error));
        } else {
            // Reset fields if no service is selected
            document.getElementById("bill_unit_price").value = 0;
            document.getElementById("bill_discount").value = 0;
            document.getElementById("bill_service_name").value = "";
        }
    }

    function bill_invoice(id){
        $(".bill_invoice").show()
    }
</script>