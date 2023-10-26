<div class="modal fade" id="newpo" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title" id="exampleModalLabel">Confirm PO</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
            </div>
            <form method="post" action="{{url('insert-subcontractor')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="modal-body text-center">
                    <div class="row m-2">
                        <div id="detailPO" class="col-md-12"></div>
                        <div class="col-md-12 table-responsive">
                                <div id="itemPO"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" name="action" value="Save" class="btn btn-primary btn-sm pull-left" onclick ="return checkboxbtn()">
                        <input type="submit" name="action"  value="Cancel" class="btn btn-danger btn-sm pull-left" onclick="return confirm('Are you sure you will cancel this PO ?');">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Format date untuk inputan date

        $("#newpo").on('hidden.bs.modal', function () {
            $('#itemPO').empty();
            $('.tr_partial').remove();
            var rowIdx = '';
        });

        $('#datatable-responsive tbody').on('click', '.confirmpo', function ()
        {
            var number = $(this).attr('data-number');
            $.ajax({
            url : "{{url('view_confirmpo')}}?number="+number,
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {
                    /* Detail PO*/
                    var vendorName        = data.datapo.Vendor;
                    var poNumber        = data.datapo.Number;

                    $('#detailPO').html(`
                        <table class="table table-bordered table-md">
                            <thead>
                                <tr>
                                    <th>Vendor Name</th>
                                    <th>PO Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>`+vendorName+`</td>
                                    <td>`+poNumber+`</td>
                                </tr>
                            </tbody>
                        </table>
                    `);
                    /*END of Detail PO*/

                    /* Table Item Number */
                    var html = `
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th class="align-middle">
                                        <label for="checkAll" onclick="allinputrequired();">
                                            <input type="checkbox" id="checkAll" class="checkItem">
                                        </label>
                                    </th>
                                    <th class="align-middle">Item Number</th>
                                    <th class="align-middle">Material</th>
                                    <th class="align-middle">Description</th>
                                    <th class="align-middle">Delivery Date</th>
                                    <th class="align-middle">Quantity</th>
                                    <th class="align-middle">Delivery Method</th>
                                    <th class="align-middle">Confirm Quantity</th>
                                    <th class="align-middle">Confirm Arrival Date</th>
                                    <th class="align-middle">Action</th>
                                </tr>
                            </thead>
                    `;
                    for(i=0;i<data.data.length;i++){

                        var date            = new Date(data.data[i].DeliveryDate);
                        var DeliveryDate    = date.toLocaleDateString('id-ID');
                        var date_now        = new Date();
                        var date_fix        = date_now.toLocaleDateString('id-ID');

                        if (date < date_now){
                            var valDate = '';
                        }
                        else{
                            var valDate = DeliveryDate;
                        }

                        html+=`
                            <tbody id="tbody`+i+`">
                            <tr>
                                <td>
                                    <label for="checkbox`+i+`" onclick="inputrequired('`+i+`');">
                                        <input type="checkbox" id="checkbox`+i+`" class="checkItem" name="ID[]" value="`+data.data[i].ID+`">
                                    </label>
                                    <input type="hidden" class="checkdisable`+i+` allcheck" name="IDpartial`+i+`[]" value="`+data.data[i].ID+`" disabled>
                                </td>
                                <td>`+data.data[i].ItemNumber+`</td>
                                <td>`+data.data[i].Material+`</td>
                                <td>`+data.data[i].Description+`</td>
                                <td>`+DeliveryDate+`</td>
                                <td>`+data.data[i].Quantity+`</td>
                                <td>
                                    <select id="dropdowndm`+i+`" class="form-control checkdisable`+i+` allcheck" onchange="actionbuttonselect('dropdowndm`+i+`','tomboltambahkurang`+i+`','`+i+`');" disabled>
                                        <option value="full">Full</option>
                                        `;
                                        if(data.data[i].Quantity > 1){
                                            html+=`
                                                <option value="partial">Partial</option>
                                            `;
                                        }
                                        html+=`
                                    </select>
                                </td>
                                <td>
                                    <input type="number" min="1" name="ConfirmedQuantity`+i+`[]" class="form-control text-center checkdisable`+i+` allcheck" autocomplete="off" value="`+data.data[i].Quantity+`" disabled>
                                </td>
                                <td><input style="cursor:pointer;" type="text" name="ConfirmedDate`+i+`[]"  class="form-control text-center inputdate checkdisable`+i+` allcheck" autocomplete="off" value="`+valDate+`" disabled></td>
                                <td id="tomboltambahkurang`+i+`" class="text-center">
                                    <button id="addrow`+i+`" class="btn-sm btn-primary" type="button" onclick="clickbuttonaddrow('tbody`+i+`','`+i+`','`+data.data[i].ID+`');"><i class="fa fa-plus"></i></button>
                                </td>
                            </tr>
                            </tbody>
                        `;
                    }
                    html+=`
                        </table>
                    `;

                    $('#itemPO').append(html);

                    //hide addrow partial
                    for(i=0;i<data.data.length;i++){
                        $('#tomboltambahkurang'+i).hide();
                    }
                    //show modal
                    $('#newpo').modal('show');

                    //Checkbox All function
                    $('#checkAll').click(function(){
                        if ($(this).is(':checked') ==  true){
                            $('.checkItem').prop('checked', true);
                        }
                        else{
                            $('.checkItem').prop('checked', false);
                        }
                    });

                    //Datepicker
                    $(".inputdate").datepicker({
                        // daysOfWeekDisabled: [0,6],
                        format: "dd/mm/yyyy",
                        minDate: '+1d',
                        startDate: '+1d',
                        setDate: new Date(),
                        autoclose: true,
                        todayHighlight: true
                    });
                }
            });
        });

    });
