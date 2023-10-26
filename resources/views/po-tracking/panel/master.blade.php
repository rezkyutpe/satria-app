<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('public/assetss/images/icon.ico') }}" type="image/ico" />
    <title>PATRIA  </title>
    @include('po-tracking.panel.css')
    @yield('mycss')
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
                <center>
                    <img src="{{ asset('public/assetss/images/patria2.png') }}" style="width: 70%">
                </center>
            </div>
            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
              <div class="profile_pic mt-2">
                  <img src="@if(Auth::user()->photo!='') {{ asset('public/profile/'.Auth::user()->photo) }} @else{{ asset('public/assetss/images/user.png') }}@endif" alt="..."
                      class="img-circle profile_img" style="max-height: 60px; max-width: 60px;margin-top:22px;">
              </div>
              <div class="profile_info">
                <span>Welcome,</span>
                <h2>{{ Auth::user()->name }}</h2>
              </div>
            </div>

            <!-- /menu profile quick info -->
            <br />

            <!-- sidebar menu -->
            @include('po-tracking.panel.sidebar')
            <!-- /sidebar menu -->

          </div>
        </div>

        <!-- top navigation -->
        @include('po-tracking.panel.header')
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <!-- top tiles -->
          @yield('content')
        </div>
        <!-- /page content -->
        @yield('mymodal')
        <!-- footer content -->
        @include('po-tracking.panel.footer')
        <!-- /footer content -->
      </div>
    </div>
    @yield('top_javascript')
    @include('po-tracking.panel.javascript')
    @yield('myscript')
  </body>
</html>
