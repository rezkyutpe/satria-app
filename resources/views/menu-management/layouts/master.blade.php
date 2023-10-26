<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Satria</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{ asset('public/assets/global/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('public/assets/fe/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Roboto:100,300,400,500,700|Philosopher:400,400i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link href="{{ asset('public/assets/fe/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('public/assets/fe/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('public/assets/fe/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('public/assets/fe/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('public/assets/fe/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{ asset('public/assets/fe/css/style.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/chat.css') }}" rel="stylesheet" type="text/css" />
  <style>
    #cat{
      display: none;
    }
    #much{
      display: none;
    }
    [type=radio]:checked + label {
      /* outline: 4px solid rgb(0, 238, 255); */
      background-color: #d1e5ff;
    }
    .card-block {
        width: 40%;
        border: 1px solid lightgrey;
        border-radius: 5px !important;
        background-color: #FAFAFA;
        margin-bottom: 30px
    }
    
    .card-block-small {
        width: 30%;
        border: 1px solid lightgrey;
        border-radius: 5px !important;
        background-color: #FAFAFA;
        margin-bottom: 30px
    }
    
    .card-block-x-small {
        height: 25%;
        border: 1px solid lightgrey;
        border-radius: 5px !important;
        background-color: #FAFAFA;
        /*margin-bottom: 30px*/
    }
    .modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 200px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  /* padding: 20px; */
  border: 1px solid #888;
  width: 70%;
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

.text1 {
    background-color: #dc3545;
    color: white;
    width: 100%;
    margin-top: 5%;
    text-align: center
}

.icon2 {
    color: #dc3545
}

/* .modal-header {
    color: white;
    background-color: #dc3545
} */
.float{
	position:fixed;
	width:60px;
	height:60px;
	bottom:120px;
	right:100px;
	/* background-color:#0C9; */
	color:#FFF;
	/* border-radius:50px; */
	text-align:center;
	/* box-shadow: 2px 2px 3px #999; */
}

.msg{
	position:fixed;
  /* width: 50%;  */
	height:60px;
	top:120px;
	right:10px;
	/* background-color:#0C9; */
	color:#FFF;
	/* border-radius:50px; */
	/* text-align:center; */
	/* box-shadow: 2px 2px 3px #999; */
}
.my-float{
	margin-top:22px;
}
.badge-notif {
        position:relative;
}

