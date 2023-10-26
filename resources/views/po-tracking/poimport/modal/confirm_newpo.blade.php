<div class="modal fade" id="newpo" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title" id="exampleModalLabel">Confirm PO</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
            </div>
            <form method="post" action="{{url('insert-poimport')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="modal-body text-center">
                    <div class="row m-2">
                        <div id="detailPO" class="col-md-12"></div>
                        <input type="hidden" name="ID" class="form-control ID" autocomplete="off" value="" readonly>
                        <div class="col-md-12 table-responsive">
                            <table class="table">
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
                                            <input type="text" name="DeliveryDate" class="form-control DeliveryDates text-center" autocomplete="off" readonly></td>
                                        <td>
                                            <select id="dropdowndm" class="form-control">
                                            <option value="full">Full</option>
                                            <option value="partial">Partial</option>
                                        </td>
                                        <td><input type="number" min="1" name="ConfirmedQuantity[]" id="Quantitys" class="form-control text-center qty" autocomplete="off" value="" ></td>
                                        <td><input style="cursor:cell;" type="text" name="ConfirmedDate[]"  class="form-control text-center DeliveryDates inputdate" autocomplete="off" value=""></td>
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
                        <input type="submit" name="action" value="Save" class="btn btn-primary btn-sm pull-left">
                        <input type="submit" name="action"  value="Cancel" class="btn btn-danger btn-sm pull-left" onclick="return confirm('Are you sure you will cancel this PO ?');">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#tomboltambahkurang').hide();
        // Tombol Action partial
        $('#dropdowndm').change(function(){
            var tambah = $('#dropdowndm').val();
            if(tambah == 'partial'){
                $('#tomboltambahkurang').show();
            }
            else{
                $('#tomboltambahkurang').hide();
            }
        });
        // Format date untuk inputan date
        $(".inputdate").datepicker({
            startDate: new Date(),
            daysOfWeekDisabled: [0,6],
            format: "dd/mm/yyyy",
            autoclose: true,
            todayHighlight: true
        });

        $('#datatable-visibility tbody').on('click', '.confirmpo', function ()
        {
            var id = $(this).attr('data-id');
            $.ajax({
                url : "{{url('viewpoimport')}}?id="+id,
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
                    var TQuantity       = data.subcont.Quantity;

                    $('#detailPO').html(`
                        <div class="table-responsive">
                            <table class="table table-bordered">
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
                        </div>
                    `);

                    $('#NoPos').val(data.subcont.Number);
                    $('.ID').val(data.subcont.ID);
                    $('#Quantitys').val(data.subcont.Quantity);
                    $('.DeliveryDates').val(DeliveryDate);
                    $('#newpo').modal('show') ;
                }
            });
        });

        var j = 0;
        $( "#addrow").click(function() {
            j++;
            $('#tbody').append(`
                <tr id="n_row">
                <td colspan="2"></td>
                <td><input type="number" min="1" name="ConfirmedQuantity[]" id="Quantitys" class="form-control text-center qty" autocomplete="off" value="" ></td>
                <td><input type="text" name="ConfirmedDate[]"  class="form-control text-center DeliveryDates inputdate" autocomplete="off" value=""></td>
                <td></td>
                </tr>
            `);
            $('#tbody').find(".inputdate").datepicker({
                startDate: new Date(),
                daysOfWeekDisabled: [0,6],
                format: "dd/mm/yyyy",
                autoclose: true,
                todayHighlight: true
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