@extends('po-tracking.panel.master')

@section('content')
    <div class="container-fluid">
        <!-- Masthead Avatar Image-->

        <div class="row">
            <div
                style="position: absolute;
                    left: 60%;
                    top: 40%;
                    transform: translate(-50%, -50%);">
                <h1 class="text-center"
                    style="font-size: 2rem;
                    font-weight: 700;
                    background: linear-gradient(-45deg, #3db3c5, #274685);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;">
                    Welcome <b>{{ Auth::user()->name }}</b> <br> PO Tracking
                    PATRIA</h1>
                <div class="text-center mt-4">
                    <img style="width:300px; height:200px;" src="{{ url('/public/potracking/patria.jpeg') }}" alt="no image" />
                </div>
            </div>

        </div>
    </div>
@endsection
