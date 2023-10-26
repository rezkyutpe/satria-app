<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="icon" href="{{ asset('public/assetss/images/icon.ico') }}" type="image/ico" />
        <title>{{ $data['title'] }}</title>
        @include('completeness-component.panel.css')
        @yield('mycss')
    </head>

    <body class="nav-sm">
        <div class="container body">
            <div class="main_container">
                <div class="col-md-3 left_col">
                    <div class="left_col scroll-view">
                        <div class="navbar nav_title" style="border: 0;">
                            <center>
                                <img src="{{ asset('public/assetss/images/patria2.png') }}" alt="patria.png" style="width:70%;">
                            </center>
                        </div>
                        <div class="clearfix"></div>
                        <!-- sidebar menu -->
                        @include('completeness-component.panel.sidebar')
                        <!-- /sidebar menu -->
                    </div>
                </div>

                <!-- top navigation -->
                @include('completeness-component.panel.header')
                <!-- /top navigation -->

                <!-- page content -->
                <div class="right_col" role="main">
                    <div class="clearfix"></div>
                    @yield('contents')
                </div>
                <!-- /page content -->
                @include('completeness-component.panel.modal')
                @yield('modal')
                <!-- footer content -->
                @include('completeness-component.panel.footer')
                <!-- /footer content -->
            </div>
        </div>
        @include('completeness-component.panel.script')
        @yield('myscript')
    </body>
</html>
