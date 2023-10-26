@extends('layouts.master')

@section('content')
  <main id="main">

    <!-- ======= Breadcrumbs ======= -->
    <section class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>Review</h2>
          <ol>
            <li><a href="{{ url('welcome') }}">Home</a></li>
            <li><a href="{{ url('user-pr') }}">Purchasing Request</a></li>
            <li>Review</li>
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
                
                <span style="background-color: green;white-space: pre-line;text-align: left;" class="badge badge-secondary">{{ Auth::user()->department }}</span></div>
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

          </div>

          <div class="col-lg-8 col-md-12">
          <form action="{{url('rate-pr')}}" method="post" enctype="multipart/form-data">
              {{csrf_field()}}
              <div class="modal-header">
                <strong>Review  Purchasing Request</strong>
               @if($data['pr']->status=='0')
                                {{ 'New'}}
                                @elseif($data['pr']->status=='1')
                                {{ 'Partial Aproved'}}
                                @elseif($data['pr']->status=='2')
                                {{ 'Fully Approved'}}
                                @else
                                {{ '-' }}
                                @endif  : {{ date('d F Y, H:i',strtotime($data['pr']->updated_at))}}
              </div> <!-- Modal Body-->
              <div class="modal-body">
              <strong><i class="bi bi-person"></i> Category<br>
                                    <span style="color: grey;font-size:12px">@if( $data['pr']->pr_category==1)
                                {{ "License" }}
                                @else
                                {{ "Inventory : " }}
                                @endif {{  $data['pr']->inventory_nama }}</span> -
                                    <span style="color: grey;font-size:12px">{{  $data['pr']->dept_name }}</span> <br>
                <div class="form-group">
                  <label>Your Rate</label>
                  <p>Bagaimana kualitas pelayanan ini secara keseluruhan?</p>
                </div>
                <div class="rate">
                  <input type="radio" id="star5" name="rate" value="5" />
                  <label for="star5" title="Sangat Baik">5 stars</label>
                  <input type="radio" id="star4" name="rate" value="4" />
                  <label for="star4" title="Baik">4 stars</label>
                  <input type="radio" id="star3" name="rate" value="3" />
                  <label for="star3" title="Cukup">3 stars</label>
                  <input type="radio" id="star2" name="rate" value="2" />
                  <label for="star2" title="Buruk">2 stars</label>
                  <input type="radio" id="star1" name="rate" value="1" />
                  <label for="star1" title="Sangat Buruk">1 star</label>
                </div>
                <div class="form-group">
                  <br>
                  <br>
                  <label>Your Review</label>
                </div>
                <div class="form-group mt-3">
                  <input type="hidden" name="id" value="{{  $data['pr']->pr_id }}">
                  <textarea class="form-control" name="review" rows="5" placeholder="Message" required></textarea>
                </div>
              </div> <!-- Modal Footer-->
              
              <div class="modal-footer"> 
                
              <span id="copied" class="pull-left"></span>
              <div class="text-center" style="margin:5px;"> <input type="submit" class="btn btn-success"  value="Save"> </div>
          </form>
          </div>
        </div>
      </div>
    </section>

  </main><!-- End #main -->

  @endsection
  
@section('myscript')