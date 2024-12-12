<!doctype html>
<html lang="en">
@include('common.head')
<body class="theme-cyan">
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="m-t-30"><img src="https://www.wrraptheme.com/templates/lucid/hospital/assets/images/logo-icon.svg" width="48" height="48" alt="Lucid"></div>
            <p>Please wait...</p>        
        </div>
    </div>
    <div id="wrapper">
        @include('common.navbar')
        @include('common.sidebar')
        @yield('content')    
    </div>
    @include('common.footer-js')
</body>
</html>
