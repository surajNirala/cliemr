{{-- <div class="container-fluid">
    <div class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="{{ route('dashboard') }}"><i class="icon-home"></i> Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <!-- User Management Dropdown -->
                <li class="nav-item dropdown {{ Request::is('users*') || Request::is('role-permission*') || Request::is('roles*') || Request::is('permissions*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#" id="userManagementDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-users"></i> User Management
                    </a>
                    <div class="dropdown-menu" aria-labelledby="userManagementDropdown">
                        <a class="dropdown-item {{ Request::is('users*') ? 'active' : '' }}" href="{{ url('users') }}">Users</a>
                        <a class="dropdown-item {{ Request::is('role-permission*') ? 'active' : '' }}" href="{{ url('role-permission') }}">Role Permission</a>
                        <a class="dropdown-item {{ Request::is('roles*') ? 'active' : '' }}" href="{{ url('roles') }}">Roles</a>
                        <a class="dropdown-item {{ Request::is('permissions*') ? 'active' : '' }}" href="{{ url('permissions') }}">Permissions</a>
                    </div>
                </li>
    
                <!-- Custom Templates Dropdown -->
                <li class="nav-item dropdown {{ Request::is('custom-templates*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#" id="customTemplatesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-layers"></i> Custom Templates
                    </a>
                    <div class="dropdown-menu" aria-labelledby="customTemplatesDropdown">
                        <a class="dropdown-item {{ Request::is('custom-templates/quicknotes*') ? 'active' : '' }}" href="{{ url('custom-templates/quicknotes') }}">Quick Notes</a>
                        <a class="dropdown-item {{ Request::is('custom-templates/advice*') ? 'active' : '' }}" href="{{ url('custom-templates/advice') }}">Advice</a>
                        <a class="dropdown-item {{ Request::is('custom-templates/testprescribes*') ? 'active' : '' }}" href="{{ url('custom-templates/testprescribes') }}">Test Prescribes</a>
                    </div>
                </li>
    
                <!-- Medicine Management Dropdown -->
                <li class="nav-item dropdown {{ Request::is('medicinelibraries*') || Request::is('medicines*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#" id="medicineManagementDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-medkit"></i> Medicine Management
                    </a>
                    <div class="dropdown-menu" aria-labelledby="medicineManagementDropdown">
                        <a class="dropdown-item {{ Request::is('medicines*') ? 'active' : '' }}" href="{{ url('medicines') }}">Medicine</a>
                        <a class="dropdown-item {{ Request::is('medicinelibraries*') ? 'active' : '' }}" href="{{ url('medicinelibraries') }}">Medicine Library</a>
                    </div>
                </li>
    
                <!-- Other Static Links -->
                <li class="nav-item {{ Request::is('complaints*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('complaints') }}"><i class="icon-list"></i> Complaints Remembered</a>
                </li>
                <li class="nav-item {{ Request::is('diagnosis*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('diagnosis') }}"><i class="icon-list"></i> Diagnosis Remembered</a>
                </li>
                <li class="nav-item {{ Request::is('notes*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('notes') }}"><i class="icon-list"></i> Notes Remembered</a>
                </li>
            </ul>
        </div>
    </div>
</div> --}}
<div class="block-header">
    <div class="row">
        <div class="col-lg-6 col-md-8 col-sm-12">
            <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> {{ ucfirst(Request::segment(count(Request::segments()))) }}</h2>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="/"><i class="icon-home"></i></a></li>                            
                <li class="breadcrumb-item active">{{ Request::path() }}</li>
            </ul>
        </div>  
        @if (Auth::user()->flag == 1)         
        <div class="col-lg-6 col-md-4 col-sm-12 text-right">
            <div class="bh_chart hidden-xs">
                <div class="float-left m-r-15">
                    <small>Visitors</small>
                    <h6 class="mb-0 mt-1"><i class="icon-user"></i> 1,784</h6>
                </div>
                <span class="bh_visitors float-right">2,5,1,8,3,6,7,5</span>
            </div>
            <div class="bh_chart hidden-sm">
                <div class="float-left m-r-15">
                    <small>Visits</small>
                    <h6 class="mb-0 mt-1"><i class="icon-globe"></i> 325</h6>
                </div>
                <span class="bh_visits float-right">10,8,9,3,5,8,5</span>
            </div>
            <div class="bh_chart hidden-sm">
                <div class="float-left m-r-15">
                    <small>Chats</small>
                    <h6 class="mb-0 mt-1"><i class="icon-bubbles"></i> 13</h6>
                </div>
                <span class="bh_chats float-right">1,8,5,6,2,4,3,2</span>
            </div>
        </div> 
        @endif
    </div>
</div>
