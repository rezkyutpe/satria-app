@extends('cogs.panel.master')
@section('content')
<div class="container-fluid" style="margin-top: 20%!important;">

    <div class="row">
        <div class="col">
            <div class="row">
                <div class="col">
                    <!-- <a href=" #"> -->
                    <!-- {{ url($link_newPO ?? '#') }} -->
                    <div class="card baseBlock" style="background: #01116b; height: 152.5%!important;">
                        <div class="card-body text-white">
                            <div class="media d-flex" style="position: relative">
                                <div class="media-body text-left">
                                    <h1>{{ $data['CountPopulation'] }}</h1>
                                    <h3 style="font-weight: bold;">Population </h3>
                                </div>
                                <div class="cardicons">
                                    <i class="fa fa-inbox fa-5x"></i>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="card-footer bg-white text-center">
                        <a href="{{ url('polocalnewpo') }}">Show Data
                        </a>
                    </div> -->
                    </div>
                    <!-- </a> -->
                </div>
            </div>
        </div>
        <div class="col">
            <div class="row">
                <div class="col">
                    <!-- <a href="#"> -->
                    <!-- {{ url($link_newPO ?? '#') }} -->
                    <div class="card baseBlock h-75" style="background: rgb(10, 110, 1);">
                        <div class="card-body text-white">
                            <div class="media d-flex" style="position: relative">
                                <div class="media-body text-left">
                                    <h3>{{ $data['CountJakarta'] }}</h3>
                                    <h5 style="font-weight: bold;">Jakarta</h5>
                                </div>
                                <div class="cardicons">
                                    <i class="fa fa-send fa-4x"></i>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="card-footer bg-white text-center">
                        <a href="{{ url('polocalnewpo') }}">Show Data
                        </a>
                    </div> -->
                    </div>
                    <!-- </a> -->
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <!-- <a href="#"> -->
                    <!-- {{ url($link_newPO ?? '#') }} -->
                    <div class="card baseBlock h-75" style="background: rgb(139, 7, 2);">
                        <div class="card-body text-white">
                            <div class="media d-flex" style="position: relative">
                                <div class="media-body text-left">
                                    <h3>{{ $data['CountKalsel'] }}</h3>
                                    <h5 style="font-weight: bold;">Kalsel</h5>
                                </div>
                                <div class="cardicons">
                                    <i class="fa fa-send fa-4x"></i>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="card-footer bg-white text-center">
                        <a href="{{ url('polocalnewpo') }}">Show Data
                        </a>
                        </div> -->
                    </div>
                    <!-- </a> -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>{{-- $data['title'] --}}</h2>

                <div class="clearfix"></div>
            </div>
            <div class="x_content">

            </div>
        </div>
    </div>
</div> -->
@endsection

@section('myscript')
@endsection
