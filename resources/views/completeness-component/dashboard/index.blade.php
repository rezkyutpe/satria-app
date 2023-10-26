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
                    <h2><a href="{{ url('completeness-component') }}">{{ $data['title'] }}</a></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    
                    <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" id="gPbyStatus-tab" data-toggle="tab" href="#gPbyStatus" role="tab" aria-controls="gPbyStatus" aria-selected="true">Group Product by Status</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="goodIssue-tab" data-toggle="tab" href="#goodIssue" role="tab" aria-controls="goodIssue" aria-selected="false">Percentage GI</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="gPbyStatus" role="tabpanel" aria-labelledby="gPbyStatus-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="gpStatus" style="min-width: 310px; min-height: 450px; margin: 0 auto"></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="goodIssue" role="tabpanel" aria-labelledby="goodIssue-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="percentageGI" style="min-width: 310px; min-height: 450px; margin: 0 auto"></div>
                                </div>
                            </div>
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

    {{--  Group Product by Status --}}
    <script>
        // Create the chart
        var groupProductStatus = Highcharts.chart('gpStatus', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Group Product by Status (On Going)'
            },
            xAxis: {
                type        : 'category',
                title : {
                    text    : 'Group Product'
                }
            },
            yAxis: {
                min         : 0,
                title: {
                    text    : 'Total Serial Number'
                }
            },
            plotOptions: {
                series: {
                    stacking                    : 'normal',
                    borderWidth                 : 0,
                    dataLabels: {
                        enabled                 : true,
                        format                  : '{point.y}'
                    },
                    cursor                      : 'pointer',
                    point: {
                        events: {
                            click: function() {
                                location.href   = '{{ url('detail-product-ccr') }}?drilldown=' + this.options.drilldown;
                            }
                        }
                    }
                }
            },
            series: <?= $data['dataStatusOnGoing'] ?>
        });

        groupProductStatus.xAxis[0].labelGroup.element.childNodes.forEach(function(label)
        {
            label.style.cursor    = "pointer";
            label.onclick = function(){
                var product       = this.textContent.replace("&", "^");
                location.href     = '{{ url('detail-product-ccr') }}?groupProduct=' + product;
            }
        });
    </script>

    <script>
        Highcharts.Tick.prototype.drillable = function () {};
        // Create the chart
        var giPercentage = Highcharts.chart('percentageGI', {
            chart: {
                type: 'column',
                events: {
                    drilldown: function(e) {
                        this.setTitle({ text: 'Percentage Good Issue Material Type' });
                        this.xAxis[0].setTitle({ text: 'Material Type' });
                        this.yAxis[0].setTitle({ text: 'Percentage' });
                        this.options.legend["enabled"] = false;
                    },
                    drillup: function(e) {
                        this.setTitle({ text: 'Group Product by Status ( on Going )' });
                        this.xAxis[0].setTitle({ text: 'Group Product' });
                        this.yAxis[0].setTitle({ text: 'Total Serial Number' });
                        this.options.legend["enabled"] = true;
                    }
                }
            },
            title: {
                text: 'Group Product by Status ( on Going )'
            },
            xAxis: {
                type: 'category',
            },
            yAxis: {
                title: {
                    text: 'Total Serial Number'
                }
            },
            breadcrumbs: {
                floating: true,
                position: {
                    align: 'right'
                }
            },

            plotOptions: {
                series: {
                    stacking: 'normal',
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        style: {
                            color: 'black',
                            textShadow: '0 0 3px white, 0 0 3px white'
                        }
                    },
                    cursor: "pointer",
                    point: {
                        events: {
                            click: function () {
                                if (this.options != null) {
                                    location.href = this.options.url;
                                }
                            }
                        }
                    }
                }
            },
            series: <?= $data['dataStatusOnGoing'] ?>,
            drilldown: {
                series: <?= $data['dataGIOnGoing'] ?>
            }
        });
    </script>

@endsection