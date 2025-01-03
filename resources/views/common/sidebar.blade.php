<div id="left-sidebar" class="sidebar">
    <div class="sidebar-scroll">
        <div class="user-account">
            <img src="../assets/images/user.png" class="rounded-circle user-photo" alt="User Profile Picture">
            <div class="dropdown">
                <span>Welcome, {{Auth::user()->name}}</span>
                <a href="javascript:void(0);" class="dropdown-toggle user-name" data-toggle="dropdown"><strong>{{getRole(Auth::user()->id)}}</strong></a>
                <ul class="dropdown-menu dropdown-menu-right account">
                    <li><a href="doctor-profile.html"><i class="icon-user"></i>My Profile</a></li>
                    <li><a href="app-inbox.html"><i class="icon-envelope-open"></i>Messages</a></li>
                    <li><a href="javascript:void(0);"><i class="icon-settings"></i>Settings</a></li>
                    <li class="divider"></li>
                    
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"><i class="icon-power"></i>Logout</a>
                        </form>
                    </li>
                </ul>
            </div>
            @if (Auth::user()->flag == 1)
            <hr>
            <ul class="row list-unstyled">
                <li class="col-4">
                    <small>Exp</small>
                    <h6>14</h6>
                </li>
                <li class="col-4">
                    <small>Awards</small>
                    <h6>13</h6>
                </li>
                <li class="col-4">
                    <small>Clients</small>
                    <h6>213</h6>
                </li>
            </ul>
            @endif
        </div>

        @if (Auth::user()->flag == 1)
        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#menu">Menu</a></li>                
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#sub_menu"><i class="icon-grid"></i></a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Chat"><i class="icon-book-open"></i></a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#setting"><i class="icon-settings"></i></a></li>                
        </ul>
        @endif
            
        <!-- Tab panes -->
        <div class="tab-content p-l-0 p-r-0">
            <div class="tab-pane active" id="menu">
                <nav class="sidebar-nav">
                    <ul class="main-menu metismenu">
                        <li><a href="{{ route('dashboard') }}"><i class="icon-home"></i><span>Dashboard</span></a></li>
                        
                        <li class="{{ Request::is('patients*') || Request::is('medicines*')  ? 'active' : '' }}">
                            <a href="javascript:void(0);" class="has-arrow" aria-expanded="true">
                                <i class="fa fa-plus-square"></i>
                                <span>Patient Management</span>
                            </a>
                            <ul aria-expanded="true" class="collapse {{ Request::is('patients*') || Request::is('medicines*')  ? 'in' : '' }}">
                                <li class="{{ Request::is('patients*') ? 'active' : '' }}">
                                    <a href="{{ url('patients') }}">Patients</a>
                                </li>
                            </ul>
                        </li>
                        <li class="{{ Request::is('users*') || Request::is('role-permission*') || Request::is('roles*') || Request::is('permissions*') ? 'active' : '' }}">
                            <a href="javascript:void(0);" class="has-arrow" aria-expanded="true">
                                <i class="icon-users"></i>
                                <span>User Management</span>
                            </a>
                            <ul aria-expanded="true" class="collapse {{ Request::is('users*') || Request::is('role-permission*') || Request::is('roles*') || Request::is('permissions*') ? 'in' : '' }}">
                                <li class="{{ Request::is('users*') ? 'active' : '' }}">
                                    <a href="{{ url('users') }}">Users</a>
                                </li>
                                <li class="{{ Request::is('role-permission*') ? 'active' : '' }}">
                                    <a href="{{ url('role-permission') }}">Role Permission</a>
                                </li>
                                <li class="{{ Request::is('roles*') ? 'active' : '' }}">
                                    <a href="{{ url('roles') }}">Roles</a>
                                </li>
                                <li class="{{ Request::is('permissions*') ? 'active' : '' }}">
                                    <a href="{{ url('permissions') }}">Permissions</a>
                                </li>
                            </ul>
                        </li>
                        <li class="{{ Request::is('custom-templates*')  ? 'active' : '' }}">
                            <a href="javascript:void(0);" class="has-arrow" aria-expanded="true">
                                <i class="icon-layers"></i>
                                <span>Custom Templates</span>
                            </a>
                            <ul aria-expanded="true" class="collapse {{ Request::is('custom-templates*') ? 'in' : '' }}">
                                <li class="{{ Request::is('custom-templates/quicknotes*') ? 'active' : '' }}">
                                    <a href="{{ url('custom-templates/quicknotes') }}">Quick Notes</a>
                                </li>
                                <li class="{{ Request::is('custom-templates/advice*') ? 'active' : '' }}">
                                    <a href="{{ url('custom-templates/advice') }}">Advice</a>
                                </li>
                                <li class="{{ Request::is('custom-templates/testprescribes*') ? 'active' : '' }}">
                                    <a href="{{ url('custom-templates/testprescribes') }}">Test Prescribes</a>
                                </li>
                            </ul>
                        </li>
                        <li class="{{ Request::is('complaints*') || Request::is('diagnosis*') || Request::is('notes*') ? 'active' : '' }}">
                            <a href="javascript:void(0);" class="has-arrow" aria-expanded="true">
                                <i class="fa fa-list"></i>
                                <span>Prescription</span>
                            </a>
                            <ul aria-expanded="true" class="collapse {{ Request::is('complaints*') || Request::is('diagnosis*') || Request::is('notes*') ? 'in' : '' }}">
                                <li class="{{ Request::is('complaints*') ? 'active' : '' }}">
                                    <a href="{{ url('complaints') }}">Complaints</a>
                                </li>
                                <li class="{{ Request::is('diagnosis*') ? 'active' : '' }}">
                                    <a href="{{ url('diagnosis') }}">Diagnosis</a>
                                </li>
                                <li class="{{ Request::is('notes*') ? 'active' : '' }}">
                                    <a href="{{ url('notes') }}">Notes</a>
                                </li>
                            </ul>
                        </li>
                        {{-- <li class="{{ Request::is('complaints*')  ? 'active' : '' }}">
                            <a href="{{ url('complaints') }}"><i class="icon-list"></i>Complaints Remembered</a>
                        </li>
                        <li class="{{ Request::is('diagnosis*')  ? 'active' : '' }}">
                            <a href="{{ url('diagnosis') }}"><i class="icon-list"></i>Diagnosis Remembered</a>
                        </li>
                        <li class="{{ Request::is('notes*')  ? 'active' : '' }}">
                            <a href="{{ url('notes') }}"><i class="icon-list"></i>Notes Remembered</a>
                        </li> --}}
                        <li class="{{ Request::is('medicinelibraries*') || Request::is('medicines*')  ? 'active' : '' }}">
                            <a href="javascript:void(0);" class="has-arrow" aria-expanded="true">
                                <i class="fa fa-medkit"></i>
                                <span>Medicine Management</span>
                            </a>
                            <ul aria-expanded="true" class="collapse {{ Request::is('medicinelibraries*') || Request::is('medicines*')  ? 'in' : '' }}">
                                <li class="{{ Request::is('medicines*') ? 'active' : '' }}">
                                    <a href="{{ url('medicines') }}">Medicine</a>
                                </li>
                                <li class="{{ Request::is('medicinelibraries*') ? 'active' : '' }}">
                                    <a href="{{ url('medicinelibraries') }}">Medicine Library</a>
                                </li>
                                {{-- 
                                <li class="{{ Request::is('roles*') ? 'active' : '' }}">
                                    <a href="{{ url('roles') }}">Roles</a>
                                </li>
                                <li class="{{ Request::is('permissions*') ? 'active' : '' }}">
                                    <a href="{{ url('permissions') }}">Permissions</a>
                                </li> --}}
                            </ul>
                        </li>
                        @if (Auth::user()->flag == 1)
                        <li><a href="app-appointment.html"><i class="icon-calendar"></i>Appointment</a></li>
                        <li><a href="app-taskboard.html"><i class="icon-list"></i>Taskboard</a></li>
                        <li><a href="app-inbox.html"><i class="icon-home"></i>Inbox App</a></li>
                        <li><a href="app-chat.html"><i class="icon-bubbles"></i>Chat App</a></li>
                        <li><a href="javascript:void(0);" class="has-arrow"><i class="icon-user-follow"></i><span>Doctors</span> </a>
                            <ul>
                                <li><a href="doctors-all.html">All Doctors</a></li>
                                <li><a href="doctor-add.html">Add Doctor</a></li>
                                <li><a href="doctor-profile.html">Doctor Profile</a></li>
                                <li><a href="doctor-events.html">Doctor Schedule</a></li>
                            </ul>
                        </li>
                        <li><a href="javascript:void(0);" class="has-arrow"><i class="icon-user"></i><span>Patients</span> </a>
                            <ul>
                                <li><a href="patients-all.html">All Patients</a></li>
                                <li><a href="patient-add.html">Add Patient</a></li>
                                <li><a href="patient-profile.html">Patient Profile</a></li>
                                <li><a href="patient-invoice.html">Invoice</a></li>
                            </ul>
                        </li>
                        <li><a href="javascript:void(0);" class="has-arrow"><i class="icon-wallet"></i><span>Payments</span> </a>
                            <ul>
                                <li><a href="payments.html">Payments</a></li>
                                <li><a href="payments-add.html">Add Payment</a></li>
                                <li><a href="payments-invoice.html">Invoice</a></li>
                            </ul>
                        </li>
                        <li><a href="javascript:void(0);" class="has-arrow"><i class="icon-layers"></i><span>Departments</span> </a>
                            <ul>
                                <li><a href="depa-add.html">Add</a></li>
                                <li><a href="depa-all.html">All Departments</a></li>
                                <li><a href="javascript:void(0);">Cardiology</a></li>
                                <li><a href="javascript:void(0);">Pulmonology</a></li>
                                <li><a href="javascript:void(0);">Gynecology</a></li>
                                <li><a href="javascript:void(0);">Neurology</a></li>
                                <li><a href="javascript:void(0);">Urology</a></li>
                                <li><a href="javascript:void(0);">Gastrology</a></li>
                                <li><a href="javascript:void(0);">Pediatrician</a></li>
                                <li><a href="javascript:void(0);">Laboratory</a></li>
                            </ul>
                        </li>
                        <li><a href="our-centres.html"><i class="icon-pointer"></i>WorldWide Centres</a></li>
                        <li>
                            <a href="#Authentication" class="has-arrow"><i class="icon-lock"></i><span>Authentication</span></a>
                            <ul>
                                <li><a href="page-login.html">Login</a></li>
                                <li><a href="page-register.html">Register</a></li>
                                <li><a href="page-lockscreen.html">Lockscreen</a></li>
                                <li><a href="page-forgot-password.html">Forgot Password</a></li>
                                <li><a href="page-404.html">Page 404</a></li>
                                <li><a href="page-403.html">Page 403</a></li>
                                <li><a href="page-500.html">Page 500</a></li>
                                <li><a href="page-503.html">Page 503</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#Widgets" class="has-arrow"><i class="icon-puzzle"></i><span>Widgets</span></a>
                            <ul>
                                <li><a href="widgets-statistics.html">Statistics Widgets</a></li>
                                <li><a href="widgets-data.html">Data Widgets</a></li>
                                <li><a href="widgets-chart.html">Chart Widgets</a></li>
                                <li><a href="widgets-weather.html">Weather Widgets</a></li>
                                <li><a href="widgets-social.html">Social Widgets</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#Pages" class="has-arrow"><i class="icon-docs"></i><span>Extra Pages</span></a>
                            <ul>
                                <li><a href="page-blank.html">Blank Page</a> </li>
                                <li><a href="doctor-profile.html">Profile</a></li>
                                <li><a href="page-gallery.html">Image Gallery <span class="badge badge-default float-right">v1</span></a> </li>
                                <li><a href="page-gallery2.html">Image Gallery <span class="badge badge-warning float-right">v2</span></a> </li>
                                <li><a href="page-timeline.html">Timeline</a></li>
                                <li><a href="page-timeline-h.html">Horizontal Timeline</a></li>
                                <li><a href="page-pricing.html">Pricing</a></li>
                                <li><a href="page-search-results.html">Search Results</a></li>
                                <li><a href="page-helper-class.html">Helper Classes</a></li>
                                <li><a href="page-maintenance.html">Maintenance</a></li>
                                <li><a href="page-testimonials.html">Testimonials</a></li>
                                <li><a href="page-faq.html">FAQs</a></li>
                            </ul>
                        </li>
                        @endif
                    </ul>
                </nav>
            </div>
            <div class="tab-pane" id="sub_menu">
                <nav class="sidebar-nav">
                    <ul class="main-menu metismenu">
                        <li>
                            <a href="#uiElements" class="has-arrow"><i class="icon-diamond"></i> <span>UI Elements</span></a>
                            <ul>
                                <li><a href="ui-typography.html">Typography</a></li>
                                <li><a href="ui-tabs.html">Tabs</a></li>
                                <li><a href="ui-buttons.html">Buttons</a></li>
                                <li><a href="ui-bootstrap.html">Bootstrap UI</a></li>
                                <li><a href="ui-icons.html">Icons</a></li>
                                <li><a href="ui-notifications.html">Notifications</a></li>
                                <li><a href="ui-colors.html">Colors</a></li>
                                <li><a href="ui-dialogs.html">Dialogs</a></li>                                    
                                <li><a href="ui-list-group.html">List Group</a></li>
                                <li><a href="ui-media-object.html">Media Object</a></li>
                                <li><a href="ui-modals.html">Modals</a></li>
                                <li><a href="ui-nestable.html">Nestable</a></li>
                                <li><a href="ui-progressbars.html">Progress Bars</a></li>
                                <li><a href="ui-range-sliders.html">Range Sliders</a></li>
                                <li><a href="ui-treeview.html">Treeview</a></li>
                            </ul>
                        </li>                            
                        <li>
                            <a href="#forms" class="has-arrow"><i class="icon-pencil"></i> <span>Forms</span></a>
                            <ul>
                                <li><a href="forms-validation.html">Form Validation</a></li>
                                <li><a href="forms-advanced.html">Advanced Elements</a></li>
                                <li><a href="forms-basic.html">Basic Elements</a></li>
                                <li><a href="forms-wizard.html">Form Wizard</a></li>                                    
                                <li><a href="forms-dragdropupload.html">Drag &amp; Drop Upload</a></li>                                    
                                <li><a href="forms-cropping.html">Image Cropping</a></li>
                                <li><a href="forms-summernote.html">Summernote</a></li>
                                <li><a href="forms-editors.html">CKEditor</a></li>
                                <li><a href="forms-markdown.html">Markdown</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#Tables" class="has-arrow"><i class="icon-tag"></i> <span>Tables</span></a>
                            <ul>
                                <li><a href="table-basic.html">Tables Example<span class="badge badge-info float-right">New</span></a> </li>
                                <li><a href="table-normal.html">Normal Tables</a> </li>
                                <li><a href="table-jquery-datatable.html">Jquery Datatables</a> </li>
                                <li><a href="table-editable.html">Editable Tables</a> </li>
                                <li><a href="table-color.html">Tables Color</a> </li>
                                <li><a href="table-filter.html">Table Filter <span class="badge badge-info float-right">New</span></a> </li>
                                <li><a href="table-dragger.html">Table dragger <span class="badge badge-info float-right">New</span></a> </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#charts" class="has-arrow"><i class="icon-bar-chart"></i> <span>Charts</span></a>
                            <ul>
                                <li><a href="chart-e.html">E Charts</a> </li>
                                <li><a href="chart-morris.html">Morris</a> </li>
                                <li><a href="chart-flot.html">Flot</a> </li>
                                <li><a href="chart-chartjs.html">ChartJS</a> </li>                                    
                                <li><a href="chart-jquery-knob.html">Jquery Knob</a> </li>                                        
                                <li><a href="chart-sparkline.html">Sparkline Chart</a></li>
                                <li><a href="chart-peity.html">Peity</a></li>
                                <li><a href="chart-c3.html">C3 Charts</a></li>
                                <li><a href="chart-gauges.html">Gauges</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#Maps" class="has-arrow"><i class="icon-map"></i> <span>Maps</span></a>
                            <ul>
                                <li><a href="map-google.html">Google Map</a></li>
                                <li><a href="map-yandex.html">Yandex Map</a></li>
                                <li><a href="map-jvectormap.html">jVector Map</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="tab-pane p-l-15 p-r-15" id="Chat">
                <form>
                    <div class="input-group m-b-20">
                        <div class="input-group-prepend">
                            <span class="input-group-text" ><i class="icon-magnifier"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Search...">
                    </div>
                </form>
                <ul class="right_chat list-unstyled">
                    <li class="online">
                        <a href="javascript:void(0);">
                            <div class="media">
                                <img class="media-object " src="../assets/images/xs/avatar4.jpg" alt="">
                                <div class="media-body">
                                    <span class="name">Dr. Chris Fox</span>
                                    <span class="message">Dentist</span>
                                    <span class="badge badge-outline status"></span>
                                </div>
                            </div>
                        </a>                            
                    </li>
                    <li class="online">
                        <a href="javascript:void(0);">
                            <div class="media">
                                <img class="media-object " src="../assets/images/xs/avatar5.jpg" alt="">
                                <div class="media-body">
                                    <span class="name">Dr. Joge Lucky</span>
                                    <span class="message">Gynecologist</span>
                                    <span class="badge badge-outline status"></span>
                                </div>
                            </div>
                        </a>                            
                    </li>
                    <li class="offline">
                        <a href="javascript:void(0);">
                            <div class="media">
                                <img class="media-object " src="../assets/images/xs/avatar2.jpg" alt="">
                                <div class="media-body">
                                    <span class="name">Dr. Isabella</span>
                                    <span class="message">CEO, WrapTheme</span>
                                    <span class="badge badge-outline status"></span>
                                </div>
                            </div>
                        </a>                            
                    </li>
                    <li class="offline">
                        <a href="javascript:void(0);">
                            <div class="media">
                                <img class="media-object " src="../assets/images/xs/avatar1.jpg" alt="">
                                <div class="media-body">
                                    <span class="name">Dr. Folisise Chosielie</span>
                                    <span class="message">Physical Therapy</span>
                                    <span class="badge badge-outline status"></span>
                                </div>
                            </div>
                        </a>                            
                    </li>
                    <li class="online">
                        <a href="javascript:void(0);">
                            <div class="media">
                                <img class="media-object " src="../assets/images/xs/avatar3.jpg" alt="">
                                <div class="media-body">
                                    <span class="name">Dr. Alexander</span>
                                    <span class="message">Audiology</span>
                                    <span class="badge badge-outline status"></span>
                                </div>
                            </div>
                        </a>                            
                    </li>                        
                </ul>
            </div>
            <div class="tab-pane p-l-15 p-r-15" id="setting">
                <h6>Choose Skin</h6>
                <ul class="choose-skin list-unstyled">
                    <li data-theme="purple">
                        <div class="purple"></div>
                        <span>Purple</span>
                    </li>                   
                    <li data-theme="blue">
                        <div class="blue"></div>
                        <span>Blue</span>
                    </li>
                    <li data-theme="cyan" class="active">
                        <div class="cyan"></div>
                        <span>Cyan</span>
                    </li>
                    <li data-theme="green">
                        <div class="green"></div>
                        <span>Green</span>
                    </li>
                    <li data-theme="orange">
                        <div class="orange"></div>
                        <span>Orange</span>
                    </li>
                    <li data-theme="blush">
                        <div class="blush"></div>
                        <span>Blush</span>
                    </li>
                </ul>
                <hr>
                <h6>General Settings</h6>
                <ul class="setting-list list-unstyled">
                    <li>
                        <label class="fancy-checkbox">
                            <input type="checkbox" name="checkbox">
                            <span>Report Panel Usag</span>
                        </label>
                    </li>
                    <li>
                        <label class="fancy-checkbox">
                            <input type="checkbox" name="checkbox">
                            <span>Email Redirect</span>
                        </label>
                    </li>
                    <li>
                        <label class="fancy-checkbox">
                            <input type="checkbox" name="checkbox" checked>
                            <span>Notifications</span>
                        </label>                      
                    </li>
                    <li>
                        <label class="fancy-checkbox">
                            <input type="checkbox" name="checkbox" checked>
                            <span>Auto Updates</span>
                        </label>
                    </li>
                    <li>
                        <label class="fancy-checkbox">
                            <input type="checkbox" name="checkbox">
                            <span>Offline</span>
                        </label>
                    </li>
                    <li>
                        <label class="fancy-checkbox">
                            <input type="checkbox" name="checkbox" checked>
                            <span>Location Permission</span>
                        </label>
                    </li>
                </ul>
            </div>             
        </div>          
    </div>
</div>