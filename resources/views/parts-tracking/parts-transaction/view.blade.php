@extends('panel.master')

@section('css')

<link href="{{ asset('public/assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<style>
    a.disabled {
        pointer-events: none;
        cursor: default;
    }
.panel-heading .accordion-toggle:after {
    /* symbol for "opening" panels */
    font-family: 'Glyphicons Halflings';  /* essential for enabling glyphicon */
    content: "\e114";    /* adjust as needed, taken from bootstrap.css */
    float: right;        /* adjust as needed */
    color: grey;         /* adjust as needed */
}
.panel-heading .accordion-toggle.collapsed:after {
    /* symbol for "collapsed" panels */
    content: "\e080";    /* adjust as needed, taken from bootstrap.css */
}
    a.disabled {
        pointer-events: none;
        cursor: default;
    }
    .timeline {
    position: relative;
    padding: 21px 0px 10px;
    margin-top: 4px;
    margin-bottom: 30px;
}

.timeline .line {
    position: absolute;
    width: 4px;
    display: block;
    background: currentColor;
    top: 0px;
    bottom: 0px;
    margin-left: 30px;
}

.timeline .separator {
    border-top: 1px solid currentColor;
    padding: 5px;
    padding-left: 40px;
    font-style: italic;
    font-size: .9em;
    margin-left: 30px;
}

.timeline .line::before { top: -4px; }
.timeline .line::after { bottom: -4px; }
.timeline .line::before,
.timeline .line::after {
    content: '';
    position: absolute;
    left: -4px;
    width: 12px;
    height: 12px;
    display: block;
    border-radius: 50%;
    background: currentColor;
}

.timeline .panel {
    position: relative;
    margin: 10px 0px 21px 70px;
    clear: both;
}

.timeline .panel::before {
    position: absolute;
    display: block;
    top: 8px;
    left: -24px;
    content: '';
    width: 0px;
    height: 0px;
    border: inherit;
    border-width: 12px;
    border-top-color: transparent;
    border-bottom-color: transparent;
    border-left-color: transparent;
}

.timeline .panel .panel-heading.icon * { font-size: 20px; vertical-align: middle; line-height: 40px; }
.timeline .panel .panel-heading.icon {
    position: absolute;
    left: -59px;
    display: block;
    width: 40px;
    height: 40px;
    padding: 0px;
    border-radius: 50%;
    text-align: center;
    float: left;
}

.timeline .panel-outline {
    border-color: transparent;
    background: transparent;
    box-shadow: none;
}

.timeline .panel-outline .panel-body {
    padding: 10px 0px;
}

.timeline .panel-outline .panel-heading:not(.icon),
.timeline .panel-outline .panel-footer {
    display: none;
}
</style>

@endsection

@section('content')

