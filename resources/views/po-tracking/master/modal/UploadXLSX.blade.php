<div id="modal-upload-xlsx" class="modal fade">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form method="post" action="{{url('update-uservendor-wthtax')}}" enctype="multipart/form-data">
            <div class="modal-body text-center">
                {{csrf_field()}}
                <h2>Upload File Vendor Withholding Tax</h2>
                <br>
                <div class="row">
                    <input id="fileupload" class="col" type=file name="file">
                </div>
                <div class="row mt-2">
                    <div class="table-wrapper" style="
                        max-height: 400px;
                        overflow: auto;
                        display:inline-block;
                        ">
                        <table id="tblItems" class="table table-bordered table-responsive">
                            <thead style="position:sticky; top:0;"></thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Yes</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.0/dist/xlsx.full.min.js"></script>
<script>
    var ExcelToJSON = function() {
        this.parseExcel = function(file) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#tblItems tbody').empty();
                $('#tblItems thead').empty();
                var data = e.target.result;
                var workbook = XLSX.read(data, {
                    type: 'binary'
                });
                workbook.SheetNames.forEach(function(sheetName) {
                    var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
                    var productList = JSON.parse(JSON.stringify(XL_row_object));

                    //HEADER
                    var cols = $('#tblItems thead');
                    var object_keys = Object.keys(productList[0]);
                    cols.append(`<tr>`);
                    for (i = 0; i < object_keys.length; i++) {
                        cols.append(`<th>${object_keys[i]}</th>`);
                    }
                    cols.append(`<input type="hidden" name="header" value="`+object_keys+`">`);
                    cols.append(`</tr>`);

                    //BODY
                    var rows = $('#tblItems tbody');
                    console.log(productList[0]);
                    for (i = 0; i < productList.length; i++) {
                        var columns = Object.values(productList[i]);
                        rows.append(`<tr class="tr_content">`);
                            for (n = 0; n < object_keys.length; n++) {
                                rows.append(`
                                <td>${columns[n]}</td>
                                `);
                            }
                        rows.append(`<input type="hidden" name="data[`+i+`]" value="`+columns+`">`);
                        rows.append(`</tr>`);
                    }
                });
            };
            reader.onerror = function(ex) {
            console.log(ex);
            };

            reader.readAsBinaryString(file);
        };
    };

    function handleFileSelect(evt) {
        var files = evt.target.files; // FileList object
        var xl2json = new ExcelToJSON();
        xl2json.parseExcel(files[0]);
    }

    document.getElementById('fileupload').addEventListener('change', handleFileSelect, false);
</script>

<script>
    var pageSize = 3;

    function showPage(page) {
        $(".tr_content").hide();
        $(".tr_content").each(function(n) {
            if (n >= pageSize * (page - 1) && n < pageSize * page)
                $(this).show();
        });        
    }
        
    showPage(1);

    $("#pagin li a").click(function() {
        $("#pagin li a").removeClass("current");
        $(this).addClass("current");
        showPage(parseInt($(this).text())) 
    });
</script>