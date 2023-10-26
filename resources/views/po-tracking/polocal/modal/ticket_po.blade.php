<div class="modal fade" id="Ticketing" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: auto;">
        <div class="modal-content">
            <div class="modal-header bg-info" id="Juduls">
            </div>
            <form method="post" id="formTicketing" action="{{ url('proses-polocalongoing') }}"
                enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="table-responsive">
                    <div class="modal-body text-center" id="tickets"></div>
                    <div class="modal-footer">
                        <input type="submit" id="submitTicket" class="btn btn-primary btn-sm pull-left">
                        <button type="button" class="btn btn-warning btn-sm pull-left"
                            data-dismiss="modal">Back</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function myFunction(val, n) {
        if (val > n) {
            alert("Qty Ticket Kelebihan BosQ")

        }
    }
    $(document).ready(function() {

        $('#datatable-responsive tbody').on('click', '.Ticketing', function() {

            var id = $(this).attr('data-id');

            $.ajax({
                url: "{{ url('view_cekticketlocal') }}?id=" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    var qty = data.dataid.Quantity;
                    var id = data.dataid.ID;
                    if (data.dataid.ConfirmedDate) {
                        var datess = new Date(data.dataid.ConfirmedDate);
                    } else {
                        var datess = new Date(data.dataid.DeliveryDate);
                    }

                    var datenow = new Date();
                    var dd = String(datess.getDate()).padStart(2, '0');
                    var mm = String(datess.getMonth() + 1).padStart(2, '0'); //January is 0!
                    var yyyy = datess.getFullYear();
                    var dddd = String(datenow.getDate()).padStart(2, '0');
                    var ddd = String(datenow.getDate()).padStart(2, '0');
                    var mmm = String(datenow.getMonth() + 1).padStart(2,
                        '0'); //January is 0!
                    var yyyyy = datenow.getFullYear();
                    var DeliveryDate = dd + '/' + mm + '/' + yyyy;
                    var DateNows = ddd + '/' + mmm + '/' + yyyyy;


                    //Tambahan Detail
                    var poNumber = data.dataid.Number;
                    var ItemNumber = data.dataid.ItemNumber;
                    var Material = data.dataid.Material;
                    var MatDesc = data.dataid.Description;

                    if (data.dataid.ConfirmTicketDate) {
                        var c = new Date(data.dataid.ConfirmTicketDate);
                    } else if (data.dataid.ConfirmedDate) {
                        var c = new Date(data.dataid.ConfirmedDate);
                    } else {
                        var c = new Date();
                        c.setDate(c.getDate() + 1);
                    }

                    if (c > datenow) {
                        var d = c;
                    } else {
                        var d = new Date();
                        d.setDate(d.getDate() + 1);
                    }

                    if (data.dataid.ActiveStage == null) {
                        var ActiveStage = 4;
                    } else {
                        var ActiveStage = data.dataid.ActiveStage;
                    }
                    var date = d.getDate();
                    var month = d.getMonth() + 1;
                    var year = d.getFullYear();

                    var datedelivery = date + '/' + month + '/' + year;

                    if (d.getTime() < datenow.getTime()) {
                        var dates = ddd + '/' + mmm + '/' + yyyyy;
                    } else {
                        var dates = date + '/' + month + '/' + year;
                    }
                    $('#Juduls').empty();
                    $('#tickets').empty();
                    var html =
                        `
                        <div class="row m-4">
                                <table class="table table-md table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">PO Number</th>
                                            <th scope="col">` + poNumber + `</th>
                                            <th scope="col">Delivery Date</th>
                                            <input type="hidden" name="deliverydatesap" value="` + DeliveryDate +
                        `">
                                            <th scope="col"><input type="text" style="text-align: center;cursor:pointer" class="form-control datepicker readonly"  name="deliverydate" placeholder="dd/mm/yyyy" autocomplete="off" id="deliveryd" required></th>
                                            <th scope="col">Delivery Time</th>
                                            <th scope="col"><input type="time" style="text-align: center;" class="form-control"  name="deliverytime"  required></th>
                                        </tr>

                                    </thead>
                                    <tbody class="thead-light">
                                        <tr>
                                            <th>NO Surat Jalan</th>
                                            <th><input type="text" class="form-control"  name="DeliveryNote" autocomplete="off" required></th>
                                            <th>Header Text</th>
                                            <th><input type="text"  class="form-control"  name="headertext" autocomplete="off" required></th>
                                            <th style="vertical-align:middle;">SPB Date</th>
                                            <th style="vertical-align:middle;"><input type="text" style="text-align: center;cursor:pointer" class="form-control readonly" autocomplete="off" name="SPBdate" placeholder="dd/mm/yyyy" id="spbd" required></th>
                                        <tr>
                                    </tbody>
                                </table>
                            </div>`;
                    html +=
                        `<div class="row m-4">
                                <table class="table table-md">
                                    <thead class="thead-light">
                                        <tr>
                                        <th><input type="checkbox" class="checkItem" id="allpayment"></th>
                                        <th>ItemNumber</th>
                                        <th>Material</th>
                                        <th>Description</th>
                                        <th>Qty Po</th>
                                        <input type="hidden" name="ActiveStage" class="form-control" value="` +
                        ActiveStage + `" >
                                        <th>Qty Delivery</th>
                                        <th></>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                    for (i = 0; i < data.dataticket.length; i++) {


                        var q = "";
                        var s = "";
                        if (data.dataticket[i].ActualQuantity == null) {
                            var qty = data.dataticket[i].Quantity;
                            var sisa = data.dataticket[i].OpenQuantity - data.dataticket[i]
                                .totalticket;
                        } else {
                            if (data.dataticket[i].totalticket == null) {
                                var qty = data.dataticket[i].ActualQuantity;
                                var sisa = data.dataticket[i].OpenQuantity - data
                                    .dataticket[i].totalticket;
                            } else {
                                var qty = data.dataticket[i].ActualQuantity;
                                var sisa = data.dataticket[i].ActualQuantity - data
                                    .dataticket[i].totalticket;
                            }

                        }
                        if (data.dataticket[i].totalgr == data.dataticket[i].Quantity) {
                            var sisa = 0;
                        }
                        if (sisa == 0) {
                            var s = "readonly";
                            var d = "Ticket FULL";
                            var q = "disabled";
                        } else {
                            var d = sisa;
                        }
                        if (data.dataticket[i].ParentID == null) {
                            var ids = data.dataticket[i].ID;
                        } else {
                            var ids = data.dataticket[i].ParentID;
                        }

                        html +=
                            `<tr>`;
                        if (q == "") {
                            html += `<td><input id="row_payment` + i +
                                `" type="checkbox" class="ticket-rownya" ` + q +
                                ` name="ID[]" class="checkItem" value="` + ids + `"></td>`;
                        } else {
                            html += `<td></td>`;
                        }
                        html += `
                                <input type="hidden" name="number[]" disabled value="` + data.dataticket[i].Number + `">
                                <input type="hidden" name="numbers" disabled value="` + data.dataticket[i].Number + `">
                                <input type="hidden" name="itemnumber[]" disabled value="` + data.dataticket[i]
                            .ItemNumber + `">
                                <input type="hidden" name="material[]" disabled value="` + data.dataticket[i]
                            .Material + `">
                                <input type="hidden" name="description[]" disabled value="` + data.dataticket[i]
                            .Description + `">
                                <input type="hidden" name="plant[]" disabled value="` + data.dataticket[i].Plant + `">
                                <input type="hidden" name="sloc[]" disabled value="` + data.dataticket[i].SLoc + `">
                                <td >` + data.dataticket[i].ItemNumber + `</td>
                                <td >` + data.dataticket[i].Material + `</td>
                                <td >` + data.dataticket[i].Description + `</td>
                                <td>` + qty + `</td>
                                <input type="hidden" disabled name="qtysap[]" value="` + qty + `">
                                <td ><input type="text" style="text-align: center;" disabled name="QtyDelivery[` +
                            ids + `][]" ` + s + `  class="form-control" value="` + d +
                            `"  onchange="myFunction(this.value,` + d +
                            `)"></td>
                                <td><a href="javascript:void(0);" data-toggle="collapse" data-target="#collapseExample` +
                            i + `"><span class="glyphicon glyphicon-eye-open"></span></a></td>
                                </tr>
                                <tr class="collapse" id="collapseExample` + i + `" >
                                <td colspan="12">
                                    <div class="collapse" id="collapseExample` + i + `">
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="pb` + i +
                            `" role="tabpanel" aria-labelledby="pb-tab` + i + `">
                                                <div class="row m-4">
                                                    <table class="table table-md">
                                                        <thead class="thead-light">
                                                        <tr>
                                                        <th>Qty </th>
                                                        <th>DeliveryDate</th>
                                                        </tr>
                                                        </thead>`

                        for (n = 0; n < data.dataall.length; n++) {

                            if (data.dataticket[i].ParentID == data.dataall[n].ParentID) {

                                if (data.dataid.ConfirmedDate == null) {
                                    var datesss = new Date(data.dataall[n].DeliveryDate);
                                    var qty = data.dataall[n].Quantity;
                                } else {
                                    var datesss = new Date(data.dataall[n].ConfirmedDate);
                                    var qty = data.dataall[n].ConfirmedQuantity;
                                }
                                var ddd = String(datesss.getDate()).padStart(2, '0');
                                var mmm = String(datesss.getMonth() + 1).padStart(2,
                                    '0'); //January is 0!
                                var yyyyy = datesss.getFullYear();

                                var DeliveryDatess = ddd + '/' + mmm + '/' + yyyyy;
                                html += `
                                     <tbody class="thead-light">
                                        <tr>
                                            <td>` + qty + `</td>
                                            <td>` + DeliveryDatess + `</td>
                                        </tr>
                                    </tbody>
                            `;
                            }
                        }
                        html += ` </table>
                                    </div>
                                    </div>
                                    </div>
                                    </td>
                                    </tr>
                                  `;
                    }
                    html += `
                                </tbody>
                                </table>
                            </div>`;
                    $('#tickets').append(html);

                    //disable date
                    var day_disable = [];
                    for (x = 0; x < data.days_disabled.length; x++) {
                        const today = new Date(data.days_disabled[x].event_date);
                        const yyyy = today.getFullYear();
                        let mm = today.getMonth() + 1; // Months start at 0!
                        let dd = today.getDate();

                        if (dd < 10) dd = '0' + dd;
                        if (mm < 10) mm = '0' + mm;

                        const formattedday = dd + '/' + mm + '/' + yyyy;
                        day_disable.push(formattedday);
                    }

                    $(".datepicker").datepicker({
                        format: "dd/mm/yyyy",
                        autoclose: true,
                        minDate: '+2d',
                        startDate: '+2d',
                        daysOfWeekDisabled: [0, 6],
                        datesDisabled: day_disable,
                    });
                    $("#Juduls").append(`
                        <h6 text-center>Create Ticketing</h6>
                        `);
                    $('#allpayment').on('click', function() {
                        if ($(this).is(':checked')) {
                            for (i = 0; i < data.dataticket.length; i++) {
                                $('#row_payment' + i).prop('checked', $(this).prop(
                                    'checked'));
                                if ($('#row_payment' + i).is(':checked')) {
                                    $('#row_payment' + i).closest('tr')
                                        .find('input[type=text]')
                                        .prop('disabled', false);
                                    $('#row_payment' + i).closest('tr')
                                        .find('input[type=hidden]')
                                        .prop('disabled', false);
                                } else {
                                    $('#row_payment' + i).closest('tr')
                                        .find('input[type=text]')
                                        .prop('disabled', true);
                                    $('#row_payment' + i).closest('tr')
                                        .find('input[type=hidden]')
                                        .prop('disabled', true);
                                }

                            }
                        } else {
                            for (i = 0; i < data.dataticket.length; i++) {
                                $('#row_payment' + i).prop('checked', $(this).prop(
                                    'checked'));
                                if ($('#row_payment' + i).is(':checked')) {
                                    $('#row_payment' + i).closest('tr')
                                        .find('input[type=text]')
                                        .prop('disabled', false);
                                    $('#row_payment' + i).closest('tr')
                                        .find('input[type=hidden]')
                                        .prop('disabled', false);
                                } else {
                                    $('#row_payment' + i).closest('tr')
                                        .find('input[type=text]')
                                        .prop('disabled', true);
                                    $('#row_payment' + i).closest('tr')
                                        .find('input[type=hidden]')
                                        .prop('disabled', true);
                                }
                            }
                        }
                    })

                    for (i = 0; i < data.dataticket.length; i++) {
                        $('#row_payment' + i).on('change', function() {
                            if ($(this).is(':checked')) {
                                $(this).closest('tr')
                                    .find('input[type=text]')
                                    .prop('disabled', false);
                                $(this).closest('tr')
                                    .find('input[type=hidden]')
                                    .prop('disabled', false);
                            } else {
                                $(this).closest('tr')
                                    .find('input[type=text]')
                                    .prop('disabled', true);
                                $(this).closest('tr')
                                    .find('input[type=hidden]')
                                    .prop('disabled', true);
                            }
                        });
                    }
                    $(".readonly").on('keydown paste', function(e) {
                        if (e.keyCode != 9) // ignore tab
                            e.preventDefault();
                    });
                    $("#deliveryd").on('change', function(e){
                            var tanggal = $("#deliveryd").val();
                            $("#spbd").val(tanggal);
                        });
                    $('#Ticketing').modal('show');
                }
            });

        });


        $('#Ticketing').on('hidden.bs.modal', function() {
            $('#submitTicket').prop('disabled', false);
        });

        $('#formTicketing').on('submit', function(e) {
            $('#submitTicket').prop('disabled', true);
        });
    });
</script>
