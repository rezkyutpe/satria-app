@extends('cogs.panel.master')
@section('content')


<div class="clearfix"></div>
<div class="">
    <div class="page-title">
        <div class="title_left">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent; font-size: 24px;">
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="cogs-product-classification" onmouseover="this.style.textDecoration='underline';"
                            onmouseout="this.style.textDecoration='none';">{{ $data['title'] }}</a>
                        <p class="d-none" id="UserName" value="{{ Auth::user()->name }}"></p>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="title_right">
            <div class="col-md-5 col-sm-5  form-group pull-right top_search">
                <div class="input-group">
                    <input id="input-category" type="text" class="form-control" style="height:38px"
                        placeholder="Search..." 
                        onkeyup="actionInput()"
                        >
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2> {{ $data['subtitle_product'] }} </h2>
                    <div class="nav pull-right" role="button">
                        <li><a class="collapse-link p-0"><i class="fa fa-chevron-up"></i></a>
                        </li>
                    </div>
                    <h6>
                        <a href="#" style="color:white;"
                            class="badge badge-primary pull-right mx-3 my-1 create-cogs">
                            <i class="fa fa-plus fa-xs"></i> Calculate
                        </a>
                    </h6>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div id="data-category" class="row">
                    </div>
                </div>
            </div>

        </div>
    </div>
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
                            <label class="my-2">PN Reference *</label>
                        </div>
                        <div class="col-4" id="htmlPNReference">
                            <input id="PNReference" name="PNReference" class="form-control" type="text" value="">
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
                            <label class="my-2">Cost Estimator </label>
                        </div>
                        <div class="col-4">
                            <input id="CostEstimator" name="CostEstimator" class="form-control" type="text" value=""
                                readonly>
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
                            <label class="my-2">Marketing Dept Head </label>
                        </div>
                        <div class="col-4">
                            <input id="MarketingDeptHead" name="MarketingDeptHead" class="form-control" type="text"
                                value="" readonly>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-2">
                            <label class="my-2">Calculation Type *</label>
                        </div>
                        <div class="col-4">
                            <select id="CalculationType" name="CalculationType" class="form-control"
                                style="width: 100%">
                                
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-2">
                            <label class="my-2">SCM Division Head </label>
                        </div>
                        <div class="col-4">
                            <input id="SCMDivisionHead" name="SCMDivisionHead" class="form-control" type="text" value=""
                                readonly>
                        </div>
                    </div>

                    <div class="row mt-2">
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
$('#CreateFormCOGS').on("click", function(e) {
    if ($('#PNReference').val() == "") {
        $('#PNReference').val(" ");
        // $('#PNReference').val($('#COGSID').val())
    }
    Swal.fire({
        title: 'Create Form COGS',
        html: "Apakah yakin ingin membuat form baru ? ",
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



function actionInput() {
    req = $("#input-category").val();
    req == "" ? req = "all" : req
    if (req) {
        ajaxSearch(req);
    }
}

function capitalizeTheFirstLetterOfEachWord(words) {
    var separateWord = words.toLowerCase().split(' ');
    for (var i = 0; i < separateWord.length; i++) {
        separateWord[i] = separateWord[i].charAt(0).toUpperCase() +
            separateWord[i].substring(1);
    }
    return separateWord.join(' ');
}

function ajaxSearch(req) {
    $.ajax({
            method: "GET",
            dataType: "JSON",
            url: "{{ url('cogs-serch-list') }}",
            data: {
                req: req
            },
        })
        .done(function(data) {
            console.log(data);
            htmlCategory = ``;
            htmlCE = ``;
            dataCategory = data.category;
            dataCE = data.cost_estimator;
            for (i = 0; i < dataCategory.length; i++) {
                No = i + 1;
                CategoryName = dataCategory[i].CategoryName;
                CategoryLink = CategoryName.replaceAll(' ', '')
                htmlCategory +=
                    `<div class="col-md-55">
                        <div class="thumbnail">
                            <div style="height: 60px" class="image view view-first">
                                <a href={{ url('cogs-list-detail/` + CategoryLink + `') }}>
                                    <img style="width: 200px; display: block; margin-left: auto; margin-right: auto;"
                                        src="https://www.patria.co.id/images/patria.png" alt="image" />
                                </a>
                            </div>
                            <div class="caption p-0">
                                <a class="link-category" href={{ url('cogs-list-detail/` +
                    CategoryLink + `') }}>
                                    <div class="caption">
                                        <p><strong>` + No + `. </strong> ` + CategoryName + ` </p>
                                        <p>(` + CategoryName + `)</p>
                                    </div> 
                                </a> 
                            </div> 
                        </div> 
                    </div>`;
            };
            
            $("#data-category").html(htmlCategory);
            $("#data-ce").html(htmlCE);
        });
};

$(document).ready(function() {
    actionInput();

    $('.create-cogs').on("click", function(e) {
    
            function first() {
                return  $.ajax({
                    url: "{{url('cogs-search-cogspn')}}",
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        // alert('succes');
                        // console.log(data);
                        $('#COGSID').val(data.COGSID);
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

});
</script>
@endsection
