<div class="modal fade" id="preparingDocument" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: auto;">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h6>Preparing Document</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ url('shipmentDocumentImport') }}" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <div class="col-md-6 form-group">
                        <label for="ship_book_date">Ship Book Date</label>
                        <input type="date" name="ship_book_date" class="form-control" required>
                        <span id='dataIDNumber'></span>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="atd">Arrival Time Delivery</label>
                        <input type="date" name="atd" class="form-control" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="target_eta">Target ETA on Airport/Seaport</label>
                        <input type="date" name="target_eta" class="form-control" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="target_ata">Target ATA on Airport/Seaport</label>
                        <input type="date" name="target_ata" class="form-control" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="ship-book">Ship Book Document</label><br>
                        <input type="file" name="ship-book[]" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="document-shipment">Shipment Document</label><br>
                        <input type="file" name="document-shipment[]" multiple required>
                    </div>
                    <div class="col-md-12">
                        <small class="text-danger">*Type file PDF, max size 5 MB</small>
                        {{-- <small class="text-danger">*Type file PDF, make it into one PDF file</small> --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary btn-sm">
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() 
    {
        $('#datatable-visibility tbody').on('click', '.preparingDocument', function ()
        {
            var id = $(this).attr('data-number');
            $.ajax({
                url : "{{url('viewcaridataimport')}}?id="+id,
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {
                    $('#dataIDNumber').empty();
                    $(".datepicker").datepicker();
                    $("#dataIDNumber").append
                    (`
                        <input type="hidden" name="Number" class="form-control" value="`+data.dataid.Number+`">
                        <input type="hidden" name="POID" class="form-control" value="`+data.dataid.POID+`">
                        <input type="hidden" name="Vendor" class="form-control" value="`+data.dataid.VendorCode+`">
                    `);

                    $('#preparingDocument').modal('show');
                }
            });
        });
    });
</script>