<div class="content-body-white">
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
    <div class="page-head">
        <div class="page-title">
            <!-- <h1>Parts Transactions </h1> -->
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

        <div class="user-profile">

      
        @php($now  = new DateTime(date('Y-m-d')))
        @php($life = new DateTime(date('Y-m-d', strtotime($data['parts']['tgl_lifetime']))))
        @php($sisa = $life->diff($now)->days)
        @php($percent = round($sisa/$data['parts']['lifetime']*100))
        <div class="row">
        <div class="col-md-5">
            <div class="user-display">

            <table>
                <tr>
                <td rowspan="3"> {!! QrCode::size(120)->generate($data['parts']['id_transaksi']); !!}</td>
                <td>&nbsp;</td>
                <td colspan="2"><img src="{{ asset('public/assets/global/img/tracking-print.png') }}" width="250"><br><strong><span style="font-size: 17px;color: #000;font-weight: bold;"> {{$data['parts']['id_transaksi'] }}</span></strong></td>

                </tr>
            </table>
            <br>
            </div>
            <div class="user-info-list panel panel-default">
            <div class="panel-heading panel-heading-divider"> {{ $data['parts']['pn_assy'] }}
                <span class="pull-right"> 
                <i class="fa fa-calendar"> </i>{{ date('d F Y', strtotime($data['parts']['tgl_transaksi'])) }}</span>
                <br>
                <span class="panel-subtitle"><strong>#</strong> {{ $data['parts']['hose_transaksi'] }}.</span><br>
                <span class="panel-subtitle"><strong>#</strong> {{ $data['parts']['konf_transaksi'] }}.</span></div>
            <div class="panel-body">
                <table class="table">
                <tbody class="no-border-x no-border-y">
                    <tr>
                    <td class="icon"><i class="fa fa-circle-o-notch"></i></td>
                    <td class="item">Diameter<span class="icon s7-portfolio"></span></td>
                    <td> : {{ $data['parts']['diameter'] }}</td>
                    </tr>
                    <tr>
                    <td class="icon"><i class="fa fa-exchange"></i></td>
                    <td class="item">Panjang Hose<span class="icon s7-gift"></span></td>
                    <td> : {{ $data['parts']['panjang'] }}</td>
                    </tr>
                    <tr>
                    <td class="icon"><i class="fa fa-wrench" aria-hidden="true"></i></td>
                    <td class="item">Fitting 1<span class="icon s7-phone"></span></td>
                    <td> : {{ $data['parts']['fitting1']." - ".$data['parts']['ukuran1'] }}</td>
                    </tr>
                    <tr>
                    <td class="icon"><i class="fa fa-wrench" aria-hidden="true"></i></td>
                    <td class="item">Fitting 2<span class="icon s7-map-marker"></span></td>
                    <td> : {{ $data['parts']['fitting2']." - ".$data['parts']['ukuran2'] }}</td>
                    </tr>
                    <tr>
                    <td class="icon"><i class="fa fa-briefcase" aria-hidden="true"></i></span></td>
                    <td class="item">Maximum Work Pressure<span class="icon s7-global"></span></td>
                    <td> : {{ $data['parts']['mwp'] }}</td>
                    </tr>
                    <tr>
                    <td class="icon"><i class="fa fa-briefcase" aria-hidden="true"></i></td>
                    <td class="item">Minimum Burh Pressure<span class="icon s7-global"></span></td>
                    <td> : {{ $data['parts']['mbp'] }}</td>
                    </tr>
                </tbody>
                </table>
            </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
            <div class="pull-right" style="margin:5px">
                <a href="{{ url('parts-transaction-print/'.$data['parts']->id_transaksi) }}" target="_blank" class="btn btn-warning">Print QR</a>
                    <br>
            </div>
            <div class="panel-heading panel-heading-divider">
                {{ $data['parts']['aplikasi'] }}<span class="panel-subtitle"><br><strong>#</strong>  {{ $data['parts']['sn_unit'] }}</span>
                <br><i class="fa fa-map-marker" aria-hidden="true"></i> {{ $data['parts']['lokasi'] }}
               @if ($data['parts']['kondisi_transaksi'] == 'Normal')
                | <a href="#" class="btn btn-success btn-sm"> {{ $data['parts']['kondisi_transaksi'] }} </a>
               @else
                | <a href="#" class="btn btn-danger btn-sm"> {{ $data['parts']['kondisi_transaksi'] }}</a>
               @endif</span>

            </div>
            <div class="panel-body">
                <div class="row user-progress">
                <div class="col-md-10"><span class="title">lifetime :  {{ date('d F Y', strtotime($data['parts']['tgl_lifetime'])) }} - {{ $sisa }} Days Left</span>
                    <div class="progress">
                    <div style="width: {{ $percent }}%" class="progress-bar progress-bar-primary"></div>
                    </div>
                </div>
                <div class="col-md-2"><span class="value" ></span>{{ $percent . "%" }}</div>
                </div>

            </div>
            </div>
        </div>
        <div class="col-md-6 pull-right">
            <div class="panel panel-default">
            <div class="panel-heading panel-heading-divider">Latest Activity<span class="panel-subtitle"><br> <strong>#</strong> {{ $data['parts']['id'] }}</span></div>
            <div class="panel-body">
                <ul class="user-timeline">
                <table class="table timeline">
                    <thead>
                        <tr>
                            <th ><div class="line text-muted"></div></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php ($i = 1)
                        @foreach ($data['historytrx'] as $historytrx)
                        
                        @if ($historytrx->created_at != null)
                        <tr>
                            <td>  
                                    <article class="panel panel-info panel-outline">
                                    <div class="panel-heading icon">
                                        <i class="glyphicon glyphicon-plus-sign"></i>
                                    </div>
                                    <div class="panel-body"> 
                                        <div class="user-timeline-date">{{ $historytrx->created_at }}</div>
                                        <div class="user-timeline-title"><strong> Create New Lot Transaksi : {{ $historytrx->transaksi }}</strong></div>
                                        <div class="user-timeline-description"> {{ $historytrx->nama_lokasi }}
                                        @if ($historytrx->kondisi == 'Normal')
                                            | <a href="#" class="btn btn-success btn-sm"> <strong>{{ $historytrx->kondisi }}</strong></a>
                                        @else
                                            | <a href="#" class="btn btn-danger btn-sm"> <strong>{{ $historytrx->kondisi }}</strong></a>
                                        @endif
                                        </div>
                                        <div class="user-timeline-description"> By : {{ $historytrx->created_by }}</div>
                                    </div>
                                </article>
                            </td>
                        </tr>
                        @else
                        <tr>
                            <td>  
                                    <article class="panel panel-info panel-outline">
                                    <div class="panel-heading icon">
                                        <i class="glyphicon glyphicon-plus-sign"></i>
                                    </div>
                                    <div class="panel-body"> 
                                        <div class="user-timeline-date">{{ $historytrx->updated_at }}</div>
                                        <div class="user-timeline-title"><strong>Update at Lot Transaksi : {{ $historytrx->transaksi }}</strong></div>
                                        <div class="user-timeline-description"> {{ $historytrx->nama_lokasi }}
                                        @if($historytrx->kondisi == 'Normal') 
                                            | <a href="#" class="btn btn-success btn-sm"> <strong>{{ $historytrx->kondisi }}</strong></a>
                                            @else
                                            | <a href="#" class="btn btn-danger btn-sm"> <strong>{{ $historytrx->kondisi }}</strong></a>
                                        @endif
                                        </div>
                                        <div class="user-timeline-description"> By :{{ $historytrx->updated_by }}</div>
                                    </div>
                                </article>
                            </td>
                        </tr>
                        @endif
                        @php ($i = $i+1)
                        @endforeach
                    </tbody>
                    
                </table>
                   
                </ul>

            </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="panel panel-default">
            <div class="panel-heading">History PN Updated</div>
            <div class="panel-body">
                
            <table class="table timeline">
                    <thead>
                        <tr>
                            <th ><div class="line text-muted"></div></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php ($i = 1)
                        @foreach ($data['historypn'] as $historypn)
                        @php($status='')
                        @if ($historypn['status']==1)
                            @php($status='<a class="btn btn-space btn-danger  btn-xs" href=""> New</a>')
                        @else
                            @php($status='<a class="btn btn-space btn-warning  btn-xs" href=""> Old</a>')
                        @endif
                        @if ($historypn['id_transaksi'] == $data['parts']['id_transaksi'])
                        <tr>
                            <td>  
                                    <article class="panel panel-info panel-outline">
                                    <div class="panel-heading icon">
                                        <i class="glyphicon glyphicon-plus-sign"></i>
                                    </div>
                                    <div class="panel-body"> 
                                        <div class="user-timeline-title">{{ $historypn['pn_assy'] }}</div>
                                        <div class="user-timeline-description">{{ $historypn['nama_lokasi'] }}
                                        @if($historypn['kondisi_transaksi'] == 'Normal')
                                            | <a href="#" class="btn btn-success btn-sm"> {{ $historypn['kondisi_transaksi'] }} </a>
                                        @else
                                            | <a href="#" class="btn btn-danger btn-sm"> {{ $historypn['kondisi_transaksi'] }}</a>
                                        @endif</span>
                                        </div>
                                        <div class="user-timeline-description">
                                        <?= $status ?>
                                        </div>
                                    </div>
                                </article>
                            </td>
                            <td></td>
                            <td>
                                        <div class="user-timeline-date"> {{ date('d F Y H:i', strtotime($historypn['tgl_transaksi'])) }}</div></td>
                        </tr>
                        @else
                        <tr>
                            <td>  
                                    <article class="panel panel-info panel-outline">
                                    <div class="panel-heading icon">
                                        <i class="glyphicon glyphicon-plus-sign"></i>
                                    </div>
                                    <div class="panel-body"> 
                                        <div class="user-timeline-date">
                                        <a class="btn  btn-circle btn-sm" href="/posts/view_transaksi/' . $historypn['id_transaksi']"><i class="icon mdi mdi-eye"></i> View</a>
                                        </div>
                                        <div class="user-timeline-title">{{ $historypn['pn_assy'] }}</div>
                                        <div class="user-timeline-description">{{ $historypn['nama_lokasi'] }}
                                        @if ($historypn['kondisi_transaksi'] == 'Normal') { }}
                                            | <a  class="btn btn-success btn-sm"> {{ $historypn['kondisi_transaksi'] }} </a>
                                        @else
                                            | <a  class="btn btn-danger btn-sm"> {{ $historypn['kondisi_transaksi'] }}</a>
                                        @endif</span>
                                        </div>
                                        <div class="user-timeline-description">
                                        {{ $status }}
                                        </div>
                                    </div>
                                </article>
                            </td>
                            <td></td>
                            <td>
                                        <div class="user-timeline-date"> {{ date('d F Y H:i', strtotime($historypn['tgl_transaksi'])) }}</div></td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                    
                </table>
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

    <script src="{{ asset('public/assets/global/plugins/datatables/datatables.min.js') }}"></script>
    <script>
    $(function () {
        $('#search-button').click(function(){
            var search = $('#search-value').val();
            if (search == null || search == ""){
                window.location.href="parts-transaction";
            } else {
                window.location.href="parts-transaction?search="+search;
            }
        });
        $('#sorting-table').DataTable( {
            "dom": '<"toolbar">frtip',
            "ordering": false,
            "info":     false,
            "paging":     true,
            "searching":     false,
        } );
      
    });
    </script>
@endsection
