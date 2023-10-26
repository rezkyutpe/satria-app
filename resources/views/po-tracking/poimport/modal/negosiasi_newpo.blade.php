{{-- Modal Negosiasi PO --}}
<div class="modal fade" id="ponegotiated" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h6 class="modal-title" id="exampleModalLabel">Negotiation</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
            </div>
            <form method="post" action="{{url('update-poimport')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="modal-body text-center" id="ponego"></div>
                <div class="modal-footer">
                    @if ($actionmenu->c==1)
                    <input type="submit" name="action" value="Update" class="btn btn-warning btn-sm pull-left">
                    @elseif($actionmenu->u==1)
                        <input type="submit" name="action" value="Yes" class="btn btn-primary btn-sm pull-left">
                        <input type="submit" name="action"  value="Cancel" class="btn btn-danger btn-sm pull-left" onclick="return confirm('Apakah anda Yakin Ingin Cancel PO Ini ?');">
                    @else
                        <input type="submit" name="action" value="Update" class="btn btn-warning btn-sm pull-left">
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

        $('#datatable-visibility tbody').on('click', '.negosiasi', function () {
            var id = $(this).attr('data-parent');
            console.log(id);
            $.ajax({
                url : "{{url('viewpoimport')}}?id="+id,
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {
                    var id              = data.subcont.ID;
                    var date            = new Date(data.subcont.DeliveryDate);
                    var dd              = String(date.getDate()).padStart(2, '0');
                    var mm              = String(date.getMonth() + 1).padStart(2, '0'); //January is 0!
                    var yyyy            = date.getFullYear();
                    var DeliveryDate    = dd + '/' + mm + '/' + yyyy;

                    //Tambahan Detail
                    var poNumber        = data.subcont.Number;
                    var ItemNumber      = data.subcont.ItemNumber;
                    var Material        = data.subcont.Material;
                    var qty             = data.subcont.Quantity;
                    var MatDesc         = data.subcont.Description;

                    $('#ponego').empty();
                    var html =
                    `
                        <div class="row m-2">
                            <div class='table-responsive'>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th >PO Number</th>
                                            <th >Item Number</th>
                                            <th >Material</th>
                                            <th >Description</th>
                                            <th >Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>`+poNumber+`</td>
                                            <td>`+ItemNumber+`</td>
                                            <td>`+Material+`</td>
                                            <td>`+MatDesc+`</td>
                                            <td>`+qty+`</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    `;
                    html +=
                        `<div class="row m-2">
                            <div class='table-responsive'>
                            <table class="table">
                                <thead class="thead-light">
                                    <tr>
                                    <th><input type="checkbox" name="ID" class="checkItem" id="allpayment"></th>
                                    <th>Delivery Date</th>
                                    <th>Confirm Quantity</th>
                                    <th>Confirm Arrival Date</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                                for(i=0;i<data.viewtable.length;i++){
                                    if(data.actionmenu.u==1) {
                                    var status = "readonly";
                                    }else{
                                        var datepickers = "datepicker"
                                        var status = "";
                                    }
                                    var tgl            = new Date(data.viewtable[i].ConfirmedDate);
                                    var tanggal              = String(tgl.getDate()).padStart(2, '0');
                                    var bulan              = String(tgl.getMonth() + 1).padStart(2, '0'); //January is 0!
                                    var tahun            = tgl.getFullYear();
                                    var ConfirmedDate    = tanggal + '/' + bulan + '/' + tahun;
                                    html +=
                                    `
                                        <tr>
                                            <td><input type="checkbox"  name="ID[]" class="checkItem" value="`+data.viewtable[i].ID+`"></td>
                                            <input type="hidden"  name="IDS[]" class="checkItem" value="`+data.viewtable[i].ID+`">
                                            <td>`+DeliveryDate+`</td>
                                            <td>`+data.viewtable[i].ConfirmedQuantity+`</td>
                                            <td><input type="text" name="ConfirmedDate[]"  class=" form-control `+datepickers+`  text-center" `+status+` autocomplete="off" value="`+ConfirmedDate+`"></td></td>

                                        </tr>
                                    `
                                }
                    html+=`</tbody></table></div>
                        </div>`;
                    $('#ponego').append(html);
                    $(".datepicker").datepicker({
                format: "dd/mm/yyyy",
                autoclose: true
            });
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
                    $('#ponegotiated').modal('show') ;
                }
            });
        });

    });
</script>
