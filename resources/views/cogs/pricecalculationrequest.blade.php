@extends('cogs.panel.master')
@section('mycss')
<style>
::-webkit-scrollbar {
    -webkit-appearance: none;
    width: 7px;
}

::-webkit-scrollbar-thumb {
    border-radius: 4px;
    background-color: rgba(0, 0, 0, .25);
    -webkit-box-shadow: 0 0 1px rgba(255, 255, 255, .25);
    box-shadow: 0 0 1px rgba(255, 255, 255, .25);
}

</style>
@endsection
@section('content')
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>{{ $data['title'] }}</h2>
                <p class="d-none" id="UserName" value="{{ Auth::user()->name }}"></p>
                <div class="nav pull-right" role="button">
                    <li><a class="collapse-link p-0"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </div>
                <h6>
                    <!-- <a href="{{ url('cogs-refresh-alldata') }}" style="color:white;"
                        class="badge badge-success pull-right mx-3 my-1">
                        <i class="fa fa-refresh fa-xs"></i> ALL DATA
                    </a> -->
                    <a href="{{ url('cogs-refresh-apcr') }}" style="color:white;"
                        class="badge badge-success pull-right mx-3 my-1">
                        <i class="fa fa-refresh fa-xs"></i> APCR
                    </a>
                </h6>
                <div class="clearfix"></div>
            </div>
            @if(session()->has('err_message'))
            <div class="alert alert-danger alert-dismissible" role="alert" auto-close="10000">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                {{ session()->get('err_message') }}
            </div>
            @endif
            @if(session()->has('suc_message'))
            <div class="alert alert-success alert-dismissible" role="alert" auto-close="10000">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                {{ session()->get('suc_message') }}
            </div>
            @endif

            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <table id="datatable-visibility-pcr" class="table text-center table-hover" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr style="white-space:nowrap;">
                                        <th>No</th>
                                        <th>Owner</th>
                                        <th>Created On</th>
                                        <th>PCR ID</th>
                                        <th>Opportunity</th>
                                        <th>PIC</th>
                                        <th>Status</th>
                                        <th>Stagging</th>
                                        <th>COGS Status</th>
                                        <th>COGS ID</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($data['data_pcr']))
                                    @foreach ($data['data_pcr'] as $i => $item)
                                    <tr class="baseBlock">
                                        <td><b>{{ $i+1 }}</b></td>
                                        <td class="text-left">{{ $item->Owner }}</td>
                                        <td>{{ $item->CreatedOn }}</td>
                                        <td><b>{{ $item->PCRID }}</b></td>
                                        <td>{{ $item->Opportunity }}</td>
                                        <td>{{ $item->PIC }}</td>
                                        <td>{{ $item->Status }}</td>
                                        <td>{{ $item->Stagging }}</td>
                                        <td>{{ $item->COGSStatus }}</td>
                                        <td>{{ $item->COGSID }}</td>
                                        <td>
                                            @if ($item->COGSStatus != "Created")
                                            @if($data['actionmenu']->c==1)
                                            <a href="#" style="color:white; margin:2px;"
                                                class="badge badge-primary create-cogs" pcrid="{{ $item->PCRID }}">
                                                <i class="fa fa-plus fa-xs"></i>
                                                Calculate
                                            </a>
                                            @endif
                                            @else
                                            @if($data['actionmenu']->u==1)
                                            <a href="{{ url('cogs-form-cogs/'.$item->COGSID.'/view') }}"
                                                style="color:white; margin:2px;" class="badge badge-warning form-cogs"
                                                cogsid="{{ $item->COGSID }}">
                                                <i class="fa fa-pencil fa-xs"></i>
                                                Form
                                            </a>
                                            @endif
                                            @if($data['actionmenu']->d==1)
                                            <a href="#" style="color:white; margin:2px;"
                                                class="badge badge-danger delete-cogs" cogsid="{{ $item->COGSID }}"
                                                menu="apcr">
                                                <i class="fa fa-trash fa-xs"></i>
                                                Delete
                                            </a>
                                            @endif
                                            @if($data['actionmenu']->v==1)
                                            <a href="#" style="color:white; margin:2px;"
                                                class="badge badge-info view-cogs" data-toggle="popover"
                                                data-trigger="focus" title="<b>Info</b>" data-content="
                                                    <b> Product Category : </b><br>{{  $item->CategoryName }}
                                                    <br><b> Calculation Type : </b><br>{{  $item->CalculationType }}
                                                    <br><b> PN Reference : </b><br>{{  $item->PNReference }}"
                                                data-html="true">
                                                <i class="fa fa-info-circle fa-xs"></i>
                                                Info
                                            </a>
                                            @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="my-2 data-time-update">
                            <div class="mt-2">
                                <a><i class="fa fa-clock-o"></i></a>
                                <span class="d-none data_update_time">{{ $data['updated_at_apcr'] }}</span>
                                <span class="text-dark update_time"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DELETE -->
