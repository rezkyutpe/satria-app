<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head><base href="{{ asset('public/assets/theme/dist/assets/') }}">
		<title>SATRiA</title>
		<meta name="description" content="SATRiA - PATRiA" />
		<meta name="keywords" content="SATRiA - PATRiA" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta charset="utf-8" />
    	<meta name="csrf-token" content="{{ csrf_token() }}" >
    	<!-- <meta http-equiv="Content-Security-Policy" content="
        img-src 'self' data:;
        " /> -->
		<link rel="shortcut icon" href="{{ asset('public/assets/global/img/favicon.png') }}" />
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans|Rock+Salt|Shadows+Into+Light|Cedarville+Cursive" />
		<!--end::Fonts-->
		<!--begin::Global Stylesheets Bundle(used by all pages)-->
		<link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		{{-- <link rel="stylesheet" href="{{ URL::asset('public/assets/Atlantis-Lite-master/css/atlantis.css') }} "> --}}
  		<!-- FullCalendar -->
		<link rel="stylesheet" href="{{ URL::asset('public/assets/Atlantis-Lite-master/plugins/fullcalendar/main.css') }} ">
		<!-- Chosen -->
		<link rel="stylesheet" href="{{ URL::asset('public/assets/Atlantis-Lite-master/plugins/chosen/chosen.css') }} ">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css">
		
    <style>
.dropbtn {
  border: none;
  cursor: pointer;
}

.dropbtn:hover, .dropbtn:focus {
  /*background-color: #2980B9;*/
  /*padding: 16px;*/
}

.dropdown {
  position: relative;
  /*display: inline-block;*/
}

.dropdown-content {
  display: none;
  position: absolute;
  /*background-color: #f1f1f1;*/
  min-width: 10px;
  overflow: auto;
  padding: -30px;
  /*box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);*/
  /*z-index: 15;*/
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}


