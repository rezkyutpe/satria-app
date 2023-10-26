@extends('po-tracking.panel.master')
@section('content')
<div class="clearfix"></div>

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
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>SLA - PR Create to PO Release</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <table class="table table-responsive table-bordered nowrap text-center align-middle" width="50%">
                                <thead>
                                    <tr>
                                        <th>PR Create <i class="fa fa-arrow-right"></i> PR Release</th>
                                        <th>PR Release <i class="fa fa-arrow-right"></i> PO Create</th>
                                        <th>PO Create <i class="fa fa-arrow-right"></i> PO Release</th>
                                        <th>Total LeadTime</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $k=> $item)
                                        <tr>
                                            @php
                                                $hasil = $item->prcreatetoprrel + $item->prtopocreate + $item->pocreatetoporel ;
                                            @endphp
                                            <td class="text-center align-middle">{{ $item->prcreatetoprrel }} days</td>
                                            <td class="text-center align-middle">{{ $item->prtopocreate }} days</td>
                                            <td class="text-center align-middle">{{ $item->pocreatetoporel }} days</td>
                                            <td class="text-center align-middle">{{ $hasil }} days</td>
                                            <td class="text-center align-middle">
                                                <button href="#"  class="edit btn btn-sm btn-info" data-toggle="modal" data-id="{{ $item->id }}"><i class="fa fa-pencil fa-lg custom--1"></i> Edit</a>
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

<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>SLA - Ticket PO Tracking</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <table class="table table-responsive table-bordered nowrap text-center align-middle" width="50%">
                                <thead>
                                    <tr>
                                        <th>Hour Tolerance</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $k=> $item)
                                        <tr>
                                            <td class="text-center align-middle">{{ $item->ticket_hour }} hours</td>
                                            <td class="text-center align-middle">
                                                <button href="#"  class="edit1 btn btn-sm btn-info" data-toggle="modal" data-id="{{ $item->id }}"><i class="fa fa-pencil fa-lg custom--1"></i> Edit</a>
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

<div class="modal fade" id="modal-edit" aria-hidden="true">
    <form method="post" action="{{url('update-parametersla')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Edit SLA - PR Create to PO Release</h2>
                        <br>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> PR Create <i class="fa fa-arrow-right"></i> PR Release : *</label>
                                    <input type="text" name="prcreatetoprrel" class="form-control" autocomplete="off" value="" id="prcreatetoprrel" >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> PR Release <i class="fa fa-arrow-right"></i> PO Create : *</label>
                                    <input type="text" name="prtopocreate" class="form-control" autocomplete="off" value="" required="" id="prtopocreate" >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> PO Create <i class="fa fa-arrow-right"></i> PO Release : *</label>
                                    <input type="text" name="pocreatetoporel" class="form-control" autocomplete="off" value="" required="" id="pocreatetoporel" >
                                    <input type="hidden" name="id" class="form-control" autocomplete="off" value="" id="id">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success" name="action" value="editpopr">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="modal-edit1" aria-hidden="true">
    <form method="post" action="{{url('update-parametersla')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Edit SLA - Ticket PO Tracking</h2>
                        <br>
                        <input type="hidden" name="id" class="form-control" autocomplete="off" value="" id="id1">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group text-left">
                                    <label class="form-control-label"> Hour Tolerance : *</label>
                                    <input type="text" name="tickethour" class="form-control" autocomplete="off" value="" id="ticket_hour" >
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-success" name="action" value="edittickethour">Yes</button>
                </div>
            </div>
        </div>
    </form>
</div>


<script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>

<script>
    $(document).ready(function() {
        //edit data
        $('.edit').on("click" ,function() {
            var id = $(this).attr('data-id');
            $.ajax({
                url : "{{url('cariparametersla')}}?id="+id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data)
                    {
                        $('#id').val(data.id );
                        $('#prcreatetoprrel').val(data.prcreatetoprrel);
                        $('#prtopocreate').val(data.prtopocreate);
                        $('#pocreatetoporel ').val(data.pocreatetoporel );
                        $('#modal-edit').modal('show');
                    }
            });
        });

        //edit data ticket hour
        $('.edit1').on("click" ,function() {
            var id = $(this).attr('data-id');
            $.ajax({
                url : "{{url('cariparametersla')}}?id="+id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data)
                    {
                        $('#id1').val(data.id);
                        $('#ticket_hour').val(data.ticket_hour);
                        $('#modal-edit1').modal('show');
                    }
            });
        });

    });
    </script>
@endsection
