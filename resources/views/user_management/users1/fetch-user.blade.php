<h2>Details</h2>
<hr>
<p class="float-md-right">ROLE:<span class="badge badge-success">{{getRole($user_info->id)}}</span></p>
<div class="media-object">
    <img src="../assets/images/xs/avatar1.jpg" alt="" width="35px" class="rounded-circle">
    <span>{{$user_info->name}}</span>
</div>
<hr>
<ul class="nav nav-tabs-new">
    <li class="nav-item m-1"><a class="nav-link show active" data-toggle="tab" href="#details">Details</a></li>
    <li class="nav-item m-1"><a class="nav-link" data-toggle="tab" href="#permissions">Permissions</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane show active" id="details">
        <div class="row clearfix">
            <div class="form-group m-2">
                <input type="text" class="form-control" placeholder="Name" name="name" value="{{$user_info->name}}">
            </div>
            <div class="form-group m-2">
                <input type="text" class="form-control" placeholder="Username" name="username" value="{{$user_info->username}}">
            </div>
            <div class="form-group m-2">
                <input type="text" class="form-control" placeholder="Email" name="email" value="{{$user_info->email}}">
            </div>
            <div class="form-group m-2">
                <input type="text" class="form-control" placeholder="Phone" name="phone" value="{{$user_info->phone}}">
            </div>
            <div class="col-sm-12">
                <button disabled type="submit" class="btn btn-primary">Submit</button>
                <button type="submit" class="btn btn-outline-secondary">Cancel</button>
            </div>
        </div>
    </div>
    <div class="tab-pane" id="permissions">
        <div class="row clearfix">
            @forelse (getAllActivePermissions()  as $p => $permission)
                <div class="fancy-checkbox">
                    <label>
                        <input type="checkbox" name="permissions[]" {{ in_array($permission->id,$permission_ids) ? "checked" : ""}} value="{{ $permission->id }}">
                        <span>{{ $permission->name }}</span>
                    </label>
                    <p class="text-danger">{{$permission->description}}</p>
                </div>
            @empty
                <div class="fancy-checkbox">
                    <label class="text-danger">Please contact to admin</label>
                </div>
            @endforelse
        </div>
    </div>
</div>
