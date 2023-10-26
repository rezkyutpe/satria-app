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
                    </li>
                </ol>
            </nav>
        </div>
        <div class="title_right">
            <div class="col-md-5 col-sm-5  form-group pull-right top_search">
                <div class="input-group">
                    <input id="input-category" type="text" class="form-control" style="height:38px"
                        placeholder="Search..." onkeyup="actionInput()">
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
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div id="data-category" class="row">
                    </div>
                </div>
            </div>

            <div class="x_panel">
                <div class="x_title">
                    <h2> {{ $data['subtitle_pic'] }} </h2>
                    <div class="nav pull-right" role="button">
                        <li><a class="collapse-link p-0"><i class="fa fa-chevron-up"></i></a>
                        </li>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div id="data-ce" class="row">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('myscript')
<script>
$(document).ready(function() {
    actionInput();
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
            url: "{{ url('cogs-serch-product-classification') }}",
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
                                <a href={{ url('cogs-product-classification-detail/` + CategoryLink + `') }}>
                                    <img style="width: 200px; display: block; margin-left: auto; margin-right: auto;"
                                        src="https://www.patria.co.id/images/patria.png" alt="image" />
                                </a>
                            </div>
                            <div class="caption p-0">
                                <a class="link-category" href={{ url('cogs-product-classification-detail/` +
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
            for (i = 0; i < dataCE.length; i++) {
                No = i + 1;
                CEName = capitalizeTheFirstLetterOfEachWord(dataCE[i].CostEstimator);
                CELink = CEName.replaceAll(' ', '')
                htmlCE +=
                    `<div class="col-md-55">
                        <div class="thumbnail">
                            <div style="height: 60px" class="image view view-first">
                                <a href={{ url('cogs-product-classification-detail/` + CELink + `') }}>
                                    <img style="width: 200px; display: block; margin-left: auto; margin-right: auto;"
                                        src="https://www.patria.co.id/images/patria.png" alt="image" />
                                </a>
                            </div>
                            <div class="caption p-0">
                                <a class="link-category" href={{ url('cogs-product-classification-detail/` +
                    CELink + `') }}>
                                    <div class="caption">
                                        <p><strong>` + No + `. </strong> ` + CEName + ` </p>
                                        <p>(` + CEName + `)</p>
                                    </div> 
                                </a> 
                            </div> 
                        </div> 
                    </div>`;
            }
            $("#data-category").html(htmlCategory);
            $("#data-ce").html(htmlCE);
        });
};
</script>
@endsection
