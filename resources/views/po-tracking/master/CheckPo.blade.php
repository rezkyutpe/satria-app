@extends('po-tracking.panel.master')
@section('content')


<style>
h6{
    font-size: 0.9rem;
}

</style>
<div class="clearfix"></div>
<div class="row">

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_title">
        <h2>{{ $header_title }}</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
      </div>
      @if (count($errors) > 0)
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
      @endif
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

      <div class="well" style="overflow: auto">
        @include('po-tracking.panel.search')

      </div>
      <div class="row">
        <div class="col-sm-12">

             <div class="card-box table-responsive">
                <table id="datatable-visibility-server-side"
                class="table text-center table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                        <th>PO Number</th>
                        <th>PO Item</th>
                        <th>Material</th>
                        <th>Description</th>
                        <th>Qty</th>
                        <th>Price&nbsp;&frasl;<sub>pcs</sub>&nbsp;</th>
                        <th>DeliveryDate</th>

                        </tr>
                  </thead>
                  <tbody>

            </tbody>
            </table>
            </div>
        </div>
       </div>
    </div>
    </div>
</div>


@endsection

@section('myscript')
<script>
$('#datatable-visibility-server-side').DataTable({
    processing: true,
    paging: true,
    searchable: true,
    serverSide: true,
    // stateSave: true,
    // ordering: false,
    // searching: false,
    // deferRender: true,
    // scroller: {
    //     loadingIndicator: true
    // },
    dom: 'Bfrtip',
    buttons: [
        'pageLength', 'colvis', 'copy', 'csv', 'excel', [{
            extend: 'pdfHtml5',
            orientation: 'landscape',
            pageSize: 'LEGAL'
        }], 'print'
    ],
    ajax: {
        url: "{{ url('searchpoall') }}",
        type: 'GET',
    },

    columns: [{
            data: 'Number',
            name: 'PO Number'
        },
        {
            data: 'ItemNumber',
            name: 'PO Item'
        },
        {
            data: 'Material',
            name: 'Material'
        },
        {
            data: 'Description',
            name: 'Description'
        },
        {
            data: 'Quantity',
            name: 'Quantity'
        },
        {
            data: 'NetPrice',
            name: 'NetPrice'
        },
        {
            data: 'DeliveryDate',
            name: 'DeliveryDate'
        },

    ]

});
</script>
@endsection

