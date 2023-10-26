 {{-- Modal Negosiasi PO --}}
 <div class="modal fade" id="ponegotiated" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " style="max-width: 900px;">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="exampleModalLabel">Negotiation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
            </div>
            <form method="post" action="{{url('update-posubcontractor')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="modal-body text-center" id="ponego" style="padding: 0%"></div>
                <div class="modal-footer">
                    @if($actionmenu->v==1)
                    <input type="submit" name="action" value="Update" class="btn btn-warning btn-sm pull-left">
                      @endif
                @if ($actionmenu->d==1)
                    <input type="submit" name="action" value="Yes" class="btn btn-primary btn-sm pull-left">
                    <input type="submit" name="action"  value="Cancel" class="btn btn-danger btn-sm pull-left" onclick="return confirm('Apakah anda Yakin Ingin Cancel PO Ini ?');">
                @endif
                   </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $('#datatable-responsive tbody').on('click', '.negosiasi', function ()
        {
                var number = $(this).attr('data-number');
                var item = $(this).attr('data-item');
                $.ajax({
                url : "{{url('view_negosiasiposubcontractor')}}?number="+number+"&item="+item,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data)
                    {
                        var poNumber        = data.dataid.Number;
                        var vendor      = data.dataid.Vendor;


                        $('#ponego').empty();
                        var html =
                        `   <div class="row m-4">
                                <table class="table table-bordered table-md ">
                                    <thead>
                                        <tr>
                                        <th scope="col">Vendor Name</th>
                                        <th scope="col">PO Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>`+vendor+`</td>
                                            <td>`+poNumber+`</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>`;
                        html +=
                            `<div class="row m-4">
                                <table class="table table-md">
                                    <thead class="thead-light">
                                        <tr>
                                        <th><input type="checkbox" name="ID" class="checkItem" id="allpayment"></th>
                                        <th>Item Number</th>
                                        <th>Material</th>
                                        <th>Description</th>
                                        <th>Quantity</th>
                                        <th>Delivery Date</th>
                                        <th>Confirm Quantity</th>
                                        <th>Confirm Arrival Date</th>
                                        <th>Req CCR Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                        for(i=0;i<data.data.length;i++){

                            if(data.status==2) {
                                    var status = "readonly";
                            }else{
                                    var datepickers = "datepicker"
                                    var status = "";
                            }
                            var tgldelivery = new Date(data.data[i].DeliveryDate);
                            var cektgldelivery = tgldelivery.toLocaleDateString('id-ID');
                            var tglconfirm = new Date(data.data[i].ConfirmedDate);
                            var cektglconfirm = tglconfirm.toLocaleDateString('id-ID');

                            //Req Date dari CCR
                        if(data.data[i].req_date == null){
                            var ReqDate         = '-';
                        }else{
                            var date1           = new Date(data.data[i].req_date);
                            var dd1             = String(date1.getDate()).padStart(2, '0');
                            var mm1             = String(date1.getMonth() + 1).padStart(2, '0'); //January is 0!
                            var yyyy1           = date1.getFullYear();
                            var ReqDate         = dd1 + '/' + mm1 + '/' + yyyy1;
                        }

                            html +=
                            `
                                <tr>
                                <td><input type="checkbox"  name="ID[]" class="checkItem" value="`+data.data[i].ID+`"></td>
                                <input type="hidden"  name="IDS[]" class="" value="`+data.data[i].ID+`">
                                <td>`+data.data[i].ItemNumber+`</td>
                                <td>`+data.data[i].Material+`</td>
                                <td>`+data.data[i].Description+`</td>
                                <td>`+data.data[i].Quantity+`</td>
                                <td>`+cektgldelivery+`</td>
                                <td>`+data.data[i].ConfirmedQuantity+`</td>
                                <td><input type="text" name="ConfirmedDate[]" class="form-control `+datepickers+`  text-center" `+status+` autocomplete="off" value="`+cektglconfirm+`"></td></td>
                                `;

                                    html+=`<td>`+ReqDate+`</td>`;

                            html +=`
                                </tr>
                            `
                        }
                        html+=`</tbody></table>
                            </div>`;

                        $('#ponego').append(html);

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

                        $(".datepicker").datepicker({
                            format: "dd/mm/yyyy",
                            autoclose: true
                        });
                        $('#ponegotiated').modal('show') ;
                    }
                });
            });

    });
</script>
