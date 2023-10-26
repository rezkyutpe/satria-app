
<!DOCTYPE html>
<html lang="en">
    <!--begin::Head-->
    <head><base href="{{ asset('public/assets/theme/dist/assets/') }}">
        <title>SATRiA</title>
        <meta name="description" content="SATRiA - PATRiA" />
        <meta name="keywords" content="SATRiA - PATRiA" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta charset="utf-8" />
        <link rel="shortcut icon" href="{{ asset('public/assets/global/img/favicon.png') }}" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
        <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    </head>
    <!--end::Head-->
    <!--begin::Body-->
    <body id="kt_body" class="auth-bg">
        <div class="d-flex flex-column flex-root">
            <!--begin::Authentication - Sign-in -->
            <div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" style="background-image: url(assets/media/illustrations/dozzy-1/14.png">
                <!--begin::Content-->
                <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
                    <!--begin::Logo-->
                    <a  class="mb-12">
                        <img alt="Logo" src="{{ asset('public/assets/global/img/side-logo.png') }}" class="h-100px" />
                    </a>
                    <!--end::Logo-->
                    <!--begin::Wrapper-->
                    <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
                        <!--begin::Form-->
                        
                        <form id="form-submit" class="form w-100 form-sbmt" method="POST" action="{{url('login-in')}}" >
                            {{csrf_field()}}
                        <div class="text-center mb-10">
                            <!--begin::Title-->
                            <h1 class="text-dark mb-3">Sign In to Satria</h1>
                            <!--end::Title-->
                            <!--begin::Link-->
                            <div class="text-gray-400 fw-bold fs-4">New Here? 
                            <a href="{{ url('register') }}" class="link-primary fw-bolder">Create an Account</a></div>
                            <!--end::Link-->
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                @if(session()->has('err_message'))
                                    <div class="alert alert-danger alert-dismissible" role="alert" auto-close="2000">
                                        {{ session()->get('err_message') }}
                                    </div>
                                </span>
                                @endif
                                @if(session()->has('succ_message'))
                                    <div class="alert alert-success alert-dismissible" role="alert" auto-close="10000">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        {{ session()->get('succ_message') }}
                                    </div>
                                @endif
                            
                            
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label fw-bolder text-dark fs-6">Email or NRP Sunfish</label>
                            <input id="email" type="text" class="form-control form-control-lg form-control-solid @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="off">
                            
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label fw-bolder text-dark fs-6">Password</label>
                            <input id="password" type="password" class="form-control form-control-lg form-control-solid @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}" required autocomplete="off">
                            
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    
                        <div class="text-center">
                            <br>
                            <button onclick="mySubmitLoad()" class="submit-button btn btn-lg btn-primary w-100 mb-5">
                                <span id="submit-label" class="indicator-label">Login
                                <span id="submit-progress" class="indicator-progress" style="display: none">Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </span>
                            </button>            
                        </div>
                    </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Content-->
                <div class="d-flex flex-center flex-column-auto p-10">
                    <!--begin::Links-->
                    <div class="d-flex align-items-center fw-bold fs-6">
                                <span class="text-muted fw-bold me-1">2021©</span>
                                <a class="text-gray-800 text-hover-primary">SATRiA</a>
                    </div>
                    <!--end::Links-->
                </div>
            </div>
            <!--end::Authentication - Sign-in-->
        </div>
        <!--end::Main-->
        <!--begin::Javascript-->
        <script>var hostUrl = "assets/";</script>
        <!--begin::Global Javascript Bundle(used by all pages)-->
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
       
        <script>
            function mySubmitLoad() {
            document.getElementById("submit-label").innerHTML = "Please wait...<span class='spinner-border spinner-border-sm align-middle ms-2'></span>";
            document.getElementByClassName("submit-button ").disabled = true;
            // document.getElementById("submit-progress").style.display = "block";
            document.getElementById("form-submit").submit();
            }
        </script>
    </body>
    <!--end::Body-->
</html>