.show {display: block;}
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
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" style="background-image: url(assets/media/patterns/header-bg.png)" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled">
		<!--begin::Main-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Page-->
			<div class="page d-flex flex-row flex-column-fluid">
				<!--begin::Wrapper-->
				<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
					<!--begin::Header-->
					<div id="kt_header" class="header align-items-stretch" data-kt-sticky="true" data-kt-sticky-name="header" data-kt-sticky-offset="{default: '200px', lg: '300px'}">
						<!--begin::Container-->
						<div class="container-xxl d-flex align-items-center">
							<!--begin::Heaeder menu toggle-->
							<div class="d-flex align-items-center d-lg-none ms-n2 me-3" title="Show aside menu">
								<div class="btn btn-icon btn-custom w-30px h-30px w-md-40px h-md-40px" id="kt_header_menu_mobile_toggle">
									<!--begin::Svg Icon | path: icons/duotune/abstract/abs015.svg-->
									<span class="svg-icon svg-icon-2x">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
											<path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z" fill="black" />
											<path opacity="0.3" d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z" fill="black" />
										</svg>
									</span>
									<!--end::Svg Icon-->
								</div>
							</div>
							<!--end::Heaeder menu toggle-->
							<!--begin::Header Logo-->
							<div class="header-logo me-5 me-md-10 flex-grow-1 flex-lg-grow-0">
								<a href="{{ url('welcome')}}">
													<!-- <span class="h-15px h-lg-20px logo-default"> SATRiA </span>					
													<span class="h-15px h-lg-20px logo-sticky"> SATRiA </span>					 -->
									<img alt="Logo" src="{{ asset('public/assets/global/img/satria-fe-logo.png') }}" class="h-15px h-lg-40px logo-default" />
									<img alt="Logo" src="{{ asset('public/assets/global/img/satria-fe-logo1.png') }}" class="h-15px h-lg-40px logo-sticky" />		
								</a>
							</div>
							<!--end::Header Logo-->
							<!--begin::Wrapper-->
							<div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
								<!--begin::Navbar-->
								<div class="d-flex align-items-stretch" id="kt_header_nav">
									<!--begin::Menu wrapper-->
									<div class="header-menu align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_header_menu_mobile_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
										<!--begin::Menu-->
										<div class="menu menu-lg-rounded menu-column menu-lg-row menu-state-bg menu-title-gray-700 menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-400 fw-bold my-5 my-lg-0 align-items-stretch" id="#kt_header_menu" data-kt-menu="true">
											
											<div class="menu-item  me-lg-1">
												<a class="menu-link active py-3" href="{{ url('welcome')}}">
													<span class="menu-title">Home</span>
												</a>
											</div>
											<div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion me-lg-1">
												<span class="menu-link py-3">
													<span class="menu-title">Dashboard</span>
													<span class="menu-arrow d-lg-none"></span>
												</span>
												<div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-rounded-0 py-lg-4 w-lg-225px">
													<div class="menu-item">
														<a class="menu-link py-3" href="{{ url('dashboard')}}">
															<span class="menu-icon">
																<!--begin::Svg Icon | path: icons/duotune/art/art002.svg-->
																<span class="svg-icon svg-icon-2">
																	<svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" viewBox="0 0 24 25" fill="none">
																		<path opacity="0.3" d="M8.9 21L7.19999 22.6999C6.79999 23.0999 6.2 23.0999 5.8 22.6999L4.1 21H8.9ZM4 16.0999L2.3 17.8C1.9 18.2 1.9 18.7999 2.3 19.1999L4 20.9V16.0999ZM19.3 9.1999L15.8 5.6999C15.4 5.2999 14.8 5.2999 14.4 5.6999L9 11.0999V21L19.3 10.6999C19.7 10.2999 19.7 9.5999 19.3 9.1999Z" fill="black" />
																		<path d="M21 15V20C21 20.6 20.6 21 20 21H11.8L18.8 14H20C20.6 14 21 14.4 21 15ZM10 21V4C10 3.4 9.6 3 9 3H4C3.4 3 3 3.4 3 4V21C3 21.6 3.4 22 4 22H9C9.6 22 10 21.6 10 21ZM7.5 18.5C7.5 19.1 7.1 19.5 6.5 19.5C5.9 19.5 5.5 19.1 5.5 18.5C5.5 17.9 5.9 17.5 6.5 17.5C7.1 17.5 7.5 17.9 7.5 18.5Z" fill="black" />
																	</svg>
																</span>
																<!--end::Svg Icon-->
															</span>
															<span class="menu-title">Satria Dashboard</span>
														</a>
													</div>
													
												</div>
											</div>
											<div class="menu-item me-lg-1">
												<a class="menu-link py-3" href="{{ url('about')}}">
													<span class="menu-title">About Us</span>
												</a>
											</div>
											<div class="menu-item me-lg-1">
												<a class="menu-link py-3" href="{{ url('faq')}}">
													<span class="menu-title">FAQ</span>
												</a>
											</div>
										</div>
										<!--end::Menu-->
									</div>
									<!--end::Menu wrapper-->
								</div>
								<!--end::Navbar-->
								<!--begin::Topbar-->
								<div class="d-flex align-items-stretch flex-shrink-0">
									<!--begin::Toolbar wrapper-->
									<div class="topbar d-flex align-items-stretch flex-shrink-0">
										
										<!--begin::Notifications-->
										<div class="d-flex align-items-center ms-1 ms-lg-3">
											<!--begin::Menu- wrapper-->
											<div class="btn btn-icon btn-custom btn-active-light position-relative w-30px h-30px w-md-40px h-md-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
												<!--begin::Svg Icon | path: icons/duotune/general/gen022.svg-->
												<span class="svg-icon svg-icon-1">
													<i class="fa fa-bell fs-2"></i>
												</span>
                       							@if(count($data['pr'])+count($data['ticket'])+ count($data['notifreqpr'])+count($data['notifreqticket'])!=0)
													<span class="bullet bullet-dot bg-success h-6px w-6px position-absolute translate-middle top-0 start-50 animation-blink"></span>
                        						@endif
												<!--end::Svg Icon-->
											</div>
											<!--begin::Menu-->

											<div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true">
												<!--begin::Heading-->
												<div class="d-flex flex-column bgi-no-repeat rounded-top" style="background-image:url('assets/media//patterns/dropdown-header-bg.png')">
													<!--begin::Title-->
													<h3 class="text-white fw-bold px-9 mt-10 mb-6">Notifications
													<span class="fs-8 opacity-75 ps-3">{{ count($data['pr'])+count($data['ticket'])+ count($data['notifreqpr'])+count($data['notifreqticket']) }} transaction</span></h3>
													<!--end::Title-->
													<!--begin::Tabs-->
													<ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-bold px-9">
														<li class="nav-item">
															<a class="nav-link text-white opacity-75 opacity-state-100 pb-4 active" data-bs-toggle="tab" href="#kt_topbar_notifications_1"> <span> PR<span class="badge badge-danger fs-10" style=" vertical-align: top">{{ count($data['pr']) }}</span></span></a>
																
														</li>
														<li class="nav-item">
															<a class="nav-link text-white opacity-75 opacity-state-100 pb-4" data-bs-toggle="tab" href="#kt_topbar_notifications_3"> <span> Ticket<span class="badge badge-danger fs-10" style=" vertical-align: top">{{ count($data['ticket']) }}</span></span></a>
														</li>
														<li class="nav-item">
															<a class="nav-link text-white opacity-75 opacity-state-100 pb-4" data-bs-toggle="tab" href="#kt_topbar_notifications_4"> <span> Approval<span class="badge badge-danger fs-10" style=" vertical-align: top">{{ count($data['notifreqpr'])+count($data['notifreqticket'])}}</span></span></a>
														</li>
														<li class="nav-item">
															<a class="nav-link text-white opacity-75 opacity-state-100 pb-4" data-bs-toggle="tab" href="#kt_topbar_notifications_5"> <span> QRGS<span class="badge badge-danger fs-10" style=" vertical-align: top">{{ $data['notifHisTms']+$data['notifHisRoom']}}</span></span></a>
														</li>
													</ul>
													<!--end::Tabs-->
												</div>
												<!--end::Heading-->
												<!--begin::Tab content-->
												<div class="tab-content">
													<!--begin::Tab panel-->
													<div class="tab-pane fade show active" id="kt_topbar_notifications_1" role="tabpanel">
														<div class="scroll-y mh-325px my-5 px-8">
															<!--begin::Item-->
															@foreach($data['pr'] as $pr)
															<div class="d-flex flex-stack py-4">
																<!--begin::Section-->
																<div class="d-flex align-items-center me-2">
                               									@if($pr->status=='0')
																	<span class="w-70px badge badge-light-danger me-4">New</span>
                               									@else
																	<span class="w-70px badge badge-light-warning me-4">PA</span>
                                								@endif
																	<a  class="text-gray-800 text-hover-primary fw-bold">#{{ $pr->pr_number }}</a>
																</div>
																<span class="badge badge-light fs-8">{{ date('d F y',strtotime($pr->updated_at))}}</span>
															</div>
															@endforeach
														</div>
														<div class="py-3 text-center border-top">
															<a href="{{ url('user-pr') }}" class="btn btn-color-gray-600 btn-active-color-primary">View All
															<!--begin::Svg Icon | path: icons/duotune/arrows/arr064.svg-->
															<span class="svg-icon svg-icon-5"><i class="fa fa-angle-right fs-2"></i>
															</span>
															<!--end::Svg Icon--></a>
														</div>
														<!--end::View more-->
													</div>
													<!--end::Tab panel-->
													<!--begin::Tab panel-->
													<div class="tab-pane fade" id="kt_topbar_notifications_3" role="tabpanel">
														<!--begin::Items-->
														<div class="scroll-y mh-325px my-5 px-8">
															<!--begin::Item-->
                              								@foreach($data['ticket'] as $ticket)
															<div class="d-flex flex-stack py-4">
																<!--begin::Section-->
																<div class="d-flex align-items-center me-2">
																	<span  style="background-color: {{ $ticket->bg_color }}; color: {{ $ticket->fg_color }};" class="w-70px badge me-4">{{ $ticket->flow_name}}</span>
                                
																	<a class="text-gray-800 text-hover-primary fw-bold">#{{ $ticket->ticket_id }}</a>
																</div>
																<span class="badge badge-light fs-8">{{ date('d F y',strtotime($ticket->updated_at))}}</span>
															</div>
															@endforeach
														</div>
														<!--end::Items-->
														<!--begin::View more-->
														<div class="py-3 text-center border-top">
															<a href="{{ url('user-ticket') }}" class="btn btn-color-gray-600 btn-active-color-primary">View All
															<!--begin::Svg Icon | path: icons/duotune/arrows/arr064.svg-->
															<span class="svg-icon svg-icon-5">
																
															<i class="fa fa-angle-right fs-2"></i>
															</span>
															<!--end::Svg Icon--></a>
														</div>
														<!--end::View more-->
													</div>
													<div class="tab-pane fade" id="kt_topbar_notifications_4" role="tabpanel">
														<!--begin::Items-->
														<div class="scroll-y mh-325px my-5 px-8">
															<!--begin::Item-->
															<div class="d-flex flex-stack py-4">
																<!--begin::Section-->
																<div class="d-flex align-items-center me-2">
                               									
																	<span class="w-70px badge badge-light-warning me-4">PR</span>
																	<a  href="{{ url('pr-approval-get') }}" class="text-gray-800 text-hover-primary fw-bold">Need Your Approval</a>
																</div>
																<span class="badge badge-light-danger fs-8">{{ count($data['notifreqpr']) }}</span>
															</div>
															<div class="d-flex flex-stack py-4">
																<!--begin::Section-->
																<div class="d-flex align-items-center me-2">
                               									
																	<span class="w-70px badge badge-light-warning me-4">TK</span>
																	<a  href="{{ url('ticket-approval-get') }}" class="text-gray-800 text-hover-primary fw-bold">Need Your Approval</a>
																</div>
																<span class="badge badge-light-danger fs-8">{{ count($data['notifreqticket']) }}</span>
															</div>
															<div class="d-flex flex-stack py-4">
																<!--begin::Section-->
																<div class="d-flex align-items-center me-2">
                               									
																	<span class="w-70px badge badge-light-warning me-4">QFD</span>
																	<a   href="{{ url('qfd-approval-get') }}" class="text-gray-800 text-hover-primary fw-bold">Need Your Approval</a>
																</div>
																<span class="badge badge-light-danger fs-8">0</span>
															</div>
														</div>
													</div>
													<div class="tab-pane fade" id="kt_topbar_notifications_5" role="tabpanel">
														<!--begin::Items-->
														<div class="scroll-y mh-325px my-5 px-8">
															<!--begin::Item-->
															<div class="d-flex flex-stack py-4">
																<!--begin::Section-->
																<div class="d-flex align-items-center me-2">
                               									
																	<span class="w-70px badge badge-light-warning me-4">TMS</span>
																	<a  href="{{ url('user-tms') }}" class="text-gray-800 text-hover-primary fw-bold">Your History TMS</a>
																</div>
																<span class="badge badge-light-danger fs-8">{{ $data['notifHisTms'] }}</span>
															</div>
															<div class="d-flex flex-stack py-4">
																<!--begin::Section-->
																<div class="d-flex align-items-center me-2">
                               									
																	<span class="w-70px badge badge-light-warning me-4">Room</span>
																	<a  href="{{ url('user-room') }}" class="text-gray-800 text-hover-primary fw-bold">Your History Room</a>
																</div>
																<span class="badge badge-light-danger fs-8">{{ $data['notifHisRoom'] }}</span>
															</div>
															
														</div>
													</div>
													<!--end::Tab panel-->
													<!--end::Tab panel-->
												</div>
												<!--end::Tab content-->
											</div>
											<!--end::Menu-->
											<!--end::Menu wrapper-->
										</div>
										<div class="d-flex align-items-center ms-1 ms-lg-3">
											<!--begin::Menu- wrapper-->
											<div class="btn btn-icon btn-custom btn-active-light position-relative w-30px h-30px w-md-40px h-md-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
												<!--begin::Svg Icon | path: icons/duotune/general/gen022.svg-->
												<span class="svg-icon svg-icon-1">
												<i class="fa fa-comment-alt fs-2"></i>
												</span>
												<!--end::Svg Icon-->
                        						@if($data['count_comment']!=0)
												<span class="bullet bullet-dot bg-success h-6px w-6px position-absolute translate-middle top-0 start-50 animation-blink"></span>
                       							 @endif
												<!--end::Svg Icon-->
											</div>
											<!--begin::Menu-->
											
											<div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true">
												<!--begin::Heading-->
												<div class="d-flex flex-column bgi-no-repeat rounded-top" style="background-image:url('assets/media//patterns/dropdown-header-bg.png')">
													<!--begin::Title-->
													<h3 class="text-white fw-bold px-9 mt-10 mb-6">Messages
													<span class="fs-8 opacity-75 ps-3">{{ $data['count_comment']}} unread</span></h3>
													<!--end::Title-->
													
												</div>
												<!--end::Heading-->
												<!--begin::Tab content-->
												<div class="tab-content">
													<!--begin::Tab panel-->
													<div class="tab-pane fade show active" role="tabpanel">
														<!--begin::Items-->
														<div class="scroll-y mh-325px my-5 px-8">
                              
                              								@foreach($data['comment'] as $comment)
															<!--begin::Item-->
															<div class="d-flex flex-stack py-4">
																<!--begin::Section-->
																<div class="d-flex align-items-center">
																	<!--begin::Symbol-->
																	<div class="symbol symbol-35px me-4">
																		<span class="symbol-label bg-light-primary">
																			<!--begin::Svg Icon | path: icons/duotune/technology/teh008.svg-->
																			<span class="svg-icon svg-icon-2 svg-icon-primary">
                                        										<i class="fa fa-user-circle fs-2"></i>
																			</span>
																			<!--end::Svg Icon-->
																		</span>
																	</div>
																	<!--end::Symbol-->
																	<!--begin::Title-->
																	<div class="mb-0 me-2">
																		<a onclick="getChat({{ $comment->id_ticket }})" id="kt_explore_toggle" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-dismiss="click"  class="fs-6 text-gray-800 text-hover-primary fw-bolder">{{ $comment->name }}</a>
																		<div class="text-gray-400 fs-7">#{{ $comment->ticket_id }}</div>
																	</div>
																	<!--end::Title-->
																</div>
																<!--end::Section-->
																<!--begin::Label-->
																<span class="badge badge-light-danger fs-8">{{ $comment->notif }}</span>
																<!--end::Label-->
															</div>
															<!--end::Item-->
															@endforeach
															
														</div>
														<div class="py-3 text-center border-top">
															<!-- <a href="../dist/account/activity.html" class="btn btn-color-gray-600 btn-active-color-primary">View All
															<span class="svg-icon svg-icon-5">
																<i class="fa fa-angle-right fs-2"></i>
															</span></a> -->
														</div>
													</div>
													<!--end::Tab panel-->
													
												</div>
												<!--end::Tab content-->
											</div>
											<!--end::Menu-->
											<!--end::Menu wrapper-->
										</div>
										<!--end::Notifications-->
										
										<div class="d-flex align-items-center ms-1 ms-lg-3">
											<!--begin::Menu wrapper-->
											<div class="btn btn-icon btn-custom btn-active-light w-30px h-30px w-md-40px h-md-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
												<!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
												<span class="svg-icon svg-icon-1">
													<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
														<rect x="2" y="2" width="9" height="9" rx="2" fill="black" />
														<rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2" fill="black" />
														<rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2" fill="black" />
														<rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2" fill="black" />
													</svg>
												</span>
												<!--end::Svg Icon-->
											</div>
											<div class="menu menu-sub menu-sub-dropdown menu-column w-250px w-lg-325px" data-kt-menu="true">
												<div class="d-flex flex-column flex-center bgi-no-repeat rounded-top px-9 py-10" style="background-image:url('assets/media//patterns/dropdown-header-bg.png')">
													<h3 class="text-white fw-bold mb-3">Go To Apps</h3>
													 <div class="d-flex align-items-center position-relative my-1">
								                        <span class="svg-icon svg-icon-1 position-absolute ms-4">
								                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
								                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
								                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
								                            </svg>
								                        </span>
								                        <input type="text" list="list_apps" class="form-control form-control-solid w-250px ps-14" onkeydown="searchmenu(this)" placeholder="Search Menu Code" />
														<datalist list="list_apps" id="list_apps">
															@foreach($data['appsmenu'] as $appsmenu)
															<option value="{{ $appsmenu['menu_link'] }}">{{ $appsmenu['app_menu'] }}</option>
																@endforeach
														</datalist>
								                    </div>
												</div>

												
												<div class="row g-0"  style="max-height: 600px; overflow-y:auto; padding: 0px;">
													<center><span class="py-2 px-3"> <br> List your Authorize Apps</span></center>
												@php($actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]")

												<div class="col-6">
													<a href="{{ $actual_link }}/satria-eqm/public/eqm-management" class="d-flex flex-column flex-center h-100 p-6 bg-hover-light border-bottom">
														<span class="svg-icon svg-icon-3x svg-icon-success mb-2"> <img src="{{ asset('public/assets/global/img/icon/eqm.svg') }}" width="40" height="40">> </span>
														<span class="fs-5 fw-bold text-gray-800 mb-0">Eqm</span>
													</a>
												</div>
												@if(Auth::user()->role_id=='')
												<div class="col-6">
													<a href="{{ $actual_link }}/satria-kpi-tracking/public/kpi-tracking" class="d-flex flex-column flex-center h-100 p-6 bg-hover-light border-bottom">
														<span class="svg-icon svg-icon-3x svg-icon-success mb-2"> <img src="{{ asset('public/assets/global/img/icon/kpi-tracking.svg') }}" width="40" height="40">> </span>
														<span class="fs-5 fw-bold text-gray-800 mb-0">KPI Tracking</span>
													</a>
												</div>
												@endif
												@foreach($data['haveapp'] as $haveapp)
													@if($haveapp->app!='15' && $haveapp->app!='20' )
													<div class="col-6">
														<a onclick="changeapps({{ $haveapp->app }})" class="d-flex flex-column flex-center h-100 p-6 bg-hover-light border-bottom">
															<span class="svg-icon svg-icon-3x svg-icon-success mb-2"> <img src="{{ asset('public/assets/global/img/icon/'.$haveapp->logo) }}" width="40" height="40">> </span>
															<span class="fs-5 fw-bold text-gray-800 mb-0">{{ $haveapp->app_name}}</span>
														</a>
													</div>
													@endif
												@endforeach
												</div>
												
												@if(count($data['haveapp'])==0)
												<div class="py-3 text-center border-top">
													<a class="btn btn-color-gray-600 btn-active-color-primary">You don't have another authorization application</a>
												</div>
												@endif
												<div class="py-3 text-center border-top">
													<a href="{{ url('assist-ticket') }}" class="btn btn-color-gray-600 btn-active-color-primary">Assist Page
													<!--begin::Svg Icon | path: icons/duotune/arrows/arr064.svg-->
													<span class="svg-icon svg-icon-5">
														
													<i class="fa fa-angle-right fs-2"></i>
													</span>
													<!--end::Svg Icon--></a>
												</div>
											</div>
											<!--end::Menu-->
											<!--end::Menu wrapper-->
										</div>
										<!--begin::User-->
										<div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
											<!--begin::Menu wrapper-->
                      						<div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
												<img alt="Pic" src="@if(Auth::user()->photo!='') {{ asset('public/profile/'.Auth::user()->photo) }} @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif" />
											</div>
											<!--begin::Menu-->
											<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px" data-kt-menu="true">
												<!--begin::Menu item-->
												<div class="menu-item px-3">
													<div class="menu-content d-flex align-items-center px-3">
														<!--begin::Avatar-->
														<div class="symbol symbol-50px me-5">
															<img alt="Logo" src="@if(Auth::user()->photo!='') {{ asset('public/profile/'.Auth::user()->photo) }} @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif" />
														</div>
														<!--end::Avatar-->
														<!--begin::Username-->
														<div class="d-flex flex-column">
															<div class="fw-bolder d-flex align-items-center fs-5">{{ Auth::user()->name }}</div>
															<a class="fw-bold text-muted text-hover-primary fs-7">{{ Auth::user()->email_sf }}</a>
														</div>
														<!--end::Username-->
													</div>
												</div>
												<!--end::Menu item-->
												<!--begin::Menu separator-->
												<div class="separator my-2"></div>
												<!--end::Menu separator-->
												<!--begin::Menu item-->
												<div class="menu-item px-5">
													<a href="{{ url('profile') }}" class="menu-link px-5">My Profile</a>
												</div>
												<div class="separator my-2"></div>
												<!--end::Menu separator-->
												<!--begin::Menu item-->
												<div class="menu-item px-5">
													<a href="{{ url('profile-password') }}" class="menu-link px-5">Change Password</a>
												</div>
												<!--end::Menu item-->
												<!--begin::Menu item-->
												
												<!--begin::Menu separator-->
												<div class="separator my-2"></div>
												<!--end::Menu separator-->
												<!--begin::Menu item-->
												<div class="menu-item px-5" data-kt-menu-trigger="hover" data-kt-menu-placement="right-start">
													
													<!--begin::Menu sub-->
													
													<!--end::Menu sub-->
												</div>
												<!--end::Menu item-->
												<!--begin::Menu item-->
												<div class="menu-item px-5">
                        						<a href="{{ route('logout') }}" class="menu-link px-5" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" >Sign Out</a>
												<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
													@csrf
												</form>
												</div>
												<!--end::Menu item-->
												<!--begin::Menu separator-->
												<div class="separator my-2"></div>
												<!--end::Menu separator-->
											</div>
											<div class="d-flex align-items-center d-lg-none ms-4" title="Show header menu">
												<div class="btn btn-icon btn-custom w-30px h-30px w-md-40px h-md-40px" id="kt_aside_mobile_toggle">
													<!--begin::Svg Icon | path: icons/duotune/text/txt001.svg-->
													<span class="svg-icon svg-icon-1">
														<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
															<path d="M13 11H3C2.4 11 2 10.6 2 10V9C2 8.4 2.4 8 3 8H13C13.6 8 14 8.4 14 9V10C14 10.6 13.6 11 13 11ZM22 5V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4V5C2 5.6 2.4 6 3 6H21C21.6 6 22 5.6 22 5Z" fill="black" />
															<path opacity="0.3" d="M21 16H3C2.4 16 2 15.6 2 15V14C2 13.4 2.4 13 3 13H21C21.6 13 22 13.4 22 14V15C22 15.6 21.6 16 21 16ZM14 20V19C14 18.4 13.6 18 13 18H3C2.4 18 2 18.4 2 19V20C2 20.6 2.4 21 3 21H13C13.6 21 14 20.6 14 20Z" fill="black" />
														</svg>
													</span>
													<!--end::Svg Icon-->
												</div>
											</div>
										</div>
										<!--end::User -->
										<!--begin::Aside mobile toggle-->
										<!--end::Aside mobile toggle-->
									</div>
									<!--end::Toolbar wrapper-->
								</div>
								<!--end::Topbar-->
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Header-->
          @if(session()->has('err_message'))
          <div class="col-md-12">
          <div class="alert alert-danger d-flex align-items-center p-5 mb-10 col-md-4" style="float: right; margin-right:15px;">
							<span class="svg-icon svg-icon-2hx svg-icon-danger me-4"><i class="far fa-lightbulb text-danger fs-2"></i></span>
							<div class="d-flex flex-column">
									<h4 class="mb-1 text-danger">Error alert</h4>
                  <span> {{ session()->get('err_message') }}</span>
              </div>
              <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <span class="svg-icon svg-icon-2x svg-icon-light"> <i class="fas fa-times text-info fs-2"></i></span>
              </button>
            </div>
          </div>
          @endif
          @if(session()->has('suc_message'))
          <div class="col-md-12">
          <div class="alert alert-success d-flex align-items-center p-5 mb-10 col-md-4" style="float: right; margin-right:15px;">
							<span class="svg-icon svg-icon-2hx svg-icon-success me-4"><i class="far fa-lightbulb text-success fs-2"></i></span>
							<div class="d-flex flex-column">
									<h4 class="mb-1 text-success">Success alert</h4>
                  <span>{{ session()->get('suc_message') }}</span>
              </div>
              <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <span class="svg-icon svg-icon-2x svg-icon-light"> <i class="fas fa-times text-success fs-2"></i></span>
              </button>
            </div>
          </div>
          @endif
          
          	<!--begin::Toolbar-->
					<div class="toolbar py-2 py-lg-5" id="kt_toolbar">
						<!--begin::Container-->
						<div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
							<!--begin::Title-->
							<h3 class="text-white fw-bolder fs-2qx me-5">Webportal</h3>
							<!--begin::Title-->
							<!--begin::Actions-->
							<div class="d-flex align-items-center flex-wrap py-2">
								<!--begin::Action-->
								@if(Auth::user()->role_id=='10')
									<a href="{{ url('user-attendance') }}" class="btn btn-custom btn-color-white btn-active-color-success my-2 me-2 me-lg-6" tooltip="Employee Attendance"  >Attendance</a>
								@endif
								

								@if(Auth::user()->role_id!='30')
									
									@if(Auth::user()->dept=="")
										<a class="btn btn-custom btn-color-white btn-active-color-success my-2 me-2 me-lg-6" tooltip="New Booking Room or Car" href="{{ url('profile') }}" >New Booking</a>
									@else
										<a class="btn btn-custom btn-color-white btn-active-color-success my-2 me-2 me-lg-6" tooltip="New Booking Room or Car" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app" >New Booking</a>
									@endif
								@endif

								@if(Auth::user()->email=='1103009')

                            	<a class="btn btn-light my-2" style="margin-right: 10px;"
                                    data-bs-toggle="modal" data-bs-target="#kt_modal_submission">New Submission</a>
								<a class="btn btn-light my-2" style="margin-right: 10px;" data-bs-toggle="modal"data-bs-target="#kt_modal_upgrade_plan" >E-Letter</a>
								@endif
								@if(Auth::user()->dept=="")
									<a href="{{ url('profile') }}" class="btn btn-success my-2" tooltip="New Ticket or PR"  >New Request</a>
								@else
									<a class="btn btn-success my-2" data-bs-toggle="modal" tooltip="New Ticket or PR"  data-bs-target="#kt_modal_invite_friends">New Request</a>
								@endif
								</div>
							<!--end::Actions-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Toolbar-->
          
          @yield('content')
          <div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
						<!--begin::Container-->
						<div class="container-xxl d-flex flex-column flex-md-row align-items-center justify-content-between">
							<!--begin::Copyright-->
							<div class="text-dark order-2 order-md-1">
								<span class="text-muted fw-bold me-1">2021©</span>
								<a class="text-gray-800 text-hover-primary">SATRiA</a>
							</div>
						</div>
						<!--end::Container-->
					</div>
					<!--end::Footer-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
    
		<!--begin::Modals-->
    
   <div id="kt_explore" class="explore bg-body" data-kt-drawer="true" data-kt-drawer-name="explore" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'lg': '440px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_explore_toggle" data-kt-drawer-close="#kt_explore_close">
			<!-- <form> -->
			<div class="card shadow-none rounded-0 w-100">
			  	<div class="card-header" id="kt_explore_header">
					<div class="card-title">
						<div class="d-flex justify-content-center flex-column me-3">
							<a class="fs-4 fw-bolder text-gray-900 text-hover-primary me-1 mb-2 lh-1" id="id_ticket"></a>
			            </div>
			         </div>
					<div class="card-toolbar">
						<button type="button" class="btn btn-sm btn-icon explore-btn-dismiss me-n5" id="kt_explore_close">
							<span class="svg-icon svg-icon-2"> <i class="fas fa-times"></i></span>
						</button>
					</div>
				</div>
				<div class="card-body" id="kt_drawer_chat_messenger_body">
					<div id="chat-content" class="scroll-y me-n5 pe-5" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer" data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" data-kt-scroll-offset="50px">
						<div id="chatData"></div>
					</div>
				</div>
				<div class="card-footer pt-4" id="kt_drawer_chat_messenger_footer">
          			<input type="hidden" name="id_ticket"  id="id_ticket" class="publisher-input">
					<textarea class="form-control form-control-flush mb-3" rows="1" name="text" id="pesan" data-kt-element="input" placeholder="Type a message"></textarea>
					
					<div class="d-flex flex-stack">
						<div class="d-flex align-items-center me-2">
						</div>
            			<span id="msg"></span>
						<button class="btn btn-primary btn-submit-chat" type="button" data-kt-element="send">Send</button>
					</div>
				</div>
			</div>
      		<!-- </form> -->
		</div>
    