</script>
<script>
    //FULL OR PARTIAL button
    function actionbuttonselect(selectid,tambahkurang,trcount){
        var tambah = $('#'+selectid).val();
        if(tambah == 'partial'){
            $('#'+tambahkurang).show();
        }
        else{
            $('.tr_partial'+trcount).remove();
            $('#'+tambahkurang).hide();
        }
    }

    //ADD ROW PARTIAL
    function clickbuttonaddrow(tbody,trcount,parentid){
        $('#'+tbody).append(`
            <tr id="id_partial`+trcount+`" class="tr_partial`+trcount+`">
                <td><input type="hidden" class="checkdisable`+trcount+` allcheck" name="IDpartial`+trcount+`[]" value="`+parentid+`" disabled></td>
                <td colspan="6"></td>
                <td class="text-center">
                    <input type="number" min="1" name="ConfirmedQuantity`+trcount+`[]" id="Quantitys" class="form-control text-center checkdisable`+trcount+` allcheck" autocomplete="off" value="" disabled>
                </td>
                <td class="text-center">
                    <input style="cursor:pointer;" type="text" name="ConfirmedDate`+trcount+`[]"  class="form-control text-center inputdate checkdisable`+trcount+` allcheck" autocomplete="off" value="" disabled>
                </td>
                <td class="text-center">
                    <button class="btn-sm btn-danger remove" type="button" onclick="removebuttonpartial('id_partial`+trcount+`')"><i class="fa fa-minus"></i></button>
                </td>
            </tr>`
        );
        //Datepicker
        $(".inputdate").datepicker({
            // daysOfWeekDisabled: [0,6],
            format: "dd/mm/yyyy",
            minDate: '+1d',
            startDate: '+1d',
            autoclose: true,
            todayHighlight: true
        });

        if ($("#checkbox"+trcount).is(':checked')){
            $('.checkdisable'+trcount).prop('required',true);
            $('.checkdisable'+trcount).prop('disabled',false);
        }
        else{
            $('.checkdisable'+trcount).prop('required',false);
            $('.checkdisable'+trcount).prop('disabled',true);
        }
    }

    //DELETE ROW PARTIAL
    function removebuttonpartial(param1){
        $('#'+param1).remove();
    }

    //Validate Checkbox
    function checkboxbtn(){
        var checked = $("input[type=checkbox]:checked").length;

        if(!checked) {
            alert("You must check at least one item.");
            return false;
        }
    }

    //add Properties of input type checkbox when checkbox is checked
    function inputrequired(trcount){
        if ($("#checkbox"+trcount).is(':checked')){
            $('.checkdisable'+trcount).prop('required',true);
            $('.checkdisable'+trcount).prop('disabled',false);
        }
        else{
            $('.checkdisable'+trcount).prop('required',false);
            $('.checkdisable'+trcount).prop('disabled',true);
        }
    }

    function allinputrequired(){
        if($("input[type=checkbox]").is(':checked')){
            $('.allcheck').prop('required',true);
            $('.allcheck').prop('disabled',false);
        }
        else{
            $('.allcheck').prop('required',false);
            $('.allcheck').prop('disabled',true);
        }
    }

    /* END of FUNCTION */
</script>
