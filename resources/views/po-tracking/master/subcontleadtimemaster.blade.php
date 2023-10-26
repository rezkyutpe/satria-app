@extends('po-tracking.panel.master')
@section('content')


        <div class="clearfix"></div>
<div class="row">

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_title">
        <h2>Leadtime Subcont Master  
        </h2>
        <a class="btn btn-primary btn-sm pull-right"  data-toggle="modal" data-target="#modal-add-appsmenu" href="#">Create New <i class="fa fa-plus"> </i></a>
        {{-- <ul class="nav navbar-right panel_toolbox">

          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>

          <li><a class="close-link"><i class="fa fa-close"></i></a>
          </li>
        </ul> --}}
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

          <div class="row">
              <div class="col-sm-12">
                <div class="card-box table-responsive">

        <table id="datatable-responsive" class="table text-center table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
          <thead>

            <tr>
              <th style="width: 5%">No</th>
              <th style="width: 5%">ID</th>
              <th>Name</th>
              <th style="width: 5%">Value</th>
              <th style="width: 10%">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($data as $k=> $item)
            <tr>
                <td>{{ $k+1 }}</td>
                <td>{{ $item->ID }}</td>
                <td class="text-left">{{ $item->LeadtimeName }}</td>
                <td>{{ $item->Value * 100 }} %</td>
                <td>
                    <a href="#"  class="edit" data-toggle="modal" data-id="{{ $item->ID }}"><i class="fa fa-pencil fa-lg custom--1"></i></a>
                    <a href="#" class="delete" data-toggle="modal"  data-id="{{ $item->ID }}" ><i class="fa fa-trash fa-lg custom--1"></i></a>
                </td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
              <tr>
                <td class="h6" colspan="3">
                Total
                </td>
                @php
                 $total = $data->sum('Value')*100;
                 if($total != 100){
                     $color = 'bg-danger text-white';
                 } else {
                    $color = '';
                 }
                @endphp
                <td class="h6 {{$color}}">
                    {{$data->sum('Value')*100}}%
                </td>
                <td>
                </td>
              </tr>
          </tfoot>
        </table>


      </div>
    </div>
  </div>
</div>
    </div>
  </div>
</div>
<div class="modal fade" id="delete" aria-hidden="true">
    <form method="post" action="{{url('delete-subcontleadtimemaster')}}" enctype="multipart/form-data">
      {{csrf_field()}}
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2>Warning</h2>
                    <p>Are you sure delete ? </p>
                    </div>

                <input type="hidden" name="ID" id="ID" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-success">Yes</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal fade" id="modal-edit" aria-hidden="true">
    <form method="post" action="{{url('update-subcontleadtimemaster')}}" enctype="multipart/form-data">
        {{csrf_field()}}
    <div class="modal-dialog">
       <form id="companydata">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2>Edit Leadtime</h2>
                    <br>
                    <div class="form-group text-left">
                        <label class="form-control-label"> Name: *</label>
                    </div>
                    <input type="text" name="Name" id="Nameleadtime" class="form-control" autocomplete="off" value="" required="">
                    <br>
                    <div class="form-group text-left">
                        <label class="form-control-label"> Value:</label>
                    <input type="number" name="Value" min="0" max="100" id="Valueleadtime" autocomplete="off" value="" required="">%
                    </div>
                </div>
                <input type="hidden" name="ID" id="IDleadtime" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-success">Yes</button>
                </div>
            </div>
        </div>
       </form>
    </div>
</div>
<div id="modal-add-appsmenu" class="modal fade">
    <form method="post" action="{{url('insert-subcontleadtimemaster')}}" enctype="multipart/form-data">
      {{csrf_field()}}
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2>Add Leadtime</h2>
                    <br>
                    <div class="form-group text-left">
                        <label class="form-control-label"> Name: *</label>
                        <input type="text" name="Name" class="form-control" autocomplete="off" value="" required="">
                    </div>
                    <div class="form-group text-left">
                        <label class="form-control-label"> Value: </label>
                        <input type="number" name="Value" min="0" max="100" id="Valueleadtime" autocomplete="off" value="" required="">%
                    </div>
                </div>
                <input type="hidden" name="id" value=""/>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-success">Yes</button>
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
    
    // fetch('http://localhost/editreason?id=2')
    $.ajax({
    url : "{{url('carisubcontleadtimemaster')}}?id="+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

        var value = data.Value * 100;

        $('#Nameleadtime').val(data.LeadtimeName);
        $('#Valueleadtime').val(value);
        $('#IDleadtime').val(data.ID);
        $('#modal-edit').modal('show');
        }
        });
    });

    $('.delete').on("click" ,function() {
    var id = $(this).attr('data-id');
    $.ajax({
        url : "{{url('carisubcontleadtimemaster')}}?id="+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
        $('#Name').val(data.LeadtimeName);
        $('#ID').val(data.ID);
        $('#delete').modal('show');
        }
        });
    });

    });
    </script>
@endsection
