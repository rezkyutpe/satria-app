<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>SATRiA</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicons -->
    <link href="{{ asset('public/assets/global/img/favicon.png') }}" rel="icon">
    <link href="img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Main styles for this application -->
    <!-- <link href="{{ asset('public/css/style.css') }}" rel="stylesheet"> -->

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/assets/global/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="{{ asset('public/assets/global/css/components.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('public/assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="{{ asset('public/assets/layouts/layout4/css/layout.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/assets/layouts/layout4/css/themes/light.min.css') }}" rel="stylesheet" type="text/css" id="style_color" />
    <link href="{{ asset('public/assets/layouts/layout4/css/custom.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/css/chat.css') }}" rel="stylesheet" type="text/css" />

    <style>
        [data-tooltip] {
            position: relative;
            z-index: 2;
            cursor: pointer;
        }

        /* Hide the tooltip content by default */
        [data-tooltip]:before,
        [data-tooltip]:after {
            visibility: hidden;
            -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
            filter: progid: DXImageTransform.Microsoft.Alpha(Opacity=0);
            opacity: 0;
            pointer-events: none;
        }

        /* Position tooltip above the element */
        [data-tooltip]:before {
            position: absolute;
            bottom: 150%;
            left: 50%;
            margin-bottom: 5px;
            margin-left: -80px;
            padding: 7px;
            width: 160px;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            background-color: #000;
            background-color: hsla(0, 0%, 20%, 0.9);
            color: #fff;
            content: attr(data-tooltip);
            text-align: center;
            font-size: 14px;
            line-height: 1.2;
        }

        /* Triangle hack to make tooltip look like a speech bubble */
        [data-tooltip]:after {
            position: absolute;
            bottom: 150%;
            left: 50%;
            margin-left: -5px;
            width: 0;
            border-top: 5px solid #000;
            border-top: 5px solid hsla(0, 0%, 20%, 0.9);
            border-right: 5px solid transparent;
            border-left: 5px solid transparent;
            content: " ";
            font-size: 0;
            line-height: 0;
        }

        /* Show tooltip content on hover */
        [data-tooltip]:hover:before,
        [data-tooltip]:hover:after {
            visibility: visible;
            -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
            filter: progid: DXImageTransform.Microsoft.Alpha(Opacity=100);
            opacity: 1;
        }
        .sidebar li .submenu{ 
	list-style: none; 
	margin: 0; 
	padding: 0; 
	padding-left: 1rem; 
	padding-right: 1rem;
}
    </style>
    @yield('css')

    <!-- Responsive Stylesheet File -->
    <!-- <link href="css/responsive.css" rel="stylesheet"> -->
</head>

<body class="page-container-bg-solid page-header-fixed page-footer-fixed page-sidebar-closed-hide-logo page-sidebar-fixed">
    <!-- Header !-->
    <div class="page-header navbar navbar-fixed-top">
        @include('panel.header')
    </div>
    <div class="clearfix"> </div>
    <div class="page-container">

        <!-- Sidebar !-->
        <div class="page-sidebar-wrapper">
            @include('panel.sidebar')
        </div>

        <!-- Body Content !-->
        <div class="page-content-wrapper">
            <div class="page-content">
                @yield('content')
            </div>
        </div>
    </div>
    
    <div class="page-footer">
        <!-- <div class="page-footer-inner"> 2020 &copy; Rafi Zendaris.
            <a href="#" title="#" target="_blank" rel="noopener noreferrer">Visit Site!</a>
        </div> -->
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
    </div>

    <div class="chat-popup" id="myForm">
    <!-- <form class="form-container" action="{{url('comment-ticket')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}} -->
                    
    <form class="form-container">
    <div class="col-md-12">
                <div class="card card-bordered">
                    <div class="card-header"  id="chatHead">
                    </div>
                    <div class="outerDiv" id="chat-content">
                        <div id="chatData"></div>
                    </div>
                    <div class="publisher bt-1 border-lightclass"> <img class="avatar avatar-xs" src="{{ asset('public/assets/global/img/no-profile.jpg') }}" alt="..."> 
                        <input type="hidden" name="id_ticket"  id="id_ticket" class="publisher-input">
                        <input class="publisher-input" type="text" name="text" autocomplete="off" id="pesan" placeholder="Write something"> 
                        <!-- <input type="submit" class="publisher-btn text-info" value="Sent"> -->
                        <button class="btn btn-success btn-submit-chat">Sent</button>
                        <!-- <a class="publisher-btn text-info" href="#" data-abc="true"><i class="bi bi-send"></i></a>  -->
                  </div>
                </div>
            </div>
    </form>
    </div>
    @include('panel.scripts')
    @yield('myscript')
</body>

</html>