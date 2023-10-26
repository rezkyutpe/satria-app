@extends('fe-layouts.master')

@section('content')
<!--begin::Container-->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
	<!--begin::Post-->
	<div class="content flex-row-fluid" id="kt_content">
		<!--begin::Index-->
		<div class="card card-page">
			<!--begin::Card body-->
			<div class="card-body">
				<!--begin::Row-->
				<div class="row gy-5 g-xl-8">

					<!--begin::Col-->
					<div class="col-xxl-6">
						<!--begin::Row-->
						<div class="row g-5 g-xl-8" style="background-color: #ff0; max-height: 70%;">
							<!--begin::Col-->
							<div class="col-xxl-6">
								<!--begin::Statistics Widget 1-->
								<div class="card card-xxl-stretch-50 mb-5 mb-xl-8">
									<!--begin::Body-->
									<div class="card-body d-flex flex-column justify-content-between p-0">
										<!--begin::Hidden-->
										<div class="d-flex flex-column px-9 pt-5">
											<!--begin::Number-->
											<div id="countticket" class="text-success fw-boldest fs-2hx"> </div>
											<!--end::Number-->
											<!--begin::Description-->
											<span class="text-gray-400 fw-bold fs-6">Ticket Requested Today</span>
											<!--end::Description-->
										</div>
										<!--end::Hidden-->
										<!--begin::Chart-->
										<div id="kt_mixed_widget_1_statistics"  class="statistics-widget-1-chart card-rounded-bottom" data-kt-ticket="0,0,0" data-kt-chart-color="success" style="height: 150px"></div>
										
										<!--end::Chart-->
									</div>
									<!--end::Body-->
								</div>
								<!--end::Statistics Widget 1-->
								<!--begin::Mixed Widget 1-->
								<div class="card card-xxl-stretch-50 mb-xxl-8">
									<!--begin::Body-->
									<div class="card-body pt-5">
										<!--begin::Chart-->
										<div id="kt_mixed_widget_1_chart"  data-kt-percent="0" class="mb-n15" ></div>
										<span class="badge badge-lg badge-light-warning w-100 text-gray-800 text-start d-flex align-items-center">
										Ticket Percentage Today </span>
										<!--end::Label-->
									</div>
									<!--end::Body-->
								</div>
								<!--end::Mixed Widget 1-->
							</div>
							<!--end::Col-->
							<!--begin::Col-->
							<div class="col-xxl-6">
								<!--begin::Mixed Widget 2-->
								<div class="card card-xxl-stretch-50 mb-5 mb-xl-8">
									<!--begin::Body-->
									<div class="card-body d-flex flex-column justify-content-between p-0">
										<!--begin::Hidden-->
										<div class="d-flex flex-column px-9 pt-5">
											<!--begin::Number-->
											<span id="countpr"  class="text-primary fw-boldest fs-2hx"></span>
											<!--end::Number-->
											<!--begin::Description-->
											<span class="text-gray-400 fw-bold fs-6">PR Requested This Month</span>
											<!--end::Description-->
										</div>
										<!--end::Hidden-->
										<!--begin::Chart-->
										<div id="kt_mixed_widget_2_chart" class="mx-3" data-kt-pr="0,0,0,0" data-kt-color="primary" style="height: 175px"></div>
										<!--end::Chart-->
									</div>
								</div>
								<!--end::Mixed Widget 2-->
								<!--begin::Engage widget 1-->
								<div class="card card-xxl-stretch-50" style="background-color: #1C53E1">
														<!--begin::Card body-->
									<div class="card-body d-flex align-items-end p-0 pt-10">
										<!--begin::Wrapper-->
										<div class="flex-grow-1 ps-9 pb-9">
											<span class="badge badge-lg w-100 text-gray-800 text-start d-flex align-items-center">
											<i class="fas fa-exclamation-circle text-warning me-3 fs-3"></i> <span class="text-white">New PR</span> </span>
											<div class="pt-8">
												<div id="tblopenpr"></div>
											</div>
											<!--end::Items-->
											<!--begin::Link-->
											<a href="{{ url('pr-list') }}" class="btn btn-sm btn-success" >Go to PR List</a>
											<!--end::Link-->
										</div>
										<!--end::Wrapper-->
										<img class="mh-200px" alt="" src="{{ asset('public/assets/theme/dist/assets/media/svg/illustrations/engage.svg') }}" />
									</div>
									<!--end::Card body-->
								</div>
													<!--end::Engage widget 1-->
								<!--end::Engage widget 1-->
							</div>
							<!--end::Col-->
						</div>
						<!--end::Row-->
					</div>
					<!--end::Col-->
					<!--begin::Col-->
					<div class="col-xxl-6">
						<!--begin::Table Widget 1-->
						<div class="card card-xxl-stretch">
							<!--begin::Header-->
							<div class="card-header border-0 pt-5 pb-3">
								<!--begin::Card title-->
								<h3 class="card-title fw-bolder text-gray-800 fs-2">New Open Ticket</h3>
								
									<span id="countopenticket" class="text-gray-400 mt-2 fw-bold fs-6"> Open Ticket</span>
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class="card-body py-0">
								<!--begin::Table-->
								<div class="table-responsive">
									<table class="table align-middle table-row-bordered table-row-dashed gy-5" id="kt_table_widget_1">
										<!--begin::Table body-->
										<tbody id="tblopenticket">
											<!--begin::Table row-->
											
											
										</tbody>
										<!--end::Table body-->
									</table>
								</div>
								<!--end::Table-->
							</div>
							<!--end::Body-->
						</div>
						<!--end::Table Widget 1-->
					</div>
					<!--end::Col-->
				</div>
				<!--end::Row-->
				<!--begin::Row-->
				<div class="row g-5 g-xl-8">
					<!--begin::Col-->
					<div class="col-xxl-6">
						<!--begin::List Widget 4-->
						<div class="card card-xl-stretch mb-5 mb-xl-8">
							<!--begin::Header-->
							<div class="card-header align-items-center border-0 mt-5">
								<h3 class="card-title align-items-start flex-column">
									<span class="fw-bolder text-dark fs-2">Your On Progress Ticket </span>
									<span id="countactiveticket"  class="text-gray-400 mt-2 fw-bold fs-6"> Active Ticket</span>
								</h3>
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class="card-body pt-1">
								<div class="table-responsive">
									<table class="table align-middle table-row-bordered table-row-dashed gy-5" id="kt_table_widget_1">
										<tr class="text-start text-gray-400 fw-boldest fs-7 text-uppercase">
											<th class="min-w-150px px-0">Ticket</th>
											<th class="min-w-150px">From</th>
											<th class="text-end pe-2 min-w-150px">Action</th>
										</tr>
										<tbody id="exampleid">
										</tbody>
										<!--end::Table body-->
									</table>
								</div>
							</div>
							<!--end::Body-->
						</div>
						<!--end::List Widget 4-->
					</div>
					<!--end::Col-->
					<!--begin::Col-->
					<div class="col-xxl-6">
						<!--begin::List Widget 5-->
						<div class="card card-xl-stretch mb-5 mb-xl-8">
							<!--begin::Header-->
							<div class="card-header border-0 pt-5">
								<h3 class="card-title align-items-start flex-column">
									<span class="card-label fw-bolder text-dark">Trends</span>
									<span class="text-muted mt-1 fw-bold fs-7">This Month</span>
								</h3>
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class="card-body pt-5">
								<div class="d-flex align-items-sm-center mb-7">
									<!--begin::Symbol-->
									<div class="symbol symbol-circle symbol-50px me-5">
										<span class="symbol-label">
											<img id="topassistimg" class="h-50 align-self-center" alt="" />
										</span>
									</div>
									<div class="d-flex align-items-center flex-row-fluid flex-wrap">
										<div class="flex-grow-1 me-2">
											<a class="text-gray-800 text-hover-primary fs-6 fw-bolder" id="topassistname"></a>
											<span class="text-muted fw-bold d-block fs-7">Top Assist</span>
										</div>
										<span class="badge badge-light fw-bolder my-2" id="topassistcount"> </span>
									</div>
								</div>
								<div class="d-flex align-items-sm-center mb-7">
									<div class="symbol symbol-circle symbol-50px me-5">
										<span class="symbol-label">
											<img id="topreporterimg"  class="h-50 align-self-center" alt="" />
										</span>
									</div>
									<div class="d-flex align-items-center flex-row-fluid flex-wrap">
										<div class="flex-grow-1 me-2">
											<a class="text-gray-800 text-hover-primary fs-6 fw-bolder" id="topreportername"></a>
											<span class="text-muted fw-bold d-block fs-7">Top Reporter</span>
										</div>
										<span class="badge badge-light fw-bolder my-2" id="topreportercount"> </span>
									</div>
								</div>
								<div class="d-flex align-items-sm-center mb-7">
									<div class="symbol symbol-circle symbol-50px me-5">
										<span class="symbol-label">
											<img src="{{ asset('public/assets/theme/dist/assets/media/svg/brand-logos/plurk.svg') }}" class="h-50 align-self-center" alt="" />
										</span>
									</div>
									<div class="d-flex align-items-center flex-row-fluid flex-wrap">
										<div class="flex-grow-1 me-2">
											<a class="text-gray-800 text-hover-primary fs-6 fw-bolder" id="topslaname"></a>
											<span class="text-muted fw-bold d-block fs-7">Top Issue</span>
										</div>
										<span class="badge badge-light fw-bolder my-2" id="topslacount"> </span>
									</div>
								</div>
							</div>
							<!--end::Body-->
						</div>
						<!--end::List Widget 5-->
					</div>
					<!--end::Col-->
				</div>
				
			</div>
			<!--end::Card body-->
		</div>
		<!--end::Index-->
	</div>
	<!--end::Post-->
