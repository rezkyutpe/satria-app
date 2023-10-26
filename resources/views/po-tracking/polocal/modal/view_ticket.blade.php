<div class="modal fade" id="viewticket" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info" id="Juduls"></div>
            <div class="table-responsive">
                <div class="modal-body text-center" id="tickets"></div>
                    <div class="modal-footer">
                            <button type="button" class="btn btn-warning btn-sm pull-left" data-dismiss="modal">Back</button>
                    </div>
            </div>
        </div>
    </div>
</div>
<script>
  function getChat(val){
        $('#datatable-visibility tbody').on('click', '.view_ticket', function ()
        {

            var id = $(this).attr('data-id');
                $.ajax({
                    url : "{{url('view_ticket')}}?id="+id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data)
                    {
                        $('#Juduls').empty();
                        $('#tickets').empty();

                        var tgl             = new Date(data.dataviewticket.SPBDate);
                        var tanggal         = String(tgl.getDate()).padStart(2, '0');
                        var bulan           = String(tgl.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var tahun           = tgl.getFullYear();
                        var SPBDate         = tanggal + '/' + bulan + '/' + tahun;

                        var html =
                        `
                        <div class="row m-4">
                            <table class="table table-bordered table-md ">
                                    <thead >
                                        <tr>
                                        <th class="col-2">PO Number :</th>
                                        <th class="col-2">`+data.dataviewticket.Number+`</th>
                                        <th class="col-2">Vendor :</th>
                                        <th class="col-2">`+data.dataviewticket.Name+`</th>
                                        <th class="col-2">PO Creator :</th>
                                        <th class="col-2">`+data.dataviewticket.NRP+`</th>
                                        </tr>
                                        <tr>
                                            <th class="col-2">SPB Date :</th>
                                            <th class="col-2">`+SPBDate+`</th>
                                        </tr>
                                    </thead>


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
                        for(i=0;i<data.data.length;i++){
                            html +=
                            `
                                <tr>
                                    <td >`+data.data[i].ItemNumber+`</td>
                                    <td >`+data.data[i].Material+`</td>
                                    <td >`+data.data[i].Description+`</td>
                                    <td>`+data.data[i].Quantity+`</td>

                                </tr>
                            `
                        }
                        html+=`</tbody></table>
                            </div>`;
                        $('#tickets').append(html);
                        $("#Juduls").append
                        (`
                        <h6 text-center>Ticketing</h6>
                        `);

                        $('#viewticket').modal('show');
                    }
                });

        });
    });


</script>
