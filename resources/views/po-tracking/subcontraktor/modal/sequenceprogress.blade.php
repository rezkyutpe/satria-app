{{-- Modal sequenceprogress --}}
<div class="modal fade" id="sequenceprogress" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="top:10px;">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="mt-2">Sequence Progress</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="row table-responsive m-auto">
                    <div id="col">
                         <!-- Detail Tabel -->
                        <table class="table table-bordered table-sm ">
                            <thead>
                                <tr>
                                    <th>PO Number</th>
                                    <th>Item Number</th>
                                    <th>Material</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="poNumber"></td>
                                    <td id="itemNumber"></td>
                                    <td id="Material" nowrap></td>
                                    <td id="dMaterial" nowrap></td>
                                    <td id="Qty"></td>
                                </tr>
                            </tbody>
                        </table>
                         <!-- END of Detail Tabel -->
                    </div>
                </div>
                <div class="row table-responsive m-auto">
                    <div class="col">
                        <div id="contentsequenceprogress"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        //Datepicker
        $(".datepicker").datepicker({
            format: "dd/mm/yyyy",
            autoclose: true,
            todayHighlight: true,
        });
        //END of Datepicker

        //Sequence Progress
        $('#datatable-responsive tbody').on('click', '.sequenceprogressclass', function (){
        var id = $(this).attr('data-id');

        $.ajax({
            url : "{{url('viewsubcontractorsequence')}}?id="+id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
                // console.log(data.actionmenu.r);
                var id              = data.dataid[0].ID;
                var activestage     = data.dataid[0].ActiveStage;

                /* Tabel Detail PO yang di klik */
                var poNumber        = data.dataid[0].Number;
                var ItemNumber      = data.dataid[0].ItemNumber;
                var Material        = data.dataid[0].Material;
                var MatDesc         = data.dataid[0].Description;
                var qty             = data.dataid[0].Quantity;

                $('#itemID').html(id);
                $('#ActiveStage').html(activestage);
                $('#CnfrDate').html(data.dataid.ConfirmedDate);

                $('#poNumber').html(poNumber);
                $('#itemNumber').html(ItemNumber);
                $('#Material').html(Material);
                $('#dMaterial').html(MatDesc);
                $('#Qty').html(qty);
                /* END of Tabel Detail PO yang di klik */

                $('#contentsequenceprogress').empty();

                var html =`<table class="table table-borderless">
                            <thead>
                                <th style="background-color:#B0EDF4;">Delivery Date</th>
                                <th style="background-color:#B0EDF4;">Confirmed Quantity</th>
                                <th style="background-color:#B0EDF4;">Action</th>
                            </thead>
                            <tbody id="tbody">`;
                for(i=0;i<data.dataall.length;i++){
                    //inisialisasi variable
                    var idpartial               = data.dataall[i].ID;
                    // console.log(data.dataall[i].ID);
                    if(data.dataall[i].ConfirmedDate == null){
                        var confirmedquantity       = data.dataall[i].OpenQuantity;
                    }else{
                        var confirmedquantity       = data.dataall[i].ConfirmedQuantity;
                    }

                    //Jika Data dari SAP
                    if(data.dataall[i].LeadTimeItem == null){
                        var totalleadtime           = parseInt(data.dataall[i].WorkTime);

                        var pb                      = (data.dataleadtime[0].Value * totalleadtime);
                        var pbactualdate            = new Date(data.dataall[i].PBActualDate);
                        var pblatereasonid          = data.dataall[i].PBLateReasonID;

                        var setting                 = (data.dataleadtime[1].Value * totalleadtime);
                        var settingactualdate       = new Date(data.dataall[i].SettingActualDate);
                        var settinglatereasonid     = data.dataall[i].SettingLateReasonID;

                        var fullweld                = (data.dataleadtime[2].Value * totalleadtime);
                        var fullweldactualdate      = new Date(data.dataall[i].FullweldActualDate);
                        var fullweldlatereasonid    = data.dataall[i].FullweldLateReasonID;

                        var primer                  = (data.dataleadtime[3].Value * totalleadtime);
                        var primeractualdate        = new Date(data.dataall[i].PrimerActualDate);
                        var primerlatereasonid      = data.dataall[i].PrimerLateReasonID;
                        if(data.dataall[i].ConfirmedDate == null){
                            var confirmeddate            = new Date(data.dataall[i].DeliveryDate);
                        }else{
                            var confirmeddate            = new Date(data.dataall[i].ConfirmedDate);
                        }
                        var date                    = confirmeddate.toLocaleDateString('id-ID');

                        var etcdate                 = new Date(confirmeddate.setDate(confirmeddate.getDate() - (totalleadtime + 1)));
                        var pbetcdate               = new Date(etcdate.setDate(etcdate.getDate() + pb));
                        if(!(data.dataall[i].PBActualDate)){
                            var settingetcdate          = new Date(etcdate.setDate(etcdate.getDate() + setting));
                        }else{
                            var settingetcdate          = new Date(pbactualdate.setDate(pbactualdate.getDate() + setting));
                        }
                        if(!(data.dataall[i].SettingActualDate)){
                            var fullweldetcdate         = new Date(etcdate.setDate(etcdate.getDate() + fullweld));
                        }else{
                            var fullweldetcdate         = new Date(settingactualdate.setDate(settingactualdate.getDate() + fullweld));
                        }
                        if(!(data.dataall[i].FullweldActualDate)){
                            var primeretcdate           = new Date(etcdate.setDate(etcdate.getDate() + primer));
                        }else{
                            var primeretcdate           = new Date(fullweldactualdate.setDate(fullweldactualdate.getDate() + primer));
                        }
                    }
                    //Jika Data dari App PO Tracking
                    else{
                        var totalleadtime           = parseInt(data.dataall[i].LeadTimeItem);

                        var pb                      = data.dataall[i].PB;
                        var pbactualdate            = new Date(data.dataall[i].PBActualDate);
                        var pblatereasonid          = data.dataall[i].PBLateReasonID;

                        var setting                 = data.dataall[i].Setting;
                        var settingactualdate       = new Date(data.dataall[i].SettingActualDate);
                        var settinglatereasonid     = data.dataall[i].SettingLateReasonID;

                        var fullweld                = data.dataall[i].Fullweld;
                        var fullweldactualdate      = new Date(data.dataall[i].FullweldActualDate);
                        var fullweldlatereasonid    = data.dataall[i].FullweldLateReasonID;

                        var primer                  = data.dataall[i].Primer;
                        var primeractualdate        = new Date(data.dataall[i].PrimerActualDate);
                        var primerlatereasonid      = data.dataall[i].PrimerLateReasonID;
                        if(data.dataall[i].ConfirmedDate == null){
                            var confirmeddate            = new Date(data.dataall[i].DeliveryDate);
                        }else{
                            var confirmeddate            = new Date(data.dataall[i].ConfirmedDate);
                        }
                        var date                    = confirmeddate.toLocaleDateString('id-ID');

                        var etcdate                 = new Date(confirmeddate.setDate(confirmeddate.getDate() - totalleadtime));
                        var pbetcdate               = new Date(etcdate.setDate(etcdate.getDate() + pb));
                        if(!(data.dataall[i].PBActualDate)){
                            var settingetcdate          = new Date(etcdate.setDate(etcdate.getDate() + setting));
                        }else{
                            var settingetcdate          = new Date(pbactualdate.setDate(pbactualdate.getDate() + setting));
                        }
                        if(!(data.dataall[i].SettingActualDate)){
                            var fullweldetcdate         = new Date(etcdate.setDate(etcdate.getDate() + fullweld));
                        }else{
                            var fullweldetcdate         = new Date(settingactualdate.setDate(settingactualdate.getDate() + fullweld));
                        }
                        if(!(data.dataall[i].FullweldActualDate)){
                            var primeretcdate           = new Date(etcdate.setDate(etcdate.getDate() + primer));
                        }else{
                            var primeretcdate           = new Date(fullweldactualdate.setDate(fullweldactualdate.getDate() + primer));
                        }
                    }


                    var temp1= [];
                    var temp2= [];
                    var temp3= [];
                    var temp4= [];

                    temp1.push(pbetcdate);
                    temp2.push(settingetcdate);
                    temp3.push(fullweldetcdate);
                    temp4.push(primeretcdate);

                    try{
                        if(!(data.photoprogress[i].length == 0)){
                            var linkfoto                = data.photoprogress[i][0].FileName;
                            var linkfotoid              = data.photoprogress[i][0].PurchasingDocumentItemID;
                            var linkfotoname            = data.photoprogress[i][0].ProcessName;
                        }
                    }
                    catch(err){
                        console.log(err);
                    }


                    if((data.dataall[i].ActiveStage == '3' || data.dataall[i].ActiveStage == null) && (data.actionmenu.r == 1 || data.actionmenu.c == 1)){
                        var navlinksetting = 'disabled';
                        var navlinkfullweld = 'disabled';
                        var navlinkprimer = 'disabled';
                    }
                    if(data.dataall[i].ActiveStage == '3a' && (data.actionmenu.r == 1 || data.actionmenu.c == 1)){
                        var navlinksetting = '';
                        var navlinkfullweld = 'disabled';
                        var navlinkprimer = 'disabled';
                    }
                    if(data.dataall[i].ActiveStage == '3b' && (data.actionmenu.r == 1 || data.actionmenu.c == 1)){
                        var navlinksetting = '';
                        var navlinkfullweld = '';
                        var navlinkprimer = 'disabled';
                    }
                    if(data.dataall[i].ActiveStage == '3c' && (data.actionmenu.r == 1 || data.actionmenu.c == 1)){
                        var navlinksetting = '';
                        var navlinkfullweld = '';
                        var navlinkprimer = '';
                    }
                    if(data.dataall[i].ActiveStage >= '4' && (data.actionmenu.r == 1 || data.actionmenu.c == 1)){
                        var navlinksetting = '';
                        var navlinkfullweld = '';
                        var navlinkprimer = '';
                    }
                    if(data.actionmenu.u==1){
                        var navlinksetting = 'disabled';
                        var navlinkfullweld = 'disabled';
                        var navlinkprimer = 'disabled';
                    }
                    //END of inisialisasi variable

                    if(data.dataall[i].ActiveStage != 4){
                        html +=`<tr style="background-color:#DDF7FA;">`;
                    }
                    else{
                        html+=`<tr class="bg-light font-italic">`;
                    }
                    html +=`
                                    <td>`+date+`</td>
                                    <td>`+confirmedquantity+`</td>
                                    <td>
                                    <button class="btn btn-sm btn-transparent" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseExample`+i+`">
                                        <i class="fa fa-th-list"></i>
                                    </button>
                                    </td>
                                </tr>`;

                        //hasil expand/collapse + Content
                        html +=`
                                <tr class="collapse" id="collapseExample`+i+`" style="border-bottom:2px solid grey;">
                                    <td colspan="3">
                                        <div class="collapse" id="collapseExample`+i+`">
                                            <div class="row justify-content-md-center">
                                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" id="pb-tab`+i+`" data-toggle="tab" href="#pb`+i+`" role="tab" aria-controls="pb`+i+`" aria-selected="true">PB</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="setting-tab`+i+`" data-toggle="tab" href="#setting`+i+`" role="tab" aria-controls="setting`+i+`" aria-selected="false">Setting</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="fullweld-tab`+i+`" data-toggle="tab" href="#fullweld`+i+`" role="tab" aria-controls="fullweld`+i+`" aria-selected="false">Fullweld / Assy</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="primer-tab`+i+`" data-toggle="tab" href="#primer`+i+`" role="tab" aria-controls="primer`+i+`" aria-selected="false">Primer / QC</a>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="tab-content mt-4 mb-4" id="myTabContent">
                                                `;/* PB Sequence */
                                                for(j=0;j<temp1.length;j++){
                                                    var finishdatepb = new Date(data.dataall[i].PBActualDate);
                                                    var finishdatepbfix = finishdatepb.toLocaleDateString('id-ID');
                                                html+=`
                                                <div class="tab-pane active" id="pb`+i+`" role="tabpanel" aria-labelledby="pb-tab`+i+`">
                                                    <form method="post" action="{{url('insertprogress-subcontractor')}}" onsubmit="return confirm('Is the PB form filled out correctly?');" enctype="multipart/form-data">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="ID" class="form-control" autocomplete="off" value="`+idpartial+`" readonly>
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col-sm-3 mb-2">
                                                                    <div class="font-weight-bold mb-3">ETC</div>
                                                                    `+temp1[j].toLocaleDateString('id-ID')+`
                                                                    <input type="hidden" id="PBETCDate`+i+`" name="PBETCDate" value="`+temp1[j].toLocaleDateString('id-ID')+`">
                                                                </div>
                                                                <div class="col-sm-3 mb-2">
                                                                    `;
                                                                    if(data.dataall[i].PBActualDate){
                                                                    html += `<div class="font-weight-bold mb-3">Finish Date</div>
                                                                    `+finishdatepbfix+``;
                                                                    }else{
                                                                    html +=`<div class="font-weight-bold mb-2">Finish Date</div>
                                                                    <input type="text" id="PBActualDate`+i+`" class="form-control datepicker" autocomplete="off" name="PBActualDate" value="" placeholder="d/m/yyyy" onchange="checkReasonIssue(this,'PBETCDate`+i+`','PBreasonId`+i+`')" required>`;
                                                                    }
                                                                    html +=`
                                                                </div>
                                                                <div class="col-sm-3 mb-2">
                                                                    `;
                                                                    if(data.dataall[i].PBLateReasonID || data.dataall[i].PBLateReasonID == ''){
                                                                        for(n=0;n<data.reasonsubcont.length;n++){
                                                                            if(pblatereasonid == data.reasonsubcont[n].ID){
                                                                                html+= `<div class="font-weight-bold mb-3">Reason Issue</div>`
                                                                                        +data.reasonsubcont[n].Name+``;
                                                                            }
                                                                        }
                                                                    }else if(data.dataall[i].PBLateReasonID == 0){
                                                                        html+= `<div class="font-weight-bold mb-3">Reason Issue</div>`;
                                                                    }else
                                                                    {
                                                                        html +=`<div class="font-weight-bold mb-2">Reason Issue</div>
                                                                            <select id="PBreasonId`+i+`" class="form-control" name="PBreasonID">
                                                                            <option value="" selected disabled hidden>--NaN--</option>
                                                                            `;
                                                                            for(n=0;n<data.reasonsubcont.length;n++){
                                                                            html+=`
                                                                            <option value="`+data.reasonsubcont[n].ID+`">`+data.reasonsubcont[n].Name+`</option>
                                                                            `;
                                                                            }
                                                                        html +=`
                                                                        </select>`;
                                                                    }
                                                                    html +=`
                                                                </div>
                                                                `;
                                                                    if(data.dataall[i].PBActualDate){
                                                                    html += ``;
                                                                    }else{
                                                                    html +=`<div class="col-sm-3 mb-2">
                                                                    <div class="font-weight-bold mb-2">Upload Image</div>
                                                                    <input type="file" id="PBfoto`+i+`" style="border:0;" name="PBfoto[]" accept="image/*" class="form-control" onchange="previewImage(this,'main_image_productpb`+i+`')" multiple required/>
                                                                </div>`;
                                                                    }
                                                                html +=`
                                                            </div>
                                                            <div class="row mt-4">
                                                                <div class="col font-weight-bold justify-content-md-center">
                                                                    Live Preview
                                                                </div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col justify-content-md-center">
                                                                    `;
                                                                    if(linkfotoid == idpartial)
                                                                    {
                                                                        for(jmlfoto=0;jmlfoto<data.photoprogress[i].length;jmlfoto++){

                                                                            if(data.photoprogress[i][jmlfoto].ProcessName == "PB")
                                                                            {
                                                                            html+=`
                                                                            <a style="cursor:pointer;">
                                                                                <img onclick="openInNewTab('{{url('/public/potracking/progressphoto/`+data.photoprogress[i][jmlfoto].FileName+`')}}');" class="image-preview" src="{{ url('/public/potracking/progressphoto/`+data.photoprogress[i][jmlfoto].FileName+`') }}" alt="no image"/>
                                                                            </a>
                                                                            `;
                                                                            }
                                                                        }
                                                                    }else{
                                                                    html+=`
                                                                    <div id="main_image_productpb`+i+`"></div>
                                                                    `;
                                                                    }
                                                                html+=`</div>
                                                            </div>
                                                            `;
                                                            if((data.actionmenu.r == 1 || data.actionmenu.c == 1) && (data.dataall[i].ActiveStage == 3 || data.dataall[i].ActiveStage == null)){
                                                                html +=`
                                                                    <div class="row mt-4">
                                                                        <div class="col">
                                                                            <button class="btn btn-primary" type="submit" name="action" value="PB">
                                                                                Save
                                                                            </button>
                                                                            <button class="btn btn-warning ml-4" type="reset" onclick="resetImage('main_image_productpb`+i+`');">
                                                                                Reset
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                `;
                                                            }
                                                            html+=`
                                                        </div>
                                                    </form>
                                                </div>
                                                `;} /* Setting Sequence */
                                                for(k=0;k<temp2.length;k++){
                                                    var finishdatesetting = new Date(data.dataall[i].SettingActualDate);
                                                html+=`
                                                <div class="tab-pane fade" id="setting`+i+`" role="tabpanel" aria-labelledby="setting-tab`+i+`">
                                                    <form method="post" action="{{url('insertprogress-subcontractor')}}" onsubmit="return confirm('Is the Setting form filled out correctly?');" enctype="multipart/form-data">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="ID" class="form-control" autocomplete="off" value="`+idpartial+`" readonly>
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col-sm-3 mb-2">
                                                                    <div class="font-weight-bold mb-3">ETC</div>
                                                                    `+temp2[k].toLocaleDateString('id-ID')+`
                                                                    <input type="hidden" id="SettingETCDate`+i+`" name="SettingETCDate" value="`+temp2[k].toLocaleDateString('id-ID')+`">
                                                                </div>
                                                                <div class="col-sm-3 mb-2">
                                                                    `;
                                                                    if(data.dataall[i].SettingActualDate){
                                                                    html += `<div class="font-weight-bold mb-3">Finish Date</div>
                                                                    `+finishdatesetting.toLocaleDateString('id-ID')+``;
                                                                    }else{
                                                                    html +=`<div class="font-weight-bold mb-2">Finish Date</div>
                                                                    <input type="text" class="form-control datepicker" autocomplete="off" name="SettingActualDate" value="" placeholder="d/m/yyyy" onchange="checkReasonIssue(this,'SettingETCDate`+i+`','SettingreasonId`+i+`')" `+navlinksetting+` required>`;
                                                                    }
                                                                    html +=`
                                                                </div>
                                                                <div class="col-sm-3 mb-2">
                                                                    `;
                                                                    if(data.dataall[i].SettingLateReasonID || data.dataall[i].SettingLateReasonID == ''){
                                                                        for(n=0;n<data.reasonsubcont.length;n++){
                                                                            if(settinglatereasonid == data.reasonsubcont[n].ID){
                                                                                html+= `<div class="font-weight-bold mb-3">Reason Issue</div>`
                                                                                        +data.reasonsubcont[n].Name+``;
                                                                            }
                                                                        }
                                                                    }else if(data.dataall[i].SettingLateReasonID == 0){
                                                                        html+= `<div class="font-weight-bold mb-3">Reason Issue</div>`;
                                                                    }else
                                                                    {
                                                                        html +=`<div class="font-weight-bold mb-2">Reason Issue</div>
                                                                            <select id="SettingreasonId`+i+`" class="form-control" name="SettingreasonID" `+navlinksetting+`>
                                                                            <option value="" selected disabled hidden>--NaN--</option>
                                                                            `;
                                                                            for(n=0;n<data.reasonsubcont.length;n++){
                                                                            html+=`
                                                                            <option value="`+data.reasonsubcont[n].ID+`">`+data.reasonsubcont[n].Name+`</option>
                                                                            `;
                                                                            }
                                                                        html +=`
                                                                        </select>`;
                                                                    }
                                                                    html +=`
                                                                </div>
                                                                `;
                                                                    if(data.dataall[i].SettingActualDate){
                                                                    html += ``;
                                                                    }else{
                                                                    html +=`<div class="col-sm-3 mb-2">
                                                                    <div class="font-weight-bold mb-2">Upload Image</div>
                                                                    <input type="file" style="border:0;" name="Settingfoto[]" accept="image/*" class="form-control" onchange="previewImage(this,'main_image_productsetting`+i+`')" `+navlinksetting+` multiple required/>
                                                                </div>`;
                                                                    }
                                                                html +=`
                                                            </div>
                                                            <div class="row mt-4">
                                                                <div class="col font-weight-bold justify-content-md-center">
                                                                    Live Preview
                                                                </div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col justify-content-md-center">
                                                                    `;
                                                                    if(linkfotoid == idpartial)
                                                                    {
                                                                        for(jmlfoto=0;jmlfoto<data.photoprogress[i].length;jmlfoto++){
                                                                            if(data.photoprogress[i][jmlfoto].ProcessName == "Setting")
                                                                            {
                                                                            html+=`
                                                                            <a style="cursor:pointer;">
                                                                                <img onclick="openInNewTab('{{url('/public/potracking/progressphoto/`+data.photoprogress[i][jmlfoto].FileName+`')}}');" class="image-preview" src="{{ url('/public/potracking/progressphoto/`+data.photoprogress[i][jmlfoto].FileName+`') }}" alt="no image"/>
                                                                            </a>
                                                                            `;
                                                                            }
                                                                        }
                                                                    }
                                                                    html+=`
                                                                    <div id="main_image_productsetting`+i+`"></div>
                                                                    `;
                                                                html+=`</div>
                                                            </div>
                                                            `;
                                                            if((data.actionmenu.r == 1 || data.actionmenu.c == 1) && data.dataall[i].ActiveStage == '3a'){
                                                                html +=`
                                                                    <div class="row mt-4">
                                                                        <div class="col">
                                                                            <button class="btn btn-primary btn-submit" type="submit" name="action" value="Setting">
                                                                                Save
                                                                            </button>
                                                                            <button class="btn btn-warning ml-4" type="reset" onclick="resetImage('main_image_productsetting`+i+`');">
                                                                                Reset
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    `;
                                                            }
                                                            html+=`
                                                        </div>
                                                    </form>
                                                </div>
                                                `;} /* Fullweld Sequence */
                                                for(l=0;l<temp3.length;l++){
                                                    var finishdatefullweld = new Date(data.dataall[i].FullweldActualDate);
                                                html+=`
                                                <div class="tab-pane fade" id="fullweld`+i+`" role="tabpanel" aria-labelledby="fullweld-tab`+i+`">
                                                    <form method="post" action="{{url('insertprogress-subcontractor')}}" onsubmit="return confirm('Is the Fullweld/Assy form filled out correctly?');" enctype="multipart/form-data">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="ID" class="form-control" autocomplete="off" value="`+idpartial+`" readonly>
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col-sm-3 mb-2">
                                                                    <div class="font-weight-bold mb-3">ETC</div>
                                                                    `+temp3[l].toLocaleDateString('id-ID')+`
                                                                    <input type="hidden" id="FullweldETCDate`+i+`" name="FullweldETCDate" value="`+temp3[l].toLocaleDateString('id-ID')+`">
                                                                </div>
                                                                <div class="col-sm-3 mb-2">
                                                                    `;
                                                                    if(data.dataall[i].FullweldActualDate){
                                                                    html += `<div class="font-weight-bold mb-3">Finish Date</div>
                                                                    `+finishdatefullweld.toLocaleDateString('id-ID')+``;
                                                                    }else{
                                                                    html +=`<div class="font-weight-bold mb-2">Finish Date</div>
                                                                    <input type="text" class="form-control datepicker" autocomplete="off" name="FullweldActualDate" value="" placeholder="d/m/yyyy" onchange="checkReasonIssue(this,'FullweldETCDate`+i+`','FullweldreasonId`+i+`')" `+navlinkfullweld+` required>`;
                                                                    }
                                                                    html +=`
                                                                </div>
                                                                <div class="col-sm-3 mb-2">
                                                                    `;
                                                                    if(data.dataall[i].FullweldLateReasonID || data.dataall[i].FullweldLateReasonID == ''){
                                                                        for(n=0;n<data.reasonsubcont.length;n++){
                                                                            if(fullweldlatereasonid == data.reasonsubcont[n].ID){
                                                                                html+= `<div class="font-weight-bold mb-3">Reason Issue</div>`
                                                                                        +data.reasonsubcont[n].Name+``;
                                                                            }
                                                                        }
                                                                    }else if(data.dataall[i].FullweldLateReasonID == 0){
                                                                        html+= `<div class="font-weight-bold mb-3">Reason Issue</div>`;
                                                                    }else
                                                                    {
                                                                        html +=`<div class="font-weight-bold mb-2">Reason Issue</div>
                                                                            <select id="FullweldreasonId`+i+`" class="form-control" name="FullweldreasonID" `+navlinkfullweld+`>
                                                                            <option value="" selected disabled hidden>--NaN--</option>
                                                                            `;
                                                                            for(n=0;n<data.reasonsubcont.length;n++){
                                                                            html+=`
                                                                            <option value="`+data.reasonsubcont[n].ID+`">`+data.reasonsubcont[n].Name+`</option>
                                                                            `;
                                                                            }
                                                                        html +=`
                                                                        </select>`;
                                                                    }
                                                                    html +=`
                                                                </div>
                                                                `;
                                                                    if(data.dataall[i].FullweldActualDate){
                                                                    html += ``;
                                                                    }else{
                                                                    html +=`<div class="col-sm-3 mb-2">
                                                                    <div class="font-weight-bold mb-2">Upload Image</div>
                                                                    <input type="file" style="border:0;" name="Fullweldfoto[]" accept="image/*" class="form-control" onchange="previewImage(this,'main_image_productfullweld`+i+`');" `+navlinkfullweld+` multiple required/>
                                                                </div>`;
                                                                    }
                                                                html +=`
                                                            </div>
                                                            <div class="row mt-4">
                                                                <div class="col font-weight-bold justify-content-md-center">
                                                                    Live Preview
                                                                </div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col justify-content-md-center">
                                                                    `;
                                                                    if(linkfotoid == idpartial)
                                                                    {
                                                                        for(jmlfoto=0;jmlfoto<data.photoprogress[i].length;jmlfoto++){
                                                                            if(data.photoprogress[i][jmlfoto].ProcessName == "Fullweld")
                                                                            {
                                                                            html+=`
                                                                            <a style="cursor:pointer;">
                                                                                <img onclick="openInNewTab('{{url('/public/potracking/progressphoto/`+data.photoprogress[i][jmlfoto].FileName+`')}}');" class="image-preview" src="{{ url('/public/potracking/progressphoto/`+data.photoprogress[i][jmlfoto].FileName+`') }}" alt="no image"/>
                                                                            </a>
                                                                            `;
                                                                            }
                                                                        }
                                                                    }
                                                                    html+=`
                                                                    <div id="main_image_productfullweld`+i+`"></div>
                                                                    `;
                                                                html+=`</div>
                                                            </div>
                                                            `;
                                                            if((data.actionmenu.r == 1 || data.actionmenu.c == 1) && data.dataall[i].ActiveStage == '3b'){
                                                                html +=`
                                                                    <div class="row mt-4">
                                                                        <div class="col">
                                                                            <button class="btn btn-primary btn-submit" type="submit" name="action" value="Fullweld">
                                                                                Save
                                                                            </button>
                                                                            <button class="btn btn-warning ml-4" type="reset" onclick="resetImage('main_image_productfullweld`+i+`');">
                                                                                Reset
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                `;
                                                            }
                                                            html+=`
                                                        </div>
                                                    </form>
                                                </div>
                                                `;} /* Primer Sequence */
                                                for(m=0;m<temp4.length;m++){
                                                    var finishdateprimer = new Date(data.dataall[i].PrimerActualDate);
                                                html+=`
                                                <div class="tab-pane fade" id="primer`+i+`" role="tabpanel" aria-labelledby="primer-tab`+i+`">
                                                    <form method="post" action="{{url('insertprogress-subcontractor')}}" onsubmit="return confirm('Is the Primer/QC form filled out correctly?');" enctype="multipart/form-data">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="ID" class="form-control" autocomplete="off" value="`+idpartial+`" readonly>
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col-sm-3 mb-2">
                                                                    <div class="font-weight-bold mb-3">ETC</div>
                                                                    `+temp4[m].toLocaleDateString('id-ID')+`
                                                                    <input type="hidden" id="PrimerETCDate`+i+`" name="PrimerETCDate" value="`+temp4[m].toLocaleDateString('id-ID')+`">
                                                                </div>
                                                                <div class="col-sm-3 mb-2">
                                                                    `;
                                                                    if(data.dataall[i].PrimerActualDate){
                                                                    html += `<div class="font-weight-bold mb-3">Finish Date</div>
                                                                    `+finishdateprimer.toLocaleDateString('id-ID')+``;
                                                                    }else{
                                                                    html +=`<div class="font-weight-bold mb-2">Finish Date</div>
                                                                    <input type="text" class="form-control datepicker" autocomplete="off" name="PrimerActualDate" value="" placeholder="d/m/yyyy" onchange="checkReasonIssue(this,'PrimerETCDate`+i+`','PrimerreasonId`+i+`')" `+navlinkprimer+` required>`;
                                                                    }
                                                                    html +=`
                                                                </div>
                                                                <div class="col-sm-3 mb-2">
                                                                    `;
                                                                    if(data.dataall[i].PrimerLateReasonID || data.dataall[i].PrimerLateReasonID == ''){
                                                                        for(n=0;n<data.reasonsubcont.length;n++){
                                                                            if(primerlatereasonid == data.reasonsubcont[n].ID){
                                                                                html+= `<div class="font-weight-bold mb-3">Reason Issue</div>`
                                                                                        +data.reasonsubcont[n].Name+``;
                                                                            }
                                                                        }
                                                                    }else if(data.dataall[i].PrimerLateReasonID == 0){
                                                                        html+= `<div class="font-weight-bold mb-3">Reason Issue</div>`;
                                                                    }else
                                                                    {
                                                                        html +=`<div class="font-weight-bold mb-2">Reason Issue</div>
                                                                            <select id="PrimerreasonId`+i+`" class="form-control" name="PrimerreasonID" `+navlinkprimer+`>
                                                                            <option value="">--NaN--</option>
                                                                            `;
                                                                            for(n=0;n<data.reasonsubcont.length;n++){
                                                                            html+=`
                                                                            <option value="`+data.reasonsubcont[n].ID+`">`+data.reasonsubcont[n].Name+`</option>
                                                                            `;
                                                                            }
                                                                        html +=`
                                                                        </select>`;
                                                                    }
                                                                    html +=`
                                                                </div>
                                                                `;
                                                                    if(data.dataall[i].PrimerActualDate){
                                                                    html += ``;
                                                                    }else{
                                                                    html +=`<div class="col-sm-3 mb-2">
                                                                    <div class="font-weight-bold mb-2">Upload Image</div>
                                                                    <input type="file" style="border:0;" name="Primerfoto[]" accept="image/*" class="form-control" onchange="previewImage(this,'main_image_productprimer`+i+`');" `+navlinkprimer+` multiple required/>
                                                                </div>`;
                                                                    }
                                                                html +=`
                                                            </div>
                                                            <div class="row mt-4">
                                                                <div class="col font-weight-bold justify-content-md-center">
                                                                    Live Preview
                                                                </div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col justify-content-md-center">
                                                                    `;
                                                                    if(linkfotoid == idpartial)
                                                                    {
                                                                        for(jmlfoto=0;jmlfoto<data.photoprogress[i].length;jmlfoto++){
                                                                            if(data.photoprogress[i][jmlfoto].ProcessName == "Primer")
                                                                            {
                                                                            html+=`
                                                                            <a style="cursor:pointer;">
                                                                                <img onclick="openInNewTab('{{url('/public/potracking/progressphoto/`+data.photoprogress[i][jmlfoto].FileName+`')}}');" class="image-preview" src="{{ url('/public/potracking/progressphoto/`+data.photoprogress[i][jmlfoto].FileName+`') }}" alt="no image"/>
                                                                            </a>
                                                                            `;
                                                                            }
                                                                        }
                                                                    }
                                                                    html+=`
                                                                    <div id="main_image_productprimer`+i+`"></div>
                                                                    `;
                                                                html+=`</div>
                                                            </div>
                                                            `;
                                                            if((data.actionmenu.r == 1 || data.actionmenu.c == 1) && data.dataall[i].ActiveStage == '3c'){
                                                                html +=`
                                                                    <div class="row mt-4">
                                                                        <div class="col">
                                                                            <button class="btn btn-primary btn-submit" type="submit" name="action" value="Primer">
                                                                                Save
                                                                            </button>
                                                                            <button class="btn btn-warning ml-4" type="reset" onclick="resetImage('main_image_productprimer`+i+`');">
                                                                                Reset
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                `;
                                                            }
                                                            html+=`
                                                        </div>
                                                    </form>
                                                </div>
                                                `;}
                                                html+=`
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            `;
                    }
                    html += `</tbody></table>`;
                    $('#contentsequenceprogress').append(html);
                    $('#contentsequenceprogress').find(".datepicker").datepicker({
                        format: "d/m/yyyy",
                        autoclose: true,
                        todayHighlight: true,
                    });
                    $('#sequenceprogress').modal('show');
                }
            });
        });
    //END of Sequence Progress
    });
