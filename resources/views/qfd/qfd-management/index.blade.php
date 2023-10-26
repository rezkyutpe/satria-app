@extends('panel.master')

@section('css')

<link href="{{ asset('public/assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />

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
            <h1>QFD Management</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="table-responsive custom--2">
                <div class="row custom-position-header">
                    <div class="float-left col-xl-3 col-md-3 col-xs-8 m-b-10px">
                        <input name="name" id="search-value" type="search" value="" autocomplete="off" placeholder="Search" class="form-control">
                    </div>
                    <div class="float-left col-xl-3 col-md-3 col-xs-4 m-b-10px">
                        <button type="button" id="search-button" class="btn btn-primary">Cari</button>
                    </div>
                </div>
                <table id="sorting-table" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Material Num</th>
                            <th>Material Desc</th>
                            <th>QFD Date</th>
                            <th>Status</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['trxmat'] as $trxmat)
                        <tr>
                            <td>{{ $trxmat->no }}</td>
                            <td>{{ $trxmat->material_number }}</td>
                            <td>{{ $trxmat->material_description }}</td>
                            <td>{{ $trxmat->created_at }}</td>
                             <td>@if($trxmat['status']=='0')
                                {{ 'New'}}
                                @elseif($trxmat['status']=='1')
                                {{ 'Partial Approved'}}
                                @elseif($trxmat['status']=='2')
                                {{ 'Fully Approved'}}
                                @elseif($trxmat['status']=='3')
                                {{ 'Revise'}}
                                 @elseif($trxmat['status']=='31')
                                {{ 'Revised'}}
                                 @elseif($trxmat['status']=='4')
                                {{ 'Rejected'}}
                                 @elseif($trxmat['status']=='5')
                                {{ 'Escalated'}}
                                @elseif($trxmat['status']=='99')
                                {{ 'Canceled'}}
                                @else
                                {{ '-' }}
                                @endif 
                            </td>
                            <td class="text-right">
                                
                                @if($data['actionmenu']->c==1)
                                <a href="{{ url('copy-qfd/'.$trxmat->id)}}" data-toggle="tooltip" data-placement="top" title="Copy Qfd" ><i class="fa fa-plus fa-lg custom--1"></i></a>
                                <a href="{{ url('qfd-attendance-email/'.$trxmat->id)}}" data-toggle="tooltip" data-placement="top" title="Sent Mom to Attendance" ><i class="fa fa-envelope fa-lg custom--1"></i></a>
                                    @endif
                                @if($data['actionmenu']->v==1)
                                    <a href="{{ url('history-qfd/'.$trxmat->id)}}" data-toggle="tooltip" data-placement="top" title="History Qfd" ><i class="fa fa-history fa-lg custom--1"></i></a>
                                @endif
                                @if($data['actionmenu']->c==1)
                                <a href="{{ url('detail-qfd/'.$trxmat->id)}}" data-toggle="tooltip" data-placement="top" title="Detail Qfd" ><i class="fa fa-eye fa-lg custom--1"></i></a>
                                @endif
                                 @if($trxmat->status==3)
                                    @if($data['actionmenu']->u==1)
                                    <a href="{{url('edit-qfd/'.$trxmat->id)}}" data-toggle="tooltip" data-placement="top" title="Edit Qfd"><i class="fa fa-pencil fa-lg custom--1"></i></a>
                                 @endif
                                @endif
                                
                                @if($data['actionmenu']->d==1)
                                    @if($trxmat->status==0)
                                        <a href="#" data-toggle="modal" data-target="#modal-delete-qfd-{{ $trxmat->id }}"><i class="fa fa-trash fa-lg custom--1"></i></a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
     <div id="modal-add-trxmat" class="modal fade">
        <form method="post" action="{{url('add-qfd')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Add QFD</h2>
                        <br>
                        <div class="form-group text-left">
                             <div class="form-group">
                             <label class="form-control-label">Material Desc : </label>
                                           
                            <input type="text" list="list_pro" id="matdesc" name="matdesc" autocomplete="off"
                                onchange="getsapmat(this)" class="form-control" required />
                            <datalist id="list_pro">
                                @foreach($data['mstsap'] as $mstsap)
                                <option value="{{ $mstsap['smt_desc'] }}">{{ $mstsap['smt_name'] }}
                                    @endforeach
                            </datalist>
                        </div>
                       <div class="form-group text-left">
                             <div class="form-group">
                                <label class="form-control-label">Material Number : </label>
                                <input type="text" id="val_num" name="num" class="form-control"
                                 autocomplete="off"  required />
                            </div>
                        </div>
                      
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>


@endsection

    <!-- Modal Edit trxmat -->
    @foreach($data['trxmat'] as $trxmat)
    <div id="modal-delete-qfd-{{ $trxmat->id }}" class="modal fade">
        <form method="post" action="{{url('delete-qfd')}}" enctype="multipart/form-data">
          {{csrf_field()}}
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>Warning</h2>
                        <p>Are you sure?</p>
                    </div>
                    <input type="hidden" name="id" value="{{ $trxmat->id }}"/>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @endforeach
@section('myscript')

    <script src="{{ asset('public/assets/global/plugins/datatables/datatables.min.js') }}"></script>
    <script>
    $(function () {
        $('#search-button').click(function(){
            var search = $('#search-value').val();
            if (search == null || search == ""){
                window.location.href="menu-management";
            } else {
                window.location.href="menu-management?search="+search;
            }
        });
        $('#sorting-table').DataTable( {
            "dom": '<"toolbar">frtip',
            "ordering": false,
            "info":     false,
            "paging":     false,
            "searching":     false,
        } );
        
        <?php if($data['actionmenu']->c==1){ ?>

        $("div.toolbar").html('<a class="float-right btn btn-success" href="{{ url('qfd-draft') }}">Draft</a> | <a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-add-trxmat" href="#">Tambah</a>');
        <?php }else{ ?>
            $("div.toolbar").html('<br>');
        <?php } ?>
    });

    function getsapmat(sel) {
        var id = sel.value;

        // AJAX request 
        $.ajax({
            url: 'get-sapmat/' + id,
            type: 'get',
            dataType: 'json',
            success: function (response) {

                console.log(response['data']);
                document.getElementById("val_num").value = response['data'].smt_name;
                // document.getElementById("val_pn_patria").value = response['data'].pn_patria;
                // document.getElementById("val_pn_vendor").value = response['data'].pn_vendor;
            }
        });
    }
    </script>
@endsection
