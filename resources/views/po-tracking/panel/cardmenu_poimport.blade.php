<div class="container-fluid">
    <div class="row">
        @if ($actionmenu->c== 1 || $actionmenu->u== 1 || $actionmenu->r== 1 || $actionmenu->v== 1 || $actionmenu->d== 1)
        <div class="col">
            <a href="{{ url($link_newPO ?? '#') }}">
                <div class="card baseBlock h-75" style="cursor:pointer; background: rgb(139, 7, 2);">
                    <div class="card-body text-white">
                        <div class="media d-flex" style="position: relative">
                            <div class="media-body text-left">
                            <h3>{{ $countNewpoImport ?? 0 }} <div style="display: inline-block; font-size:1rem">Items</div></h3>
                            <h5 style="font-weight: bold;">New PO </h5>
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
            <a href="{{ url($link_ongoing ?? '#') }}">
                <div class="card baseBlock h-75" style="background: #01116b;cursor:pointer">
                    <div class="card-body text-white">
                        <div class="media d-flex" style="position: relative">
                            <div class="media-body text-left">
                            <h3>{{ $countongoingpoImport ?? 0 }} <div style="display: inline-block; font-size:1rem">Items</div> </h3>
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
        @endif
        @if ($actionmenu->c== 1 || $actionmenu->u== 1 || $actionmenu->r== 1 || $actionmenu->v== 1 || $actionmenu->d== 1)
        <div class="col">
            <a href="{{ url($link_historyPO ?? '#') }}">
                <div class="card baseBlock h-75" style="background: rgb(2, 128, 111);cursor:pointer">
                    <div class="card-body text-white">
                        <div class="media d-flex" style="position: relative">
                            <div class="media-body text-left">
                            <h3>{{ $countHistorypoImport ?? 0 }} <div style="display: inline-block; font-size:1rem">Items</div> </h3>
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
        @endif
    </div>
</div>
