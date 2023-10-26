<div class="modal fade" id="cekpo" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info" id="Judulss"></div>
            <div class="table-responsive">
                <div class="modal-body text-center" id="ticketss"></div>
                    <div id="Footer" class="modal-footer">

                    </div>
            </div>
        </div>
    </div>
</div>

<script>

 function getNotif(val){

                $.ajax({
                    url : "{{url('cek_ticket')}}?id="+val,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data)
                    {
                        $('#Judulss').empty();
                        $('#Footer').empty();
                        $('#ticketss').empty();
                        var html =
                        `
                        <div class="row m-4">
                                    <table class="table table-bordered table-md ">
                                    <thead>
                                        <tr>
                                        <th scope="col">PO Number</th>
                                        <th scope="col">Ticket ID </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>`+data.datafirst.Number+`</td>
                                            <td>`+data.datafirst.TicketID+`</td>
                                        </tr>
                                    </tbody>
                                   </table>
                            </div>`;
                        html +=
                            `<div class="row m-4">
                                <table class="table table-md">
                                    <thead >
                                        <tr>
                                        <th>Item Number</th>
                                        <th>Material</th>
                                        <th>Description</th>
                                        <th>DeliveryDate</th>
                                        <th>Qty Delivery</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                       for(i=0;i<data.data.length;i++){
                        if(data.data[i].status == 'P'){
                            var status = "New Ticket";
                            var warna = "primary";
                        }else if(data.data[i].status == 'D'){
                            var status = "Ticket Delivery";
                            var warna = "warning";
                        }else if(data.data[i].status == 'C'){
                            var status = "Ticket Cancel";
                            var warna = "danger";
                        }else if(data.data[i].status == 'A'){
                            var status = "Ticket Approve";
                            var warna = "success";
                        }
                        if(data.data[i].remarks == null){
                            var remarks = "-";
                        }else{
                            var remarks = data.data[i].remarks ;
                        }
                        var date            = new Date(data.data[i].DeliveryDate);
                        var DeliveryDate    = date.toLocaleDateString('id-ID');
                            html +=
                            `
                                <tr>
                                    <td >`+data.data[i].ItemNumber+`</td>
                                    <td >`+data.data[i].Material+`</td>
                                    <td >`+data.data[i].Description+`</td>
                                    <td>`+DeliveryDate+`</td>
                                    <td>`+data.data[i].Quantity+`</td>
                                    <td class="btn btn-`+warna+`">`+status+`</td>
                                    <td>`+remarks+`</td>
                                </tr>
                            `
                        }

                        html+=`</tbody></table>
                            </div>`;
                        $('#ticketss').append(html);
                        if(data.datanotif.menu == "Ticket Local"){
                            var proses = "Approve ?";
                            var url = "{{url('caripolocalplandelivery')}}" ;
                        }else if(data.datanotif.menu == "Ticket Subcont"){
                            var proses = "Approve ?";
                            var url = "{{url('carisubcontractorplandelivery')}}" ;
                        }else if(data.datanotif.menu == "Cancel Ticket Local"){
                            var proses = "Create Ticket ?";
                            var url = "{{url('caripolocalongoing')}}" ;
                        }else if(data.datanotif.menu == "Cancel Ticket Subcont"){
                            var proses = "Create Ticket ?";
                            var url = "{{url('carisubcontractorongoing')}}" ;
                        }else if(data.datanotif.menu == "Approve Ticket Local"){
                            var proses = "Cek Ticket ?";
                            var url = "{{url('print-ticket')}}" ;
                        }else if(data.datanotif.menu == "Approve Ticket Subcont"){
                            var proses = "Cek Ticket ?";
                            var url = "{{url('subcontractorcheckticket')}}" ;
                        }


                        $("#Footer").append
                        (`
                        <form action="`+url+`" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="number"  value="`+data.datafirst.Number+`">
                            <input type="hidden" name="TicketID"  value="`+data.datafirst.TicketID+`">
                            <button type="submit" class="btn btn-primary btn-sm pull-left">`+proses+`</button>
                            <button type="button" class="btn btn-warning btn-sm pull-left" data-dismiss="modal">Back</button>
                        </form>
                          `);
                        $("#Judulss").append
                        (`
                        <h6 text-center>Notification</h6>
                        `);

                        $('#cekpo').modal('show');
                    }


    });
}

</script>
