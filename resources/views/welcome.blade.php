@extends('layouts.master')

@section('content')
  <!-- ======= Hero Section ======= -->
  <section id="hero">
 
    <div class="hero-container" data-aos="fade-in">
    
      <h1>Welcome to Satria Webportal</h1>
      <img src="{{ asset('public/assets/global/img/side-logo.png') }}" alt="Hero Imgs" data-aos="zoom-out" data-aos-delay="100">
      <h2>Need Help ?</h2>
      <a href="#contact" class="btn-get-started scrollto">Click Me</a>
      <!-- <div class="btns">
        <a href="#"><i class="fa fa-apple fa-3x"></i> App Store</a>
        <a href="#"><i class="fa fa-play fa-3x"></i> Google Play</a>
        <a href="#"><i class="fa fa-windows fa-3x"></i> windows</a>
      </div> -->
    </div>
  </section><!-- End Hero Section -->

  <main id="main">

    <!-- ======= Get Started Section ======= -->
    <section id="get-started" class="padd-section text-center">

      <div class="container" data-aos="fade-up">
        <div class="section-title text-center">

          <h2>Welcoming a fresh start </h2>
          <!-- <p class="separator">Integer cursus bibendum augue ac cursus .</p> -->

        </div>
      </div>

      <div class="container">
        <div class="row">

          <div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="100">
            <div class="feature-block">

              <img src="{{ asset('public/assets/fe/img/svg/cloud.svg') }}" alt="img">
              <h4>introducing satria</h4>
              <!-- <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>
              <a href="#">read more</a> -->

            </div>
          </div>

          <div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="200">
            <div class="feature-block">

              <img src="{{ asset('public/assets/fe/img/svg/planet.svg') }}" alt="img">
              <h4>user friendly interface</h4>
              <!-- <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>
              <a href="#">read more</a> -->

            </div>
          </div>

          <div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="300">
            <div class="feature-block">

              <img src="{{ asset('public/assets/fe/img/svg/asteroid.svg') }}" alt="img">
              <h4>build the app everyone love</h4>
              <!-- <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>
              <a href="#">read more</a> -->

            </div>
          </div>

        </div>
      </div>

    </section><!-- End Get Started Section -->

    <!-- ======= About Us Section ======= -->
    <section id="about-us" class="about-us padd-section">
      <div class="container" data-aos="fade-up">
        <div class="row justify-content-center">

          <div class="col-md-6 col-lg-4">
            <img src="{{ asset('public/assets/fe/img/about-img.png') }}" alt="About" data-aos="zoom-in" data-aos-delay="100">
          </div>

          <div class="col-md-4 col-lg-5">
            <div class="about-content" data-aos="fade-left" data-aos-delay="100">

              <h2><span>Satria</span>Mobile Apps</h2>
              <p>Super Architecture To Reunite Internal Application
              </p>

              <ul class="list-unstyled">
                <li><i class="vi bi-chevron-right"></i>Creative Simple Design</li>
                <li><i class="vi bi-chevron-right"></i>Multi Platform</li>
                <li><i class="vi bi-chevron-right"></i>Easy to Use</li>
                <li><i class="vi bi-chevron-right"></i>Easy to Access</li>
                <li><i class="vi bi-chevron-right"></i>Many Features</li>
              </ul>

            </div>
          </div>

        </div>
      </div>
    </section><!-- End About Us Section -->

    <!-- ======= Features Section ======= -->
    <section id="features" class="padd-section text-center">

      <div class="container" data-aos="fade-up">
        <div class="section-title text-center">
          <h2>Our Services</h2>
          <p class="separator">The Apps from Our Development at Satria.</p>
        </div>

        <div class="row" data-aos="fade-up" data-aos-delay="100">
            @foreach($data['apps'] as $apps)
            <div class="col-md-6 col-lg-3">
                <div class="feature-block">
                <img src="{{ asset('public/assets/global/img/icon/'.$apps->logo) }}" alt="img">
                <h4>{{ $apps->app_name }}</h4>
                <!-- <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry</p> -->
                </div>
            </div>
            @endforeach
        </div>
      </div>
    </section><!-- End Features Section -->

    <!-- ======= Screenshots Section ======= -->
    <section id="screenshots" class="padd-section text-center">

      <div class="container" data-aos="fade-up">
        <div class="section-title text-center">
          <h2>Apps Gallery</h2>
          <!-- <p class="separator">Integer cursus bibendum augue ac cursus .</p> -->
        </div>

        <div class="screens-slider swiper">
          <div class="swiper-wrapper align-items-center">
            <div class="swiper-slide"><img src="{{ asset('public/assets/fe/img/screen/1.jpg') }}" class="img-fluid" alt=""></div>
            <div class="swiper-slide"><img src="{{ asset('public/assets/fe/img/screen/2.jpg') }}" class="img-fluid" alt=""></div>
            <div class="swiper-slide"><img src="{{ asset('public/assets/fe/img/screen/3.jpg') }}" class="img-fluid" alt=""></div>
            <div class="swiper-slide"><img src="{{ asset('public/assets/fe/img/screen/4.jpg') }}" class="img-fluid" alt=""></div>
            <div class="swiper-slide"><img src="{{ asset('public/assets/fe/img/screen/5.jpg') }}" class="img-fluid" alt=""></div>
            <div class="swiper-slide"><img src="{{ asset('public/assets/fe/img/screen/6.jpg') }}" class="img-fluid" alt=""></div>
            <div class="swiper-slide"><img src="{{ asset('public/assets/fe/img/screen/7.jpg') }}" class="img-fluid" alt=""></div>
          </div>
          <div class="swiper-pagination"></div>
        </div>
      </div>

    </section><!-- End Screenshots Section -->

    <!-- ======= Newsletter Section ======= -->
    <section id="newsletter" class="newsletter text-center">
      <div class="overlay padd-section">
        <div class="container" data-aos="zoom-in">

          
        </div>
      </div>
    </section>
    <!-- End Newsletter Section -->

    <!-- ======= Contact Section ======= -->
    <section id="contact" class="padd-section">

      <div class="container" data-aos="fade-up">
        <div class="section-title text-center">
          <h2>We Will Help You</h2>
          <!-- <p class="separator">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque</p> -->
        </div>

        <div class="container">
          <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="100">
  
            <div class="col-lg-3 col-md-4">
  
              <div class="info">
                <div>
                  <i class="bi bi-ticket"></i>
                  <p>If you have problem<br>you can sent Ticket</p>
                </div>
  
                <div class="email">
                  <i class="bi bi-newspaper"></i>
                  <p>If you want to Purchase Request <br>you can sent PR </p>
                </div>
  
                <div>
                  <i class="bi bi-phone"></i>
                  <p>Your message will be processed by the department you choose</p>
                </div>
              </div>
  
              <div class="social-links">
                <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
              </div>
  
            </div>
  
            <div class="col-lg-5 col-md-8">
              <div class="form">
                <form action="{{url('add-request-action')}}" method="post" enctype="multipart/form-data">
                 {{csrf_field()}}
                  <div class="form-group">
                    <label>What your Action?</label>
                  </div>
                  <div class="form-group mt-3">
                    <input type="radio" class="btn-check" name="action" onclick="assetselect(this.value)" id="ticket" value="ticket" autocomplete="off" checked>
                     <label class="btn card-block" for="ticket" style="margin: 5px">
                        <div class="pic"> <i class="bi bi-ticket" style='font-size:50px; color: black'></i></div>
                        <span  style="color: black"> <strong>Ticket</strong></span>
                    </label>
  
                    <input type="radio" class="btn-check" name="action" onclick="catselect(this.value)" id="pr" value="pr" autocomplete="off">
                    <label class="btn card-block" for="pr" style="margin: 5px">
                        <div class="pic"> <i class="bi bi-newspaper" style='font-size:50px; color: black'></i></div>
                        <span  style="color: black"> <strong>PR</strong></span>
                    </label>
                 </div>
                 <br>
                 <div class="form-group">
                    <label>The Action to Department?</label>
                  </div>
                  
                 <div class="form-group mt-3">
                    <select name="dept" class="form-control js-example-basic-single" data-live-search="true" required="">
                      <option value="">Choose Departement</option>
                      @foreach($data['dept'] as $dept)
                          <option value="{{ $dept->id }}">{{ $dept->nama }}</option>
                      @endforeach
                    </select>
                  </div>
                 <br>
                 <div class="form-group"  id="cat">
                    <label>What The Category?</label>
                    <div class="form-group mt-3">
                    <input type="radio" class="btn-check" name="category" id="account" value="1" autocomplete="off" onclick="accountselect(this.value)" checked>
                     <label class="btn card-block-small" for="account" style="margin: 5px; align-content: center; ">
                      <i class="bi bi-ticket" style='font-size:25px; color: black'></i>
                      <br>
                       <strong style='font-size:9px; color: black'>Account</strong>
                       
                    </label>
  
                    <input type="radio" class="btn-check" name="category" id="inventory" value="0" onclick="inventoryselect(this.value)" autocomplete="off">
                    <label class="btn card-block-small" for="inventory" style="margin: 5px;align-content: center; ">
                      <i class="bi bi-newspaper" style='font-size:25px; color: black'></i>
                      <br>
                      <strong style='font-size:9px; color: black'>Inventory</strong>
                    </label>
                    <br>
                 </div>
                  </div>
                 <div class="form-group"  id="asset">
                    <label>Which is Your Asset?</label>
                    <div class="form-group mt-3">
                      @foreach($data['usingasset'] as $usingasset)
                        <input type="radio" class="btn-check" name="assets" value="{{ $usingasset->asset_id }}" id="{{ $usingasset->asset_id }}" autocomplete="off" >
                        <label class="btn card-block-x-small" for="{{ $usingasset->asset_id }}" style="margin: 5px; align-content: center; ">
                          <span style='font-size:7px; color: black'><strong>{{ $usingasset->asset_name }}</strong></span>
                          
                        </label>
                      @endforeach
                        <!-- <input type="radio" class="btn-check" name="assets" value="330" id="asset2" autocomplete="off">
                        <label class="btn card-block-x-small" for="asset2" style="margin: 5px; align-content: center; ">
                          <span style='font-size:7px; color: black'><strong>Dell Latitude 3380</strong></span>
                        </label> -->
                    </div>
                  </div>
                  <div class="form-group" id="much">
                    <label>How much?</label>
                    <div class="form-group mt-3">
                        <input type="number" value="1" name="qty" class="form-control" autocomplete="off">
                    </div>
                  </div>
                  <div class="form-group">
                    <br>
                    <label>What Your Message?</label>
                  </div>
                  <div class="form-group mt-3">
                    <textarea class="form-control" name="message" rows="5" placeholder="Message" required></textarea>
                  </div>
                  <!-- <div class="my-3">
                    <div class="loading">Loading</div>
                    <div class="error-message"></div>
                    <div class="sent-message">Your message has been sent. Thank you!</div>
                  </div> -->
  
                  <div class="text-center" style="margin:5px;"><button class="btn btn-success" type="submit">Send</button></div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section><!-- End Contact Section -->

  </main><!-- End #main -->

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
            <h3>HUT Astra 65</h3>
            <span class="close">&times;</span>
            </div> <!-- Modal Body-->
            <div class="modal-body">
                <div class="row">
                    <!--Gift Icon-->
                    <div class="col text-center">
                      <img src="{{ asset('public/assets/fe/img/astra-banner.png') }}" alt="img" height="250"> </div>
                    <!--Modal Text-->
                    <div class="col">
                        <p>Menuju HUT ke-65 Astra, Kobarkan Semangat Bergerak dan Tumbuh Bersama. 
                         <a href="http://65tahunastra.jagat.live/" target="_blank" class="btn btn-info">Link Live HUT Astra 65<i class="fa fa-gem"></i></a></p>
                        <h4> Ayo Insan Astra! Simpan kode voucher anda di bawah ini untuk digunakan di acara HUT Astra 65 </h4>
                        <br>
                        <input type="text"  id="code"  class="form-control text1" style="background-color: #dc3545;font-weight: bold; "  readonly   name="code" value="{{ $data['code'] }}">
                    </div>
                </div>
            </div> <!-- Modal Footer-->
            
            <div class="modal-footer"> 
              
            <span id="copied" class="pull-left"></span>
            <button onclick="copy()" class="btn btn-danger">Salin ke clipboard<i class="fa fa-gem"></i></button></div>
       
  </div>

</div>
  @endsection
  
  
@section('myscript')
<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
@endsection
