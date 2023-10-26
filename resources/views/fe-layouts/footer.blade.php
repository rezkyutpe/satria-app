
<script>var hostUrl = "{{ asset('public/assets/theme/dist/assets/') }}";</script>
<!-- <script src="js/highcharts.js"></script> -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="assets/plugins/global/plugins.bundle.js"></script>
<script src="assets/js/scripts.bundle.js"></script>
<script src="assets/plugins/custom/fslightbox/fslightbox.bundle.js"></script>
<script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
		<!-- <script src="/assets/js/custom/utilities/modals/users-search.js"></script> -->
		<!--begin::Page Custom Javascript(used by this page)-->
		<script src="assets/js/custom/widgets.js"></script>
		<script src="assets/js/custom/modals/create-app.js"></script>
    <script src="assets/js/custom/intro.js"></script>
    <script src="{{ URL::asset('public/assets/Atlantis-Lite-master/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ URL::asset('public/assets/Atlantis-Lite-master/plugins/fullcalendar/main.js') }}"></script>
    <script src="{{ URL::asset('public/assets/Atlantis-Lite-master/plugins/fullcalendar/locales/id.js') }}"></script>
    <!-- Chosen -->
    <script src="{{ URL::asset('public/assets/Atlantis-Lite-master/plugins/chosen/chosen.jquery.js') }}"></script>

<script type="text/javascript">

$(document).ready(function() {
  $(".chzn-select").chosen();
});

function performanceDownload(){
  document.getElementById("performancepassword").style.display = "block";
  document.getElementById("performanceyear").style.display = "block";
  document.getElementById("performancestring").style.display = "none";
  document.getElementById("performancesubmit").style.display = "block";
  document.getElementById("performancedownload").style.display = "none";

}
function competenceDownload(){
  document.getElementById("competencepassword").style.display = "block";
  document.getElementById("competenceyear").style.display = "block";
  document.getElementById("competencestring").style.display = "none";
  document.getElementById("competencesubmit").style.display = "block";
  document.getElementById("competencedownload").style.display = "none";

}
function catselect(val) {
  document.getElementById("cat").style.display = "block";
  document.getElementById("asset").style.display = "none";
  document.getElementById("capture").style.display = "none";
  document.getElementById("subject").style.display = "none";
  document.getElementById("lokasi").style.display = "none";
  document.getElementById("pruser").style.display = "block";
            document.getElementById("label0").innerHTML = "Inventory";
            document.getElementById("label1").innerHTML = "Account";
  
  setPrUserList();
}
    function assetselect(val) {
        document.getElementById("capture").style.display = "block";
        document.getElementById("subject").style.display = "block";
        document.getElementById("pruser").style.display = "none";
        document.getElementById("lokasi").style.display = "block";
        document.getElementById("asset").style.display = "none";
        document.getElementById("much").style.display = "none";
        document.getElementById("account").checked = true;
            document.getElementById("label0").innerHTML = "Machine";
            document.getElementById("label1").innerHTML = "Tools";
            console.log(document.getElementById("dept").value);
        if(document.getElementById("dept").value=="8884"){
        document.getElementById("cat").style.display = "block";
        }else{
        document.getElementById("cat").style.display = "none";
        }
    }

    function accountselect(val) {
        document.getElementById("asset").style.display = "none";
        document.getElementById("much").style.display = "none";
    }

    function inventoryselect(val) {
        // console.log(document.getElementById("kt_create_account_form_account_type_personal").checked);
        if (document.getElementById("kt_create_account_form_account_type_personal").checked == true) {
            document.getElementById("asset").style.display = "block";
        } else {
            document.getElementById("asset").style.display = "none";
            document.getElementById("much").style.display = "block";
        }
    }

    function getChooseDept(sel) {
        var dept = sel.value;
        // console.log(dept);
        if (dept == '8884') {
            document.getElementById("cat").style.display = "block";
            document.getElementById("label0").innerHTML = "Machine";
            document.getElementById("label1").innerHTML = "Tools";
            APP_URL = '{{ url('/') }}';
            var select1 = $('#child');
            select1.children().remove().end()
            $.ajax({
                url: APP_URL + '/get-data-mesin/' + dept,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    // console.log(response); 
                    var total = response.length;
                    for (var i = 0; i < total; i++) {
                        var added = document.createElement('option');
                        added.value = response[i]['id_mesin'];
                        added.innerHTML = response[i]['desc_mesin'];
                        select1.append(added);
                    }
                }
            });
        } else {
          if (document.getElementById("kt_create_account_form_account_type_personal").checked == true) {
            document.getElementById("cat").style.display = "none";
          } else {
            document.getElementById("cat").style.display = "block";
            document.getElementById("label0").innerHTML = "Inventory";
            document.getElementById("label1").innerHTML = "Account";
          }
        }
    }
function searchmenu(string) {
    if(event.key === 'Enter') {
        window.location.href="{{URL::to('search-menu?search=')}}"+string.value;
    }
}
</script>

<script>
      
   $.ajaxSetup({
       headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       }
   });
   
