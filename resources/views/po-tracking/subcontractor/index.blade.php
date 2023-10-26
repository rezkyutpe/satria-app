@extends('po-tracking.panel.master')
@section('content')


        <div class="clearfix"></div>
<div class="row">


<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_title">
        <h2>PO Subcontraktor </h2>
        <ul class="nav navbar-right panel_toolbox">

          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>

          <li><a class="close-link"><i class="fa fa-close"></i></a>
          </li>
        </ul>
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


      <div class="well" style="overflow: auto">
        <form method="post" action="{{url('carisubcontractor')}}" enctype="multipart/form-data">
            {{csrf_field()}}
            <fieldset>
                <div class="col-md-1">


                    <div class="control-group ">
                      <div class="controls">
                        <div class="input-prepend input-group">
                        <label for="" style="MARGIN-TOP: 10px;">PO DATE</label>
                        </div>
                      </div>
                    </div>


              </div>
        <div class="col-md-4">


              <div class="control-group ">
                <div class="controls">
                  <div class="input-prepend input-group">
                    <span class="add-on input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" id="single_cal3" name="tanggal2" class="form-control" autocomplete="off">
                </div>
                </div>
              </div>


        </div>
        <div class="col-md-1">


            <div class="control-group ">
              <div class="controls">
                <div class="input-prepend input-group">
                <label for="" style="MARGIN-TOP: 10px;">TO DATE</label>
                </div>
              </div>
            </div>


      </div>
        <div class="col-md-4">

                <div class="control-group ">
                  <div class="controls">
                    <div class="input-prepend input-group">
                      <span class="add-on input-group-addon"><i class="fa fa-calendar"></i></span>
                      <input type="text" name="tanggal1" id="single_cal4" class="form-control" autocomplete="off" >
                    </div>
                  </div>
                </div>

      </div>
      <div class="col-md-2">

        <button type="submit" class="btn btn-primary" style="color: #ffffff"><i class="glyphicon glyphicon-search"></i> Find </button>


      </div>

          </fieldset>
        </form>
      </div>
          <div class="row">
              <div class="col-sm-12">
                <div class="card-box table-responsive">

        <table id="datatable-fixed-header" class="table table-striped table-bordered" style="width:100%">
          <thead>

            <tr>
              <th>No</th>
              <th>No PO</th>
              <th>PO Date</th>
              <th>Item ID</th>
              <th>Material</th>
              <th>Description</th>
              <th>Qty</th>
               <th>Delivery Date</th>
              <th>Progress</th>
              <th>Action</th>

            </tr>
          </thead>
          <tbody>
            @foreach ($data as $k=> $item)
            <tr>
            <td>{{ $k+1 }}</td>
            @php
            $source =  $item->Date ;
            $sources =  $item->DeliveryDate ;
            $date = new DateTime($source);
            $dates = new DateTime($sources);

        @endphp
        <td>{{ $item->Number }}</td>
        <td>@php
            echo $date->format('d/m/Y');
        @endphp </td>
        <td>{{ $item->ItemNumber }}</td>
        <td>{{ $item->Material }}</td>
        <td>{{ $item->Description }}</td>
        <td>{{ $item->Quantity }}</td>
         <td>@php
            echo $dates->format('d/m/Y');
        @endphp</td>
            <td>{{ $item->ActiveStage }}</td>
            <td><a href="#"  class="edit btn btn-secondary btn-sm" data-toggle="modal" data-id="{{ $item->ID }}"><i class="fa fa-pencil "></i></a>
                <a href="#" class="delete btn btn-danger btn-sm" data-toggle="modal"  data-id="{{ $item->ID }}" ><i class="fa fa-trash"></i></a>
                <a href="#" class="delete btn btn-info btn-sm" data-toggle="modal"  data-id="{{ $item->ID }}" ><i class="fa fa-eye"></i></a>
                <a href="#" class="delete btn btn-success btn-sm" data-toggle="modal"  data-id="{{ $item->ID }}" ><i class="fa fa-download "></i></a>
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




@endsection