<div class="modal fade" id="delete">
    <form method="post" action="{{url('cogs-delete-cogs')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h3>Warning</h3>
                    <h2 id="message"></h2>
                </div>
                <input id="COGSIDdelete" type="hidden" name="COGSIDdelete" value="">
                <input id="PCRIDdelete" type="hidden" name="PCRIDdelete" value="">
                <input id="CategoryNamedelete" type="hidden" name="CategoryNamedelete" value="">
                <input id="Menu" type="hidden" name="Menu" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-success">Yes</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- MODAL NEW COGS -->
<div class="modal fade" id="modalAddCOGS" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-lg h-100 my-0 mx-auto d-flex flex-column justify-content-center" role="document"
        style="max-width: 80%">
        <form method="post" id="AddCOGS" action="{{url('cogs-create-form-cogs')}}" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="modal-content m-2">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">Calculate New COGS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-2">
                            <label class="my-2">COGS ID </label>
                        </div>
                        <div class="col-4">
                            <input id="COGSID" name="COGSID" class="form-control" type="text" value="" readonly>
                        </div>
                        <div class="col-2">
                            <label class="my-2">Calculation Type *</label>
                        </div>
                        <div class="col-4">
                            <select id="CalculationType" name="CalculationType" class="form-control"
                                style="width: 100%">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-2">
                            <label class="my-2">PCR ID </label>
                        </div>
                        <div class="col-4" id="InputPCRID">
                            <input id="PCRID" name="PCRID" class="form-control" type="text" value="" readonly>
                        </div>
                        <div class="col-2">
                            <label class="my-2">Product Category *</label>
                        </div>
                        <div class="col-4">
                            <select id="ProductCategory" name="ProductCategory" class="form-control"
                                style="width: 100%">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-2">
                            <label class="my-2">Opportunity </label>
                        </div>
                        <div class="col-4">
                            <textarea id="Opportunity" name="Opportunity" class="form-control" type="text" value=""
                                readonly>
                            </textarea>
                        </div>
                        <div class="col-2">
                            <label class="my-2">PN Reference *</label>
                        </div>
                        <div class="col-4" id="htmlPNReference">
                            <input id="PNReference" name="PNReference" class="form-control" type="text" value="">
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-2">
                            <label class="my-2">PIC Triatra </label>
                        </div>
                        <div class="col-4">
                            <input id="PICTriatra" name="PICTriatra" class="form-control" type="text" value="" readonly>
                        </div>
                        <div class="col-2">
                            <label class="my-2">Cost Estimator </label>
                        </div>
                        <div class="col-4">
                            <input id="CostEstimator" name="CostEstimator" class="form-control" type="text" value=""
                                readonly>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-2">
                            <label class="my-2">Pre Eliminary Drw. Number </label>
                        </div>
                        <div class="col-4">
                            <input id="PEDNumber" name="PEDNumber" class="form-control" type="text" value=""
                                placeholder="e.g. ABC-123" required>
                        </div>
                        <div class="col-2">
                            <label class="my-2">Marketing Dept Head </label>
                        </div>
                        <div class="col-4">
                            <input id="MarketingDeptHead" name="MarketingDeptHead" class="form-control" type="text"
                                value="" readonly>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-2">
                            <label class="my-2">Unit Weight (Kg)</label>
                        </div>
                        <div class="col-4">
                            <input id="UnitWeight" name="UnitWeight" class="form-control NumberInputComma" type="text"
                                value="" placeholder="e.g. 123.456" required>
                        </div>
                        <div class="col-2">
                            <label class="my-2">SCM Division Head </label>
                        </div>
                        <div class="col-4">
                            <input id="SCMDivisionHead" name="SCMDivisionHead" class="form-control" type="text" value=""
                                readonly>
                        </div>
                    </div>
                    <!-- <div class="row mt-2">
                        <select name="skills" class="ui fluid search dropdown">
                            <option value="">Skills</option>
                            <option value="angular">Angular</option>
                            <option value="css">CSS</option>
                            <option value="design">Graphic Design</option>
                            <option value="ember">Ember</option>
                            <option value="html">HTML</option>
                            <option value="ia">Information Architecture</option>
                            <option value="javascript">Javascript</option>
                            <option value="mech">Mechanical Engineering</option>
                            <option value="meteor">Meteor</option>
                            <option value="node">NodeJS</option>
                            <option value="plumbing">Plumbing</option>
                            <option value="python">Python</option>
                            <option value="rails">Rails</option>
                            <option value="react">React</option>
                            <option value="repair">Kitchen Repair</option>
                            <option value="ruby">Ruby</option>
                            <option value="ui">UI Design</option>
                            <option value="ux">User Experience</option>
                        </select>
                    </div> -->

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm pull-left" data-dismiss="modal">Cancel</button>
                    <a href="#" style="color:white;" class="btn btn-sm btn-primary" id="CreateFormCOGS" pcrid="">
                        Create Form COGS
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('myscript')
<script>
$('.view-cogs').popover({
    trigger: 'focus'
})

