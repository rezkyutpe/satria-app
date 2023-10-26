{{-- Modal Chat PO Tracking --}}
<div class="modal fade" id="chatings" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="width: 90%; margin: 0 auto;">
            <div class="containers">
                <div class="modal-header">
                    <p class="modal-title" id="header"></p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="messaging">
                    <div class="inbox_msg">
                        <div class="mesgs">
                            <div class="msg_history"  id="chat-content">
                                <div id="datachat"></div>
                            </div>
                            <div class="type_msg">
                                <div class="input_msg_write">
                                    <input type="hidden" class="form-control" name="numbers" id="numbers">
                                    <input type="hidden" class="form-control" name="items" id="items">
                                    <input type="hidden" class="form-control" name="namer" id="names">
                                    <input type="hidden" class="form-control" name="proc" id="proc">
                                    <input type="hidden" class="form-control" name="vendors" id="vendors">
                                    <div id="datasubmit"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Plotting Stock-->
<div class="modal fade" id="plottingStockModal" tabindex="-1" role="dialog" aria-labelledby="plottingStockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="plottingStockModalLabel"><span id="dataMaterial"></span></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table nowrap table-striped text-center" style="width: 100%">
                    <thead class="thead-dark">
                        <tr>
                            <th>No.</th>
                            <th>Production Order</th>
                            <th>Product Number</th>
                            <th>Req. Date</th>
                            <th>Req. Qty</th>
                            <th>Ploting Stock</th>
                            <th>Sisa Stock</th>
                        </tr>
                    </thead>
                    <tbody id="detailPlotting"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Stock-->
<div class="modal fade" id="StockModal" tabindex="-1" role="dialog" aria-labelledby="StockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="StockModalLabel"><span id="dataMaterialStock"></span></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table nowrap table-striped text-center" style="width: 100%">
                    <thead class="thead-dark">
                        <tr>
                            <th>No.</th>
                            <th>Plant</th>
                            <th>Storage Location</th>
                            <th>Stock</th>
                        </tr>
                    </thead>
                    <tbody id="detailStock"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>  

<!-- Modal Minus / Shortage-->
<div class="modal fade" id="modalMinus" tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><span id="judulMinus"></span></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table nowrap table-striped text-center" style="width: 100%">
                        <thead class="thead-dark">
                            <tr>
                                <th>No.</th>
                                <th>Production Order</th>
                                <th>Product Number</th>
                                <th>Description</th>
                                <th>Req. Date</th>
                                <th>Req. Qty</th>
                                <th>Good Issue</th>
                                <th>Reserve</th>
                                <th>Minus</th>
                            </tr>
                        </thead>
                        <tbody id="detailDataMinus"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail PRO -->
<div class="modal fade" id="detailPROModal" tabindex="-1" role="dialog" aria-labelledby="detailPROModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailPROModalLabel">Production Order Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="proDetail"></div>
        </div>
    </div>
</div>