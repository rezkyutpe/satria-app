<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="modal fade" id="viewticketkanban" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 900px;">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h6 text-center>TICKET KANBAN</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" name="ticket" id="ticketkanban">
            <input type="hidden" name="subjek" id="subjekticket">
            <div class="table-responsive">
                <div class="modal-body text-center" id="ticketkanbans"></div>
                <div class="modal-footer">
                    <input name="savekanban" type="submit" onclick="SaveKanban()" value="Approve"
                        class="btn btn-primary btn-sm pull-left">
                    <input name="rejectkanban" type="submit" onclick="SaveReject()" value="Reject"
                        class="btn btn-danger btn-sm pull-left">

                </div>
            </div>

        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
<script>
    function getKanban(val) {

        $.ajax({
            url: "{{ url('cekkanban') }}?id=" + val,
            type: "GET",
            dataType: "JSON",
            success: function(data) {

                $('#ticketkanbans').empty();

                var tgl = new Date(data.dataviewticket.DeliveryDate);
                var tanggal = String(tgl.getDate()).padStart(2, '0');
                var bulan = String(tgl.getMonth() + 1).padStart(2, '0'); //January is 0!
                var tahun = tgl.getFullYear();
                var DeliveryDate = tanggal + '/' + bulan + '/' + tahun;

                var html =
                    `
                          <div class="row m-4">
                            <table class="table table-striped table-bordered text-center">
                                <tr>

                                    <th>PO Number</th>
                                    <th>Delivery Date</th>
                                    <th>NO Ticket</th>
                                </tr>
                                <tr>

                                    <td>` + data.dataviewticket.Number + `</td>
                                    <td>` + DeliveryDate + `</td>
                                    <td>` + data.dataviewticket.TicketID + `</td>
                                </tr>
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
                                              <th>Qty Delivery</th>
                                          </tr>
                                      </thead>
                                      <tbody>`;
                for (i = 0; i < data.data.length; i++) {
                    html +=
                        `
                                  <tr>
                                      <td >` + data.data[i].ItemNumber + `</td>
                                      <td >` + data.data[i].Material + `</td>
                                      <td >` + data.data[i].Description + `</td>
                                      <td>` + data.data[i].Quantity + `</td>

                                  </tr>
                              `
                }
                html += `</tbody></table>
                              </div>`;
                html += ``;
                $('#ticketkanbans').append(html);
                $('#ticketkanban').val(data
                    .dataviewticket.TicketID);
                $('#subjekticket').val(data.notif.Subjek);
                $(
                    '#viewticketkanban').modal('show');
            }
        });
    }

    function SaveKanban() {
        var ticket = $("input[name=ticket]").val();
        var subjek = $("input[name=subjek]").val();
        var action = $("input[name=savekanban]").val();
        let _token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: "{{ url('confirm-kanban') }}",
            type: "POST",
            data: {
                Ticket: ticket,
                Action: action,
                _token: _token
            },

            success: function(data) {

                Swal.fire({
                    title: 'Approve!',
                    text: 'Success',
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok!",
                    timer: 2000,
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
                if (subjek == 'Ticket Local') {
                    window.location.href = "{{ url('polocalreadydelivery-vendor') }}";
                } else {
                    window.location.href = "{{ url('subcontractorreadydelivery-vendor') }}";
                }


            },


        });
        $('#viewticketkanban').modal('hide');

    }

    function SaveReject() {
        var ticket = $("input[name=ticket]").val();
        var subjek = $("input[name=subjek]").val();
        var action = $("input[name=rejectkanban]").val();
        let _token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: "{{ url('confirm-kanban') }}",
            type: "POST",
            data: {
                Ticket: ticket,
                Action: action,
                _token: _token
            },

            success: function(data) {

                Swal.fire({
                    title: 'Reject!',
                    text: 'Success',
                    icon: "warning",
                    buttonsStyling: false,
                    confirmButtonText: "Ok!",
                    timer: 2000,
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
                if (subjek == 'Ticket Local') {
                    window.location.href = "{{ url('historyticketlocal-vendor') }}";
                } else {
                    window.location.href = "{{ url('historyticketsubcont-vendor') }}";
                }


            },


        });
        $('#viewticketkanban').modal('hide');



    }
</script>
