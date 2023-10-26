@extends('panel.master')

@section('css')
<style>
    a.disabled {
        pointer-events: none;
        cursor: default;
    }
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

<link href="{{ asset('public/assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="loader" style="display:none;">
    <div class="loader-main"><i class="fa fa-spinner fa-pulse"></i></div>
</div>

<div class="content-body-white">

    <form method="post" action="{{url('insert-contract')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="page-head">
            <div class="page-title">
                <h1>Detail Contract</h1>
            </div>
            
        </div>
        <div class="wrapper">
            <div class="row">
                @if(session()->has('err_message'))
                <div class="alert alert-danger alert-dismissible" role="alert" auto-close="10000">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    {{ session()->get('err_message') }}
                </div>
                @endif
                @if(session()->has('succ_message'))
                <div class="alert alert-success alert-dismissible" role="alert" auto-close="10000">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    {{ session()->get('succ_message') }}
                </div>
                @endif
                <div class="col-md-12 element">
                    <div class="box-pencarian-family-tree" style=" background: #fff; ">
                        <div class="row">

                            <div class="col-xl-12 col-md-12 m-b-12px">
                                <div class="row">

                                <table class="table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Company</th>
                                        <th>: </th>
                                        <th>{{ $data['kontrak']['kontrak_perusahaan']}} </th>
                                        <th>Category</th>
                                        <th>: </th>
                                        <th>@if($data['kontrak']->kontrak_category=='1') {{ 'Contract' }} @else {{'License'}} @endif </th>
                                    </tr>
                                    <tr>
                                        <th>PIC</th>
                                        <th>: </th>
                                        <th>{{ $data['kontrak']['kontrak_pic_name']}}  </th>
                                        <th>Email </th>
                                        <th>: </th>
                                        <th>{{ $data['kontrak']['kontrak_pic_email']}}  </th>
                                    </tr>
                                    <tr>
                                        <th>Contract/SN Num</th>
                                        <th>: </th>
                                        <th>{{ $data['kontrak']['kontrak_no_kontrak']}}  </th>
                                        
                                        <th></th>
                                        <th> </th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                            <br>
                                <div class="col-md-4 col-xl-12 m-b-10px">
                                        <legend>Last 5 Renew Contract</legend>
                                        <table class="table table-bordered timeline">
                                                <thead>
                                                    <tr>
                                                        <th ><div class="line text-muted"></div></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($data['lastnew'] as $lastnew)
                                                    <tr>
                                                        <td>  
                                                             <article class="panel panel-info panel-outline">
                                                                <div class="panel-heading icon">
                                                                    <i class="glyphicon glyphicon-calendar"></i>
                                                                </div>
                                                                <div class="panel-body">
                                                                    - <strong> Deal Date : {{ $lastnew->detail_renew_date_renew }} </strong> with durations <strong>{{ $lastnew->durasi }}</strong> <br>
                                                                    - <strong>Note : </strong>{{ $lastnew->detail_renew_note }} <br>
                                                                    </div>
                                                            </article>
                                                            
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                              
                                            </table>
                                </div>
                                <div class="col-md-8 col-xl-12 m-b-10px">
                                     <legend>History Contract</legend>
                                    <div class="table-responsive custom--2">
                                        <table id="sorting-table" class="table">
                                            <thead>
                                                <th>No</th>
                                                <th>Deal Date</th>
                                                <th>Duration</th>
                                                <th>Contract Note</th>
                                                <th>File</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php($no =0)
                                            @foreach($data['history'] as $history)
                                            @php($no =$no+1)
                                            <tr>
                                                <td>{{ $no }}</td>
                                                <td>{{ $history->detail_renew_date_renew }}</td>
                                                <td>{{ $history->durasi }} Days</td>
                                                <td>{{ $history->detail_renew_note }}</td>
                                                <td><a href="{{ asset('public/contract/'.$history->detail_renew_file) }}" target="_BLANK">{{ $history->detail_renew_file }}</a>
                                                </td>
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
                    </div>
                </div>
            </div>

         
        </div>
    </form>
</div>

@endsection

@section('myscript')

    <script src="{{ asset('public/assets/global/plugins/datatables/datatables.min.js') }}"></script>
    <script>
    $(function () {
        $('#sorting-table').DataTable( {
            "dom": '<"toolbar">frtip',
            "ordering": false,
            "info":     false,
            "paging":     true,
            "searching":     true,
        } );
        
    });
    </script>
@endsection