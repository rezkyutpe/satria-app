<div class="modal fade" id="newpo" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="exampleModalLabel">Confirm PO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span style="color:white;" aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{url('insert-subcontractor')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="modal-body text-center">
                    <div class="row table-responsive">
                        <div class="col col-xs-12">
                            <div id="detailPO"></div>
                        </div>
                    </div>
                    <div class="row table-responsive">
                        <input type="hidden" name="ID" class="form-control ID" autocomplete="off" value="" readonly>
                        <table class="table table-md table-responsive">
                            <thead class="thead-light">
                                <tr>
                                    <th>Delivery Date</th>
                                    <th>Delivery Method</th>
                                    <th width="15%">Quantity</th>
                                    <th>Confirm Arrival Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                <tr>
                                    <td id = "DeliveryDate" style="cursor:default;">
                                        <input style="border:0;" type="text" name="DeliveryDate" class="form-control DeliveryDates text-center" autocomplete="off" readonly>
                                    </td>
                                    <td>
                                        <select id="dropdowndm" class="form-control">
                                            <option value="full">Full</option>
                                            <option value="partial">Partial</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" min="1" name="ConfirmedQuantity[]" id="Quantitys" class="form-control text-center qty" autocomplete="off" value="" >
                                    </td>
                                    <td>
                                        <input style="cursor:cell;" type="text" name="ConfirmedDate[]"  class="form-control text-center DeliveryDates datepicker" autocomplete="off" value="">
                                    </td>
                                    <td id="tomboltambahkurang" class="text-left">
                                        <a href="#" class="btn-icon" id="addrow" style="visibility:display;"><i class="fa fa-plus-circle"></i>add</a><br>
                                        <a href="#" class="btn-icon " id="delrow" style="visibility:display;"><i class="fa fa-minus-circle"></i>remove</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" name="action" value="Save PO" class="btn btn-primary btn-sm" onclick="return confirm('Is the form filled out correctly?');">
                    <input type="submit" name="action"  value="Cancel PO" class="btn btn-danger btn-sm" onclick="return confirm('Cancel this PO?');">
                    {{-- <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal"></i>Back</button> --}}
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        
        $('.confirmpo').on("click" ,function() {
            var id = $(this).attr('data-id');
            $.ajax({
                url : "{{url('viewsubcontractor')}}?id="+id,
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {
                    var date        = new Date(data.subcont.DeliveryDate);
                    var dd          = String(date.getDate()).padStart(2, '0');
                    var mm          = String(date.getMonth() + 1).padStart(2, '0'); //January is 0!
                    var yyyy        = date.getFullYear();
                    var DeliveryDate= dd + '/' + mm + '/' + yyyy;
                    //Tambahan Detail
                    var poNumber        = data.subcont.Number;
                    var ItemNumber      = data.subcont.ItemNumber;
                    var Material        = data.subcont.Material;
                    var MatDesc         = data.subcont.Description;
                    var TQuantity       = data.subcont.Quantity.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");;

                    $('#detailPO').html(`
                        <table class="table table-bordered table-md">
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
                                    <td>`+poNumber+`</td>
                                    <td>`+ItemNumber+`</td>
                                    <td>`+Material+`</td>
                                    <td>`+MatDesc+`</td>
                                    <td>`+TQuantity+`</td>
                                </tr>
                            </tbody>
                        </table>
                    `);

                    $('#NoPos').val(data.subcont.Number);
                    $('.ID').val(data.subcont.ID);
                    $('#Quantitys').val(data.subcont.Quantity);
                    $('.DeliveryDates').val(DeliveryDate);
                    $('#newpo').modal('show') ;
                }
            });
        });

        //Inisialisasi Datepicker
        $(".datepicker").datepicker({
                startDate: new Date(),
                daysOfWeekDisabled: [0,6],
                format: "dd/mm/yyyy",
                autoclose: true,
                todayHighlight: true,
        });

        // Show/Hide Tombol Add/Remove
        $('#tomboltambahkurang').hide();
        $('#dropdowndm').change(function(){
            var tambah = $('#dropdowndm').val();
            if(tambah == 'partial'){
                $('#tomboltambahkurang').show();
            }
            else{
                $('#tomboltambahkurang').hide();
            }
        });

        var j = 0;
        $( "#addrow").click(function() {
            j++;
            $('#tbody').append(`
                <tr id="n_row">
                <td colspan="2"></td>
                <td><input type="number" min="1" name="ConfirmedQuantity[]" id="Quantitys" class="form-control text-center qty" autocomplete="off" value="" ></td>
                <td><input style="cursor:cell;" type="text" name="ConfirmedDate[]"  class="form-control text-center DeliveryDates inputdate" autocomplete="off" value=""></td>
                <td></td>
                </tr>
            `);
            $('#tbody').find(".inputdate").datepicker({
                startDate: new Date(),
                daysOfWeekDisabled: [0,6],
                format: "dd/mm/yyyy",
                autoclose: true,
                todayHighlight: true,
            });
            $('#n_row').val(j);
        });

        $( "#delrow" ).click(function() {
            if(j !=0 ){
                $("#tbody").children().eq(j).remove();
                j--;
                $('#n_row').val(j);
            }
        });
    });
</script>