<div class="modal fade" id="ponegotiated" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="exampleModalLabel">Negotiation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="updatesubcontractor" method="post" action="{{url('update-subcontractor')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="modal-body text-center">
                    <div class="container">
                        <div class="row table-responsive">
                            <div class="col">
                                <div id="ponego"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @if ($actionmenu->c==1)
                    <input type="submit" name="action" value="Update" class="btn btn-warning btn-sm pull-left">
                    @elseif($actionmenu->u==1)
                    <input type="submit" name="action" value="Yes" class="btn btn-primary btn-sm pull-left" onclick="return confirm('Accept this partial?');">
                    <input type="submit" name="action"  value="Cancel" class="btn btn-danger btn-sm pull-left" onclick="return confirm('Cancel this partial?');">
                   @else
                    <input type="submit" name="action" value="Update" class="btn btn-warning btn-sm pull-left">
                    <input type="submit" name="action" value="Yes" class="btn btn-primary btn-sm pull-left" onclick="return confirm('Accept this partial?');">
                    <input type="submit" name="action"  value="Cancel" class="btn btn-danger btn-sm pull-left" onclick="return confirm('Cancel this partial?');">
                    @endif
                       {{-- <button type="button" class="btn btn-warning btn-sm pull-left" data-dismiss="modal">Back</button> --}}
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    $(document).ready(function() {

        $( '#updatesubcontractor' ).on('submit', function(e) {
            if($( 'input[class^="checkItem"]:checked' ).length === 0) {
                alert( 'Oops! You not select any data.' );
                e.preventDefault();
            }
        });

        $('.negosiasi').on("click" ,function() {
            var id = $(this).attr('data-parent');
            $.ajax({
                url : "{{url('viewsubcontractor')}}?id="+id,
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {
                    var qty             = data.subcont.Quantity.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
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
                    var MatDesc         = data.subcont.Description;

                    $('#ponego').empty();
                    var html =
                    `
                        <table class="table table-bordered table-md w-100">
                            <thead>
                                <tr>
                                <th scope="col">PO Number</th>
                                <th scope="col">Item Number</th>
                                <th scope="col">Material</th>
                                <th scope="col">Description</th>
                                <th scope="col">Quantity</th>
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
                    `;
                    html += `
                            <table class="table table-md w-100">
                                <thead class="thead-light">
                                    <tr>
                                    <th><input type="checkbox" name="ID" id="allpayment"></th>
                                    <th>Delivery Date</th>
                                    <th>Confirm Quantity</th>
                                    <th>Confirm Arrival Date</th>
                                    `+
                                    // <th></th>
                                    `
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
                        var tgl              = new Date(data.viewtable[i].ConfirmedDate);
                        var tanggal          = String(tgl.getDate()).padStart(2, '0');
                        var bulan            = String(tgl.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var tahun            = tgl.getFullYear();
                        var ConfirmedDate    = tanggal + '/' + bulan + '/' + tahun;
                        html +=
                        `
                            <tr>
                                <td><input type="checkbox"  name="ID[]" class="checkItem" value="`+data.viewtable[i].ID+`"></td>
                                <input type="hidden"  name="IDS[]" class="" value="`+data.viewtable[i].ID+`">
                                <td>`+DeliveryDate+`</td>
                                <td>`+data.viewtable[i].ConfirmedQuantity+`</td>
                                <td><input type="text" name="ConfirmedDate[]" class="form-control `+datepickers+`  text-center" `+status+` autocomplete="off" value="`+ConfirmedDate+`"></td></td>

                            </tr>
                            <tr class="collapse" id="collapseExample">
                                <td></td>
                                <td colspan="4">
                                        Table Here
                                </td>
                            </tr>
                        `
                    }
                    html+=
                    `
                        </tbody></table>
                    `;
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
                    });
                    $('#ponegotiated').modal('show');
                }
            });
        });

    });
</script>
