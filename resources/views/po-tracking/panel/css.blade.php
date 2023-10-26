    <!-- Bootstrap -->
    <link href="{{ asset('public/assetss/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assetss/vendors/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.css') }}" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="{{ asset('public/assetss/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

    <!-- Datatables -->
    <link href="{{ asset('public/assetss/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/assetss/datatable/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assetss/datatable/css/fixedColumns.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assetss/datatable/css/fixedHeader.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assetss/datatable/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assetss/datatable/css/select.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assetss/datatable/css/keyTable.dataTables.min.css') }}">

    <!-- bootstrap-progressbar -->
    <link href="{{ asset('public/assetss/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}" rel="stylesheet">

    <!-- bootstrap-daterangepicker -->
    <link href="{{ asset('public/assetss/vendors/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
    <!---->

    <link href="{{ asset('public/assetss/vendors/pnotify/dist/pnotify.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assetss/vendors/pnotify/dist/pnotify.buttons.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assetss/vendors/pnotify/dist/pnotify.nonblock.css') }}" rel="stylesheet">

    <style>
        .modal-header {
        padding: 2px 16px;
        background-color: #b33300;
        color: white;
            -ms-flex-align: start;
            align-items: flex-start;
            -ms-flex-pack: justify;
            justify-content: space-between;
            padding: 1rem 1rem;
            border-bottom: 1px solid #dee2e6;
            border-top-left-radius: 0.3rem;
            border-top-right-radius: 0.3rem;
        }
        .form-control-label{
            font-size: 12px;
        }
        .form-control{
            font-size: 12px;
        }

        .badge {

        line-height: 2;
        }

        /* Nav Card Menu */
        .baseBlock {
            margin: 0px 0px 35px 0px;
            padding: 0 0 15px 0px;
            border-radius: 5px;
            overflow: hidden;
            -moz-transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            -o-transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .baseBlock:hover {
            -webkit-transform: translate(0, -4px);
            -moz-transform: translate(0, -4px);
            -ms-transform: translate(0, -4px);
            -o-transform: translate(0, -4px);
            transform: translate(0, -4px);
            box-shadow: 0 8px 8px rgba(0, 0, 0, 0.1);
        }
        .cardicons {
            position: absolute;
            top:0;
            bottom: 0;
            right:0;
            opacity: 30%;
        }

        video {
            /* override other styles to make responsive */
            width: 100%    !important;
            max-height: 300px   !important;
        }

        table tr td {
            font-size: 1em;
        }

    </style>

    <!-- Custom Theme Style -->
    <link href="{{ asset('public/assetss/build/css/custom.min.css') }}" rel="stylesheet">

    <!-- 12 April 2022 -->
    <link rel="stylesheet" href="{{asset('public/assetss/build/css/jquery.dataTables.min.css')}}">
    <link href="{{ asset('public/assetss/build/css/modalImageCSS.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/assetss/css/select2.min.css') }}">


    {{-- Toast --}}
    <link rel="stylesheet" href="{{ asset('public/assetss/css/toastr.min.css') }}">
    <style>
        .toast-custom {
            top: 10%;
            right:1%;
        }
    </style>
    
    {{-- Badge Outline Bootstrap --}}
    <style>
        .badge-outline-primary {
            color: #007bff;
            background-color: transparent;
            background-image: none;
            border-color: #007bff;
            border: 1px solid;
        }

        .badge-outline-secondary {
            color: #6c757d;
            background-color: transparent;
            background-image: none;
            border-color: #6c757d;
            border: 1px solid;
        }

        .badge-outline-success {
            color: #28a745;
            background-color: transparent;
            background-image: none;
            border-color: #28a745;
            border: 1px solid;
        }

        .badge-outline-danger {
            color: #dc3545;
            background-color: transparent;
            background-image: none;
            border-color: #dc3545;
        }

        .badge-outline-warning {
            color: #ffc107;
            background-color: transparent;
            background-image: none;
            border-color: #ffc107;
            border: 1px solid;
        }

        .badge-outline-info {
            color: #17a2b8;
            background-color: transparent;
            background-image: none;
            border-color: #17a2b8;
            border: 1px solid;
        }

        .badge-outline-light {
            color: #f8f9fa;
            background-color: transparent;
            background-image: none;
            border-color: #f8f9fa;
            border: 1px solid;
        }

        .badge-outline-dark {
            color: #343a40;
            background-color: transparent;
            background-image: none;
            border-color: #343a40;
            border: 1px solid;
        }
    </style>

    <!-- FullCalendar -->
    <link href="{{ asset('public/assetss/vendors/fullcalendar/dist/fullcalendar.min.css')}}" rel="stylesheet">
    <link href="{{ asset('public/assetss/vendors/fullcalendar/dist/fullcalendar.print.css')}}" rel="stylesheet" media="print">