.badge-notif[data-badge]:after {
        content:attr(data-badge);
        position:absolute;
        top:-10px;
        right:-10px;
        font-size:.7em;
        background:#e53935;
        color:white;
        width:18px;
        height:18px;
        text-align:center;
        line-height:18px;
        border-radius: 50%;
}
.rate {
    float: left;
    height: 46px;
    padding: 0 10px;
}
.rate:not(:checked) > input {
    position:absolute;
    top:-9999px;
}
.rate:not(:checked) > label {
    float:right;
    width:1em;
    overflow:hidden;
    white-space:nowrap;
    cursor:pointer;
    font-size:30px;
    color:#ccc;
}
.rate:not(:checked) > label:before {
    content: '★ ';
}
.rate > input:checked ~ label {
    color: #ffc700;    
}
.rate:not(:checked) > label:hover,
.rate:not(:checked) > label:hover ~ label {
    color: #deb217;  
}
.rate > input:checked + label:hover,
.rate > input:checked + label:hover ~ label,
.rate > input:checked ~ label:hover,
.rate > input:checked ~ label:hover ~ label,
.rate > label:hover ~ input:checked ~ label {
    color: #c59b08;
}
      </style>
  <!-- =======================================================
  * Template Name: eStartup - v4.7.0
  * Template URL: https://bootstrapmade.com/estartup-bootstrap-landing-page-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    <div class="container d-flex align-items-center justify-content-between">

      <div id="logo">
        <h1><a href="{{ url('welcome') }}"><span></span>Satria</a></h1>
        <!-- Uncomment below if you prefer to use an image logo -->
         </div>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
          <li><a class="nav-link scrollto" href="#get-started">About</a></li>
          <li><a class="nav-link scrollto" href="#features">Services</a></li>
          <li><a class="nav-link scrollto" href="#screenshots">Gallery</a></li>
          <li><a class="nav-link scrollto" href="#contact">Need Help</a></li>
          <li class="dropdown"><a href="#"> @if($data['pr_count']+$data['ticket_count']!=0)<span class="badge-notif" data-badge="{{ $data['pr_count']+$data['ticket_count'] }}">Transaction</span>@else <span class="badge-notif" >Transaction</span>@endif</i></a>
            <ul>
              <li><a href="{{ url('user-pr') }}" style="font-size: 11px;">Purchasing Request @if($data['pr_count']!=0) <span  class="badge-notif" data-badge="{{ $data['pr_count'] }}"></span>@endif</a></li>
              <li><a href="{{ url('user-ticket') }}" style="font-size: 11px;">Ticket @if($data['ticket_count']!=0)<span  class="badge-notif" data-badge="{{ $data['ticket_count'] }}"></span>@endif</a></li>
            </ul>
          </li>
          <li class="dropdown"><a href="#">@if($data['count_comment']!=0)<span class="badge-notif" data-badge="{{ $data['count_comment'] }}">Message</span>@else <span class="badge-notif" >Message</span>@endif </a>
          <ul>
            @foreach($data['comment'] as $comment)
              <li><a  onclick="getChat({{ $comment->id_ticket }})">  <span style="font-size: 10px;@if($comment->notif==0) color: grey @endif" > <strong>{{ $comment->ticket_id }}</strong><br>from : {{ $comment->name }}</span> @if($comment->notif!=0) <span  class="badge-notif" data-badge=" {{ $comment->notif }}"></span>@endif</a></li>
            @endforeach
            </ul>
          </li>
          <li class="dropdown"><a href="#"><span>{{ Auth::user()->name }}</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li><a href="#">Account Informastion</a></li>
              <li><a href="{{ url('/') }}">Go to Admin</a></li>
              <li> <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" >Logout</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form></li>
            </ul>
          </li>
        </ul>
        
        <i class="bi bi-list mobile-nav-toggle">@if($data['pr_count']+$data['ticket_count']+$data['count_comment']!=0)<span class="badge-notif"  style="font-size: 15px;"  data-badge="{{ $data['pr_count']+$data['ticket_count']+$data['count_comment'] }}" ></span>@endif</i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->
 
  @yield('content')

  <!-- ======= Footer ======= -->
  <footer class="footer">
    <div class="container">
      <div class="row">
      <center>
        <div class="col-md-12 col-lg-4">
          <div class="footer-logo">

            <p>&copy; Copyrights Satria v 1.6.1. All rights reserved. </p>
          <span style="color: white"> Designed by</span> <a href="https://bootstrapmade.com/">BootstrapMade</a>
          <div class="credits">
            <br>
          </div>
          </div>
        </div>
      </center>
        

      </div>
    </div>

  </footer><!-- End  Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <!-- <button id="myBtn">Open Modal</button> -->
  <a href="#" id="myBtn" class="float">
    <img src="{{ asset('public/assets/fe/img/giftbox.png') }}" alt="img" height="100">
  </a>
  @if(session()->has('err_message'))
  <a href="#" id="msg" class="col msg">
    <div class="alert alert-danger alert-dismissible" role="alert" auto-close="10000">
      <span>  {{ session()->get('err_message') }}</span>
    </div>
  </a>
    @endif
    @if(session()->has('suc_message'))
    <a href="#" id="msg" class="col msg">
    <div class="alert alert-success alert-dismissible" role="alert" auto-close="10000">
      <span>  {{ session()->get('suc_message') }}</span>
    </div>
  </a>
    @endif
    
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
  
  @include('layouts.footer')
    @yield('myscript')
    
</body>

</html>