function getHistory(val){
APP_URL = '{{url('/')}}' ;
ASSET_URL = '{{asset('/public')}}' ;
$.ajax({
            url: APP_URL+'/ticket-history/' + val,
    type: 'get',
    dataType: 'json',
    success: function(response){
      console.log(response)
    var len = 0;
    $('#historyTable').empty(); // Empty >
    $('#detailTicketData').empty(); // Empty >
    $("#detailTicketDataFile").empty();
    $("#detailTicketDataSign").empty();
    if(response['data'] != null){
        len = response['data'].length;
    }
    var sla = 0;
    if(response['ticket']['resolve_status']==1){
        sla = '<span class="bullet bullet-dot bg-success mx-2"></span>Achieved</span>';
    }else if(response['ticket']['resolve_status']==0 && response['ticket']['resolve_time']!=''){
        sla='<span class="bullet bullet-dot bg-danger mx-2"></span>Not Achieved</span>';
    }else{ 
        sla='<span class="bullet bullet-dot bg-danger mx-2"></span>Not Achieved</span>';
    }
    var wa;
    var dept_assign = "";
    var div_assign = "";
    var dic_assign = "";
        var approved_dept="";
        var approved_div="";
        var approved_dic="";
     if(response['ticket']['approve_dept']!=null){
            approved_dept=response['ticket']['dept_name'];
        }else{
            approved_dept = "<br>";
        }
        if(response['ticket']['approve_div']!=null){
            approved_div=response['ticket']['div_name'];
        }else{
            approved_div = "<br>";
        }
        if(response['ticket']['approve_dic']!=null){
            approved_dic=response['ticket']['dic_name'];
        }else{
            approved_dic = "<br>";
        }
    if(response['ticket']['reporter_nrp']=='{{ Auth::user()->email }}'){
      if(response['ticket']['phone_assist']!=null){
        wa =  `<a data-fslightbox="lightbox-hot-sales" target="_blank" href="https://wa.me/62`+response['ticket']['phone_assist'].substr(1)+`?text=Hai%20`+response['ticket']['assist_name']+`%20,Saya%20ingin%20bertanya%20terkait%20Ticket%20saya%20`+response['ticket']['ticket_id']+`"><span class="badge badge-light-success">Wa.me <i class="bi bi-whatsapp text-success"></i></span></a>`;
      }else{ 
        
        wa ='<span class="badge badge-light-success"><s>Wa.me <i class="bi bi-whatsapp text-success"></i></s></span>';
      }
    }else if(response['ticket']['assist_nrp']=='{{ Auth::user()->email }}'){
      if(response['ticket']['phone_reporter']!=null){
        wa =  `<a data-fslightbox="lightbox-hot-sales" target="_blank" href="https://wa.me/62`+response['ticket']['phone_reporter'].substr(1)+`?text=Hai%20`+response['ticket']['reporter_name']+`%20Saya%20ingin%20bertanya%20terkait%20Ticket%20anda%20`+response['ticket']['ticket_id']+`"><span class="badge badge-light-success">Wa.me <i class="bi bi-whatsapp text-success"></i></span></a>`;
       }else{ 
        wa ='<span class="badge badge-light-success"><s>Wa.me <i class="bi bi-whatsapp text-success"></i></s></span>';
      }
    }else{
      wa ='Wa Not Available';
    }
    if(response['ticket']['approve_dept_to'] != 'not required'){
            dept_assign = `<div class="mw-500px col-sm-6">
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fw-bold pe-10 text-gray-600 fs-7">Approve By:</div>
                                    </div>
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fs-1 text-gray-800" style="font-family: 'Cedarville Cursive', cursive;font-size: 2.0em;">`+approved_dept+` </div>
                                    </div>
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fs-8 text-gray-500">`+response['ticket']['dept_title']+` </div>
                                    </div>
                                </div>`;
        }
        if(response['ticket']['approve_div_to'] != 'not required'){
            div_assign = `<div class="mw-500px col-sm-6">
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fw-bold pe-10 text-gray-600 fs-7">Approve By:</div>
                                    </div>
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fs-1 text-gray-800" style="font-family: 'Cedarville Cursive', cursive;font-size: 2.0em;">`+approved_div+`</div>
                                    </div>
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fs-8 text-gray-500">`+response['ticket']['div_title']+` </div>
                                    </div>
                                </div>`;
        }
        if(response['ticket']['approve_dic_to'] != 'not required'){
            dic_assign = `<div class="mw-500px col-sm-6">
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fw-bold pe-10 text-gray-600 fs-7">Acknowledge By:</div>
                                    </div>
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fs-1 text-gray-800" style="font-family: 'Cedarville Cursive', cursive;font-size: 2.0em;">`+approved_dic+`</div>
                                    </div>
                                    <div class="d-flex flex-stack mb-4">
                                        <div class="fs-8 text-gray-500">`+response['ticket']['dic_title']+` </div>
                                    </div>
                                </div>`;
        }
    var data_str = `<div class="mb-8">
                        <span style="background-color: `+response['ticket']['bg_color']+`; color:`+response['ticket']['fg_color']+`;" class="badge me-2">`+response['ticket']['flow_name']+`</span>
                      <span class="badge badge-light-warning">`+response['ticket']['rate']+` <i class="bi bi-star-fill text-warning"></i></span>
            
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal" style="margin-left: 25px;">
                        <a href="#" onclick="getChat(`+val+`)" id="kt_explore_toggle" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-dismiss="click"><span class="badge badge-light-info">Chat <i class="bi bi-chat-dots text-info"></i></span></a>
                        </div>
                        <div class="btn btn-sm btn-icon btn-active-color-success" style="margin-left: 35px;">
                        <a data-fslightbox="lightbox-hot-sales" href="`+ASSET_URL+`/ticket/`+response['ticket']['media']+`"><span class="badge badge-light-danger">Media <i class="bi bi-eye-fill text-danger"></i></span></a>
                        </div>
                        <div class="btn btn-sm btn-icon btn-active-color-success" style="margin-left: 45px;">
                       `+wa+`
                        </div>
                    </div>
                    <div class="mb-6">
                        <div class="fw-bold text-gray-600 fs-7">ID Ticket:</div>
                        <div class="fw-bolder text-gray-800 fs-6">`+response['ticket']['ticket_id']+`</div>
                    </div>
                    <div class="mb-6">
                        <div class="fw-bold text-gray-600 fs-7">Subject:</div>
                        <div class="fw-bolder text-gray-800 fs-6">`+response['ticket']['subject']+`</div>
                    </div>
                    <div class="mb-6">
                        <div class="fw-bold text-gray-600 fs-7">Message:</div>
                        <div class="fw-bolder text-gray-800 fs-6">`+response['ticket']['message']+`</div>
                    </div>
                    <div class="mb-15">
                        <div class="fw-bold text-gray-600 fs-7">Reporter Name</div>
                        <div class="fw-bolder fs-6 text-gray-800">`+response['ticket']['reporter_name']+`<br>
                        <a class="link-primary ps-1">`+response['ticket']['dept_reporter']+`</a></div>
                    </div>
                    <div class="mb-6">
                        
                        <div class="fw-bold text-gray-600 fs-7">SLA Cat</div>
                        <div class="fw-bolder fs-6 text-gray-800 d-flex align-items-center">`+response['ticket']['sla_name']+`
                        <span class="fs-7 text-danger d-flex align-items-center">
                        `+sla+`</div>
                    </div>
                    <div class="m-0">
                        <div class="fw-bold text-gray-600 fs-7">Resolve Time:</div>
                        <div class="fw-bolder fs-6 text-gray-800 d-flex align-items-center">`+response['ticket']['resolve_percent']+`%
                        <span class="fs-7 text-success d-flex align-items-center">
                        <span class="bullet bullet-dot bg-success mx-2"></span>`+response['ticket']['resolve_result']+`</span></div>
                    </div>
                    `;
    $("#detailTicketData").append(data_str);
    $("#historyTable").append('<h6 class="mb-8 fw-boldest text-gray-600 text-hover-primary">HISTORY TICKET</h6><div class="stepper-item current" data-kt-stepper-element="nav">');
     var tr_str = `<div class="stepper-item current" data-kt-stepper-element="nav">
                                <div class="stepper-line w-40px"></div>
                                <div class="stepper-icon w-40px h-40px">
                                    <i class="stepper-check fas fa-check"></i>
                                    <span class="stepper-number">
                                    <i class="fas fa-spinner text-white"></i></span>
                                </div>
                                <div class="stepper-label">
                                    <h3 class="stepper-title">Ticket Created</h3>
                                    <div class="stepper-desc">`+response['ticket']['created_at']+`</div>
                                </div>
                            </div>`;
                var desc_mesin = ``;
                if(response['ticket']['desc_mesin'] != null){
                    desc_mesin = response['ticket']['desc_mesin'];
                }
                if (response['filemesin'] != null) {
                        var data_str2 = `<div class="fw-bold text-gray-600 fs-7 mb-2"><strong>`+ desc_mesin +`</strong></div>
                              <div class="fw-bold text-gray-600 fs-7 mb-2">Mesin User Manual File:</div>
                              <iframe class="mb-1" src="{{ url('public') }}/mesinfile/` + response['filemesin'][
                            'file_name'
                        ] + `"
                                  width="30%" height="125px">
                              </iframe>
                              <br>
                              <a class="btn btn-primary btn-sm" id="viewFileMesin"
                                  onclick="location.href = '{{ url('view-file') }}/` + response['filemesin'][
                            'file_name'
                        ] + `'"><small>View
                                  File</small></a>`;
                    } else {
                        var data_str2 = `<div class="fw-bold text-gray-600 fs-7 mb-2"><strong>`+ desc_mesin +`</strong></div>
                        <div class="fw-bold text-gray-600 fs-7">Mesin User Manual File:</div>
                            <p>No data available.</p>`;
                    }
                $("#detailTicketDataFile").append(data_str2);
                if(response['ticket']['status']<=3){
                  var data_str3 = ` <div class="flex-grow-1">
                              
                          <div class="d-flex justify-content-end">
                              `+dept_assign+`
                              `+div_assign+`
                              `+dic_assign+`
                          </div>
                      </div>`;
                  $("#detailTicketDataSign").append(data_str3);
                }

            $("#historyTable").append(tr_str);
    if(len > 0){
        
        for(var i=0; i<len; i++){
            var tr_str = `<div class="stepper-item current" data-kt-stepper-element="nav">
                                <div class="stepper-line w-40px"></div>
                                <div class="stepper-icon w-40px h-40px">
                                    <i class="stepper-check fas fa-check"></i>
                                    <span class="stepper-number">
                                    <i class="fas fa-spinner text-white"></i></span>
                                </div>
                                <div class="stepper-label">
                                    <h3 class="stepper-title">`+response['data'][i]['title']+`</h3>
                                    <div class="stepper-desc">`+response['data'][i]['timestamp']+`</div>
                                    <div class="stepper-desc">note : `+response['data'][i]['description']+`</div>
                                </div>
                            </div>`;
            $("#historyTable").append(tr_str);
        }
    }else{
        var tr_str = "";

        $("#historyTable").append(tr_str);
    }

    }
});
}
    function getChat(val){
    
    var out = document.getElementById("chat-content");
  // $('#chat-content').scrollTop(out.scrollHeight+500);
    document.getElementById("id_ticket").value = val;
    APP_URL = '{{url('/')}}' ;
    $.ajax({
      url: APP_URL+'/ticket-chat/' + val,
      type: 'get',
      dataType: 'json',
      success: function(response){
        var len = 0;
        var tickid = "";
        $('#chatData').empty(); // Empty <tbody>
        $('#chatHead').empty();
        if(response['data'].length != 0){
          len = response['data'].length;
          tickid = response['data'][0]['ticket_id'];
          document.getElementById("id_ticket").innerHTML = "#"+response['data'][0]['ticket_id'];
        }
        // Empty 
        if(len > 0){
          for(var i=0; i<len; i++){
            if(response['data'][i]['created_by']!=response['data'][i]['reporter_nrp']){
                var tr_str = `<div class="d-flex justify-content-start mb-10">
                      <div class="d-flex flex-column align-items-start">
                        <div class="d-flex align-items-center mb-2">
                          <div class="symbol symbol-35px symbol-circle"><img alt="Pic" src="{{ asset('public/assets/global/img/no-profile.jpg') }}" /></div>
                          <div class="ms-3">
                            <a  class="fs-5 fw-bolder text-gray-900 text-hover-primary me-1">`+response['data'][i]['name']+`</a>
                            <span class="text-muted fs-7 mb-1">`+timeSince(new Date(response['data'][i]['created_at']))+`</span>
                          </div>
                        </div>
                        <div class="p-5 rounded bg-light-info text-dark fw-bold mw-lg-400px text-start" data-kt-element="message-text">`+response['data'][i]['text']+`</div>
                      </div>
                    </div>`
            }else{
                    var tr_str = `<div class="d-flex justify-content-end mb-10">
                      <div class="d-flex flex-column align-items-end">
                        <div class="d-flex align-items-center mb-2">
                          <div class="me-3">
                            <span class="text-muted fs-7 mb-1">`+timeSince(new Date(response['data'][i]['created_at']))+`</span>
                            <a  class="fs-5 fw-bolder text-gray-900 text-hover-primary ms-1">You</a>
                          </div>
                          <div class="symbol symbol-35px symbol-circle"><img alt="Pic" src="{{ asset('public/assets/global/img/no-profile.jpg') }}" /></div>
                        </div>
                        <div class="p-5 rounded bg-light-primary text-dark fw-bold mw-lg-400px text-end" data-kt-element="message-text">`+response['data'][i]['text']+`</div>
                    </div>
                    </div>`
            }

              $("#chatData").append(tr_str);
          }
        }else{
          var tr_str = "";

          $("#chatData").append(tr_str);
        }
      }
    });
  }
  $(".btn-submit-chat").click(function(e){
    var text =  document.getElementById("pesan").value;
    var id_ticket = document.getElementById("id_ticket").value;

    APP_URL = '{{url('/')}}' ;
    $.ajax({
        type:'POST',
        url: "{{url('comment-ticket')}}",
        data:{text:text, id_ticket:id_ticket},
        success:function(data){
        var out = document.getElementById("chat-content");
        $('#chat-content').scrollTop(out.scrollHeight+500);
          document.getElementById("msg").innerHTML= data['message'];
          document.getElementById("pesan").value="";
          const myTimeout = setTimeout(myGreeting, 6000);

          function myGreeting() {
            document.getElementById("msg").innerHTML= "";
          }
          getChat(id_ticket);
        
        }
       });
   });
   
   function changeapps(val){
    var id = val;

    APP_URL = '{{url('/')}}' ;
    $.ajax({
        type:'POST',
        url: "{{url('update-config')}}",
        data:{apps:val},
        success:function(data){
          console.log("ok");
          window.location.href = "{{ url('/')}}";
        }
       });
   }
   function timeSince(date) {

    var seconds = Math.floor((new Date() - date) / 1000);

    var interval = seconds / 31536000;

    if (interval > 1) {
      return Math.floor(interval) + " years";
    }
    interval = seconds / 2592000;
    if (interval > 1) {
      return Math.floor(interval) + " months";
    }
    interval = seconds / 86400;
    if (interval > 1) {
      return Math.floor(interval) + " days";
    }
    interval = seconds / 3600;
    if (interval > 1) {
      return Math.floor(interval) + " hours";
    }
    interval = seconds / 60;
    if (interval > 1) {
      return Math.floor(interval) + " minutes";
    }
    return Math.floor(seconds) + " seconds";
  }
