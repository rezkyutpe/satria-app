<div class="top_nav">
    <div class="nav_menu" style="background-color: red; color:white">
        <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
        </div>
        <nav class="nav navbar-nav">
            <ul class=" navbar-right">
                <li class="nav-item dropdown open" style="padding-left: 15px;">
                    <a href="#" class="user-profile" aria-haspopup="true" id="navbarDropdown"
                        data-toggle="dropdown" aria-expanded="false">
                        <img src="@if(Auth::user()->photo!='') {{ asset('public/profile/'.Auth::user()->photo) }} @else{{ asset('public/assets/images/user.png') }}@endif" alt="">
                            <span style="color: white">{{ Auth::user()->name }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ url('profile') }}"> Profile</a>
                        {{-- <a class="dropdown-item" href="{{ url('config-app') }}">Configuration</a> --}}
                        <a class="dropdown-item" href="{{ url('welcome') }}">Go To Web Portal</a>
                        <a onclick="return false" id="btn-delete" class="dropdown-item">Log Out</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
                <li role="presentation" class="nav-item dropdown open" id="notifikasi-komentar" style="margin-top: 5px;">
                    <a href="#" class="dropdown-toggle info-number" data-toggle="dropdown" style="color: white;">
                        <i class="fa fa-envelope"></i>
                        @if ($data['count'] > 0)
                            <span class="badge bg-green"><?= $data['count'] ?></span>
                        @endif
                    </a>
                    <ul class="dropdown-menu list-unstyled msg_list" role="menu" aria-labelledby="navbarDropdown1">
                        @foreach ($data['komentar'] as $komentar)
                            <li class="nav-item">
                                @if ($komentar['apps'] == 'CCR')
                                    <a class="dropdown-item" href="{{ url('material-detail/' . $komentar['material']) }}"
                                        @if ($komentar['is_read'] == null) style="color:black" @endif
                                    >
                                        <span>
                                            <span class="badge badge-primary">
                                                CCR
                                            </span>
                                            <span class="time">{{ 'Material : '.$komentar['material'] }}</span>
                                        </span>
                                        <br>
                                        <span>
                                            <span>{{ $komentar['sender'] }}</span>
                                        </span>
                                        <span class="message">
                                            {{ $komentar['chat'] }}
                                        </span>
                                    </a>
                                @else
                                    <a onclick="getChat('{{$komentar['po_no']}}','{{$komentar['itemNumber']}}')" style="cursor: pointer"
                                        @if ($komentar['is_read'] == null) style="color:black" @endif
                                    >
                                        <span>
                                            <span class="badge badge-success">
                                                PO Tracking
                                            </span>
                                            <span class="time">{{ 'Po No.'.$komentar['po_no'].'- Item:'.$komentar['itemNumber'] }}</span>
                                        </span>
                                        <br>
                                        <span>
                                            <span>{{ $komentar['sender'] }}</span>
                                        </span>
                                        <span class="message">
                                            {{ $komentar['chat'] }}
                                        </span>
                                    </a>
                                    
                                @endif
                            </li>
                        @endforeach

                        <li class="nav-item">
                            <div class="text-center">
                                <a class="dropdown-item" href="{{ url('comment-all') }}">
                                    <strong>See All Notifications</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="text-center">
                                <a class="dropdown-item" href="{{ url('mark-notification-read') }}">
                                    <strong>Mark All As Read</strong>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>