</script>

<script>
    //preview image
    function previewImage(input,imgId) {
        $('#'+imgId).html("");
        $('#'+imgId).show();
        for (var i = 0; i < input.files.length; i++) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#'+imgId).append(`<img class="image-preview" src="`+e.target.result+`" alt=""/>`);
                //   $('#'+imgId)
                //       .attr('src', e.target.result).show();
            }
            reader.readAsDataURL(input.files[i]);
        }
    }

    //remove image preview when reset button is clicked
    function resetImage(imgId) {
        $('#'+imgId).empty().hide();
    }

    //open image in new tab
    function openInNewTab(url) {
        window.open(url, '_blank').focus();
    };

    // enable or disable ReasonIssue
    function checkReasonIssue(input,dateId,reasonId){
        var varInput        = $(input).val().split("/");
        var varDateId       = $('#'+dateId).val().split("/");

        var varInputDate    = new Date(varInput[2],varInput[1] - 1,varInput[0]);
        var varDateIdDate   = new Date(varDateId[2],varDateId[1] - 1,varDateId[0]);

        if(varInputDate <= varDateIdDate){
            $('#'+reasonId).prop('disabled', 'disabled');
            $('#'+reasonId).prop('required', false);
            $('#'+reasonId).val("");
        }else{
            $('#'+reasonId).prop('disabled', false);
            $('#'+reasonId).prop('required', 'required');
        }
    }

    //Alert on Submit Button Clicked
    function submitAlert(formId){
        event.preventDefault();
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            cancelButtonColor: '#d33',
            confirmButtonColor: '#3085d6',
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.isConfirmed) {
                    $('#'+formId).submit();
            }
            else{
                return false;
            }
        })
    }
</script>