@extends('completeness-component.panel.master')


@section('mycss')
    <style>
        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 100%;
            max-width: 100%;
            margin: 1em auto;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #ebebeb;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }
    </style>
@endsection

@section('contents')
    <div class="row">
        <div class="col-md-12 col-sm-12">
            @if (session()->has('err_message'))
                <div class="alert alert-danger alert-dismissible" role="alert" auto-close="120">
                    <strong>Error! </strong>{{ session()->get('err_message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (session()->has('suc_message'))
                <div class="alert alert-success alert-dismissible" role="alert" auto-close="120">
                    <strong>Success! </strong>{{ session()->get('suc_message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ $data['title'] }}<small></small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-md-12">
                            <figure class="highcharts-figure">
                                <div id="bar-productGroup"></div>
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('myscript')
    <script src="{{ asset('public/assetss/js/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('public/assetss/js/highcharts/data.js') }}"></script>
    <script src="{{ asset('public/assetss/js/highcharts/drilldown.js') }}"></script>
    <script src="{{ asset('public/assetss/js/highcharts/exporting.js') }}"></script>
    <script src="{{ asset('public/assetss/js/highcharts/export-data.js') }}"></script>
    <script src="{{ asset('public/assetss/js/highcharts/accessibility.js') }}"></script>
    <script>
        Highcharts.chart('bar-productGroup', {

            chart: {
                backgroundColor: '#e4ebf1',
                polar: true,
                type: 'column'
            },

            title: {
                text: 'Total Product On Going by Group Product'
            },

            xAxis: {
                type: 'category'
            },

            yAxis: {
                title: {
                    text: 'Total PRO On Going'
                }

            },

            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}'
                    },
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function() {
                                location.href = '{{ url('detail-product-ccr') }}?groupProduct=' + this.options
                                    .key;
                            }
                        }
                    }
                }
            },

            legend: {
                enabled: false
            },
            series: [{
                name: "Quantity",
                colorByPoint: true,
                data: <?= $data['chart'] ?>
            }]
        });
    </script>
@endsection