</script>
<script type="text/javascript">
		var DatasRow = function () {
		    // Shared variables
		    var table;
		    var datatable;

		    // Private functions
		    var initDatatable = function () {
		        // Init datatable --- more info on datatables: https://datatables.net/manual/
		        datatable = $(table).DataTable({
		            "info": false,
		            'order': [],
		            'pageLength': 10,
		            'columnDefs': [
		                { orderable: false, targets: 0 }, // Disable ordering on column 0 (checkbox)
		                { orderable: false, targets: 7 }, // Disable ordering on column 7 (actions)
		            ]
		        });

		        // Re-init functions on datatable re-draws
		        datatable.on('draw', function () {
		            handleDeleteRows();
		        });
		    }

		    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
		    var handleSearchDatatable = () => {
		        const filterSearch = document.querySelector('[data-kt-ecommerce-product-filter="search"]');
		        filterSearch.addEventListener('keyup', function (e) {
		            datatable.search(e.target.value).draw();
		        });
		    }

		    // Handle status filter dropdown
		    var handleStatusFilter = () => {
		        const filterStatus = document.querySelector('[data-kt-ecommerce-product-filter="status"]');
		        $(filterStatus).on('change', e => {
		            let value = e.target.value;
		            if(value === 'all'){
		                value = '';
		            }
		            datatable.column(6).search(value).draw();
		        });
		    }

		    // Delete cateogry
		    var handleDeleteRows = () => {
		        // Select all delete buttons
		        const deleteButtons = table.querySelectorAll('[data-kt-ecommerce-product-filter="delete_row"]');

		        deleteButtons.forEach(d => {
		            // Delete button on click
		            d.addEventListener('click', function (e) {
		                e.preventDefault();

		                // Select parent row
		                const parent = e.target.closest('tr');

		                // Get category name
		                const productName = parent.querySelector('[data-kt-ecommerce-product-filter="product_name"]').innerText;

		                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
		                Swal.fire({
		                    text: "Are you sure you want to delete " + productName + "?",
		                    icon: "warning",
		                    showCancelButton: true,
		                    buttonsStyling: false,
		                    confirmButtonText: "Yes, delete!",
		                    cancelButtonText: "No, cancel",
		                    customClass: {
		                        confirmButton: "btn fw-bold btn-danger",
		                        cancelButton: "btn fw-bold btn-active-light-primary"
		                    }
		                }).then(function (result) {
		                    if (result.value) {
		                        Swal.fire({
		                            text: "You have deleted " + productName + "!.",
		                            icon: "success",
		                            buttonsStyling: false,
		                            confirmButtonText: "Ok, got it!",
		                            customClass: {
		                                confirmButton: "btn fw-bold btn-primary",
		                            }
		                        }).then(function () {
		                            // Remove current row
		                            datatable.row($(parent)).remove().draw();
		                        });
		                    } else if (result.dismiss === 'cancel') {
		                        Swal.fire({
		                            text: productName + " was not deleted.",
		                            icon: "error",
		                            buttonsStyling: false,
		                            confirmButtonText: "Ok, got it!",
		                            customClass: {
		                                confirmButton: "btn fw-bold btn-primary",
		                            }
		                        });
		                    }
		                });
		            })
		        });
		    }


		    // Public methods
		    return {
		        init: function () {
		            table = document.querySelector('#kt_ecommerce_products_table');

		            if (!table) {
		                return;
		            }

		            initDatatable();
		            handleSearchDatatable();
		            handleStatusFilter();
		            handleDeleteRows();
		        }
		    };
		}();

		// On document ready
		KTUtil.onDOMContentLoaded(function () {
		    DatasRow.init();
		});
