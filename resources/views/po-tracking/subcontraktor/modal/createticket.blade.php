{{-- Modal sequenceprogress --}}
<div class="modal fade" id="createticket" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="top:10px;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="mt-2">Create Ticket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="row table-responsive m-auto">
                    <div id="col">
                         <!-- Detail Tabel -->
                        <table class="table table-bordered table-sm ">
                            <thead>
                                <tr>
                                    <th>PO Number</th>
                                    <th>Item Number</th>
                                    <th>Material</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="poNumber"></td>
                                    <td id="itemNumber"></td>
                                    <td id="Material" nowrap></td>
                                    <td id="dMaterial" nowrap></td>
                                    <td id="Qty"></td>
                                </tr>
                            </tbody>
                        </table>
                         <!-- END of Detail Tabel -->
                    </div>
                </div>
                <div class="row table-responsive m-auto">
                    <div class="col">
                        <div id="createticketproses"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.createticketclass').on("click" ,function()
        {
            var id = $(this).attr('data-id');
            $.ajax({
                url : "{{url('viewcreateticketsubcontractor')}}?id="+id,
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {
                    var qty             = data.dataid.Quantity;
                    var id              = data.dataid.ID;
                    var datess            = new Date(data.dataid.DeliveryDate);
                    var dd              = String(datess.getDate()).padStart(2, '0');
                    var mm              = String(datess.getMonth() + 1).padStart(2, '0'); //January is 0!
                    var yyyy            = datess.getFullYear();
                    var DeliveryDate    = dd + '/' + mm + '/' + yyyy;

                    //Tambahan Detail
                    var poNumber        = data.dataid.Number;
                    var ItemNumber      = data.dataid.ItemNumber;
                    var Material        = data.dataid.Material;
                    var MatDesc         = data.dataid.Description;
                    var d = new Date();
                    var date = d.getDate()+2;
                    var month = d.getMonth()+1;
                    var year = d.getFullYear();
                    var dates = date + '/' + month + '/' + year;

                    $('#Juduls').empty();
                    $('#tickets').empty();
                    var html =
                    `
                    <div class="row m-4">
                            <table class="table  table-md ">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">PO Number</th>
                                        <th scope="col">`+poNumber+`</th>
                                        <th scope="col"></th>
                                        <th scope="col">Delivery Date</th>
                                        <th scope="col"><input type="text" style="text-align: center;" class="form-control datepicker"  name="deliverydate" value="`+dates+`" required></th>
                                        <th scope="col">Delivery Time</th>
                                        <th scope="col"><input type="time" style="text-align: center;" class="form-control" name="deliverytime"  required></th>
                                        <th scope="col">SPB Date</th>
                                        <th scope="col"><input type="time" style="text-align: center;" class="form-control datepicker1" name="SPBdate" required></th>
                                    </tr>
                                </thead>

                            </table>
                        </div>`;
                    html +=
                        `<div class="row m-4">
                            <table class="table table-md">
                                <thead class="thead-light">
                                    <tr>
                                        <th><input type="checkbox" name="ID" class="checkItem" id="allpayment"></th>
                                        <th>ItemNumber</th>
                                        <th>Material</th>
                                        <th>Description</th>
                                        <th>Qty Po</th>
                                        <input type="hidden" name="ActiveStage" class="form-control" value="`+data.dataid.ActiveStage+`" >
                                        <th>Qty Delivery</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>`;
                    for(i=0;i<data.dataticket.length;i++){


                        html +=
                        `<tr>
                            <td><input type="checkbox"  name="ID[]" class="checkItem" value="`+data.dataticket[i].ParentID+`" ></td>
                            <td >`+data.dataticket[i].ItemNumber+`</td>
                            <td >`+data.dataticket[i].Material+`</td>
                            <td >`+data.dataticket[i].Description+`</td>
                            <td>`+data.dataticket[i].Quantity+`</td>
                            <td ><input type="number" style="text-align: center;" name="QtyDelivery[]" class="form-control" value="`+data.dataticket[i].Quantity+`" max="`+data.dataticket[i].Quantity+`" ></td>
                            <td><a href="javascript:void(0);" data-toggle="collapse" data-target="#collapseExample`+i+`"><span class="glyphicon glyphicon-eye-open"></span></a></td>
                            </tr>
                            <tr class="collapse" id="collapseExample`+i+`" >
                            <td colspan="12">
                                <div class="collapse" id="collapseExample`+i+`">

                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="pb`+i+`" role="tabpanel" aria-labelledby="pb-tab`+i+`">
                                            <div class="row m-4">
                                                <table class="table table-md">
                                                    <thead class="thead-light">
                                                    <tr>
                                                    <th>Qty </th>
                                                    <th>DeliveryDate</th>
                                                    </tr>
                                                    </thead>`
                    

                        for(n=0;n<data.dataall.length;n++){

                            if(data.dataticket[i].ParentID == data.dataall[n].ParentID){
                            var datesss            = new Date(data.dataall[n].ConfirmedDate);
                            var ddd              = String(datesss.getDate()).padStart(2, '0');
                            var mmm              = String(datesss.getMonth() + 1).padStart(2, '0'); //January is 0!
                            var yyyyy            = datesss.getFullYear();
                            var DeliveryDatess   = ddd + '/' + mmm + '/' + yyyyy;

                            html +=`
                                <tbody class="thead-light">
                                    <tr>
                                        <td>`+data.dataall[n].ConfirmedQuantity+`</td>
                                        <td>`+DeliveryDatess+`</td>
                                    </tr>
                                </tbody>

                        `;
                        }
                    }
                    html+=` </table>
                                </div>
                                </div>
                                </div>
                                </td>
                                </tr>
                            `;
                            }
                    html+=`

                            </tbody>
                            </table>
                        </div>`;

                    $('#tickets').append(html);

                    $(".datepicker").datepicker({
                        format: "dd/mm/yyyy",
                            autoclose: true,
                            startDate: '+2d',
                            daysOfWeekDisabled: [0,6]

                        });
                    $(".datepicker1").datepicker({
                        format: "dd/mm/yyyy",
                        autoclose: true,
                    });
                    $("#Juduls").append
                    (`
                    <h6 text-center>Create Ticketing</h6>
                    `);
                    $('#allpayment').click(function(){

                    if ($(this).is(':checked') ==  true)
                        {
                            $('.checkItem').prop('checked', true);
                        }
                        else
                        {
                            $('.checkItem').prop('checked', false);
                        }
                    })
                    $('#Ticketing').modal('show');
                }
            });
        });
    });
</script>