</div>
					<!--end::Container-->
@endsection
@section('myscript')

<div class="modal fade" id="action-ticket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-650px">
        <div class="modal-content">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                <span class="svg-icon svg-icon-2x svg-icon-light"> <i class="fas fa-times text-danger fs-2"></i></span>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15">
                <div id="ticketAction"></div>              
               
	        </div>
	    </div>
</div>	


<script type="text/javascript">
$(document).ready(function(){
 	percent();
	loadajax();
});

function percent() {
 	var respond_time = document.getElementById("respond_time").value;
 	var resolution_time = document.getElementById("respond_time").value;
    var diff = Math.floor((new Date() - new Date(respond_time)));
	var msec = diff;
	var hh = Math.floor(msec / 1000 / 60 / 60);
	// msec -= hh * 1000 * 60 * 60;
	var mm = Math.floor(msec / 1000 / 60);
	var percentresolve = (Math.round(mm/(resolution_time*60)*100));
	$("#percentresolve").empty();
	var div = `<div class="d-flex flex-column w-100 me-2 mt-2">
					<span class="text-gray-400 me-2 fw-boldest mb-2">`+percentresolve+`%</span>
					<div class="progress bg-light-danger w-100 h-5px">
						<div class="progress-bar bg-danger" role="progressbar" style="width: `+percentresolve+`%"></div>
					</div>
				</div>`;
	$("#percentresolve").append(tr_str);
}
setInterval(loadajax, 5000);
function loadajax() {
 		var statistics = document.getElementById("kt_mixed_widget_1_statistics");
 		var percent = document.getElementById("kt_mixed_widget_1_chart");
 		var bar = document.getElementById("kt_mixed_widget_2_chart");
        APP_URL = '{{url('/')}}' ;
ASSET_URL = '{{asset('/public')}}' ;
        $.ajax({
            url: APP_URL+'/get-dashboard',
            type: 'get',
            dataType: 'json',         
            cache: true, 
            success: function (response) {
			    $("#tblopenticket").empty();
			    $("#tblopenpr").empty();
			    $("#exampleid").empty();
                console.log(response);
				percent.setAttribute('data-kt-percent', response['ticketpercentage']);
				statistics.setAttribute('data-kt-ticket', response['dialyrange']);
				bar.setAttribute('data-kt-pr', response['rangepr']);
          		document.getElementById("countticket").innerHTML= response['dialyticket'].length;
          		document.getElementById("countopenticket").innerHTML= response['openticket'].length+ " Open Ticket";
          		document.getElementById("countactiveticket").innerHTML= response['prosesticketbyassist'].length+ " Active Ticket";
          		document.getElementById("countpr").innerHTML= response['dialypr'].length;
          		document.getElementById("topassistname").innerHTML= response['topassist'][0]['assist_name'];
          		document.getElementById("topassistcount").innerHTML= response['topassist'][0]['count'];
          		document.getElementById("topreportername").innerHTML= response['topreporter'][0]['reporter_name'];
          		document.getElementById("topreportercount").innerHTML= response['topreporter'][0]['count'];
          		document.getElementById("topslaname").innerHTML= response['topsla'][0]['sla_name'];
          		document.getElementById("topslacount").innerHTML= response['topsla'][0]['count'];
			    document.getElementById("topassistimg").src = ASSET_URL+`/assets/global/img/no-profile.jpg`;
          		if(response['topassist'][0]['assist_photo']!=null){
			    	document.getElementById("topassistimg").src = ASSET_URL+`/profile/`+response['topassist'][0]['assist_photo'];
			    } 
			    document.getElementById("topreporterimg").src = ASSET_URL+`/assets/global/img/no-profile.jpg`;
          		if(response['topreporter'][0]['reporter_photo']!=null){
			    	document.getElementById("topreporterimg").src = ASSET_URL+`/profile/`+response['topreporter'][0]['reporter_photo'];
			    } 
    			if(response['openpr'].length > 0){
			        for(var i=0; i<response['openpr'].length; i++){
			            var tr_str = `<div class="d-flex align-items-center mb-3">
										<span class="svg-icon svg-icon-3 svg-icon-white me-2">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
												<path d="M6.8 15.8C7.3 15.7 7.9 16 8 16.5C8.2 17.4 8.99999 18 9.89999 18H17.9C19 18 19.9 17.1 19.9 16V8C19.9 6.9 19 6 17.9 6H9.89999C8.79999 6 7.89999 6.9 7.89999 8V9.4H5.89999V8C5.89999 5.8 7.69999 4 9.89999 4H17.9C20.1 4 21.9 5.8 21.9 8V16C21.9 18.2 20.1 20 17.9 20H9.89999C8.09999 20 6.5 18.8 6 17.1C6 16.5 6.3 16 6.8 15.8Z" fill="black" />
												<path opacity="0.3" d="M12 9.39999H2L6.3 13.7C6.7 14.1 7.3 14.1 7.7 13.7L12 9.39999Z" fill="black" />
											</svg>
										</span>
										<!--end::Svg Icon-->
										<span class="fw-bolder fs-7 text-white">`+response['openpr'][i]['pr_number']+`</span>
									</div>`;
			            $("#tblopenpr").append(tr_str);
			        }
			    }
    			var tr_str = `<tr class="text-start text-gray-400 fw-boldest fs-7 text-uppercase">
												<th class="min-w-200px px-0">Tickets</th>
												<th class="min-w-125px">From</th>
												<th class="text-end pe-2 min-w-70px">Action</th>
											</tr>`;
				KTWidgetsLoads.init();
			    $("#tblopenticket").append(tr_str);
    			if(response['openticket'].length > 0){
			        for(var i=0; i<response['openticket'].length; i++){
			        	var imgopen= ASSET_URL+`/assets/global/img/no-profile.jpg`;
			        	if(response['openticket'][i]['media']!=null){
			        		imgopen = ASSET_URL+`/ticket/`+response['openticket'][i]['media'];
			        	}
			            var tr_str = `<tr>
										<td class="p-0">
											<div class="d-flex align-items-center">
												<div class="symbol symbol-50px me-2">

													<span class="symbol-label bg-light-danger">
								                        	<a data-fslightbox="lightbox-hot-sales" href="`+imgopen+`"><span class="badge badge-light-danger"><img alt="" class="w-25px" src="`+imgopen+`" /></span></a>
								                    </span>
												</div>
												<div class="ps-3">
													<a class="text-gray-800 fw-boldest fs-5 text-hover-primary mb-1">#`+response['openticket'][i]['ticket_id']+`</a>
													<span class="text-gray-400 fw-bold d-block">`+response['openticket'][i]['subject']+`</span>
												</div>
											</div>
										</td>
										<td>
											<div class="d-flex flex-column w-100 me-2 mt-2">
												<span class="text-gray-600 me-2 fw-boldest mb-2">`+response['openticket'][i]['reporter_name']+`</span>
													<span class="text-gray-400 fw-bold d-block">`+response['openticket'][i]['dept_reporter']+`</span>
											</div>
										</td>
										
										<td class="pe-0 text-end">
											<a onclick='openticketaction()' class="dropbtn btn btn-sm btn-icon btn-color-gray-500 btn-active-color-primary"> Actions
												
											</a>
											<div id="openDropdown" class="dropdown-content menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
												<div class="menu-item px-3">
			                                        <a onclick="getHistory(`+response['openticket'][i]['id']+`)" class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#detail-ticket" class="menu-link px-3">Detail</a>
			                                    </div>
			                                    <div class="menu-item px-3">
			                                        <a onclick="setAssign(`+response['openticket'][i]['id']+`,'Assign Ticket')" class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#action-ticket" class="menu-link px-3">Assign to</a>
			                                    </div>
												<div class="menu-item px-3">
		                                            <a onclick="setProccess(`+response['openticket'][i]['id']+`,'Proccess')" class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#action-ticket"  class="menu-link px-3">Proccess</a>
		                                        </div>
		                                        <div class="menu-item px-3">
		                                            <a onclick="setCancel(`+response['openticket'][i]['id']+`,'Cancel')" class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#action-ticket"  class="menu-link px-3">Cancel</a>
		                                        </div>
											</div>
										</td>
									</tr>`;
			            $("#tblopenticket").append(tr_str);
			        }
			    }
			    $.each(response['prosesticketbyassist'], function (key, value) { 

			    	if(value.flag==1){
                        var actions = `<div class="menu-item px-3">
                            <a onclick="setProccess(`+ value.id +`,'Proccess')" class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#action-ticket"  class="menu-link px-3">Proccess</a>
                        </div>
                        <div class="menu-item px-3">
                            <a onclick="setCancel(`+ value.id +`,'Cancel')" class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#action-ticket"  class="menu-link px-3">Cancel</a>
                        </div>`;
                    
                    }else if(value.flag==2 || value.flag==3 ||value.flag==9){
                        
                        if(value.flag!=3){
                        var actions = `<div class="menu-item px-3">
                            <a onclick="setEscalate(`+ value.id +`,'Escalate')" class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#action-ticket" class="menu-link px-3">Escalate</a>
	                        </div>
	                        <div class="menu-item px-3">
	                            <a onclick="setResolve(`+ value.id +`,'Resolve')" class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#action-ticket" class="menu-link px-3">Resolve</a>
	                        </div>`;
                        
                        }else{
                        var actions = `<div class="menu-item px-3">
                            <a onclick="setEscalate(`+ value.id +`,'Escalate')" class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#action-ticket" class="menu-link px-3">Escalate</a>
	                        </div><div class="menu-item px-3">
	                            <a onclick="setClose(`+ value.id +`,'Close')" class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#action-ticket" class="menu-link px-3">Close</a>
	                        </div>`;
                        
                        }
                    
                    }else if(value.flag!=5 && value.flag!=6){
                        var actions = `<div class="menu-item px-3">
                            <a onclick="setCancel(`+ value.id +`,'Cancel')" class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#action-ticket" class="menu-link px-3">Cancel</a>
                            </div>`;
                    }
                    
                    
				    var diff = Math.floor((new Date() - new Date(value.respond_time)));
					var msec = diff;
					var hh = Math.floor(msec / 1000 / 60 / 60);
					// msec -= hh * 1000 * 60 * 60;
					var mm = Math.floor(msec / 1000 / 60);
					var perentresolve = (Math.round(mm/(value.resolution_time*60)*100));
					$('#exampleid').append(`<tr>
								<td class="p-0">
									<div class="d-flex align-items-center">
										
										<div class="ps-3">
											<a class="text-gray-800 fw-boldest fs-5 text-hover-primary mb-1">#`+value.ticket_id +`</a>
											<span class="text-gray-400 fw-bold d-block">
											<div class="symbol symbol-40px symbol-circle me-4">
												<span class="badge badge-light fw-bolder my-2">`+value.flow_name +`</span>
											</div>SLA: `+value.sla_name +`</span>
										</div>
									</div>
								</td>
								<td>
									<div class="d-flex flex-column w-100 me-2 mt-2">
										<span class="text-gray-400 me-2 fw-boldest mb-2">`+perentresolve+`%</span>
										<div class="progress bg-light-danger w-100 h-5px">
											<div class="progress-bar bg-danger" role="progressbar" style="width: `+perentresolve+`%"></div>
										</div>
									</div>
								</td>
								<td class="pe-0 text-end">
									<a onclick='assistticketaction()' class="dropbtn btn btn-sm btn-icon btn-color-gray-500 btn-active-color-primary"> Actions
										
									</a>
									<div id="assistDropdown" class="dropdown-content menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
										<div class="menu-item px-3">
	                                        <a onclick="getHistory(`+value.id+`)" class="menu-link px-3" tooltip="New App" data-bs-toggle="modal" data-bs-target="#detail-ticket" class="menu-link px-3">Detail</a>
	                                    </div>
	                                    `+actions+`
									</div>
								</td>
								</tr>`);
				})

            }
        });

    }	