</script>
<script>
    function mySubmitLoad() {
    document.getElementById("submit-label").innerHTML = "Please wait...<span class='spinner-border spinner-border-sm align-middle ms-2'></span>";
    document.getElementByClassName("submit-button").disabled = true;
    // document.getElementById("submit-progress").style.display = "block";
    document.getElementById("form-submit").submit();
    }
</script>

{{-- FRONT END QRGS --}}
<script>

  var roomLoanArray = @json($data['roomLoanArray']); //get semua data jadwal ruangan
  var carLoanArray = @json($data['carLoanArray']); //get semua data jadwal ruangan

  var Calendar = FullCalendar.Calendar;
  var calendarEl = document.getElementById('calendarRoom');
  var calendarEl1 = document.getElementById('calendarCar');

    //kalender peminjaman ruangan
  var calendarRoom = new Calendar(calendarEl, {
    height : get_height(),
    expandRows: true,
    headerToolbar: {
      left: 'title',
      center  : '',
      right : 'prev next'
    },
    themeSystem: 'solar',
    //  locale: 'id',
    events: roomLoanArray, 
    eventDisplay : 'block',
    displayEventTime : true,
    displayEventEnd : true,
    eventTimeFormat: { // like '14:30'
      hour: '2-digit',
      minute: '2-digit',
      meridiem: false
    },
    selectable: true,
    selectAllow: function (e) {
      if (e.end.getTime() / 1000 - e.start.getTime() / 1000 <= 86400) {
          return true;
      }
    },
    slotLabelFormat:  [
      { hour: 'numeric', minute: '2-digit',meridiem: 'short'  }, // top level of text
      { weekday: 'short' } // lower level of text
    ],
    allDaySlot: false,
    dateClick: function(info) {
        const formatYmd = date => date.toISOString().slice(0, 10);
        sessionStorage.setItem("date", info.dateStr);
        setscheduleAndForm();
    },
  });

  //kalender peminjaman mobil
  var calendarCar = new Calendar(calendarEl1, {
    height : get_height(),
    expandRows: true,
    headerToolbar: {
      left: 'title',
      center  : '',
      right : 'prev next'
    },
    themeSystem: 'solar',
    //  locale: 'id',
    events: carLoanArray, 
    eventDisplay : 'block',
    displayEventTime : true,
    displayEventEnd : true,
    eventTimeFormat: { // like '14:30'
      hour: '2-digit',
      minute: '2-digit',
      meridiem: 'false'
    },
    selectable: true,
    selectAllow: function (e) {
      if (e.end.getTime() / 1000 - e.start.getTime() / 1000 <= 86400) {
          return true;
      }
    },
    slotLabelFormat:  [
      { hour: 'numeric', minute: '2-digit',meridiem: 'short'  }, // top level of text
      { weekday: 'short' } // lower level of text
    ],
    allDaySlot: false,
    dateClick: function(info) {
        const formatYmd = date => date.toISOString().slice(0, 10);
        sessionStorage.setItem("date", info.dateStr);
        setscheduleAndForm();
    },
  });

  //get full height window
  function get_height() {
    return $(window).height();
  } 

  //function trigger modal new booking tampil
  $('#kt_modal_create_app').on('shown.bs.modal', function () {

    //render kalender peminjaman ruangan
    calendarRoom.render();

    //simpan tipe peminjaman menjadi ruangan
    sessionStorage.setItem("loanType", "room");

    //inisialisasi chosen select
    $(".chzn-select").chosen();

    //inisialisasi slider
    // var slider = document.getElementById("slider");

    // 0 = initial minutes from start of day
    // 1440 = maximum minutes in a day
    // step: 30 = amount of minutes to step by. 
    // var initialStartMinute = 0,
    //     initialEndMinute = 1440
    
    // var convertValuesToTime = function(values,handle){
    //   var hours = 0,
    //       minutes = 0;
          
    //   if(handle === 0){
    //     hours = convertToHour(values[0]);
    //     minutes = convertToMinute(values[0],hours);
    //     $("#start").val(formatHoursAndMinutes(hours,minutes)) ;
    //     return;
    //   };
      
    //   hours = convertToHour(values[1]);
    //   minutes = convertToMinute(values[1],hours);
    //   $("#end").val(formatHoursAndMinutes(hours,minutes));
        
    // };

    // var convertToHour = function(value){
    //   return Math.floor(value / 60);
    // };
    
    // var convertToMinute = function(value,hour){
    //   return Math.round(value - hour * 60);
    // };
    
    // var formatHoursAndMinutes = function(hours,minutes){
    //     if(hours.toString().length == 1) hours = '0' + hours;
    //     if(minutes.toString().length == 1) minutes = '0' + minutes;
    //     return hours+':'+minutes;
    // };
        
    // slider = noUiSlider.create(slider,{
    //   start:[initialStartMinute,initialEndMinute],
    //   connect:true,
    //   step:10,
    //   start:[
    //     450,
    //     990
    //   ],
    //   range:{
    //     'min':initialStartMinute,
    //     'max':initialEndMinute
    //   },
    //   tooltips:{
    //     to: function(value){
    //       hours = convertToHour(value);
    //       minutes = convertToMinute(value,hours);
    //       return formatHoursAndMinutes(hours,minutes);
    //     },
    //     to: function(value){
    //       hours = convertToHour(value);
    //       minutes = convertToMinute(value,hours);
    //       return formatHoursAndMinutes(hours,minutes);
    //     }
    //   }
    // });

    // slider.on('update',function(values,handle){
    //   convertValuesToTime(values,handle);
    // });

    // slider.on('set', function(){
    //   // alert("slider set");
    //   checkRoomBooking();
    // });
  
  });

  //fungsi apabila peminjaman kendaraan dipilih
  function loanCar(){
    sessionStorage.setItem("loanType", "car");
    $("#btn_loan_car").prop("checked", "checked");
    $("#btn_loan_room").prop("checked", "");
    $("#calendarCar").show();//show calender loan car
    $("#calendarRoom").hide();//hide calendar loan room
    calendarCar.render();
    $('#btn_continue').hide();
    $('#schedule_continer').empty();
  }

  //function apabila peminjaman ruangan dipilih
  function loanRoom(){
    sessionStorage.setItem("loanType", "room");
    $("#btn_loan_room").prop("checked", "checked");
    $("#btn_loan_car").prop("checked", "");
    $("#calendarRoom").show();//show calendar loan room
    $("#calendarCar").hide();//hide calender loan car
    calendarRoom.render();
    $('#btn_continue').hide();
    $('#schedule_continer').empty();
  }

  //function tampil detail jadwal peminjaman ruangan
  function showSchedule(date){
    $.ajax({
      type:'get',
      url: "{{ url('/show-schedule-by-date') }}"+"/"+date, //showRoomLoanByDate()
      success:function(data){
        var len = data.length;

        $('#schedule_continer').empty();

        if(len > 0){
          for(i=0; i<len; i++){
            var schedule = `<span class="d-flex align-items-center me-2 my-2">
                              <!--begin:Icon-->
                              <span class="symbol symbol-50px me-6">
                                <span class="symbol-label" style="background-color: `+data[i]['color']+`"></span>
                              </span>
                              <!--end:Icon-->
                              <!--begin:Info-->
                              <span class="d-flex flex-column">
                                <div class="d-flex">
                                  <div>
                                    <span class="fw-bolder fs-6 ">
                                      `+data[i]['ruangan']+`
                                    </span>
                                  </div>
                                  <div class="ml-3">
                                    <span class="fw-bolder fs-6 ms-3">
                                      `+formatTime(data[i]['start'])+` - `+formatTime(data[i]['end'])+`
                                    </span>
                                  </div>
                                </div>
                                <span class="fs-7 text-muted">
                                  `+data[i]['perusahaan']+`
                                </span>
                                <span class="fw-bolder fs-6">By `+data[i]['peminjam']+`</span>
                                <span class="fs-7 text-muted">
                                  `+data[i]['divisi']+`
                                </span>
                              </span>
                              <!--end:Info-->
                            </span>`;
            $('#schedule_continer').append(schedule);
            
          }
        }

      },
      error: function(xhr, status, error) {
          var err = eval("(" + xhr.responseText + ")");
      }
    });
  }

  //function atur form peminjaman ruangan
  function setRoomLoanForm(date){
    date = new Date(date);
    $("#form_room").show();
    $("#form_car").hide();
    $("#date").prop("value", date.toISOString().split('T')[0]);//set value datetime end dari date
    $("#start").prop("min", date.toISOString().split('.')[0]);//set min datetime start dari date
    $("#start").prop("value", date.toISOString().split('.')[0]);//set value datetime start dari date
    $("#end").prop("min", date.toISOString().split('.')[0]);//set min datetime end dari date
    $("#end").prop("value", date.toISOString().split('.')[0]);//set value datetime end dari date
   
    $("textarea[name='agenda']").attr("required", true); //set required form
    $("input[name='perusahaan']").attr("required", true); //set required form
    $("input[name='start']").attr("required", true); //set required form
    $("input[name='end']").attr("required", true); //set required form
    $("input[name='ruangan']").attr("required", true); //set required form
    
    
    $("textarea[name='keperluan']").attr("required", false); 
    $("input[name='tujuan']").attr("required", false); 
    $("input[name='wilayah']").attr("required", false); 
    $("input[name='jenis_perjalanan']").attr("required", false); 
    $("input[name='waktu_berangkat']").attr("required", false); 
    $("input[name='waktu_pulang']").attr("required", false); 

    setCompaniesList();
  }

  //function atur form peminjman kendaraan
  function setCarLoanForm(date){
    date = new Date(date);
    $("#form_car").show();
    $("#form_room").hide();
    $("#waktu_berangkat").prop("min", date.toISOString().split('.')[0]);//set min datetime waktu_berangkat dari date
    $("#waktu_berangkat").prop("value", date.toISOString().split('.')[0]);//set value datetime waktu_berangkat dari date
    $("#waktu_pulang").prop("min", date.toISOString().split('.')[0]);//set min datetime waktu_pulang dari date
    $("#waktu_pulang").prop("value", date.toISOString().split('.')[0]);//set value datetime waktu_pulang dari date
    $("#date").prop("value", date.toISOString().split('.')[0]);//set value datetime waktu_pulang dari date

    $("textarea[name='agenda']").attr("required", false);
    $("input[name='perusahaan']").attr("required", false);
    $("input[name='start']").attr("required", false);
    $("input[name='end']").attr("required", false);
    $("input[name='ruangan']").attr("required", false);
    
    $("textarea[name='keperluan']").attr("required", true); //set required form
    $("input[name='tujuan']").attr("required", true); //set required form
    $("input[name='wilayah']").attr("required", true); //set required form
    $("input[name='jenis_perjalanan']").attr("required", true); //set required form
    $("input[name='waktu_berangkat']").attr("required", true); //set required form
    $("input[name='waktu_berangkat']").attr("required", true); //set required form
  
    setPassangersList();
  }

  //function atur form dan jadwal dari peminjaman
  function setscheduleAndForm(){
    var date = sessionStorage.getItem("date");
    var loanType = sessionStorage.getItem("loanType");

    // 1-car loan
    // 2-room loan

    $('#btn_continue').show();
    $("#title_schedule").text("List Schedule at "+formatDate(date));

    if(loanType == "car"){
      //hide form car loan
      setCarLoanForm(date);
    } else {
      showSchedule(date);
      //hide form room loan
      setRoomLoanForm(date);
    }
  } 

  //function isi opsi select penumpang
  function setPassangersList(){

    $("#passanger_chosen").select2({
      tags:true
    });

    $.ajax({
        type:'get',
        url: "{{ url('/get-usersf/999') }}", //showRoomLoanByDate()
        success:function(data){

          // console.log(data);

          let select = $("#passanger_chosen").select2({
            tags:true
          });

          var len = data['data'].length;
          
          select.empty();

          try {
            if(len > 0){
              for(i=0; i<len; i++){
                var schedule = `<option value="`+data['data'][i]['nama']+`">`+data['data'][i]['nama']+`</option>`;
                select.append(schedule);
              }
              select.trigger('change');
            }
          } catch (error) {
            console.log(error);
          }

        },
        error: function(xhr, status, error) {
            var err = eval("(" + xhr.responseText + ")");
        }
      });

      // var select, chosen;
      // // cache the select element as we'll be using it a few times
      // select = $("#passanger_chosen");

      // // init the chosen plugin
      // try {
      //   select.chosen({ no_results_text: 'Press Enter to add new passanger :' });
      //   // console.log("berhasil set chosen");
      // } catch (error) {
      //   console.log(error);
      // }

      // // get the chosen object
      // chosen = select.data('chosen');

      

      // // Bind the keyup event to the search box input
      // $('.chosen-modal').find('.search-field').find('input').on('keyup', function(e)
      // {
      //     // if we hit Enter and the results list is empty (no matches) add the option
      //     if (e.which == 13 && chosen.dropdown.find('li.no-results').length > 0)
      //     {
      //         var option = $("<option>").val(this.value).text(this.value);
      //         // add the new option
      //         select.prepend(option);
      //         // automatically select it
      //         select.find(option).prop('selected', true);
      //         // trigger the update
      //         select.trigger("chosen:updated");
      //     }
      // });
  }

  //function isi opsi select penumpang
  function setCompaniesList(){

    $('#company_chosen').select2({
      tags:true
    });

  }

  function setPrUserList(){
    var select, chosen;
    // cache the select element as we'll be using it a few times
    select = $("#pruser_chosen");

    // init the chosen plugin
    try {
      select.chosen({ no_results_text: 'Press Enter to add new user :' });
      console.log("berhasil set chosen");
    } catch (error) {
      console.log(error);
    }

    // get the chosen object
    chosen = select.data('chosen');

    $.ajax({
      type:'get',
      url: "{{ url('/get-usersf/999') }}", //showRoomLoanByDate()
      success:function(data){
        var len = data['data'].length;
        select.empty();
        
        
        try {
          if(len > 0){
            for(i=0; i<len; i++){
              var schedule = `<option value="`+data['data'][i]['nama']+`">`+data['data'][i]['nama']+`</option>`;
              select.append(schedule);
              // console.log(schedule);
            }
            select.trigger("chosen:updated");
          }
          console.log("berhasil set user");
        } catch (error) {
          console.log(error);
        }

      },
      error: function(xhr, status, error) {
          var err = eval("(" + xhr.responseText + ")");
      }
    });

    // Bind the keyup event to the search box input
    $('.chosen-modal').find('.search-field').find('input').on('keyup', function(e)
    {
        // if we hit Enter and the results list is empty (no matches) add the option
        if (e.which == 13 && chosen.dropdown.find('li.no-results').length > 0)
        {
            var option = $("<option>").val(this.value).text(this.value);
            // add the new option
            select.prepend(option);
            // automatically select it
            select.find(option).prop('selected', true);
            // trigger the update
            select.trigger("chosen:updated");
        }
    });
  }

  //function tampil fasilitas ruangan
  function showFacilites(id){
    $("#facilities_container").hide();
    
    $.ajax({
      type:"get",
      url:"{{ url('/show-facility') }}/"+id,
      success:function(data){
        
        // console.log(data);
        $("#facilities_container").empty();
        var facilities = "";
        for(i=0; i<data['facilities'].length; i++){

          // facilities = facilities+`<div class="item card notice bg-light-primary rounded border-primary border border-dashed p-2 mx-3">
					// 											<div class="d-flex flex-column my-auto">
					// 												<div class="text-center">
					// 													<i class="`+data['facilities'][i]['icon']+` fs-2x"></i>
					// 												</div>
					// 												<div class="text-center">
					// 													<span class="fw-bold fs-6">`+data['facilities'][i]['fasilitas']+`</span>
					// 												</div>
					// 												<div class="text-center">
					// 													<span class="fs-5">`+data['facilities'][i]['jumlah']+`</span>
					// 												</div>
					// 											</div>
					// 										</div>`;
          jumlah = data['facilities'][i]['jumlah'] != '' && data['facilities'][i]['jumlah'] != null? data['facilities'][i]['jumlah'] : '';
          facilities = facilities+`<div class="item card notice bg-light-primary rounded border-primary border border-dashed p-2 mx-3">
                                        <div class="d-flex flex-column my-auto">
                                            <div class="text-center">
                                                <span class="fw-bold fs-8">`+data['facilities'][i]['fasilitas']+`</span>
                                            </div>
                                    <div class="text-center">
                                                <span class="fs-5">`+jumlah+`</span>
                                            </div>
                                        </div>
                                    </div>`;
        }

        var floor = ordinal(data['room']['lantai']);

        var card = `<div class="card shadow">
													<div class="card-body">
														<span class="fs-8 fw-bold m-3">
															`+data['room']['nama']+`
														</span>
														<div class="d-flex mt-3">
															<div class="card shadow">
																<div class="d-flex flex-wrap m-3">
																	<div class="mx-auto my-auto">
																		<i class="fas fa-map-marker-alt"></i>
																	</div>
																	<span class="fw-bolder text-center fs-8 m-2">`+data['location']['nama']+`, `+floor+` Floor</span>
																</div>
															</div>
															<div class="card shadow ms-3">
																<div class="d-flex flex-wrap m-3">
																	<div class="mx-auto my-auto">
																		<i class="fas fa-users"></i>
																	</div>
																	<span class="fw-bolder text-center fs-8 m-2">`+data['room']['kapasitas']+` People</span>
																</div>
															</div>
														</div>
														<div class="hori-scroll mt-5">
															`+facilities+`
														</div>
													</div>
												</div>`;
        $("#facilities_container").append(card);

        },error: function(xhr, status, error) {
            var err = eval("(" + xhr.responseText + ")");
        }
    });

    $("#facilities_container").show();
  }

  //function jika jenis perjalana one way
  function isOneWay(){
      $('#return').hide();
  }

  //function  jika jenis perjalanan round trip
  function isRoundTrip(){
      $('#return').show();
  }

  //function untuk memeriksa ketersediaan ruangan
  function checkRoomBooking(){
    // alert('masuk check room booking');
    var date = sessionStorage.getItem('date');
    var start = $("input[name='start']").val();
    var end = $("input[name='end']").val();
    // console.log(start);
    
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        
    $.ajax({
      type:"post",
      url:"{{ url('/check-room-booking') }}",
      data : {
        date : date,
        start : start,
        end : end,
      },
      success:function(data){
        
        // console.log(data);
        $("#rooms_container").empty();
          for(i=0; i<data['rooms'].length; i++){
            var classes = "";
            var style = "";
            var disabled = "";

            if(data['rooms'][i]['booked'] == 1){
              classes = "btn btn-bg-light btn-color-gray-400 p-1 m-2";
              style = "style='cursor:default'";
              disabled = "disabled";
            }else{
              classes = "btn btn-outline btn-outline-primary btn-active-primary p-1 m-2";
            }

            var room = `<label class="`+classes+`" `+style+` >
                            <input name="ruangan" type="radio" class="btn-check" value="`+data['rooms'][i]['id']+`" onclick="showFacilites('`+data['rooms'][i]['id']+`')" `+disabled+`/>
                            <span class="fw-bolder fs-8">`+data['rooms'][i]['nama']+`</span>
                        </label>`;
            $("#rooms_container").append(room);
          }

        },error: function(xhr, status, error) {
            var err = eval("(" + xhr.responseText + ")");
        }
    });
    
  }

  //function untuk copy item
  function copyToClipboard(target, button){
    target = document.getElementById(target);
    var clipboard = new ClipboardJS(button, {
        target: target,
        text: function() {
            return target.value;
        }
    });

    // Success action handler
    clipboard.on('success', function(e) {
        const currentLabel = button.innerHTML;

        // Exit label update when already in progress
        if(button.innerHTML === 'Copied!'){
            return;
        }

        // Update button label
        button.innerHTML = 'Copied!';

        // Revert button label after 3 seconds
        setTimeout(function(){
            button.innerHTML = currentLabel;
        }, 3000)
    });
  }

  //function konversi format tanggal
  function formatDate(value) {
      let date = new Date(value);
      const day = date.toLocaleString('default', { day: '2-digit' });
      const month = date.toLocaleString('default', { month: 'short' });
      const year = date.toLocaleString('default', { year: 'numeric' });
      return day + ' ' + month + ' ' + year;
  }

  //function konversi format jam
  function formatTime(value) {
      let date = new Date(value);
      const time = date.toLocaleString([], {hour: '2-digit', minute:'2-digit', hour12: false});
      return time;
  }

  //function konversi format tanggal dan jam
  function formatDateTime(value) {
      let date = new Date(value);
      const day = date.toLocaleString('default', { day: '2-digit' });
      const month = date.toLocaleString('default', { month: 'short' });
      const year = date.toLocaleString('default', { year: 'numeric' });
      const time = date.toLocaleString([], {hour: '2-digit', minute:'2-digit', hour12: false});
      return day + ' ' + month + ' ' + year + ' ' + time;
  }

  //function konversi format angka menggunakan ordinal
  function ordinal(number) {
    ends = ['th','st','nd','rd','th','th','th','th','th','th'];
    if (((number % 100) >= 11) && ((number%100) <= 13)){
        return number+'th';
    }else{
        return number+ends[number % 10];
    } 
  }

</script>