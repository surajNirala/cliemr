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
                    <textarea name="address" id="edit_address" cols="10" rows="2" class="form-control" placeholder="Address">{{$patient->address}}</textarea>                                       
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