<div class="modal fade" id="kt_modal_invite_friends" tabindex="-1" aria-hidden="true">
			<!--begin::Modal dialog-->
			<div class="modal-dialog mw-650px">
				<!--begin::Modal content-->
				<div class="modal-content">
					<!--begin::Modal header-->
					<div class="modal-header pb-0 border-0 justify-content-end">
						<!--begin::Close-->
						<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
						
            <span class="svg-icon svg-icon-2x svg-icon-light"> <i class="fas fa-times text-info fs-2"></i></span>
						</div>
						<!--end::Close-->
					</div>
					<div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15">
						<!--begin::Heading-->
						<div class="text-center mb-13">
							<!--begin::Title-->
							<h1 class="mb-3">New Request</h1>
							<!--end::Title-->
							<!--begin::Description-->
							<div class="text-muted fw-bold fs-5">We Will Help
							<a class="link-primary fw-bolder">You</a>, Your request will be processed by the department you choose</div>
							<!--end::Description-->
						</div>
						<!--end::Heading-->
                        
                <form id="form-submit" action="{{url('add-request-action')}}" method="post" enctype="multipart/form-data">
                 {{csrf_field()}}
						
                        {{ csrf_field() }}
                        <div class="fv-row">
                            <!--begin::Row-->
                            <div class="row">
                                <!--begin::Col-->
                                <div class="col-lg-6">
                                    <!--begin::Option-->
                                    <input type="radio" class="btn-check" name="action"
                                        onclick="assetselect(this.value)" value="ticket" checked="checked"
                                        id="kt_create_account_form_account_type_personal" />
                                    <label
                                        class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center mb-10"
                                        for="kt_create_account_form_account_type_personal">
                                        <!--begin::Svg Icon | path: icons/duotune/communication/com005.svg-->
                                        <span class="svg-icon svg-icon-3x me-5">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M20 14H18V10H20C20.6 10 21 10.4 21 11V13C21 13.6 20.6 14 20 14ZM21 19V17C21 16.4 20.6 16 20 16H18V20H20C20.6 20 21 19.6 21 19ZM21 7V5C21 4.4 20.6 4 20 4H18V8H20C20.6 8 21 7.6 21 7Z"
                                                    fill="currentColor" />
                                                <path opacity="0.3"
                                                    d="M17 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H17C17.6 2 18 2.4 18 3V21C18 21.6 17.6 22 17 22ZM10 7C8.9 7 8 7.9 8 9C8 10.1 8.9 11 10 11C11.1 11 12 10.1 12 9C12 7.9 11.1 7 10 7ZM13.3 16C14 16 14.5 15.3 14.3 14.7C13.7 13.2 12 12 10.1 12C8.10001 12 6.49999 13.1 5.89999 14.7C5.59999 15.3 6.19999 16 7.39999 16H13.3Z"
                                                    fill="currentColor" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                        <!--begin::Info-->
                                        <span class="d-block fw-bold text-start">
                                            <span class="text-dark fw-bolder d-block fs-4 mb-2">Ticketing
                                                Request</span>

                                        </span>
                                        <!--end::Info-->
                                    </label>
                                    <!--end::Option-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-6">
                                    <!--begin::Option-->
                                    <input type="radio" class="btn-check" name="action"
                                        onclick="catselect(this.value)" value="pr"
                                        id="kt_create_account_form_account_type_corporate" />
                                    <label
                                        class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center"
                                        for="kt_create_account_form_account_type_corporate">
                                        <!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
                                        <span class="svg-icon svg-icon-3x me-5">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path opacity="0.3"
                                                    d="M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15ZM13 12H11C10.5 12 10 12.4 10 13V16C10 16.5 10.4 17 11 17H13C13.6 17 14 16.6 14 16V13C14 12.4 13.6 12 13 12Z"
                                                    fill="currentColor" />
                                                <path
                                                    d="M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14ZM20 15H14V16C14 16.6 13.5 17 13 17H11C10.5 17 10 16.6 10 16V15H4C3.6 15 3.3 14.9 3 14.7V18C3 19.1 3.9 20 5 20H19C20.1 20 21 19.1 21 18V14.7C20.7 14.9 20.4 15 20 15Z"
                                                    fill="currentColor" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                        <!--begin::Info-->
                                        <span class="d-block fw-bold text-start">
                                            <span class="text-dark fw-bolder d-block fs-4 mb-2">Purchasing
                                                Request</span>
                                        </span>
                                        <!--end::Info-->
                                    </label>
                                    <!--end::Option-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--begin::Separator-->
                        <div class="separator d-flex flex-center mb-8">
                            <span class="text-uppercase bg-body fs-7 fw-bold text-muted px-3"></span>
                        </div>
                        <div class="fv-row mb-10" id="subject">
                            <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                                <span class="required">Subject ?</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7"
                                    data-bs-toggle="tooltip"title="Form Required"></i>
                            </label>
                            <input type="text" name="subject" autocomplete="off" maxlength="50"
                                placeholder="Type Your Subject Ticket"
                                class="form-control form-control-lg form-control-solid" />
                        </div>
                        <div class="mb-10">
                            <div class="fs-6 fw-bold mb-2"> <span class="required">Send Ticket or PR To?</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                    title="Form Required"></i>
                            </div>
                            <div class="mh-300px scroll-y me-n7 pe-7">

                                <select data-control="select2" class="form-select form-select-solid form-select-lg"
                                    name="dept" required="" id="dept" onchange="getChooseDept(this);">
                                    <option value="">Choose Requisition to Departement</option>
                                    @foreach ($data['dept'] as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->as_nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="fv-row" id="cat" style="display: none">
                            <div class="fs-6 fw-bold mb-2"> <span class="required"> What your Category?</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                    title="Form Required"></i>
                            </div>
                            <!--begin:Option-->
                            <label class="d-flex flex-stack mb-5 cursor-pointer">
                                <!--begin:Label-->
                                <span class="d-flex align-items-center me-2">
                                    <!--begin:Icon-->
                                    <span class="symbol symbol-50px me-6">
                                        <span class="symbol-label bg-light-primary">
                                            <!--begin::Svg Icon | path: icons/duotune/maps/map004.svg-->
                                            <span class="svg-icon svg-icon-1 svg-icon-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path opacity="0.3"
                                                        d="M18.4 5.59998C21.9 9.09998 21.9 14.8 18.4 18.3C14.9 21.8 9.2 21.8 5.7 18.3L18.4 5.59998Z"
                                                        fill="black" />
                                                    <path
                                                        d="M12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2ZM19.9 11H13V8.8999C14.9 8.6999 16.7 8.00005 18.1 6.80005C19.1 8.00005 19.7 9.4 19.9 11ZM11 19.8999C9.7 19.6999 8.39999 19.2 7.39999 18.5C8.49999 17.7 9.7 17.2001 11 17.1001V19.8999ZM5.89999 6.90002C7.39999 8.10002 9.2 8.8 11 9V11.1001H4.10001C4.30001 9.4001 4.89999 8.00002 5.89999 6.90002ZM7.39999 5.5C8.49999 4.7 9.7 4.19998 11 4.09998V7C9.7 6.8 8.39999 6.3 7.39999 5.5ZM13 17.1001C14.3 17.3001 15.6 17.8 16.6 18.5C15.5 19.3 14.3 19.7999 13 19.8999V17.1001ZM13 4.09998C14.3 4.29998 15.6 4.8 16.6 5.5C15.5 6.3 14.3 6.80002 13 6.90002V4.09998ZM4.10001 13H11V15.1001C9.1 15.3001 7.29999 16 5.89999 17.2C4.89999 16 4.30001 14.6 4.10001 13ZM18.1 17.1001C16.6 15.9001 14.8 15.2 13 15V12.8999H19.9C19.7 14.5999 19.1 16.0001 18.1 17.1001Z"
                                                        fill="black" />
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </span>
                                    <!--end:Icon-->
                                    <!--begin:Info-->
                                    <span class="d-flex flex-column">
                                        <span class="fw-bolder fs-6" id="label1">Account</span>
                                    </span>
                                    <!--end:Info-->
                                </span>
                                <!--end:Label-->
                                <!--begin:Input-->
                                <span class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="radio" checked name="category" checked
                                        value="1" id="account" onclick="accountselect(this.value)" />
                                </span>
                                <!--end:Input-->
                            </label>
                            <!--end::Option-->
                            <!--begin:Option-->
                            <label class="d-flex flex-stack mb-5 cursor-pointer">
                                <!--begin:Label-->
                                <span class="d-flex align-items-center me-2">
                                    <!--begin:Icon-->
                                    <span class="symbol symbol-50px me-6">
                                        <span class="symbol-label bg-light-danger">
                                            <!--begin::Svg Icon | path: icons/duotune/general/gen024.svg-->
                                            <span class="svg-icon svg-icon-1 svg-icon-danger">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                                    viewBox="0 0 24 24">
                                                    <g stroke="none" stroke-width="1" fill="none"
                                                        fill-rule="evenodd">
                                                        <rect x="5" y="5" width="5"
                                                            height="5" rx="1" fill="#000000" />
                                                        <rect x="14" y="5" width="5"
                                                            height="5" rx="1" fill="#000000"
                                                            opacity="0.3" />
                                                        <rect x="5" y="14" width="5"
                                                            height="5" rx="1" fill="#000000"
                                                            opacity="0.3" />
                                                        <rect x="14" y="14" width="5"
                                                            height="5" rx="1" fill="#000000"
                                                            opacity="0.3" />
                                                    </g>
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </span>
                                    <!--end:Icon-->
                                    <!--begin:Info-->
                                    <span class="d-flex flex-column">
                                        <span class="fw-bolder fs-6" id="label0">Inventory</span>
                                    </span>
                                    <!--end:Info-->
                                </span>
                                <!--end:Label-->
                                <!--begin:Input-->
                                <span class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="radio" name="category" id="inventory"
                                        value="0" onclick="inventoryselect(this.value)" />
                                </span>
                                <!--end:Input-->
                            </label>
                            <!--end::Option-->
                            <!--begin:Option-->

                            <!--end::Option-->
                        </div>

                        <div class="fv-row" id="asset" style="display: none;">
                            <div class="fs-6 fw-bold mb-2"><span>What your Assets/Machine?</span></div>
                            <div class="mh-300px scroll-y me-n7 pe-7">
                                <select id="child" name="asset"
                                    class="form-select form-select-solid form-select-sm" data-control="select2">
                                    <option value="">Choose Menu</option>
                                </select>
                            </div>
                            <br>
                        </div>
                        <!--end::Google Contacts Invite-->
                        <div class="fv-row mb-10" id="lokasi">
                            <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                                <span class="required">Where Problem Location ?</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7"
                                    data-bs-toggle="tooltip"title="Form Required"></i>
                            </label>
                            <input type="text" list="list_location" name="location" autocomplete="off"
                                maxlength="50" placeholder="Where Problem Location "
                                class="form-control form-control-lg form-control-solid" />

                            <datalist id="list_location">
                                @foreach ($data['mstloc'] as $mstloc)
                                    <option data-value="{{ $mstloc->location_mesin }}">{{ $mstloc->location_mesin }}
                                    </option>
                                @endforeach
                            </datalist>
                        </div>
                        <div class="fv-row mb-10" id="much" style="display: none;">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                                <span class="required">How Many or Qty Ordered ?</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                    title="Form Required"></i>
                            </label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="number" value="1" name="qty" required
                                class="form-control form-control-lg form-control-solid" />
                            <!--end::Input-->
                        </div>

                        <div class="fv-row mb-10" id="pruser" style="display: none;">
                            <!--begin::Label-->
                            <label class="fs-6 fw-bold mb-2">PR to
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                    title="Add User to Use PR Include your name in PR request"></i></label>
                            <!--End::Label-->
                            <!--begin::Tagify-->
                            <div class="w-100 chosen-modal">
                                <select id="pruser_chosen" name="pruser[]"
                                    data-placeholder="Choose a User Employee..." multiple
                                    class="form-control form-control-solid">

                                </select>
                            </div>
                            <!--end::Tagify-->
                        </div>
                        <!--begin::Separator-->
                        <div class="fv-row mb-10">
                            <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                                <span class="required">What Your Problem?</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                    title="Form Required"></i>
                            </label>
                            <textarea class="form-control form-control-solid mb-8" rows="3" name="message" required
                                placeholder="Type your problem here"></textarea>
                        </div>
						<div class="d-flex flex-stack"  >
							<div class="me-5 fw-bold" id="capture">
								<label class="d-flex align-items-center fs-5 fw-bold mb-2">
									<span >Capture File ?</span>
									
								</label>
								<!-- accept=".png, .jpg, .jpeg" -->
								<input  type="file" class="form-control" name="media" >
							</div>
									<br>
								<label class="form-check form-switch form-check-custom form-check-solid">
									<!-- <input type="submit" name="Submit" class="btn btn-lg btn-primary" ><span class="svg-icon svg-icon-3 ms-2 me-0"> -->
									<button onclick="mySubmitLoad()" class="submit-button btn btn-lg btn-primary w-100 mb-5">
		                                <span id="submit-label" class="indicator-label">Submit
		                                <span id="submit-progress" class="indicator-progress" style="display: none">Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
		                                </span>
		                            </button>   
								</label>
						</div>
						<!--end::Notice-->
					</div>
					<!--end::Modal body-->
                </form>
				</div>
				<!--end::Modal content-->
			</div>
			<!--end::Modal dialog-->
		</div>		

		<div class="modal fade" id="detail-ticket" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered mw-900px">
				<div class="modal-content">
					<div class="modal-header">
						<h2>Detail Ticket</h2>
						<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
							<span class="svg-icon svg-icon-2x svg-icon-light"> <i class="fas fa-times text-danger fs-2"></i></span>
						</div>
					</div>
					<div class="modal-body py-lg-10 px-lg-10">
						<div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid">
							<div class="d-flex justify-content-center justify-content-xl-start flex-row-auto w-100 w-xl-300px">
								<div class="stepper-nav ps-lg-10" id="historyTable">
								
								</div>
							</div>
							<div class="flex-row-fluid py-lg-5 px-lg-15">
								<form class="form" novalidate="novalidate">
									<div class="current" data-kt-stepper-element="content">
										<div class="w-100">
											<div class="d-print-none border border-dashed border-gray-300 card-rounded h-lg-80 min-w-md-350px p-9 bg-lighten" id="detailTicketData">
											
											</div>
											<div class="d-print-none border border-dashed border-gray-300 card-rounded h-lg-30 min-w-md-350px p-9 bg-lighten mt-5"
	                                            id="detailTicketDataFile">

	                                        </div>
	                                        <div class="d-print-none border-gray-300 card-rounded h-lg-80 min-w-md-350px p-9" id="detailTicketDataSign">
											
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>	
		<div class="modal fade" id="kt_modal_create_app" tabindex="-1" aria-hidden="true">
			<!--begin::Modal dialog-->
			<div class="modal-dialog modal-fullscreen p-9">
				<!--begin::Modal content-->
				<div class="modal-content">
					<!--begin::Modal header-->
					<div class="modal-header">
						<!--begin::Modal title-->
						<h2>Request Booking</h2>
						<!--end::Modal title-->
						<!--begin::Close-->
						<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
							<!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
							<span class="svg-icon svg-icon-1">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
									<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
								</svg>
							</span>
							<!--end::Svg Icon-->
						</div>
						<!--end::Close-->
					</div>
					<!--end::Modal header-->
					<!--begin::Modal body-->
					<div class="modal-body py-lg-10 px-lg-10">
					<!--begin::Stepper-->
					<div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid" id="kt_modal_create_app_stepper">
						<!--begin::Aside-->
						<div class="d-flex justify-content-center justify-content-xl-start flex-row-auto w-100 w-xl-300px">
							<!--begin::Nav-->
							<div class="stepper-nav ps-lg-10">
								<!--begin::Step 1-->
								<div class="stepper-item current" data-kt-stepper-element="nav">
									<!--begin::Line-->
									<div class="stepper-line w-40px"></div>
									<!--end::Line-->
									<!--begin::Icon-->
									<div class="stepper-icon w-40px h-40px">
										<i class="stepper-check fas fa-check"></i>
										<span class="stepper-number">1</span>
									</div>
									<!--end::Icon-->
									<!--begin::Label-->
									<div class="stepper-label">
										<h3 class="stepper-title">Select Booking</h3>
										<div class="stepper-desc">Select Booking & Date</div>
									</div>
									<!--end::Label-->
								</div>
								<!--end::Step 1-->
								<!--begin::Step 2-->
								<div class="stepper-item" data-kt-stepper-element="nav">
									<!--begin::Line-->
									<div class="stepper-line w-40px"></div>
									<!--end::Line-->
									<!--begin::Icon-->
									<div class="stepper-icon w-40px h-40px">
										<i class="stepper-check fas fa-check"></i>
										<span class="stepper-number">2</span>
									</div>
									<!--begin::Icon-->
									<!--begin::Label-->
									<div class="stepper-label">
										<h3 class="stepper-title">Schedule</h3>
										<div class="stepper-desc">List Schedule</div>
									</div>
									<!--begin::Label-->
								</div>
								<!--end::Step 2-->
								<!--begin::Step 3-->
								<div class="stepper-item" data-kt-stepper-element="nav">
									<!--begin::Line-->
									<div class="stepper-line w-40px"></div>
									<!--end::Line-->
									<!--begin::Icon-->
									<div class="stepper-icon w-40px h-40px">
										<i class="stepper-check fas fa-check"></i>
										<span class="stepper-number">3</span>
									</div>
									<!--end::Icon-->
									<!--begin::Label-->
									<div class="stepper-label">
										<h3 class="stepper-title">Form Booking</h3>
										<div class="stepper-desc">Input Booking Form </div>
									</div>
									<!--end::Label-->
								</div>
								<!--end::Step 3-->
								<!--begin::Step 4-->
								
								<!--end::Step 4-->
								<!--begin::Step 5-->
								{{-- <div class="stepper-item" data-kt-stepper-element="nav">
									<!--begin::Line-->
									<div class="stepper-line w-40px"></div>
									<!--end::Line-->
									<!--begin::Icon-->
									<div class="stepper-icon w-40px h-40px">
										<i class="stepper-check fas fa-check"></i>
										<span class="stepper-number">5</span>
									</div>
									<!--end::Icon-->
									<!--begin::Label-->
									<div class="stepper-label">
										<h3 class="stepper-title">Completed</h3>
										<div class="stepper-desc">Review and Submit</div>
									</div>
									<!--end::Label-->
								</div> --}}
								<!--end::Step 5-->
							</div>
							<!--end::Nav-->
						</div>
						<!--begin::Aside-->
						<!--begin::Content-->
						<div class="flex-row-fluid py-lg-5 px-lg-15">
							<!--begin::Form-->
							<form class="form" method="post" action="{{ url('add-loan-action') }}" id="kt_modal_create_app_form">
								
							
								<!--begin::Step 1-->
								<div class="current" data-kt-stepper-element="content">
									<div class="w-100">
										<!--begin::Input group-->
										<div class="fv-row mb-10">
											<div class="row">
												<!--begin::Col-->
												<div class="col-lg-6" onclick="loanRoom()">
													<!--begin::Option-->
													@csrf
													<input id="btn_loan_room" type="radio" class="btn-check" name="actionloan" checked="checked" value="room" id="kt_create_account_form_account_type_corporate" />
													<label class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center" for="kt_create_account_form_account_type_corporate">
														<!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
														<span class="svg-icon svg-icon-3x me-5">
															{{-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																<path opacity="0.3" d="M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15ZM13 12H11C10.5 12 10 12.4 10 13V16C10 16.5 10.4 17 11 17H13C13.6 17 14 16.6 14 16V13C14 12.4 13.6 12 13 12Z" fill="currentColor" />
																<path d="M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14ZM20 15H14V16C14 16.6 13.5 17 13 17H11C10.5 17 10 16.6 10 16V15H4C3.6 15 3.3 14.9 3 14.7V18C3 19.1 3.9 20 5 20H19C20.1 20 21 19.1 21 18V14.7C20.7 14.9 20.4 15 20 15Z" fill="currentColor" />
															</svg> --}}
															<i class="fas fa-door-open fs-3x"></i>
														</span>
														<!--end::Svg Icon-->
														<!--begin::Info-->
														<span class="d-block fw-bold text-start">
															<span class="text-dark fw-bolder d-block fs-4 mb-2">Booking A Room</span>
														</span>
														<!--end::Info-->
													</label>
													<!--end::Option-->
												</div>
												<!--end::Col-->
												<!--begin::Col-->
												<div class="col-lg-6" onclick="loanCar()">
													<!--begin::Option-->
													<input id="btn_loan_car" type="radio" class="btn-check" name="actionloan" value="car" id="kt_create_account_form_account_type_personal" />
													<label class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center mb-10" for="kt_create_account_form_account_type_personal">
														<!--begin::Svg Icon | path: icons/duotune/communication/com005.svg-->
														<span class="svg-icon svg-icon-3x me-5">
															{{-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																<path d="M20 14H18V10H20C20.6 10 21 10.4 21 11V13C21 13.6 20.6 14 20 14ZM21 19V17C21 16.4 20.6 16 20 16H18V20H20C20.6 20 21 19.6 21 19ZM21 7V5C21 4.4 20.6 4 20 4H18V8H20C20.6 8 21 7.6 21 7Z" fill="currentColor" />
																<path opacity="0.3" d="M17 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H17C17.6 2 18 2.4 18 3V21C18 21.6 17.6 22 17 22ZM10 7C8.9 7 8 7.9 8 9C8 10.1 8.9 11 10 11C11.1 11 12 10.1 12 9C12 7.9 11.1 7 10 7ZM13.3 16C14 16 14.5 15.3 14.3 14.7C13.7 13.2 12 12 10.1 12C8.10001 12 6.49999 13.1 5.89999 14.7C5.59999 15.3 6.19999 16 7.39999 16H13.3Z" fill="currentColor" />
															</svg> --}}
															<i class="fas fa-car-side fs-3x"></i>
														</span>
														<!--end::Svg Icon-->
														<!--begin::Info-->
														<span class="d-block fw-bold text-start">
															<span class="text-dark fw-bolder d-block fs-4 mb-2">Booking A Car</span>

														</span>
														<!--end::Info-->
													</label>
													<!--end::Option-->
												</div>
												<!--end::Col-->
											</div>
										</div>
										<!--end::Input group-->
										<!--begin::Input group-->
										<div class="fv-row">
											<!--begin::Label-->
											<label class="d-flex align-items-center fs-5 fw-bold mb-4">
												<span class="required">Select Date</span>
												<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Select your date for booking by clicking the date "></i>
											</label>
											<!--end::Label-->
											<!--begin:Options-->
											<div id="calendarRoom"></div>
											<div id="calendarCar" style="display: none"></div>
											<!--end:Options-->
										</div>
										<!--end::Input group-->
									</div>
								</div>
								<!--end::Step 1-->
								<!--begin::Step 2-->
								<div data-kt-stepper-element="content">
									<div class="w-100">
										<!--begin::Input group-->
										<div class="fv-row">
											<!--begin::Label-->
											<label class="d-flex align-items-center fs-5 fw-bold mb-4">
												<span id="title_schedule" class="required">List Schedule At </span>
												<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="All Schedules of booking in that day"></i>
											</label>
											<!--end::Label-->
											<!--begin:Option-->
											<label id="schedule_continer" class="d-flex flex-column cursor-pointer mb-5">
												{{-- filled by ajax --}}
											</label>
											<!--end::Option-->
											<!--begin::Label-->
											<label class="d-flex align-items-center fs-5 fw-bold mb-4">
												<span>Are you sure to add booking on this date?</span>
											</label>
											<!--end::Label-->
										</div>
										<!--end::Input group-->
									</div>
								</div>
								<!--end::Step 2-->
								<!--begin::Step 3-->
								<div data-kt-stepper-element="content">
									
									<div class="w-100" id="form_car" style="display: block;">
										<!--begin::Input group-->
										<div class="fv-row mb-10">
											<!--begin::Label-->
											<label class="required fs-5 fw-bold mb-2">Subject</label>
											<!--end::Label-->
											<!--begin::Input-->
											<textarea class="form-control form-control-lg form-control-solid" name="keperluan" placeholder="Type your subject of car booking" rows="3" required></textarea>
											<!--end::Input-->
										</div>
										<div class="row">
											<div class="col fv-row mb-10">
												<!--begin::Label-->
												<label class="required fs-5 fw-bold mb-2">Destination</label>
												<!--end::Label-->
												<!--begin::Input-->
												<input type="text" class="form-control form-control-lg form-control-solid" name="tujuan" placeholder="Type your destination" required/>
												<!--end::Input-->
											</div>
											<div class="col fv-row mb-10">
												<!--begin::Label-->
												<label class="required fs-5 fw-bold mb-2">Region</label>
												<!--end::Label-->
												<!--begin::Input-->
												<input type="text" class="form-control form-control-lg form-control-solid" name="wilayah" placeholder="Type your destination region" required/>
												<!--end::Input-->
											</div>
										</div>
										<!--end::Input group-->
										<!--begin::Input group-->
										<div class="fv-row">
											<!--begin::Label-->
											<label class="d-flex align-items-center fs-5 fw-bold mb-4">
												<span class="required">Type of Trip</span>
												<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Choose 'One way' to booking car for transport depart only, and choose 'Round Trip' for both"></i>
											</label>
											<!--end::Label-->
											<div class="row">
												<!--begin:Option-->
												<label class="col d-flex flex-stack cursor-pointer mb-5">
													<!--begin::Label-->
													<span class="d-flex align-items-center me-2">
														<!--begin::Input-->
														<span class="form-check form-check-custom form-check-solid me-3">
															<input class="form-check-input" type="radio" name="jenis_perjalanan" checked="checked" value="1" onclick="isOneWay()"/>
														</span>
														<!--end::Input-->
														<!--begin::Icon-->
														<span class="symbol symbol-50px me-3">
															<span class="symbol-label bg-light-primary">
																<i class="fas fa-arrow-right text-primary fs-2x"></i>
															</span>
														</span>
														<!--end::Icon-->
														<!--begin::Info-->
														<span class="d-flex flex-column">
															<span class="fw-bolder fs-6">One Way</span>
														</span>
														<!--end::Info-->
													</span>
													<!--end::Label-->
												</label>
												<!--end::Option-->
												<!--begin:Option-->
												<label class="col d-flex flex-stack cursor-pointer mb-5">
													<!--begin::Label-->
													<span class="d-flex align-items-center me-2">
														<!--begin::Input-->
														<span class="form-check form-check-custom form-check-solid me-3">
															<input class="form-check-input" type="radio" name="jenis_perjalanan" value="2" onclick="isRoundTrip()"/>
														</span>
														<!--end::Input-->
														<!--begin::Icon-->
														<span class="symbol symbol-50px me-3">
															<span class="symbol-label bg-light-primary">
																<i class="fas fa-exchange-alt text-primary fs-2x"></i>
															</span>
														</span>
														<!--end::Icon-->
														<!--begin::Info-->
														<span class="d-flex flex-column">
															<span class="fw-bolder fs-6">Round Trip</span>
														</span>
														<!--end::Info-->
													</span>
													<!--end::Label-->
												</label>
												<!--end::Option-->
											</div>
											
										</div>
										<!--end::Input group-->
										<br>
										<div class="row">
											<div class="col fv-row mb-10">
												<!--begin::Label-->
												<label class="required fs-5 fw-bold mb-2">Leave</label>
												<!--end::Label-->
												<!--begin::Input-->
												<input type="datetime-local" class="form-control form-control-lg form-control-solid" id="waktu_berangkat" name="waktu_berangkat" required/>
												<!--end::Input-->
											</div>
											<div id="return" class="col fv-row mb-10" style="display: none">
												<!--begin::Label-->
												<label class="required fs-5 fw-bold mb-2">Return</label>
												<!--end::Label-->
												<!--begin::Input-->
												<input type="datetime-local" class="form-control form-control-lg form-control-solid" id="waktu_pulang" name="waktu_pulang" required/>
												<!--end::Input-->
											</div>
										</div>
										<div class="fv-row mb-10">
											<!--begin::Label-->
											<label class="fs-6 fw-bold mb-2">Passengers 
											<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Include your name in passengers"></i></label>
											<!--End::Label-->
											<!--begin::Tagify-->
											<div class="w-100 chosen-modal"> 
												<select id="passanger_chosen" name="penumpang[]" multiple class="select2-selection select2-selection--multiple form-select form-select-lg form-select-solid" data-control="select2" data-dropdown-parent="#kt_modal_create_app" data-placeholder="Choose Passangers" >
													<option value=""></option>
												</select>
											</div>
											<!--end::Tagify-->
										</div>
									</div>
									<div class="w-100" id="form_room" style="display: block;">
										<!--begin::Input group-->
										<div class="fv-row mb-10">
											<!--begin::Label-->
											<label class="required fs-5 fw-bold mb-2">Agenda</label>
											<!--end::Label-->
											<!--begin::Input-->
											<textarea class="form-control form-control-lg form-control-solid" name="agenda" placeholder="Type your agenda of room booking" rows="3" required></textarea>
											<!--end::Input-->
										</div>
										<!--end::Input group-->
										<!--begin::Input group-->
										<div class="fv-row mb-10">
											<!--begin::Label-->
											<label class="d-flex align-items-center fs-5 fw-bold mb-4">
												<span class="required">With Company</span>
												<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Just write the Company name if it's not available"></i>
											</label>
											<!--end::Label-->
											<!--begin::Input-->
											<div class="w-100 chosen-modal">
												<select id="company_chosen" name="perusahaan" class="select2-selection select2-selection--single form-select form-select-lg form-select-solid" data-control="select2" data-dropdown-parent="#kt_modal_create_app" data-placeholder="Choose Company">
													<option value="" selected disabled>Choose Company</option>
													@foreach ($data['companies'] as $perusahaan)
													<option value="{{ $perusahaan->id }}" {{ (old('perusahaan') == $perusahaan->id)? 'selected' : '' }}>{{ $perusahaan->nama }}</option>
													@endforeach
												</select>
											</div>
											{{-- <input class="form-control form-control-lg form-control-solid" name="perusahaan" id="perusahaan" placeholder="" list="companies" required>
											<datalist id="companies" required>
												@foreach ($data['companies'] as $perusahaan)
												<option value="{{ $perusahaan->id }}" {{ (old('perusahaan') == $perusahaan->id)? 'selected' : '' }}>{{ $perusahaan->nama }}</option>
												@endforeach
											</datalist> --}}
											<!--end::Input-->
										</div>
										<!--end::Input group-->
										<!--begin::Input group-->
										<div class="row">
											{{-- <label class="required fs-5 fw-bold mb-2">Time</label>
											<div id=slider class="mt-10 mb-5 noUi-lg"></div>
											<input type="date" name="date" id="date" style="display: none"/>
											<input name="start" id="start" style="display: none"/>
											<input name="end" id="end" style="display: none"/>
											<div id="error_time" class="invalid-feedback mb-4"></div> --}}

											<div class="col fv-row mb-10">
												<!--begin::Label-->
												<label class="required fs-5 fw-bold mb-2">Start</label>
												<!--end::Label-->
												<!--begin::Input-->
												<input type="datetime-local" class="form-control form-control-lg form-control-solid" id="start" name="start" onchange="checkRoomBooking()" required/>
												<!--end::Input-->
											</div>
											<div id="return" class="col fv-row mb-10">
												<!--begin::Label-->
												<label class="required fs-5 fw-bold mb-2">End</label>
												<!--end::Label-->
												<!--begin::Input-->
												<input type="datetime-local" class="form-control form-control-lg form-control-solid" id="end" name="end" onchange="checkRoomBooking()" required/>
												<!--end::Input-->
											</div>
										</div>
										<!--end::Input group-->
										<!--begin::Input group-->
										<!--begin::Label-->
										<label class="d-flex align-items-center fs-5 fw-bold mb-4">
											<span class="required">Room</span>
											<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Fill in the start and end time first, then choose the room by clicking on a room name"></i>
										</label>
										<!--end::Label-->
										<!--begin::Input-->
										<div class="row mb-2" data-kt-buttons="true">
											<!--begin::Col-->
											<div class="col" id="rooms_container">
												  @foreach($data['rooms'] as $room)
													<label class="btn btn-bg-light btn-color-gray-400 p-1 m-2" style="cursor: default">
														<input name="ruangan" type="radio" class="btn-check" name="assets"  value="{{ $room->id }}" disabled/>
														<span class="fw-bolder fs-8">{{ $room->nama }}</span>
													</label>
												@endforeach
											</div>
											<!--end::Col-->
											<style>
												.hori-scroll{
													display: flex;
													flex-wrap: nowrap;
													overflow-x: scroll;
													
												}
												.item{
													flex: 0 0 auto;
													width: 8rem;
													height: 8rem;
												}
												.hori-scroll::-webkit-scrollbar {
													margin-top: 10rem;
													height: 1rem;
												}
											</style>
											<!--begin::Collapse-->
											<div id="facilities_container" class="">
												
											</div>
											<!--end::Collapse-->
										</div>
										<!--end::Input-->
										<!--end::Input group-->
										<br>
										<div class="fv-row mb-10 mt-2">
											<!--begin::Label-->
											<label class="fs-5 fw-bold mb-2">Color</label>
											<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Include your name in passengers"></i></label>
											<!--End::Label-->
											<!--begin::Input-->
												{{-- <input class="form-control d-flex align-items-center" value="" id="kt_modal_create_campaign_location" data-kt-flags-path="/ceres-html-pro/assets/media/flags/" /> --}}
												<div class="d-flex flex-wrap">
												<div class="m-1">
													<input class="color-picker-custom" type="radio" name="color" id="red" value="#DB2828" {{ (old('color')=='#DB2828')? 'checked' : '' }}/>
													<label class="label" for="red"><span class="red"></span></label>
												</div>
												
												<div class="m-1">
													<input class="color-picker-custom" type="radio" name="color" id="green" value="#21BA45" {{ (old('color')=='#21BA45')? 'checked' : '' }}/>
													<label class="label" for="green"><span class="green"></span></label>
												</div>
												
												<div class="m-1">
													<input class="color-picker-custom" type="radio" name="color" id="yellow" value="#FBBD08" {{ (old('color')=='#FBBD08')? 'checked' : '' }}/>
													<label class="label" for="yellow"><span class="yellow"></span></label>
												</div>
												
												<div class="m-1">
													<input class="color-picker-custom" type="radio" name="color" id="olive" value="#B5CC18" {{ (old('color')=='#B5CC18')? 'checked' : '' }}/>
													<label class="label" for="olive"><span class="olive"></span></label>
												</div>
												
												<div class="m-1">
													<input class="color-picker-custom" type="radio" name="color" id="orange" value="#F2711C" {{ (old('color')=='#F2711C')? 'checked' : '' }}/>
													<label class="label" for="orange"><span class="orange"></span></label>
												</div>
												
												<div class="m-1">
													<input class="color-picker-custom" type="radio" name="color" id="teal" value="#00B5AD" {{ (old('color')=='#00B5AD')? 'checked' : '' }}/>
													<label class="label" for="teal"><span class="teal"></span></label>
												</div>
												
												<div class="m-1">
													<input class="color-picker-custom" type="radio" name="color" id="blue" value="#2185D0" {{ (old('color')=='#2185D0')? 'checked' : '' }}/>
													<label class="label" for="blue"><span class="blue"></span></label>
												</div>
						
												<div class="m-1">
													<input class="color-picker-custom" type="radio" name="color" id="violet" value="#6435C9" {{ (old('color')=='#6435C9')? 'checked' : '' }}/>
													<label class="label" for="violet"><span class="violet"></span></label>
												</div>
												
												<div class="m-1">
													<input class="color-picker-custom" type="radio" name="color" id="purple" value="#A333C8" {{ (old('color')=='#A333C8')? 'checked' : '' }}/>
													<label class="label" for="purple"><span class="purple"></span></label>
												</div>
												
												<div class="m-1">
													<input class="color-picker-custom" type="radio" name="color" id="pink" value="#E03997" {{ (old('color')=='#E03997')? 'checked' : '' }}/>
													<label class="label" for="pink"><span class="pink"></span></label>
												</div>
											</div>
											<!--end::Input-->
										</div>
									</div>
								</div>
								<!--end::Step 3-->
								<!--begin::Step 4-->
								
								<!--end::Step 4-->
								<!--begin::Step 5-->
								{{-- <div data-kt-stepper-element="content">
									<div class="w-100 text-center">
										<!--begin::Heading-->
										<h1 class="fw-bolder text-dark mb-3">Done!</h1>
										<!--end::Heading-->
										<!--begin::Description-->
										<div class="text-muted fw-bold fs-3">Submit your loan.</div>
										<!--end::Description-->
										<!--begin::Illustration-->
										<div class="text-center p-5">
											<img src="assets/media/illustrations/dozzy-1/16.png" alt="" class="img-fluid w-50" />
										</div>
										<!--end::Illustration-->
									</div>
								</div> --}}
								<!--end::Step 5-->
								<!--begin::Actions-->
								<div class="d-flex flex-stack pt-10">
									<!--begin::Wrapper-->
									<div class="me-2">
										<button type="button" class="btn btn-lg btn-light-primary me-3" data-kt-stepper-action="previous">
										<!--begin::Svg Icon | path: icons/duotune/arrows/arr063.svg-->
										<span class="svg-icon svg-icon-3 me-1">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
												<rect opacity="0.5" x="6" y="11" width="13" height="2" rx="1" fill="black" />
												<path d="M8.56569 11.4343L12.75 7.25C13.1642 6.83579 13.1642 6.16421 12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75L5.70711 11.2929C5.31658 11.6834 5.31658 12.3166 5.70711 12.7071L11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25C13.1642 17.8358 13.1642 17.1642 12.75 16.75L8.56569 12.5657C8.25327 12.2533 8.25327 11.7467 8.56569 11.4343Z" fill="black" />
											</svg>
										</span>
										<!--end::Svg Icon-->Back</button>
									</div>
									<!--end::Wrapper-->
									<!--begin::Wrapper-->
									<div>
										<button type="submit" class="btn btn-lg btn-primary" data-kt-stepper-action="submit">
											<span class="indicator-label">Submit
											<!--begin::Svg Icon | path: icons/duotune/arrows/arr064.svg-->
											<span class="svg-icon svg-icon-3 ms-2 me-0">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
													<rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="black" />
													<path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="black" />
												</svg>
											</span>
											<!--end::Svg Icon--></span>
											<span class="indicator-progress">Please wait...
											<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
										</button>
										<button type="button" id="btn_continue" class="btn btn-lg btn-primary" data-kt-stepper-action="next" style="display: none">Continue
										<!--begin::Svg Icon | path: icons/duotune/arrows/arr064.svg-->
										<span class="svg-icon svg-icon-3 ms-1 me-0">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
												<rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="black" />
												<path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="black" />
											</svg>
										</span>
										<!--end::Svg Icon--></button>
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Actions-->
							</form>
							<!--end::Form-->
						</div>
						<!--end::Content-->
					</div>
					<!--end::Stepper-->
					</div>
					<!--end::Modal body-->
				</div>
				<!--end::Modal content-->
			</div>
			<!--end::Modal dialog-->
		</div>
	</div>

<!--begin::Modal - Upgrade plan-->
<div class="modal fade" id="kt_modal_upgrade_plan" tabindex="-1" aria-hidden="true">
			<!--begin::Modal dialog-->
			<div class="modal-dialog modal-xl">
				<!--begin::Modal content-->
				<div class="modal-content rounded">
					<!--begin::Modal header-->
					<div class="modal-header justify-content-end border-0 pb-0">
						<!--begin::Close-->
						<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
							<!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
							<span class="svg-icon svg-icon-1">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
									<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
								</svg>
							</span>
							<!--end::Svg Icon-->
						</div>
						<!--end::Close-->
					</div>
					<!--end::Modal header-->
					<!--begin::Modal body-->
					<div class="modal-body pt-0 pb-15 px-5 px-xl-20">
						<!--begin::Heading-->
						<div class="mb-13 text-center">
							<h1 class="mb-3">PEMBERITAHUAN</h1>
							<div class="text-muted fw-semibold fs-5">Untuk Seluruh Karyawan 
							<a href="#" class="link-primary fw-bold">PT UNITED TRACTORS PANDU ENGINEERING</a>.</div>
						</div>
						<!--end::Heading-->
						<!--begin::Plans-->
						<div class="d-flex flex-column">
							
							<!--begin::Row-->
							<div class="row mt-10">
                                <!--begin::Col-->
								<div class="col-lg-6">
									<!--begin::Tab content-->
									<div class="tab-content rounded h-100 bg-light p-10">
										<!--begin::Tab Pane-->
										<div class="tab-pane fade show active" id="kt_upgrade_plan_startup">
                                        <p>
                                            <center><h3>E-Letter Performance & Competence</h3></center><br><br>
                                            Dengan Hormat,<br><br>
                                            Kami, atas nama manajemen PT United Tractors Pandu Engineering, mengucapkan terima kasih atas kinerja Saudara/i selama satu tahun terkahir. Komitmen dan dedikasi profesional yang telah Saudara/i berikan memiliki kontribusi nyata terhadap performa perusahaan di satu tahun terkahir.
                                            <br><br>
                                            Kami berharap agar Saudara/i, dapat terus meningkatkan kinerja dan berkontribusi maksimal untuk meningkatkan performa perusahaan di tahun-tahun mendatang.<br> <br> <br><br><br>
                                        </p>
                                        <table>
                                            <tr style="height: 0;">
                                                <td width="300"><strong>PT United Tractors Pandu Engineering</strong>
                                                </td>
                                            </tr>
                                            
                                            <tr style="height: 0;">
                                                <td width="300"> <br> <img src="https://www.patria.co.id/images/patria.png" alt="Logo" height="25"> <br>
                                                </td>
                                            </tr>
                                            
                                            <tr style="height: 0;">
                                                <td width="300"><u><strong>Bayu Cahyono</strong></u><br> <strong>Presiden Direktur</strong>
                                                </td>
                                            </tr>
                                        </table>
										</div>
										<!--end::Tab Pane-->
									</div>
									<!--end::Tab content-->
								</div>
								<!--end::Col-->
								<!--begin::Col-->
								<div class="col-lg-6 mb-10 mb-lg-0">
									<!--begin::Tabs-->
                                    <a  class="mb-12">
                                    <img alt="Logo" src="{{ asset('public/assets/global/img/e-letter.jpg') }}"style="width:100%;" /></a>
									<form action="{{ url('get-eletter-performance') }}" method="POST">
													@csrf
									<div class="row">
											<div class="col-md-5">
												<div id="performancestring">
													<div class="d-flex align-items-center fs-2 fw-bold flex-wrap">#Performance</div>
													<div class="fw-semibold opacity-75">E-Letter Performance</div>
												</div>
													<input style="display:none;" id="performancepassword" required type="password" name="keypassword" class="form-control form-control-solid" placeholder="Type your E-Letter Password">
											</div>
											<div class="col-md-3">
												<select name="year" style="display:none;" id="performanceyear" required class="form-control form-control-solid">
													<option value="2022">2022</option>
													<option value="2021">2021</option>
												</select>
											</div>
											<div class="col-md-4">
												<button style="display:none;" type="submit" id="performancesubmit" class="btn btn-sm btn-info">Submit</button>
												<a onclick="performanceDownload()" id="performancedownload" class="btn btn-sm btn-success">Download</a>
											</div>
									</div>
									</form> 
									<br> <br>
									<form action="{{ url('get-eletter-competence') }}" method="POST">
													@csrf
									<div class="row">
											<div class="col-md-5">
												<div id="competencestring">
													<div class="d-flex align-items-center fs-2 fw-bold flex-wrap">#Competence</div>
													<div class="fw-semibold opacity-75">E-Letter Competence</div>
												</div>
													<input style="display:none;" id="competencepassword" required type="password" name="keypassword" class="form-control form-control-solid" placeholder="Type your E-Letter Password">
											</div>
											<div class="col-md-3">
												<select name="year" style="display:none;" id="competenceyear" required class="form-control form-control-solid">
													<option value="2022">2022</option>
													<option value="2021">2021</option>
												</select>
											</div>
											<div class="col-md-4">
												<button style="display:none;" type="submit" id="competencesubmit" class="btn btn-sm btn-info">Submit</button>
												<a onclick="competenceDownload()" id="competencedownload" class="btn btn-sm btn-success">Download</a>
											</div>
									</div>
									</form>
									<!--end::Tabs-->
								</div>
								<!--end::Col-->
								
							</div>
							<!--end::Row-->
						</div>
						<!--end::Plans-->
						<!--begin::Actions-->
						<!-- <div class="d-flex flex-center flex-row-fluid pt-12">
							<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary" id="kt_modal_upgrade_plan_btn">
								<span class="indicator-label">Upgrade Plan</span>
								<span class="indicator-progress">Please wait... 
								<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
							</button>
						</div> -->
						<!--end::Actions-->
					</div>
					<!--end::Modal body-->
				</div>
				<!--end::Modal content-->
			</div>
			<!--end::Modal dialog-->
		</div>
		<div class="modal fade" id="kt_modal_submission" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-xl">
            <!--begin::Modal content-->
            <div class="modal-content rounded">
                <!--begin::Modal header-->
                <div class="modal-header justify-content-end border-0 pb-0">
                    <!--begin::Close-->
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                        <span class="svg-icon svg-icon-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="6" y="17.3137" width="16"
                                    height="2" rx="1" transform="rotate(-45 6 17.3137)"
                                    fill="currentColor" />
                                <rect x="7.41422" y="6" width="16" height="2"
                                    rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body pt-0 pb-15 px-5 px-xl-20">
                    <!--begin::Heading-->
                    <div class="mb-13 text-center">
                        <h1 class="mb-3">New Submission</h1>
                        <div class="text-muted fw-semibold fs-5">Form Submission
                            <a href="#" class="link-primary fw-bold">For Work From Anyware</a>.
                        </div>
                    </div>
                    <!--end::Heading-->
                    <!--begin::Plans-->
                    <div class="d-flex flex-column">

                        <!--begin::Row-->
                            <form action="{{ url('post-submission') }}" method="POST">
                        <div class="row mt-10">
                            <!--begin::Col-->
                                @csrf
                                
                            <div class="col-lg-6 mb-10 mb-lg-0">
                                <div class="w-100" id="form_submission" style="display: block;">
                                    <div class="row">
                                        <div class="col fv-row mb-10">
                                            <label class="required fs-5 fw-bold mb-2">Start</label>
                                            <input type="date"
                                                class="form-control form-control-lg form-control-solid"
                                                name="start_date" onchange="getdatediff(this)" min="{{ date('Y-m-d', strtotime('+ 1 day')) }}" 
                                                id="start_date"  
                                                required />
                                        </div>
                                        <div id="return" class="col fv-row mb-10">
                                            <label class="required fs-5 fw-bold mb-2">End</label>
                                            <input type="date" onchange="getdateCount(this)" 
                                                class="form-control form-control-lg form-control-solid"
                                                    name="end_date" 
                                                    id="end_date" 
                                                required />
                                        </div>
                                    </div>
                                    <div class="fv-row mb-10">
                                        <label class="required fs-5 fw-bold mb-2">Search Destination</label>
                                        <div id="custom-search-input">
                                            <div class="input-group col-md-12">
                                                <input id="search" autocomplete="none" onkeyup="searchLoc(this.value)" type="text" class="form-control input-lg" placeholder="Search" />
                                            </div>
                                            <ul class="list-group" id="search-result" style="max-height: 250px; overflow-y:auto;">
                                            </ul>
                                        </div>
                                        <center><span style="display:none"  id="search-loading"><span class='spinner-border spinner-border-sm align-middle ms-2'></span></span></center>
                                    </div>
                                    <div class="fv-row mb-10">
                                        <label class="required fs-5 fw-bold mb-2">Reason</label>
                                        <textarea class="form-control form-control-lg form-control-solid" name="reason"
                                            placeholder="Type your Reason" rows="3" required></textarea>
                                    </div>
                                   
                                </div>
                            </div> 
                                <br>
                            <div class="col-lg-6">
                                <!--begin::Tab content-->
                                <div class="tab-content rounded h-100 p-10" style="background-color: #e6f0f7;">
                                    <!--begin::Tab Pane-->
                                    <div class="tab-pane fade show active" id="kt_upgrade_plan_startup">
                                        <div class="row">
                                            <div class="col fv-row mb-10">
                                                <label class="fs-5 fw-bold mb-2">Submission Information</label><br>
                                                <span>Wfa Days  (<strong id="workday">-</strong> Work Days)</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col fv-row mb-10">
                                                <label class="required fs-5 fw-bold mb-2">Country</label>
                                                <input type="text" value=""
                                                    class="form-control form-control-lg form-control-solid"
                                                   name="country_code" 
                                                   id="country" 
                                                    required readonly />
                                            </div>
                                            <div class="col fv-row mb-10">
                                                <label class="required fs-5 fw-bold mb-2">Distance (Km)</label>
                                                <input type="text" class="form-control form-control-lg form-control-solid"  name="distance"  id="distance" required readonly/>
                                                <input type="hidden" class="form-control form-control-lg form-control-solid"  name="lat"  id="lat" required readonly/>
                                                <input type="hidden" class="form-control form-control-lg form-control-solid"  name="long"  id="long" required readonly/>
                                            </div>
                                        </div>
                                        <div class="row" style="display:none">
                                            <div class="col fv-row mb-10">
                                                <label class="required fs-5 fw-bold mb-2">City</label>
                                                <input type="text" value=""
                                                    class="form-control form-control-lg form-control-solid"
                                                   name="city" 
                                                   id="city" 
                                                    required readonly/>
                                            </div>
                                            <div id="return" class="col fv-row mb-10">
                                                <label class="required fs-5 fw-bold mb-2">Province</label>
                                                <input type="text" value=""
                                                    class="form-control form-control-lg form-control-solid"
                                                     name="province" 
                                                     id="province" 
                                                    required readonly/>
                                            </div>
                                            
                                        </div>
                                        <div class="fv-row mb-10">
                                            <label class="required fs-5 fw-bold mb-2">Address</label>
                                            <textarea class="form-control form-control-lg form-control-solid" name="address" id="address"
                                                placeholder="Type your agenda of room booking" rows="3" required readonly></textarea>
                                        </div>
                                       
                                    </div>
                                </div>
                            </div>
                        </div> 
                        </div>
                            <br>
                                <div class="col-lg-6 fv-row mb-10" style="float:right">
                                    <button onclick="mySubmitLoad()"
                                        class="submit-button btn btn-lg btn-primary w-100 mb-5">
                                        <span id="submit-label" class="indicator-label">Submit
                                            <span id="submit-progress" class="indicator-progress"
                                                style="display: none">Please wait... <span
                                                    class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                        </span>
                                    </button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
  @include('fe-layouts.footer')
@yield('myscript')

</body>

<script>
    
    function getdatediff(e){
        startDate = new Date(e.value);
        var endDate = "", noOfDaysToAdd = 20, count = 0;

        while(count < noOfDaysToAdd){
            endDate = new Date(startDate.setDate(startDate.getDate() + 1));
            if(endDate.getDay() != 0 && endDate.getDay() != 6){
            count++;
            }
        }
        
        var dd = endDate.getDate();
        var mm = endDate.getMonth() + 1; //January is 0!
        var yyyy = endDate.getFullYear();
            
        datemax = yyyy + '-' + mm + '-' + dd;
        // console.log(new Date(datemax).toISOString().split("T")[0]);
        document.getElementById('end_date').max = new Date(datemax).toISOString().split("T")[0];
        document.getElementById('end_date').min =e.value;
    }

    function parseDate(input) {
      var parts = input.match(/(\d+)/g);
      return new Date(parts[0], parts[1]-1, parts[2]); 
    }
    function getdateCount(e){
        startDate = new Date(document.getElementById("start_date").value);
        end = new Date(e.value);
        
      let d1 = (document.getElementById("start_date").value);
      let d0 = (e.value);
        console.log(d1);

      var holidays = ['2021-08-17','2021-08-11'];
      var startDate = parseDate(d1);
      var endDate = parseDate(d0);  

      var millisecondsPerDay = 86400 * 1000; // Day in milliseconds
      startDate.setHours(0, 0, 0, 1);  // Start just after midnight
      endDate.setHours(23, 59, 59, 999);  // End just before midnight
      var diff = endDate - startDate;  // Milliseconds between datetime objects    
      var days = Math.ceil(diff / millisecondsPerDay);

      // Subtract two weekend days for every week in between
      var weeks = Math.floor(days / 7);
      days -= weeks * 2;

      // Handle special cases
      var startDay = startDate.getDay();
      var endDay = endDate.getDay();
        
      // Remove weekend not previously removed.   
      if (startDay - endDay > 1) {
        days -= 2;
      }
      // Remove start day if span starts on Sunday but ends before Saturday
      if (startDay == 0 && endDay != 6) {
        days--;  
      }
      // Remove end day if span ends on Saturday but starts after Sunday
      if (endDay == 6 && startDay != 0) {
        days--;
      }
      /* Here is the code */
      holidays.forEach(day => {
        if ((day >= d0) && (day <= d1)) {
          /* If it is not saturday (6) or sunday (0), substract it */
          if ((parseDate(day).getDay() % 6) != 0) {
            days--;
          }
        }
      });

        document.getElementById("workday").innerHTML = days-1;
    }
    function searchLoc(params) {
            document.getElementById("search-loading").style.display = "block";
        $('#search-result').empty(); // Empty >
        APP_URL = '{{url('/')}}' ;
        $.ajax({
            type:"GET",
            url: "{{url('search-location')}}/"+params,
            dataType: "json",                  
            success:function(data){
            document.getElementById("search-loading").style.display = "none";
                if(data['meta']['code']==200){
	                for (i = 0; i < data['addresses'].length; i++) {
	                    var ul = '<li class="list-group-item"><a type="submit" onclick="setDistance('+data['addresses'][i]['latitude']+','+data['addresses'][i]['longitude']+',\''+data['addresses'][i]['country']+'\',\''+data['addresses'][i]['state']+'\',\''+data['addresses'][i]['city']+'\',\''+data['addresses'][i]['formattedAddress']+'\')">'+data['addresses'][i]['placeLabel']+'<br> '+data['addresses'][i]['formattedAddress']+'</a type="submit"></li>';
	                    $("#search-result").append(ul); 
	                }  
	            }else{
	            	var ul = '<li class="list-group-item">Location Not Found</li>';
	                    $("#search-result").append(ul); 
	            }
            }
        });
    }
    
    function setDistance(lat,long,country,state,city,address) {
	    // document.getElementById("search").value = params;
        $('#search-result').empty();
        // document.getElementById("search").value = "";
        document.getElementById("country").value = country;
        document.getElementById("province").value = state;
        document.getElementById("city").value = city;
        document.getElementById("address").value = address;
        document.getElementById("lat").value = lat;
        document.getElementById("long").value = long;
        var daterange= document.getElementById("start_date").value + ' - '+ document.getElementById("end_date").value;
		let latvalue = lat.toString();
		let latval = latvalue.search("-");
        if(latval==null){
        	latvalue = '-'+lat;
        }
        if(country == "Indonesia"){
	        $.ajax({
	            type:"GET",
	            url: "{{url('distance')}}/"+latvalue+'/'+long,
	            dataType: "json",                  
	            success:function(data){
	                if(data['meta']['code']==200){
	                	document.getElementById("distance").value = (data['routes']['car']['distance']['value']/1000).toFixed(1);
	                }else{
	                	document.getElementById("distance").value = "International";
	                }
	            }
	        });
        }else{
        	document.getElementById("distance").value = "International";
		}

    }
</script>
</html>