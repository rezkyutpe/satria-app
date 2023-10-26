<div class="top_nav">
    <div class="nav_menu" style="background-color:#c7081b;">
        <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars" style="color:white"></i></a>
        </div>
        <nav class="nav navbar-nav">
            <ul class=" navbar-right">
                <li class="nav-item dropdown open" style="padding-left: 15px;">
                    <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown"
                        data-toggle="dropdown" aria-expanded="false">
                        <img src="https://webportal.patria.co.id/satria/public/profile/iqbalnurfauzi.patria@gmail.com.png"
                            alt="">
                        <span style="color:white">{{ Auth::user()->email }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                        <!-- <a class="dropdown-item" href="javascript:;"> Profile</a>
                        <a class="dropdown-item" href="{{ url('config-app') }}">Configuration</a> -->
						<a href="{{ url('welcome') }}" class="dropdown-item">Go to Webportal</a>
                        <a onclick="return false" id="Logout" class="dropdown-item">Log Out</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>

                <li role="presentation" class="nav-item dropdown open">
                    <!-- <a href="javascript:;" class="dropdown-toggle info-number" id="navbarDropdown1" data-toggle="dropdown" aria-expanded="false"> -->
                    <!-- <i class="fa fa-envelope-o"></i> -->
                    <!-- <span class="badge bg-green">6</span> -->
                    <!-- </a> -->
                    <ul class="dropdown-menu list-unstyled msg_list" role="menu" aria-labelledby="navbarDropdown1">
                        <li class="nav-item">
                            <a class="dropdown-item">
                                <span class="image"><img src="{{ asset('public/assets-cogs/img.jpg') }}"
                                        alt="Profile Image" /></span>
                                <span>
                                    <span>John Smith</span>
                                    <span class="time">3 mins ago</span>
                                </span>
                                <span class="message">
                                    Film festivals used to be do-or-die moments for movie makers. They were where...
                                </span>
                            </a>
                        </li>

                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>