$('#CreateFormCOGS').on("click", function(e) {
    pcrid = $(this).attr("pcrid");
    if ($('#PNReference').val() == "") {
        $('#PNReference').val(" ");
        // $('#PNReference').val($('#COGSID').val())
    }
    Swal.fire({
        title: 'Create Form COGS',
        html: "Apakah yakin ingin membuat form baru pada PCRID <br><b>" + pcrid + "</b> ? ",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        width: '600px'
    }).then((result) => {
        if (result.isConfirmed) {
            $("#AddCOGS").submit();
            return true
        }
    })
});

$(".NumberInputComma").on("keypress", function(evt) {
    if (evt.which != 8 && evt.which != 0 && evt.which != 46 && evt.which < 48 || evt.which >
        57) {
        evt.preventDefault();
    };
});

$(".NumberInputComma").on("input", function(evt) {
    if ($(this).val().replace(/[^.]/g, "").length == 1) {
        var decimalPlace = $(this).val().split(".")[1];
        if (decimalPlace.length > 3) {
            original = $(this).val().slice(0, -1);
            console.log(original);
            $(this).val(original);
        }
    }
    if ($(this).val().replace(/[^.]/g, "").length > 1) {
        original = $(this).val().slice(0, -1);
        $(this).val(original);
    }
});

$(".NumberInput").on("keypress", function(evt) {
    console.log(evt.which);
    if (evt.which != 8 && evt.which != 0 && (evt.which < 48 || evt.which > 57)) {
        evt.preventDefault();
    }
});