function openticketaction() {
  document.getElementById("openDropdown").classList.toggle("show");
}	
function assistticketaction() {
  document.getElementById("assistDropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
function review(x) {
    // indextr = x.rowIndex;
    console.log(x);
    document.getElementById('ticket_id').value=x;
}

function setEscalate(x,s) {
    $('#ticketAction').empty(); 
    var tr_str = `<div class="text-center mb-13">
                    <h1 class="mb-3">`+s+` Ticket</h1>
                    <div class="text-muted fw-bold fs-5">Are you sure the to 
                    <a class="link-primary fw-bolder">`+s+`</a> Ticket</div>
                </div>   <form action="{{url('escalate-ticket')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="fv-row">
                    <div class="fv-row mb-15">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span class="required">Assist : </span>
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Form Required"></i>
                        </label>
                        <select name="assist" class="form-control" required>
                                @foreach($data['assist'] as $assist)
                                    <option value="{{ $assist->user }}">{{ $assist->name }}</option>
                                @endforeach
                            </select>
                    </div>
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span>Note</span>
                        </label>
                        <input type="hidden" name="id" value="`+x+`">
                        <textarea class="form-control form-control-solid mb-8" rows="3" name="note" placeholder="Type yout Note"></textarea>
                    </div>
                    <div class="d-flex flex-stack">
                        <div class="me-5 fw-bold">
                        </div>
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input type="submit" name="Submit" class="btn btn-lg btn-primary" ><span class="svg-icon svg-icon-3 ms-2 me-0">
                        </label>
                    </div>
                </div>
                </form>`;
    $("#ticketAction").append(tr_str);  
}

function setResolve(x,s) {
    $('#ticketAction').empty(); 
    var tr_str = `<div class="text-center mb-13">
                    <h1 class="mb-3">`+s+` Ticket</h1>
                    <div class="text-muted fw-bold fs-5">Are you sure the to 
                    <a class="link-primary fw-bolder">`+s+`</a> Ticket</div>
                </div>   <form action="{{url('resolve-ticket')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="fv-row">
                    <div class="fv-row mb-15">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span >Reduce Inventory : </span>
                        </label>
                        <select style="width: 100%;"  name="inventory" onchange="getinventory(this)"  class="form-control js-example-basic-single" data-live-search="true">
                                <option value="">- Select Inventory -</option>
                                @foreach($data['inventory'] as $inventory)
                                    <option value="{{ $inventory->inventory_id }}" >{{ $inventory->inventory_nama }}</option>
                                @endforeach
                            </select>
                    </div>
                    
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span>Qty</span>
                        </label>
                        <input type="number" name="reduce"  id="reduce" class="form-control"  autocomplete="off">  
                    </div>
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span>Note</span>
                        </label>
                        <input type="hidden" name="id" value="`+x+`">
                        <textarea class="form-control form-control-solid mb-8" rows="3" name="note" placeholder="Type yout Note"></textarea>
                    </div>
                    <div class="d-flex flex-stack">
                        <div class="me-5 fw-bold">
                        </div>
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input type="submit" name="Submit" class="btn btn-lg btn-primary" ><span class="svg-icon svg-icon-3 ms-2 me-0">
                        </label>
                    </div>
                </div>
                </form>`;
    $("#ticketAction").append(tr_str);  
}

function setClose(x,s) {
    $('#ticketAction').empty(); 
    var tr_str = `<div class="text-center mb-13">
                    <h1 class="mb-3">`+s+` Ticket</h1>
                    <div class="text-muted fw-bold fs-5">Are you sure the to 
                    <a class="link-primary fw-bolder">`+s+`</a> Ticket</div>
                </div>   <form action="{{url('close-ticket')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="fv-row">
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span>Note</span>
                        </label>
                        <input type="hidden" name="id" value="`+x+`">
                        <textarea class="form-control form-control-solid mb-8" rows="3" name="note" placeholder="Type yout Note"></textarea>
                    </div>
                    <div class="d-flex flex-stack">
                        <div class="me-5 fw-bold">
                        </div>
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input type="submit" name="Submit" class="btn btn-lg btn-primary" ><span class="svg-icon svg-icon-3 ms-2 me-0">
                        </label>
                    </div>
                </div>
                </form>`;
    $("#ticketAction").append(tr_str);  
}
function setProccess(x,s) {
    $('#ticketAction').empty(); 
    var tr_str = `<div class="text-center mb-13">
                    <h1 class="mb-3">`+s+` Ticket</h1>
                    <div class="text-muted fw-bold fs-5">Are you sure the to 
                    <a class="link-primary fw-bolder">`+s+`</a> Ticket</div>
                </div>   <form action="{{url('proccess-ticket')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="fv-row">
                    <div class="fv-row mb-15">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span class="required">Sla : </span>
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Form Required"></i>
                        </label>
                        <select name="sla" class="form-control" required>
                            @foreach($data['sla'] as $sla)
                                <option value="{{ $sla->id }}">{{ $sla->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span>Note</span>
                        </label>
                        <input type="hidden" name="id" value="`+x+`">
                        <textarea class="form-control form-control-solid mb-8" rows="3" name="note" placeholder="Type yout Note"></textarea>
                    </div>
                    <div class="d-flex flex-stack">
                        <div class="me-5 fw-bold">
                        </div>
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input type="submit" name="Submit" class="btn btn-lg btn-primary" ><span class="svg-icon svg-icon-3 ms-2 me-0">
                        </label>
                    </div>
                </div>
                </form>`;
    $("#ticketAction").append(tr_str);  
}
function setAssign(x,s) {
    $('#ticketAction').empty(); 
    var tr_str = `<div class="text-center mb-13">
                    <h1 class="mb-3">`+s+` Ticket</h1>
                    <div class="text-muted fw-bold fs-5">Are you sure the to 
                    <a class="link-primary fw-bolder">`+s+`</a> Ticket</div>
                </div>   <form action="{{url('asign-ticket')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="fv-row">
                    <div class="fv-row mb-15">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span class="required">Assist : </span>
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Form Required"></i>
                        </label>
                        <select name="assist" class="form-control" required>
                                @foreach($data['assist'] as $assist)
                                    <option value="{{ $assist->user }}">{{ $assist->name }}</option>
                                @endforeach
                            </select>
                    </div>
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span>Note</span>
                        </label>
                        <input type="hidden" name="id" value="`+x+`">
                        <textarea class="form-control form-control-solid mb-8" rows="3" name="note" placeholder="Type yout Note"></textarea>
                    </div>
                    <div class="d-flex flex-stack">
                        <div class="me-5 fw-bold">
                        </div>
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input type="submit" name="Submit" class="btn btn-lg btn-primary" ><span class="svg-icon svg-icon-3 ms-2 me-0">
                        </label>
                    </div>
                </div>
                </form>`;
    $("#ticketAction").append(tr_str);  
}

function setCancel(x,s) {
    $('#ticketAction').empty(); 
    var tr_str = `<div class="text-center mb-13">
                    <h1 class="mb-3">`+s+` Ticket</h1>
                    <div class="text-muted fw-bold fs-5">Are you sure the to 
                    <a class="link-primary fw-bolder">`+s+`</a> Ticket</div>
                </div>   <form action="{{url('cancel-ticket')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="fv-row">
                    <div class="fv-row mb-10">
                        <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                            <span>Note</span>
                        </label>
                        <input type="hidden" name="id" value="`+x+`">
                        <textarea class="form-control form-control-solid mb-8" rows="3" name="note" placeholder="Type yout Note"></textarea>
                    </div>
                    <div class="d-flex flex-stack">
                        <div class="me-5 fw-bold">
                        </div>
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input type="submit" name="Submit" class="btn btn-lg btn-primary" ><span class="svg-icon svg-icon-3 ms-2 me-0">
                        </label>
                    </div>
                </div>
                </form>`;
    $("#ticketAction").append(tr_str);  
}
function getinventory(sel) {
        var id = sel.value;
       
        APP_URL = '{{url('/')}}' ;
        $.ajax({
            url: APP_URL+'/get-inventory/' + id,
            type: 'get',
            dataType: 'json',
            success: function (response) {
                console.log(response['data']);
                $("input[name=reduce]").val(response['data'].inventory_qty);
                $("input[name=reduce]").attr({
                    "max" : response['data'].inventory_qty,
                    "min" : 1
                });
            }
        });
    }

</script>
@endsection