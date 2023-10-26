<div class="modal fade" id="detailPO" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 70%;">

        <div class="modal-content">
            <div class="modal-header bg-success" id="Juduls">
            </div>
            <div class="table-responsive">
                <div class="modal-body text-center" id="PO"></div>
                    <div class="modal-footer">
                            <button type="button" class="btn btn-warning btn-sm pull-left" data-dismiss="modal">Back</button>
                    </div>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#datatable-responsive tbody').on('click', '.detailvendor', function() {
        var id = $(this).attr('data-id');
                // fetch('http://localhost/editreason?id=2')
                $.ajax({
                url : "{{url('caridetailvendor')}}?id="+id,
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {
                    $('#Juduls').empty();
                    $('#PO').empty();
                    var html =
                             `<div class="row m-4">
                                    <table class="table table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>PO Number</th>
                                                <th>Item Number</th>
                                                <th>Material</th>
                                                <th>Description</th>
                                                <th>Net Price</th>
                                                <th>Quantity</th>
                                                <th>Total Amount</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                `;
                                for(i=0;i<data.data.length;i++){
                                    var subst = data.data[i].NetPrice.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                                    var amount = data.data[i].TotalAmount.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                    html+=`
                                            <tr>
                                                <td >`+data.data[i].Number+`</td>
                                                <td >`+data.data[i].ItemNumber+`</td>
                                                <td >`+data.data[i].Material+`</td>
                                                <td >`+data.data[i].Description+`</td>
                                                <td >`+subst+`</td>
                                                <td >`+data.data[i].TotalQuantity+`</td>
                                                <td >`+amount+`</td>
                                            </tr>
                                            `;
                                    }
                    html+=`
                                        </tbody>
                            </tbody></table>
                        </div>`;

                    $('#PO').append(html);
                    $("#Juduls").append
                    (`
                    <h6 text-center>Details Vendor</h6>
                    `);

                    $('#detailPO').modal('show');
                }
            });

        });

    });


</script>
