<div class="modal fade" id="Ticketing" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: auto;">
        <div class="modal-content">
            <div class="modal-header bg-info" id="Juduls">
            </div>
            <form method="post" action="{{url('subcontractorcreateticket')}}" enctype="multipart/form-data">
                 {{csrf_field()}}
                <div class="table-responsive">
                    <div class="modal-body text-center" id="tickets"></div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-primary btn-sm pull-left ">
                            <button type="button" class="btn btn-warning btn-sm pull-left" data-dismiss="modal">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function myFunction(val,n) {
       if(val > n){
            alert("Qty Ticket Kelebihan BosQ")
        }
    }
    $(document).ready(function() {
        $('#datatable-responsive tbody').on('click', '.Ticketing', function ()
        {
            var id = $(this).attr('data-id');
            $.ajax({
                url : "{{url('view_subcontcreateticket')}}?id="+id,
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {
                    // console.log(data.dataticket);
                    var qty             = data.dataid.Quantity;
                    var id              = data.dataid.ID;
                    var datess          = new Date(data.dataid.DeliveryDate);
                    var datenow         = new Date();
                    var dd              = String(datess.getDate()).padStart(2, '0');
                    var mm              = String(datess.getMonth() + 1).padStart(2, '0'); //January is 0!
                    var yyyy            = datess.getFullYear();
                    var dddd            = String(datenow.getDate()).padStart(2, '0') ;
                    var ddd             = String(datenow.getDate() ).padStart(2, '0') ;
                    var mmm             = String(datenow.getMonth() + 1).padStart(2, '0'); //January is 0!
                    var yyyyy           = datenow.getFullYear();
                    var DeliveryDate    = dd + '/' + mm + '/' + yyyy;
                    var DateNows        = ddd + '/' + mmm + '/' + yyyyy;

                    //Tambahan Detail
                    var poNumber        = data.dataid.Number;
                    var ItemNumber      = data.dataid.ItemNumber;
                    var Material        = data.dataid.Material;
                    var MatDesc         = data.dataid.Description;

                    if(data.dataid.ConfirmedDate == null){
                        var d = new Date(data.dataid.DeliveryDate);
                    }else{
                        var d = new Date(data.dataid.ConfirmedDate);
                    }

                    var ActiveStage = data.dataid.ActiveStage ;

                    var date            = d.getDate();
                    var month           = d.getMonth()+1;
                    var year            = d.getFullYear();
                    var datedelivery    = date + '/' + month + '/' + year;

                    if(d.getTime() < datenow.getTime()){
                        var dates   = ddd + '/' + mmm + '/' + yyyyy;
                    } else {
                        var dates   = date + '/' + month + '/' + year;
                    }

                    $('#Juduls').empty();
                    $('#tickets').empty();
                    var html =
                    `
                        <div class="row m-4">
                                <table class="table table-md table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="vertical-align:middle;">PO Number</th>
                                            <th style="vertical-align:middle;">`+poNumber+`</th>
                                            <th style="vertical-align:middle;">Delivery Date</th>
                                            <input type="hidden" style="text-align: center;" class="form-control"  name="deliverydatesap" value="`+datedelivery+`"required>

                                            <th style="vertical-align:middle;"><input type="text" style="text-align: center;" class="form-control datepicker"  name="deliverydate" placeholder="dd/mm/yyyy" autocomplete="off" required></th>
                                            <th style="vertical-align:middle;">Delivery Time</th>
                                            <th style="vertical-align:middle;"><input type="time" style="text-align: center;" class="form-control"  name="deliverytime"  required></th>
                                        </tr>
                                        <tr>
                                            <th style="vertical-align:middle;">Delivery Note</th>
                                            <th style="vertical-align:middle;"><input type="text" class="form-control"  name="DeliveryNote" autocomplete="off" placeholder="delivery note.." required></th>
                                            <th style="vertical-align:middle;">Header Text</th>
                                            <th style="vertical-align:middle;"><input type="text"  class="form-control"  name="headertext" autocomplete="off" placeholder="header text.." required></th>
                                            <th style="vertical-align:middle;">SPB Date</th>
                                            <th style="vertical-align:middle;"><input type="text" style="text-align: center;" class="form-control datepicker1" autocomplete="off" name="SPBdate" placeholder="dd/mm/yyyy" required></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        `;
                        html +=
                        `
                            <div class="row m-4">
                                <table class="table table-md">
                                    <thead class="thead-light">
                                        <tr>
                                        <th><input type="checkbox" name="ID" class="checkItem" id="allpayment"></th>
                                        <th>ItemNumber</th>
                                        <th>Material</th>
                                        <th>Description</th>
                                        <th>Qty Po</th>
                                        <input type="hidden" name="ActiveStage" class="form-control" value="`+ActiveStage+`" >
                                        <th>Qty Delivery</th>
                                        <th></>
                                        </tr>
                                    </thead>
                                    <tbody>
                        `;
                        for(i=0;i<data.dataticket.length;i++){
                            var q = "";
                            var s = "";
                            var qty = data.dataticket[i].ActualQuantity ;
                            if(data.dataticket[i].ConfirmedItem == '' || data.dataticket[i].ConfirmedItem == null)
                            {
                                var sisa = data.dataticket[i].OpenQuantity - data.dataticket[i].totalticket;
                            }
                            else{
                                var sisa = data.dataticket[i].ConfirmedQuantity - data.dataticket[i].totalticket;
                            }

                            if (sisa <= 0){
                                var s = "readonly" ;
                                var d = "Ticket FULL" ;
                                var q = "disabled";
                                
                            }else{
                                var d = sisa;
                                                           }
                            if(data.dataticket[i].ParentID == null){
                                var ids = data.dataticket[i].ID ;
                            }else{
                                var ids = data.dataticket[i].ParentID ;
                            }

                            html +=
                            `
                                <tr>`;
                                if(sisa > 0){
                                    html += `<td><input type="checkbox"  `+q+` name="ID[]" class="checkItem" value="`+ids+`" ></td>`;
                                }else{
                                    html += `<td></td>`;
                                }
                            html +=
                            `
                                    <input type="hidden" name="number[]"  value="`+data.dataticket[i].Number+`">
                                    <input type="hidden" name="numbers"  value="`+data.dataticket[i].Number+`">
                                    <input type="hidden"  name="itemnumber[]"  value="`+data.dataticket[i].ItemNumber+`">
                                    <input type="hidden"  name="material[]"  value="`+data.dataticket[i].Material+`">
                                    <input type="hidden"  name="description[]"  value="`+data.dataticket[i].Description+`">
                                    <input type="hidden" name="qtysap[]"  value="`+qty+`">
                                    <td >`+data.dataticket[i].ItemNumber+`</td>
                                    <td >`+data.dataticket[i].Material+`</td>
                                    <td >`+data.dataticket[i].Description+`</td>
                                    <td>`+qty+`</td>
                                    <td ><input type="text" style="text-align: center;" name="QtyDelivery[`+ids+`][]" `+s+`  class="form-control" value="`+d+`"  onchange="myFunction(this.value,`+d+`)"></td>
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
                                                            </thead>
                            `;
                            for(n=0;n<data.dataall.length;n++){
                                if(data.dataticket[i].ParentID == data.dataall[n].ParentID){
                                    if(data.dataid.ConfirmedDate == null){
                                        var datesss = new Date(data.dataall[n].DeliveryDate);
                                        var qty = data.dataall[n].OpenQuantity ;
                                    }else{
                                        var datesss            = new Date(data.dataall[n].ConfirmedDate);
                                        var qty = data.dataall[n].ConfirmedQuantity ;
                                    }

                                    var ddd              = String(datesss.getDate()).padStart(2, '0');
                                    var mmm              = String(datesss.getMonth() + 1).padStart(2, '0'); //January is 0!
                                    var yyyyy            = datesss.getFullYear();
                                    var DeliveryDatess   = ddd + '/' + mmm + '/' + yyyyy;

                                    html +=
                                    `
                                        <tbody class="thead-light">
                                            <tr>
                                                <td>`+qty+`</td>
                                                <td>`+DeliveryDatess+`</td>
                                            </tr>
                                        </tbody>
                                    `;
                                }
                            }
                            html+=
                            `
                                </table>
                                </div>
                                </div>
                                </div>
                                </td>
                                </tr>
                            `;
                        }

                        html+=
                        `
                            </tbody>
                            </table>
                            </div>
                        `;
                        $('#tickets').append(html);
                        $(".datepicker").datepicker({
                            format: "dd/mm/yyyy",
                            autoclose: true,
                            startDate:'+1d',
                            daysOfWeekDisabled: [0,6]
                        });
                        $(".datepicker1").datepicker({
                            format: "dd/mm/yyyy",
                            autoclose: true
                        });

                        $("#Juduls").append(`
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
