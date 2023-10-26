@extends('po-tracking.panel.master')
@section('content')

<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Report PR Create to PO Release
                </h2>
                <div class="clearfix"></div>
            </div>

            @if(session()->has('err_message'))
                <div class="alert alert-danger alert-dismissible" role="alert" auto-close="10000">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ session()->get('err_message') }}
                </div>
            @endif
            @if(session()->has('suc_message'))
                <div class="alert alert-success alert-dismissible" role="alert" auto-close="10000">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ session()->get('suc_message') }}
                </div>
            @endif
            <div class="x_content">
                <div class="well" style="overflow: auto;">
                    <form action="{{url('report-pr-po')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-sm-12 mb-3">
                                <div class="col-md-1 mt-2">
                                    <strong>Month :</strong>
                                </div>
                                <div class="col-md-5">
                                    <select name="month" class="form-control select2">
                                        <option value=""></option>
                                        <?php
                                        for($i = 1; $i < 13; $i++){
                                        echo "<option >$i</option>" ;
                                        }
                                    ?>
                               </select> </div>
                                <div class="col-md-1">
                                    <strong>Years :</strong>
                                </div>
                                <div class="col-md-5">
                                    <select name="years" id="" class="form-control select2">
                                        <option value=""></option>
                                            <?php
                                            for($i = date('Y') ; $i >= 2019; $i--){
                                            echo "<option >$i</option>" ;
                                            }
                                        ?>
                                   </select>
                                 </div>
                            </div>
                            <div class="col-sm-12 mb-3">
                                <div class="col-md-1 mt-2">
                                    <strong>PR Number:</strong>
                                </div>
                                <div class="col-md-5">
                                    <select name="prnumber[]"  class="form-control select2" multiple="multiple" style="width: 100%">
                                        <option value="" readonly></option>
                                        @foreach ($prnumber as $item)
                                        <option value="{{ $item->PRNumber }}">{{ $item->PRNumber }}</option>
                                        @endforeach
                                    </select>
                                 </div>
                                <div class="col-md-1">
                                    <strong>PO Type :</strong>
                                </div>
                                <div class="col-md-5">
                                    <select name="potype[]" class="form-control select2" multiple="multiple" style="width: 100%">
                                        <option value="" readonly></option>
                                        @foreach ($type as $item)
                                        <option value="{{ $item->Type }}">{{ $item->Type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 mb-3">
                                <div class="col-md-1 mt-2">
                                    <strong>Search By:</strong>
                                </div>
                                <div class="col-md-5">
                                    <select name="searchby" class="form-control select2">
                                        <option >PR Create</option>
                                        <option >PR Release</option>
                                        <option >PO Create</option>
                                        <option >PO Release</option>
                                    </select>
                                 </div>

                                <div class="col-md-5">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <a href="{{ url('report-pr-po?reset=1') }}" class="btn btn-danger">Reset</a>
                                </div>
                            </div>
                            </div>

                     </form>
                    </div>
                <div class="row">
                    <div class="card-box table-responsive">
                        <table id="datatable-responsive" class="table text-center table-striped table-bordered dt-responsive" cellspacing="0" width="99%">
                            <thead>
                                <tr>
                                <th style="width: 5%">PO Number</th>
                                <th style="width: 5%">PO Type</th>
                                <th style="width: 5%">PR Number</th>
                                <th style="width: 5%">PR Item</th>
                                <th style="width: 5%">PR Create Date</th>
                                <th style="width: 5%">PR Release Date</th>
                                <th style="width: 5%">PO Create Date</th>
                                <th style="width: 5%">PO Release Date</th>
                                <th style="width: 5%">PR Create to PR Release</th>
                                <th style="width: 5%">PR Release to PO Create</th>
                                <th style="width: 5%">PO Create to PO Release</th>
                                <th style="width: 5%">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($getData as $k=>$item)
                                <tr>
                                    <td><a href="#" class="text-dark detailspo"  data-toggle="modal"  data-number="{{ $item->Number }}" >{{ $item->Number }}</a></td>
                                    <td>{{ $item->Type }}</td>
                                    <td><a href="#" class="text-dark detailspr"  data-toggle="modal"  data-number="{{ $item->PRNumber }}" >{{ $item->PRNumber }}</a></td>
                                    <td>{{ $item->PRItem }}</td>
                                    <td>{{ date('d-m-Y', strtotime($item->PRCreateDate)); }}</td>
                                    <td>{{ date('d-m-Y', strtotime($item->PRReleaseDate)); }}</td>
                                    <td>{{ date('d-m-Y', strtotime($item->Date)); }}</td>
                                    <td>{{ date('d-m-Y', strtotime($item->ReleaseDate)); }}</td>
                                    @foreach ($datasla as $k=>$items)
                                    @if ($items->prcreatetoprrel < $item->PRCreateToPRRelease)
                                    <td style="background-color:#f73a4c; color:#fff">{{ $item->PRCreateToPRRelease }}</td>
                                    @else
                                    <td >{{ $item->PRCreateToPRRelease }}</td>
                                    @endif
                                    @if ($items->prtopocreate < $item->PRReleaseToPOCreate)
                                    <td style="background-color:#f73a4c; color:#fff">{{ $item->PRReleaseToPOCreate }}</td>
                                    @else
                                    <td >{{ $item->PRReleaseToPOCreate }}</td>
                                    @endif
                                    @if ($items->pocreatetoporel < $item->POCreateToPORelease)
                                    <td style="background-color:#f73a4c; color:#fff">{{ $item->POCreateToPORelease }}</td>
                                    @else
                                    <td >{{ $item->POCreateToPORelease }}</td>
                                    @endif
                                    @php
                                        $totaleadtime = $items->prcreatetoprrel + $items->prtopocreate + $items->pocreatetoporel ;
                                    @endphp
                                    @if ($totaleadtime < $item->TotalLeadtime)
                                    <td style="background-color:#f73a4c; color:#fff">{{ $item->TotalLeadtime }} days</td>
                                    @else
                                    <td>{{ $item->TotalLeadtime }} days</td>
                                    @endif

                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('top_javascript')
    <script src="{{ asset('public/assetss/datatable/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('public/assetss/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('public/assetss/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('public/assetss/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('public/assetss/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>
     {{-- Modal DetailPo  --}}
     <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable-responsive').DataTable({
                dom: 'Bfrtip',
                fixedHeader                     : true,
                scrollCollapse                  : true,
                columnDefs: [{
                    visible                     : false
                }],
                buttons: [
                    'pageLength','colvis',
                    <?php if ($action_menu->r==1 || $action_menu->u==1|| $action_menu->d==1|| $action_menu->v==1){ ?>
                    {
                        text: 'Download',
                        action: function ( e, dt, node, config ) {
                            window.location.href = "{{ url('report-prpo-open-download') }}";
                        }
                    }
                    <?php } ?>
                ],
                select                          : true,
                stateSave: true,
                drawCallback: function(settings) {
                    $(".right_col").css("min-height", "");
                    $(".child_menu").css("display", "none");
                    $("#sidebar-menu li").removeClass("active");
                },
            });
        });
     </script>
    @include('po-tracking.master.modal.DetailPO')
    @include('po-tracking.master.modal.DetailPR')
@endsection
