<div class="container-fluid">
    <div class="row">

        <div class="col">
            <a href="{{ url($link_newPO ?? '#') }}" >
            <div class="card baseBlock h-75" style="cursor:pointer; background: rgb(139, 7, 2);">
                <div class="card-body text-white">
                    <div class="media d-flex" style="position: relative">
                        <div class="media-body text-left">
                        <h3>{{ $countNewpoPolocal ?? 0 }} <div style="display: inline-block; font-size:1rem">Items</div> </h3>
                        <h5 style="font-weight: bold;">New PO</h5>
                        </div>
                        <div class="cardicons">
                        <i class="fa fa-suitcase fa-4x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        </div>

        <div class="col">
            <a href="{{ url($link_ongoing ?? '#') }}" >
            <div class="card baseBlock h-75" style="background: #01116b;cursor:pointer">
                <div class="card-body text-white">
                    <div class="media d-flex" style="position: relative">
                        <div class="media-body text-left">
                        <h3>{{$countOngoingPolocal ?? 0}} <div style="display: inline-block; font-size:1rem">Items</div> </h3>
                        <h5 style="font-weight: bold;">PO Ongoing</h5>
                        </div>
                        <div class="cardicons">
                        <i class="fa fa-road fa-4x"></i>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>

        <div class="col">
            <a href="{{ url($link_planDelivery ?? '#') }}" >
            <div class="card baseBlock h-75" style="background: rgb(141, 81, 2);cursor:pointer">
                <div class="card-body text-white">
                    <div class="media d-flex" style="position: relative">
                        <div class="media-body text-left">
                        <h3>{{$countplanDelivery ?? 0}} <div style="display: inline-block; font-size:1rem">Items</div> </h3>
                        <h5 style="font-weight: bold;">Plan Delivery</h5>
                        </div>
                        <div class="cardicons">
                        <i class="fa fa-send fa-4x"></i>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
        <div class="col">
           <a href="{{ url($link_readyToDelivery ?? '#') }}" >
            <div class="card baseBlock h-75" style="background: rgb(10, 110, 1);cursor:pointer">
                <div class="card-body text-white">
                    <div class="media d-flex" style="position: relative">
                        <div class="media-body text-left">
                        <h3>{{$countreadyToDelivery ?? 0}} <div style="display: inline-block; font-size:1rem">Items</div> </h3>
                        <h5 style="font-weight: bold;">Ready to Delivery</h5>
                        </div>
                        <div class="cardicons">
                            <i class="fa fa-plane fa-5x"></i>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>

        <div class="col">
            <a href="{{ url($link_historyPO ?? '#') }}" >
            <div class="card baseBlock h-75" style="background: rgb(2, 128, 111);cursor:pointer">
                <div class="card-body text-white">
                    <div class="media d-flex" style="position: relative">
                        <div class="media-body text-left">
                        <h3>{{$countHistoryPolocal ?? 0}} <div style="display: inline-block; font-size:1rem">Items</div> </h3>
                        <h5 style="font-weight: bold;">History PO</h5>
                        </div>
                        <div class="cardicons">
                        <i class="fa fa-clock-o fa-4x"></i>
                        </div>
                    </div>
                </div>
            </div>
            </a>

        </div>
    </div>
</div>
