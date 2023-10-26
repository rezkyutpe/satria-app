
 <meta name="csrf-token" content="{{ csrf_token() }}">
<div class="modal fade" id="chatings" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="width: 90%; margin: 0 auto;">
          <div class="containers">
             <div id="header"></div>
              <div class="messaging">
                    <div class="inbox_msg">

                      <div class="mesgs">

                        <div class="msg_history"  id="chat-content">
                            <div id="datachat">
                            </div>
                        </div>
                        <div class="type_msg">
                          <div class="input_msg_write">
                            <input type="hidden" name="numbers" id="numbers">
                            <input type="hidden" name="items" id="items">
                            <input type="hidden" name="namer" id="names">
                            <input type="hidden" name="proc" id="proc">
                            <input type="hidden" name="vendors" id="vendors">

                            <div id="datasubmit">
                            </div>
                          </div>
                        </div>

                      </div>
                    </div>

                </div>
              </div>
          </div>
    </div>
</div>

<script>

 function getChat(val,vol){

    var out = document.getElementById("chat-content");
    $.ajax({
              url : "{{url('cek_coment')}}?number="+val+"&item="+vol,
                  type: "GET",
                  dataType: "JSON",
                  success: function(data)

                  {

                    $('#datachat').empty();
                    $('#datasubmit').empty();
                    $('#header').empty();
                    $("#header").append
                             (`
                                  <h6 class="text-center">Po. `+val+` | Item `+vol+`</h6>
                                  <h6 class="text-center">`+data.Po.Material+` | `+data.Po.Vendor+`</h6>
                              `);                    for(i=0;i<data.datar.length;i++){

                        var date            = new Date(data.datar[i].created_at);
                        var dd          = String(date.getDate()).padStart(2, '0');
                        var mm          = String(date.getMonth() + 1).padStart(2, '0');
                        var yyyy        = date.getFullYear();
                        var tanggalcoment= dd + '/' + mm + '/' + yyyy;
                        let time = date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();
                            if(data.datar[i].is_read == 1){
                                var $status = "Baca" ;
                            }else{
                                var  $status = "" ;

                            }

                            if(data.datar[i].user_by == data.Name){

                            $("#datachat").append
                              (`
                              <div class="outgoing_msg">
                                <div class="sent_msg">
                                    <p>`+data.datar[i].comment+`</p>
                                  <span class="time_date" style="font-size:10px;">`+data.datar[i].user_by+` | `+tanggalcoment+` | `+time+` &nbsp; &nbsp;&nbsp; `+status+`</span> </div>

                                </div>

                              `);

                            }else{
                            $("#datachat").append
                                (`
                                <div class="incoming_msg">
                                  <div class="received_msg">
                                    <div class="received_withd_msg">
                                       <p>`+data.datar[i].comment+`</p>
                                        <span class="time_date" style="font-size:10px;">`+data.datar[i].user_by+` | `+tanggalcoment+` | `+time+`</span> </div>
                                    </div>
                             </div>
                            `);
                          }

                     }
                     $("#datasubmit").append
                                (`
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-10">
                                        <input type="text" name="comment" id="entersave"  placeholder="Type a message" required>
                                    </div>
                                    <div class="col-2">
                                        <button type="button" class="btn btn-primary" id="id_of_button" onclick="Save()"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
                                    </div>

                                </div>
                            `);
                     $("#entersave").keyup(function(event) {
                            if (event.keyCode === 13) {
                                $("#id_of_button").click();
                            }
                    });
                      $('#names').val(data.Name);
                      $('#numbers').val(val);
                      $('#vendors').val(data.Po.Vendor);
                      $('#items').val(vol);
                      $('#proc').val(data.Po.NRP);
                      $('#chatings').modal('show');
                  }

              });

    }

        function Save() {
            var number = $("input[name=numbers]").val();
            var item = $("input[name=items]").val();
            var namer = $("input[name=namer]").val();
            var comment = $("input[name=comment]").val();
            var vendors = $("input[name=vendors]").val();
            var proc = $("input[name=proc]").val();
            let _token   = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ url('insert-comment') }}",
                type: "POST",
                data: {
                    Number:number,
                    Vendor:vendors,
                    Comment:comment,
                    Proc:proc,
                    Item:item,
                    Name:namer,
                    _token: _token
                },
                success:function(data){
                        var out = document.getElementById("chat-content");
                        $('#chat-content').scrollTop(out.scrollHeight+500);
                        $("input[name=comment]").val("");
                        getChat(number,item);

                    },

            });
        }

</script>

