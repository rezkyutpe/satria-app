@extends('layouts.master')

@section('content')
  <main id="main">

    <!-- ======= Breadcrumbs ======= -->
    <section class="breadcrumbs">
      <div class="container">

       
      <div class="d-flex justify-content-between align-items-center">
          <h2>Detail Ticket</h2>
          <ol>
            <li><a href="{{ url('welcome') }}">Home</a></li>
            <li><a href="{{ url('user-ticket') }}">Ticket</a></li>
            <li>Detail</li>
          </ol>
        </div>


      </div>
    </section><!-- End Breadcrumbs -->

    <section class="inner-page pt-4">
      <div class="container">
        <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="100">

          <div class="col-lg-3 col-md-4">

          <div class="info">
              <div>
                <div style="float: right; width:75%;">
                <Strong>{{ Auth::user()->name }}</Strong> <br>
                
                <span style="background-color: green;white-space: pre-line;text-align: left;" class="badge badge-secondary">{{ Auth::user()->department }}</span> </div>
                <div>
                <img src="{{ asset('public/assets/global/img/no-profile.jpg') }}" alt="" width="70"  class="logo-default-login" /> 
                </div>
                
              <hr>
              </div>
              <div class="email">
                <i class="bi bi-envelope"></i> {{ Auth::user()->email_sf }}
              </div>

              <div>
                <i class="bi bi-phone"></i> +{{ Auth::user()->phone }}
                <p></p>
              </div>
            </div>
            <hr>
            <div>
                <Strong>Transaction</Strong> <br> <br>
                  <div class="email">
                  <a href="{{ url('user-pr') }}"><i class="bi bi-newspaper"></i> Purchasing Request @if($data['pr_count']!=0)<span class="badge-notif" data-badge="{{ $data['pr_count'] }}"></span>@endif </a> 
                  <p></p>
              </div>

              <div>
                  <a href="{{ url('user-ticket') }}"> <i class="bi bi-ticket"></i> Ticket @if($data['ticket_count']!=0)<span class="badge-notif" data-badge="{{ $data['ticket_count'] }}"></span>@endif</a> 
                  <p></p>
              </div>
              <!-- <div>
                  <a href=""> <i class="bi bi-envelope"></i> Message @if($data['ticket_count']!=0)<span class="badge-notif" data-badge="{{ $data['ticket_count'] }}"></span>@endif</a> 
                  <p></p>
              </div> -->
            </div>
            <hr>

          </div>

          <div class="col-lg-8 col-md-12">
          <table class="table" style="width: 99%">
                        <tr>
                            <td style="vertical-align:top;width: 65%;" colspan="3"><strong> Ticket {{ $data['ticket']->flow_name}}</strong></td>
                            <td style="vertical-align:top;width: 25%;color: green;" ><span style="float:right;"> {{ $data['ticket']->rate }} <i class="bi bi-star-fill" style="color:orange"></i></span></td>
                        </tr>
                        <tr>
                            <td style="vertical-align:top;width: 65%;font-size:12px" colspan="3">Ticket Num  </td>
                                <td style="vertical-align:top;width: 25%;" >
                                <span style="float:right;font-size:12px;color: green;"><strong>  {{ $data['ticket']->ticket_id}}</strong></span>
                              
                              </td>
                        </tr>
                        <tr>
                            <td style="vertical-align:top;width: 40%;"><strong > Message</strong><br><span style="color: grey;font-size:12px">{{ $data['ticket']->message }}</span></td>
                            <td style="vertical-align:top;width: 25%;"></td>
                            <td></td>
                            <td style="vertical-align:top;width: 25%;"><strong style="float:right;"><i class="bi bi-person"></i> Assister</strong><br>
                                    <span style="color: grey;font-size:12px;float:right;">{{ $data['ticket']->assist_name }}</span><br>
                                    <span style="color: grey;font-size:12px;float:right;">{{ $data['ticket']->dept_assist }}</span>
                                   
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td> 
                            <strong  style="float:right;">SLA Category</strong> <br>
                            <span  style="color: grey;font-size:12px;float:right;">{{ $data['ticket']->sla_name }} </span> <br>
                            @if($data['ticket']->resolve_status==1)
                              <a href="#" style="text-decoration:none;color:black;float:right;" ><span style="background-color: green" class="badge badge-secondary">Achived</span></a>
                            @elseif($data['ticket']->resolve_status==0 && $data['ticket']->resolve_time!='')
                              <a href="#" style="text-decoration:none;color:black;float:right;" ><span style="background-color: red" class="badge badge-secondary">Not Achived</span></a>
                            @endif </td>
                        </tr>
                </table>
                
                  <div class="container mt-5 mb-5">
                    <div class="row">
                      <div class="col-md-12">
                        <h4>History Transaction</h4>
                          <ul class="timeline">
                            @foreach($data['history'] as $history)
                            <li>
                              <a>{{ $history->title }}</a>
                              <a class="float-right">{{ date('H:i, d F Y',strtotime($history->timestamp)) }}</a>
                              <p>{{ $history->description }}</p>
                            </li>
                            @endforeach
                          </ul>
                        </div>
                      </div>
                    </div>
                <hr>
          </div>
        </div>
      </div>
    </section>
<br>
  </main><!-- End #main -->

  @endsection
  
@section('myscript')