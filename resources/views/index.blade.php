@extends('fe-layouts.master')

@section('content')
<!--begin::Container-->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <!--begin::Post-->
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::About card-->
        <div class="card">
            <!--begin::Body-->
            <div class="card-body p-lg-17">
                <!--begin::About-->
                <div class="mb-18">
                    <!--begin::Wrapper-->
                    <div class="mb-10">
                        <!--begin::Top-->
                        <div class="text-center mb-15">
                            <!--begin::Title-->
                            <h3 class="fs-2hx text-dark mb-5">Welcome to Satria Webportal</h3>
                            <!--end::Title-->
                            <!--begin::Text-->
                            <div class="fs-5 text-muted fw-bold">Super Architecture To Reunite Internal Application<br>Creative Simple Design,Multi Platform,Easy To Use,Easy To Access,Many Features</div>
                            <!--end::Text-->
                        </div>
                        <!--end::Top-->
                        <!--begin::Overlay-->
                        <div class="overlay">
                            <!--begin::Image-->
                            <center>
                            <img class="w-20 card-rounded" src="{{ asset('public/assets/fe/img/about-img.png') }}" width="300" alt="" /></center>
                        </div>
                        <!--end::Container-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Description-->
                    <div class="fs-5 fw-bold text-gray-600">
                        <!--begin::Text-->
                        <center>
                        <h2><strong>Satria </strong>Mobile Apps</h2>	</center>							
                    </div>
                    <!--end::Description-->
                </div>
                <!--end::About-->
                                <!--begin::Testimonial-->
                        <div class="fs-2 fw-bold text-muted text-center mb-3">
                        <span class="fs-1 lh-1 text-gray-700">“</span>Welcoming A Fresh Start
                        <br />
                        <span class="text-gray-700 me-1">More Powerful Apps</span>, Build The Apps Everyone Love
                        <span class="fs-1 lh-1 text-gray-700">“</span></div><br>
                                    <!--end::Testimonial-->
                <!--begin::Statistics-->
                <div class="card bg-light mb-18">
                    <!--begin::Body-->
                    <div class="card-body py-15"><!--begin::Author-->
                        <div class="fs-2 fw-bold text-muted text-center">
                            <h2>Our Services</h2>
                            <a  class="link-primary fs-4 fw-bolder">The Apps From Our Development</a>
                            <span class="fs-4 fw-bolder text-gray-600">At Satria.</span>
                        </div> 
                        <br>
                        <!--end::Author-->
                        <!--begin::Wrapper-->
                        <div class="d-flex flex-center">
                            <!--begin::Items-->
                            <div class="row g-0 d-flex justify-content-between mb-12 mx-auto w-xl-900px">
                                <!--begin::Item-->
                                @php($actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]")
                                @foreach($data['apps'] as $apps)
                                <div class="octagon mb-3 d-flex flex-center h-200px w-200px bg-white mx-2">
                                    @if($apps->id!='15' && $apps->id!='20')
                                    <form method="post" action="{{url('update-config')}}" >
                                        {{csrf_field()}}
                                        <button type="submit" style="border: none;  padding: 0;  background: none;">
                                            <div class="text-center">
                                                <span class="svg-icon svg-icon-2tx svg-icon-primary">
                                                <img src="{{ asset('public/assets/global/img/icon/'.$apps->logo) }}" width="40" height="40">
                                                </span>
                                                <div class="mt-1">
                                                    <div class="fs-lg-2hx fs-2x fw-bolder text-gray-800 d-flex align-items-center"></div>
                                                    <span class="text-gray-600 fw-bold fs-5 lh-0">{{ $apps->app_name }}</span>
                                                    <input type="hidden" name="apps" value="{{ $apps->id }}">
                                                </div>
                                            </div>
                                        </button>
                                    </form>
                                    @else
                                        @if($apps->id=='15')
                                        <a href="{{ $actual_link }}/satria-eqm/public/eqm-management">
                                        @elseif($apps->id=='20')
                                        <a href="{{ $actual_link }}/satria-kpi-tracking/public/kpi-tracking">
                                        @else
                                        <a href="{{ $actual_link }}/satria/welcome">
                                        @endif
                                            <span class="svg-icon svg-icon-2tx svg-icon-primary">
                                            <img src="{{ asset('public/assets/global/img/icon/'.$apps->logo) }}" width="40" height="40">
                                            </span>
                                            <div class="mt-1">
                                                <div class="fs-lg-2hx fs-2x fw-bolder text-gray-800 d-flex align-items-center"></div>
                                                <span class="text-gray-600 fw-bold fs-5 lh-0">{{ $apps->app_name }}</span>
                                                <input type="hidden" name="apps" value="{{ $apps->id }}">
                                            </div>
                                        </a>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Statistics-->
                <!--begin::Section-->
                <div class="mb-16">
                    <!--begin::Top-->
                    <div class="text-center mb-12">
                        <!--begin::Title-->
                        <h3 class="fs-2hx text-dark mb-5">Apps Gallery</h3>
                        <!--end::Title-->
                    </div>
                    <!--end::Top-->
                    <!--begin::Row-->
                    <div class="row g-10">
                        <!--begin::Col-->
                        <div class="col-md-4">
                            <!--begin::Publications post-->
                            <div class="card-xl-stretch me-md-6">
                                <!--begin::Overlay-->
                                <a class="d-block overlay mb-4" data-fslightbox="lightbox-hot-sales" href="assets/media/stock/600x400/img-22.jpg">
                                    <!--begin::Image-->
                                    <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px" style="background-image:url('assets/media/stock/600x400/img-22.jpg')"></div>
                                    <!--end::Image-->
                                    <!--begin::Action-->
                                    <div class="overlay-layer bg-dark card-rounded bg-opacity-25">
                                        <i class="bi bi-eye-fill fs-2x text-white"></i>
                                    </div>
                                    <!--end::Action-->
                                </a>
                                <!--end::Overlay-->
                                
                            </div>
                            <!--end::Publications post-->
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-md-4">
                            <!--begin::Publications post-->
                            <div class="card-xl-stretch mx-md-3">
                                <!--begin::Overlay-->
                                <a class="d-block overlay mb-4" data-fslightbox="lightbox-hot-sales" href="assets/media/stock/600x400/img-1.jpg">
                                    <!--begin::Image-->
                                    <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px" style="background-image:url('assets/media/stock/600x400/img-1.jpg')"></div>
                                    <!--end::Image-->
                                    <!--begin::Action-->
                                    <div class="overlay-layer bg-dark card-rounded bg-opacity-25">
                                        <i class="bi bi-eye-fill fs-2x text-white"></i>
                                    </div>
                                    <!--end::Action-->
                                </a>
                                <!--end::Overlay-->
                                
                            </div>
                            <!--end::Publications post-->
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-md-4">
                            <!--begin::Publications post-->
                            <div class="card-xl-stretch ms-md-6">
                                <!--begin::Overlay-->
                                <a class="d-block overlay mb-4" data-fslightbox="lightbox-hot-sales" href="assets/media/stock/600x400/img-3.jpg">
                                    <!--begin::Image-->
                                    <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px" style="background-image:url('assets/media/stock/600x400/img-3.jpg')"></div>
                                    <!--end::Image-->
                                    <!--begin::Action-->
                                    <div class="overlay-layer bg-dark card-rounded bg-opacity-25">
                                        <i class="bi bi-eye-fill fs-2x text-white"></i>
                                    </div>
                                    <!--end::Action-->
                                </a>
                                <!--end::Overlay-->
                                
                            </div>
                            <!--end::Publications post-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row g-10">
                        <!--begin::Col-->
                        <div class="col-md-4">
                            <!--begin::Publications post-->
                            <div class="card-xl-stretch me-md-6">
                                <!--begin::Overlay-->
                                <a class="d-block overlay mb-4" data-fslightbox="lightbox-hot-sales" href="assets/media/stock/600x400/img-20.jpg">
                                    <!--begin::Image-->
                                    <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px" style="background-image:url('assets/media/stock/600x400/img-20.jpg')"></div>
                                    <!--end::Image-->
                                    <!--begin::Action-->
                                    <div class="overlay-layer bg-dark card-rounded bg-opacity-25">
                                        <i class="bi bi-eye-fill fs-2x text-white"></i>
                                    </div>
                                    <!--end::Action-->
                                </a>
                                <!--end::Overlay-->
                                
                            </div>
                            <!--end::Publications post-->
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-md-4">
                            <!--begin::Publications post-->
                            <div class="card-xl-stretch mx-md-3">
                                <!--begin::Overlay-->
                                <a class="d-block overlay mb-4" data-fslightbox="lightbox-hot-sales" href="assets/media/stock/600x400/img-5.jpg">
                                    <!--begin::Image-->
                                    <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px" style="background-image:url('assets/media/stock/600x400/img-5.jpg')"></div>
                                    <!--end::Image-->
                                    <!--begin::Action-->
                                    <div class="overlay-layer bg-dark card-rounded bg-opacity-25">
                                        <i class="bi bi-eye-fill fs-2x text-white"></i>
                                    </div>
                                    <!--end::Action-->
                                </a>
                                <!--end::Overlay-->
                                
                            </div>
                            <!--end::Publications post-->
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-md-4">
                            <!--begin::Publications post-->
                            <div class="card-xl-stretch ms-md-6">
                                <!--begin::Overlay-->
                                <a class="d-block overlay mb-4" data-fslightbox="lightbox-hot-sales" href="assets/media/stock/600x400/img-24.jpg">
                                    <!--begin::Image-->
                                    <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px" style="background-image:url('assets/media/stock/600x400/img-24.jpg')"></div>
                                    <!--end::Image-->
                                    <!--begin::Action-->
                                    <div class="overlay-layer bg-dark card-rounded bg-opacity-25">
                                        <i class="bi bi-eye-fill fs-2x text-white"></i>
                                    </div>
                                    <!--end::Action-->
                                </a>
                                <!--end::Overlay-->
                                
                            </div>
                            <!--end::Publications post-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Section-->
                
            </div>
            <!--end::Body-->
        </div>
        <!--end::About card-->
    </div>
    <!--end::Post-->
</div>
<!--end::Container-->

@endsection