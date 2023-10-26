

<!-- <script src="{{ asset('public/js/jquery/dist/jquery.min.js') }}"></script> -->
<script src="{{ asset('public/assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/global/plugins/js.cookie.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/global/plugins/uniform/jquery.uniform.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('public/js/chat.js') }}" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function(){
            var out = document.getElementById("chat-content");
            $('#chat-content').scrollTop(out.scrollHeight+500);
  document.querySelectorAll('.sidebar .nav-link').forEach(function(element){
    
    element.addEventListener('click', function (e) {

      let nextEl = element.nextElementSibling;
      let parentEl  = element.parentElement;	

        if(nextEl) {
            e.preventDefault();	
            let mycollapse = new bootstrap.Collapse(nextEl);
            
            if(nextEl.classList.contains('show')){
              mycollapse.hide();
            } else {
                mycollapse.show();
                // find other submenus with class=show
                var opened_submenu = parentEl.parentElement.querySelector('.submenu.show');
                // if it exists, then close all of them
                if(opened_submenu){
                  new bootstrap.Collapse(opened_submenu);
                }
            }
        }
    }); // addEventListener
  }) // forEach
}); 
$(document).ready(function(){
            var out = document.getElementById("chat-content");
            $('#chat-content').scrollTop(out.scrollHeight+500);
    $('.js-example-basic-single').select2();
    function load_unseen_notification(view = '')
    {
        $.ajax({
            url:"{{ url('notif-read') }}",
            method:"GET",
            data:{view:view},
            dataType:"json",
            success:function(data)
            {
              console.log(data);
                $('.notif-data').html(data.notification);
                if(data.unseen_notification > 0)
                {
                    $('.count').html(data.unseen_notification);
                }
            }
        });
    }
    // Run Ajax
    load_unseen_notification();
    $(document).on('click', '.dropdown-toggle', function(){
        $('.count').html('');
        load_unseen_notification('yes');
    });
    // Jika Menu Notifikasi Close, Maka Akan Panggil Fungsi Ini
    // $('.dropdown').on('hide.bs.dropdown', function () {
    //     setInterval(function(){
    //     load_unseen_notification();
    //     }, 6000);
    // })
});
// Ajax Untuk Load Semua Data
function load_unseen_notification_all(view = '')
{
    $.ajax({
        url:"{{ url('notif-read-all') }}",
        method:"GET",
        data:{view:view},
        dataType:"json",
        success:function(data)
        {
            $('.notif-data').html(data.notification);
            if(data.unseen_notification > 0)
            {
                $('.count').html(data.unseen_notification);
            }
        }
    });
}
function loadmorebutton() {
    $("#load-more-btn").removeAttr('onmouseover');
    $("#load-more-btn").html('<i class="fa fa-spinner fa-spin fa-2x"></i>');
    setTimeout(function(){
        $("#load-more-btn").hide();
        load_unseen_notification_all('yes');
    }, 5000);
}

</script>

<script type="text/javascript">
   
   $.ajaxSetup({
       headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       }
   });
   
function getChat(val){
  
  var out = document.getElementById("chat-content");
  $('#chat-content').scrollTop(out.scrollHeight+500);
        document.getElementById("myForm").style.display = "block";
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
           }
           var head = "<h4 class='card-title'><strong>Chat </strong><a><span style='background-color: grey; color: black;' class='badge badge-secondary'>#"+tickid+"</span></a></h4>"+ 
           ""+
           "<a class='pull-right' style='text-decoration:none;color:black' onclick='closeForm()' data-abc='true'>&times;</a>"
           console.log(len); // Empty <tbo
          
           $("#chatHead").append(head);
           if(len > 0){
              for(var i=0; i<len; i++){
                if(response['data'][i]['created_by']!=response['data'][i]['reporter_nrp']){
                    var tr_str = "<div class='media media-chat media-chat-main'> "+
                            "<img class='avatar' src="+"{{ asset('public/assets/global/img/no-profile.jpg') }}"+" alt='...'>"+response['data'][i]['name'].substr(0, 10)+
                            "<div class='media-body'>"+
                                "<p>"+response['data'][i]['text']+"</p>"+
                                "<p class='meta'> "+response['data'][i]['created_at']+"</p>"+
                            "</div>"+
                        "</div>";
                }else{
                    var tr_str = "<div class='media media-chat media-chat-reverse'><span style='float: right;'> You </span>"+
                            "<div class='media-body'>"+
                                "<p>"+response['data'][i]['text']+"</p>"+
                                "<p class='meta' style='color:grey;'> "+response['data'][i]['created_at']+"</p>"+
                            "</div>"+
                        "</div>";
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
     
function closeForm() {
  document.getElementById("myForm").style.display = "none";
  document.getElementById("id_ticket").value = "";
}
   $(".btn-submit-chat").click(function(e){
   
       e.preventDefault();
   
       var text = $("input[name=text]").val();
       var id_ticket = $("input[name=id_ticket]").val();

       APP_URL = '{{url('/')}}' ;
       $.ajax({
           type:'POST',
           url: "{{url('comment-ticket')}}",
           data:{text:text, id_ticket:id_ticket},
           success:function(data){
            var out = document.getElementById("chat-content");
            $('#chat-content').scrollTop(out.scrollHeight+500);
            $("input[name=text]").val("");
            getChat(id_ticket);
            
           }
       });
   
   });
   </script>
