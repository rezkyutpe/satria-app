<div class="modal fade" id="detail-po" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: auto;">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h6 text-center>Detail Item</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('detail_po') }}" method="POST">
                @csrf
                    <div class="m-2">
                        <div class="table-responsive">
                            <table class="table nowrap table-striped text-center" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>PO Number</th>
                                        <th>Item No.</th>
                                        <th>Material</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody id="dataItemPO"></tbody>
                            </table>
                            <span id="download_proforma"></span>
                            <span id="download_document"></span>
                            <span id="IdNumber"></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#datatable-visibility tbody').on('click', '.detail-po', function ()
        {
            var id = $(this).attr('data-id');
            
            $.ajax({
                url : "{{route('caridata.detailspo')}}?id="+id,
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {
                    console.log(data.dataid);
                    $('#dataItemPO').empty();
                    $('#download_proforma').empty();
                    $('#download_document').empty();
                    $('#IdNumber').empty();
                    $("#dataItemPO").append
                    (`
                        <tr>
                            <td>`+data.dataid.Number+`</td>
                            <td>`+data.dataid.ItemNumber+`</td>
                            <td>`+data.dataid.Material+`</td>
                            <td>`+data.dataid.Description+`</td>
                        </tr>
                    `);
                    if (data.dataid.ActiveStage == 3 || data.dataid.ActiveStage == 4) {
                        $("#download_proforma").append
                        (`
                            <label for="proforma_invoice">Download File</label>
                            <input type="submit" value="Download Proforma Invoice" name="action" class="btn btn-primary form-control">
                        `);
                    }else if(data.dataid.ActiveStage >= 5){
                        $("#download_document").append
                        (`
                            <label for="proforma_invoice">Download File</label>
                            <input type="submit" value="Download Proforma Invoice" name="action" class="btn btn-primary form-control">
                            <input type="submit" value="Download Shipment Document" name="action" class="btn btn-primary form-control">
                            <input type="submit" value="Download Packing List Document" name="action" class="btn btn-primary form-control">
                        `);
                    }
                    $("#IdNumber").append
                    (`
                        <input type="hidden" name="id" class="form-control" value="`+data.dataid.ID+`">
                    `);
                    $('#detail-po').modal('show');
                }
            });
        });
    } );
</script>