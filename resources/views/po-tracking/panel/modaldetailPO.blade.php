<div class="modal fade" id="detailsPO" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5>Details PO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span style="color:#fff;" aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table" style="margin-top: -17px">
                    <tr>
                        <td style="width: 27%">No. PO</td>
                        <td style="width: 1%">:</td>
                        <td id="po-number"></td>
                    </tr>
                    <tr>
                        <td>PO Date</td>
                        <td>:</td>
                        <td id="po-date"></td>
                    </tr>
                    <tr>
                        <td>Vendor Code</td>
                        <td>:</td>
                        <td id="vendor-code"></td>
                    </tr>
                    <tr>
                        <td>Vendor Code NEW</td>
                        <td>:</td>
                        <td id="vendor-code-new"></td>
                    </tr>
                    <tr>
                        <td>Vendor</td>
                        <td>:</td>
                        <td id="vendor"></td>
                    </tr>
                   <tr>
                        <td>PO Creator</td>
                        <td>:</td>
                        <td id="po-creator"></td> 
                    </tr>
                    <tr>
                        <td>NRP</td>
                        <td>:</td>
                        <td id="po-nrp"></td> 
                    </tr>
                    <tr>
                        <td>Release PO Date</td>
                        <td>:</td>
                        <td id="release-po-date"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<script> 
    $(document).ready(function(){
        // Modal Details PO
        $(document).on("click",".btn-edit",function(){
            const parents = $(this).parents("tr");
            const poNumber = parents.find(".po_number").html().trim();
            const VendorCode   = $(this).attr("data-vendor-code").trim();
            const VendorCode_new   = $(this).attr("data-vendor-code-new").trim();
            const poDate   = $(this).attr("data-po-date").trim();
            const Vendor   = $(this).attr("data-vendor").trim();
            const poCreator   = $(this).attr("data-po-creator").trim();
            const poNrp  = $(this).attr("data-po-nrp").trim();
            const releaseDate   = $(this).attr("data-release-date").trim();
            $('#po-number').html(poNumber);
            $('#po-date').html(poDate);
            $('#vendor-code').html(VendorCode);
            $('#vendor-code-new').html(VendorCode_new);
            $('#vendor').html(Vendor);
            $('#po-creator').html(poCreator);
            $('#po-nrp').html(poNrp);
            $('#release-po-date').html(releaseDate);
        });
    });
</script>