$(document).ready(function() {

    $(".right_col").each(function() {
        $.each(this.attributes, function() {
            if (this.specified) {
                if (this.name == "style") {
                    this.value = "min-height : auto;";
                }
            }
        });
    });

    $('#datatable-visibility-pcr').DataTable({
        stateSave: true,
        dom: 'Bfrtip',
        scrollCollapse: true,
        paging: true,
        scrollY: '450px',
        buttons: [
            'colvis', 'copy', 'csv', 'excel', [{
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            }], 'print', {
                className: `btn-refresh-table-pcr`,
                text: '<i class="fa fa-refresh"></i>',
                titleAttr: 'Refresh APCR',
            },
        ],
    });

    $(document).on('click', '.btn-refresh-table-pcr', function() {
        $(".btn-refresh-table-pcr").addClass('disabled');
        $(".btn-refresh-table-pcr").attr('disabled', 'disabled');;
        $(".btn-refresh-table-pcr").html(`<i class="fa fa-refresh fa-spin"></i>`);
        window.location = `{{ url('cogs-refresh-apcr') }}`
    });



    $('.create-cogs').on("click", function(e) {
        var pcrid = $(this).attr("pcrid");

        function first() {
            return $.ajax({
                url: "{{url('cogs-search-apcr')}}",
                data: {
                    pcrid: pcrid,
                },
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#COGSID').val(data.COGSID);
                    $('#PCRID').val(data.APCR.PCRID);
                    $('#CreateFormCOGS').attr("pcrid", data.APCR.PCRID);
                    $('#Opportunity').val(data.APCR.Opportunity);
                    $('#PICTriatra').val(data.APCR.PIC);
                }
            });
        }

        function second() {
            return $.ajax({
                url: "{{url('cogs-search-calculation-type')}}",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#CalculationType').html('');
                    $.each(data.CalculationType, (i, val) => {
                        $('#CalculationType').append(`<option value="` + val +
                            `">` + val + `</option>`)
                    });
                    $('select#CalculationType option[value="' + data.CalculationType[0] +
                        '"]').attr("selected", true);
                }
            });
        }

        function third() {
            return $.ajax({
                url: "{{url('cogs-search-product-category-manual')}}",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#ProductCategory').html('');
                    $.each(data.ProductCategory, (i, val) => {
                        $('#ProductCategory').append(`<option value="` + val
                            .CategoryName + `">` + val.CategoryName +
                            `</option>`
                        )
                    });
                    $('select#ProductCategory option[value="' + data.ProductCategory[0]
                        .CategoryName +
                        '"]').attr("selected", true);
                }
            });
        }


        first().then(second).then(third);
        $('#htmlPNReference').html(
            '<input id="PNReference" name="PNReference" class="form-control" type="text" value="">'
        )

        $('#CostEstimator').val($('#UserName').attr("value"));
        $('#MarketingDeptHead').val('Bagus Yuniarto ');
        $('#SCMDivisionHead').val('Christian Djaya Djasmin ');
        $('#modalAddCOGS').modal('show');
        $("textarea").each(function() {
            this.setAttribute("style", "resize:none");
        });

        $('#CalculationType').on('change', function(e) {
            var optionSelected = $("option:selected", this);
            var valueSelected = this.value;
            if (valueSelected == 'New PN') {
                $.ajax({
                    url: "{{url('cogs-search-product-category-manual')}}",
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        $('#ProductCategory').html('');
                        $.each(data.ProductCategory, (i, val) => {
                            $('#ProductCategory').append(`<option value="` +
                                val.CategoryName +
                                `">` + val.CategoryName +
                                `</option>`
                            )
                        });
                        $('select#ProductCategory option[value="' + data
                            .ProductCategory[0]
                            .CategoryName +
                            '"]').attr("selected", true);
                    }
                });
                $('#htmlPNReference').html(
                    '<input id="PNReference" name="PNReference" class="form-control" type="text" value="">'
                );
            }
            if (valueSelected == 'Repeat Order') {
                $.ajax({
                    url: "{{url('cogs-search-product-category-auto')}}",
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        $('#ProductCategory').html('');
                        $.each(data.ProductCategory, (i, val) => {
                            $('#ProductCategory').append(`<option value="` +
                                val.Category + `">` + val.Category +
                                `</option>`
                            )
                        });
                        $('select#ProductCategory option[value="' + data
                            .ProductCategory[0]
                            .Category +
                            '"]').attr("selected", true);
                        $('#htmlPNReference').html(`<select id="PNReference" name="PNReference" class="form-control" style="width: 100%" required>
                        <option value=""></option> </select>`);
                        $('#PNReference').html('');
                        $.ajax({
                            url: "{{url('cogs-search-bom')}}",
                            data: {
                                category: data.ProductCategory[0].Category
                            },
                            type: "GET",
                            dataType: "JSON",
                            success: function(data) {
                                htmlPNReferenceOption = ``;
                                $.each(data.PNReference, (i, val) => {
                                    htmlPNReferenceOption += (`
                                <option value="` + val.Material + `">` + val.Material + ` (` + val.Description +
                                        `)</option>`)
                                });
                                $('#PNReference').html(
                                    htmlPNReferenceOption);
                            }
                        });
                    }
                });
            }
            $('#ProductCategory').on('change', function(e) {
                if ($('#CalculationType').val() == 'New PN') {
                    $('#htmlPNReference').html(
                        '<input id="PNReference" name="PNReference" class="form-control" type="text" value="">'
                    );
                }
                if ($('#CalculationType').val() == 'Repeat Order') {
                    $('#htmlPNReference').html(
                        `<select id="PNReference" name="PNReference" class="form-control" style="width: 100%" required> <option value=""></option></select>`
                    );
                    $('#PNReference').html('');
                    $.ajax({
                        url: "{{url('cogs-search-bom')}}",
                        data: {
                            category: this.value
                        },
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                            htmlPNReferenceOption = ``;
                            $.each(data.PNReference, (i, val) => {
                                htmlPNReferenceOption += (`
                                    <option value="` + val.Material + `">` + val.Material + ` (` + val.Description +
                                    `)</option>`)
                            });
                            $('#PNReference').html(
                                htmlPNReferenceOption);
                        }
                    });
                }
            });
        });
    });

    $('.delete-cogs').on("click", function(e) {
        e.preventDefault();
        var cogsid = $(this).attr("cogsid");
        var menu = $(this).attr("menu");
        $.ajax({
            url: "{{url('cogs-search-cogs-header')}}",
            data: {
                cogsid: cogsid,
            },
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#COGSIDdelete').val(data.CogsHeader[0].ID);
                $('#PCRIDdelete').val(data.CogsHeader[0].PCRID);
                $('#CategoryNamedelete').val(data.CogsHeader[0].ProductCategory);
                $('#Menu').val(menu);
                $('#message').html(
                    "Anda yakin ingin menghapus data COGS pada PCRID : <b>" +
                    data.CogsHeader[0].PCRID + "</b> ?");
                $('#delete').modal('show');
            }
        });
    });

    $('.card-box').attr({
        'style': 'overflow-x: scroll !important;',
    });
});
</script>
@endsection
