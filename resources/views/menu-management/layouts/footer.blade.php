
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
  <script src="{{ asset('public/assets/fe/vendor/aos/aos.js') }}"></script>
<script src="{{ asset('public/assets/global/plugins/bootstrap/js/select2.min.js') }}"></script>
  <script src="{{ asset('public/assets/fe/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('public/assets/fe/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('public/assets/fe/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('public/assets/fe/vendor/php-email-form/validate.js') }}"></script>

  <!-- Template Main JS File -->
  
  <script src="{{ asset('public/assets/fe/js/main.js') }}"></script>
  
  <script>
  $(document).ready(function() {
      // modal.style.display = "block";
      const myTimeout = setTimeout(myGreeting, 6000);

    function myGreeting() {
      // document.getElementById("msg").style.display = "none"
    }
      $('.js-example-basic-single').select2();
  });
  function copy() {
  /* Get the text field */
  var copyText = document.getElementById("code");

  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /* For mobile devices */

   /* Copy the text inside the text field */
  navigator.clipboard.writeText(copyText.value);

  /* Alert the copied text */
  document.getElementById("copied").innerHTML="Disalin ke clipboard : " + copyText.value+"        ";
  const myTimeout = setTimeout(myGreeting, 3000);

  function myGreeting() {
    document.getElementById("copied").innerHTML = ""
  }
  // alert("Copied the text: " + copyText.value);
}
  function catselect(val) {
    document.getElementById("cat").style.display = "block";
    document.getElementById("asset").style.display = "none";
  }
  function assetselect(val) {
    document.getElementById("asset").style.display = "block";
    document.getElementById("cat").style.display = "none";
  }
  function accountselect(val) {
    document.getElementById("much").style.display = "none";
  }
  function inventoryselect(val) {
    document.getElementById("much").style.display = "block";
  }
  </script>

<script>
      
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
</body>

</html>