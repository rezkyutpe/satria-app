@extends('panel.master')

@section('css')

<link href="{{ asset('public/assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />g

@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }} {{ Auth::user()->accessed_app }} </div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    @if(session()->has('err_message'))
                    <div class="alert alert-danger alert-dismissible " role="alert" auto-close="5">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        {{ session()->get('err_message') }}
                    </div>
                    @endif
                    @if(session()->has('suc_message'))
                    <div class="alert alert-success alert-dismissible " role="alert" auto-close="5">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        {{ session()->get('suc_message') }}
                    </div>
                    @endif
                    <!-- {{ __('You are logged in!') }} -->

                    <!-- {!! QrCode::size(100)->generate('MyNotePaper'); !!} -->
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
        $('#search-button').click(function () {
            var search = $('#search-value').val();
            if (search == null || search == "") {
                window.location.href = "user-management";
            } else {
                window.location.href = "user-management?search=" + search;
            }
        });
        $('#sorting-table').DataTable({
            "dom": '<"toolbar">frtip',
            "ordering": false,
            "info": false,
            "paging": false,
            "searching": false,
        });

        $("div.toolbar").html('<a class="float-right btn btn-success" data-toggle="modal" data-target="#modal-add-user" href="#">Tambah</a>');
    });
</script>
@endsection