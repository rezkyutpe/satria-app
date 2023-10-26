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
            <h1>List Permission Role Group </h1>
        </div>
        <table class="table">
            <tr>
                <td>Name</td>
                <td>: {{ $data['user']->name }} </td>
                <td></td>
                <td>Email</td>
                <td>: {{ $data['user']->email }} </td>
            </tr>
            <tr>
                <td>Title</td>
                <td>: {{ $data['user']->title }} </td>
                <td></td>
                <td>Hp/Wa</td>
                <td>: {{ $data['user']->phone }} </td>
            </tr>
            <tr>
                <td>Department</td>
                <td>: {{ $data['user']->department }} </td>
                <td></td>
                <td>Email Notif</td>
                <td>: {{ $data['user']->email_sf }} </td>
            </tr>
            <tr>
                <td>Division</td>
                <td>: {{ $data['user']->division }} </td>
                <td></td>
                <td>Category User</td>
                <td>: {{ $data['user']->role_id }} </td>
            </tr>
            
            <tr>
                <td>Company</td>
                <td>: {{ $data['user']->company_name }} </td>
                <td></td>
                <td>Photo</td>
                <td>:  <img src="@if($data['user']->photo!='') {{ asset('public/profile/'.$data['user']->photo) }} @else{{ asset('public/assets/global/img/no-profile.jpg') }}@endif" width="100"/> </td>
            </tr>
        </table>
    </div>
    <div class="row">
        
        <div class="col-md-12">
            
            <div class="table-responsive custom--2">
                <table id="sorting-table" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th class="text-left">Role</th>
                            <th class="text-left">Apps</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($data['appsmenu'] as $appsmenu)
                        <tr>
                            <td>{{ $appsmenu->no }}</td>
                            <td class="text-left">{{ $appsmenu->role_name }}</td>
                            <td class="text-left">{{ $appsmenu->app_name }}</td>
                            <td class="text-right">
                                @if($data['actionmenu']->v==1)
                                    <a href="{{ url('rolegroup-view/'.$appsmenu->id) }}" ><i class="fa fa-eye fa-lg custom--1"></i></a>
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

@endsection

@section('myscript')

    <script src="{{ asset('public/assets/global/plugins/datatables/datatables.min.js') }}"></script>
    <script>
    $(function () {
        $('#search-button').click(function(){
            var search = $('#search-value').val();
            if (search == null || search == ""){
                window.location.href="user-management";
            } else {
                window.location.href="user-management?search="+search;
            }
        });
        $('#sorting-table').DataTable( {
            "dom": '<"toolbar">frtip',
            "ordering": false,
            "info":     false,
            "paging":     true,
            "searching":     true,
        } );

         $('#sorting-tables').DataTable( {
            "dom": '<"toolbar">frtip',
            "ordering": false,
            "info":     false,
            "paging":     true,
            "searching":     true,
        } );
        $("div.toolbar").html('<br>');
    });
    </script>
@endsection
