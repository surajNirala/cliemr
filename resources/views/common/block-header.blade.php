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