<div class="modal fade" id="detailPR" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: auto;">

        <div class="modal-content">
            <div class="modal-header bg-success" id="Judul">
            </div>
            <div class="table-responsive">
                <div class="modal-body text-center" id="PR"></div>
                    <div class="modal-footer">
                            <button type="button" class="btn btn-warning btn-sm pull-left" data-dismiss="modal">Back</button>
                    </div>
            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#datatable-responsive tbody').on('click', '.detailspr', function() {
        var id = $(this).attr('data-number');

        // fetch('http://localhost/editreason?id=2')
        $.ajax({
        url : "{{url('caridetailpr')}}?id="+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('#Judul').empty();
            $('#PR').empty();
            var html =
            `
            <div class="row m-4">
                    <table class="table table-bordered" >
                        <thead >
                            <tr>
                                <th>PR Number</th>
                                <th>Vendor</th>
                                <th>Vendor Code</th>
                                <th>PO Creator</th
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>`+data.data.PRNumber+`</td>
                                <td>`+data.data.Vendor+`</td>
                                <td>`+data.data.VendorCode+`</td>
                                <td>`+data.data.NRP+`</td>
                            </tr>
                        </tbody>
                    </table>
                </div>`;

                    html +=
                        `<div class="row m-4">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>PO Number</th>
                                        <th>Item Number</th>
                                        <th>Material</th>
                                        <th>Description</th>
                                        <th>Qty Delivery</th>
                                    </tr>
                                </thead>
                                <tbody>
                        `;
                        for(i=0;i<data.dataall.length;i++){
               html+=`
                                    <tr>
                                        <td >`+data.dataall[i].Number+`</td>
                                        <td >`+data.dataall[i].ItemNumber+`</td>
                                        <td >`+data.dataall[i].Material+`</td>
                                        <td >`+data.dataall[i].Description+`</td>
                                        <td >`+data.dataall[i].Quantity+`</td>
                                    </tr>
                                    `;
                            }
               html+=`
                                </tbody>
                    </tbody></table>
                </div>`;

            $('#PR').append(html);
            $("#Judul").append
            (`
            <h6 text-center>Details PR  </h6>
            `);

            $('#detailPR').modal('show');
        }
    });

});
    });

</script>
