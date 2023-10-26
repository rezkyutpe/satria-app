<!DOCTYPE html>
<html lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="{{ asset('public/assets-tsm/icon.ico') }}" type="image/ico" />
        <title>PATRiA</title>
        @include('cogs.panel.css')
        @yield('mycss')
    </head>

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <div class="col-md-3 left_col">
                    <div class="left_col scroll-view">
                        <div class="navbar nav_title" style="border: 0;">
                            <center>
                                <img src="{{ asset('public/assets-tsm/patria1.png') }}" style="width: 70%">
                            </center>
                        </div>
                        <div class="clearfix"></div>

                        <!-- menu profile quick info -->
                        <div class="profile clearfix">
                            <div class="profile_pic">
                                <img src="https://webportal.patria.co.id/satria/public/profile/iqbalnurfauzi.patria@gmail.com.png"
                                    alt="..." class="img-circle profile_img">
                            </div>
                            <div class="profile_info">
                                <span>Welcome,</span>
                                <h2>{{ Auth::user()->name }}</h2>
                            </div>
                        </div>

                        <!-- /menu profile quick info -->
                        <br />

                        <!-- sidebar menu -->
                        @include('cogs.panel.sidebar')
                        <!-- /sidebar menu -->

                        <!-- /menu footer buttons -->
                        {{-- <div class="sidebar-footer hidden-small">
                            <a data-toggle="tooltip" data-placement="top" title="Settings">
                                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                            </a>
                            <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                            </a>
                            <a data-toggle="tooltip" data-placement="top" title="Lock">
                                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                            </a>
                            <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
                                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                            </a>
                            </div> --}}
                        <!-- /menu footer buttons -->
                    </div>
                </div>

                <!-- top navigation -->
                @include('cogs.panel.header')
                <!-- /top navigation -->

                <!-- page content -->
                <div class="right_col" role="main">
                    <!-- top tiles -->
                    @yield('content')
                </div>
                <!-- /page content -->
                @yield('mymodal')
                <!-- footer content -->
                @include('cogs.panel.footer')
                <!-- /footer content -->
            </div>
        </div>
        @yield('top_javascript')
        @include('cogs.panel.javascript')
        @yield('myscript')
    </body>

</html>
