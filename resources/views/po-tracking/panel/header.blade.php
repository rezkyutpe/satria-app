<div class="top_nav">
    <div class="nav_menu">
        <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
        </div>
        <div style="float: left;
            margin: 0;">
            <form action="{{ url('cari-po') }}" method="post" class="form-horizontal form-label-left">
                {{ csrf_field() }}
                <div class="form-group row" style="margin-bottom:1px;">
                    <div class="input-group">
                        <input type="text" name="Number" class="form-control"
                            style="margin-top: 12px;padding: 18px;" placeholder="Search PO"
                            value="{{ request()->session()->get('Number') }}" autocomplete="off">
                        <span class="input-group-btn" style="margin-top: 12px;">
                            <button type="submit" class="btn btn-primary">Go!</button>
                        </span>

                    </div>
                </div>
            </form>

        </div>
        <nav class="nav navbar-nav">
            <ul class=" navbar-right">
                <li class="nav-item dropdown open" style="padding-left: 15px;">
                    <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown"
                        data-toggle="dropdown" aria-expanded="false">
                        <img src="@if (Auth::user()->photo != '') {{ asset('public/profile/' . Auth::user()->photo) }} @else{{ asset('public/assetss/images/user.png') }} @endif"
                            alt="">{{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                       @if (Auth::user()->role_id == 30 || Auth::user()->role_id == null)
                            <a class="dropdown-item " href="{{ url('profile-vendor') }}"> Profile</a>
                        @else
                            <a class="dropdown-item" href="{{ url('profile') }}"> Profile</a>
                        @endif 
                        {{-- <a class="dropdown-item" href="{{ url('config-app') }}">Configuration</a> --}}
                        <a class="dropdown-item" href="{{ url('welcome') }}">Go To Web Portal</a>
                        <a onclick="return false" id="btn-delete" class="dropdown-item">Log Out</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>


                <li role="presentation" class="nav-item dropdown open" data-toggle="tooltip"
                    style="margin-right:12px;margin-top:5px;" title="Notification">

                    <a href="#" class="info-number" data-toggle="dropdown" id="dropdownMenuButton2"
                        aria-expanded="false">
                        <i class="fa fa-bell" style="padding: 5px;font-size:large;"></i>
                        <span class="badge bg-green"
                            style="color: #f8f8f8; font-size:12px; font-weight: normal;
                            line-height: 13px;
                            padding: 2px 6px;
                            position: absolute;
                            right: -11px;
                            top: -13px; ">{{ $data['countnotif'] ?? 0 }}
                        </span>
                    </a>


                    <ul class="dropdown-menu list-unstyled msg_list" role="menu"
                        aria-labelledby="dropdownMenuButton1"
                        style="overflow: auto;height:500px; left:-9px; width: 341px !important;">
                        <li class="nav-item"
                            style="padding: 15px 20px;
                        border-radius: 10px 10px 0 0;
                        background: #7107b3; ">
                            <div class="text-center">
                                <strong style="color: #fff">Notification</strong>
                            </div>
                        </li>

                        @foreach ($data['notif'] as $k => $item)
                            @php
                                $source = $item->created_at;
                                $date = new DateTime($source);
                                if ($item->menu == 'Ticket Local') {
                                    $background = '#0d25cd0d;';
                                } elseif ($item->menu == 'Cancel Ticket Local') {
                                    $background = '#0d25cd0d;';
                                } elseif ($item->menu == 'Approve Ticket Local') {
                                    $background = '#0d25cd0d;';
                                } else {
                                    $background = '#0d25cd0d;';
                                }
                                if ($item->is_read == 1) {
                                    $background = '';
                                }
                            @endphp
                            <li class="nav-item" style="background:{{ $background }}">
                                @php
                                    if ($item->Subjek == 'Ticket Local') {
                                        $badge = 'bg-primary';
                                        $status = 'New Ticket';
                                    } elseif ($item->Subjek == 'Ticket Subcont') {
                                        $badge = 'bg-primary';
                                        $status = 'New Ticket';
                                    } elseif ($item->Subjek == 'Cancel Ticket Local') {
                                        $badge = 'bg-danger';
                                        $status = 'Ticket Cancel';
                                    } elseif ($item->Subjek == 'Cancel Ticket Subcont') {
                                        $badge = 'bg-danger';
                                        $status = 'Ticket Cancel';
                                    } elseif ($item->Subjek == 'Approve Ticket Local') {
                                        $badge = 'bg-success';
                                        $status = 'Ticket Approve';
                                    } elseif ($item->Subjek == 'Approve Ticket Subcont') {
                                        $badge = 'bg-success';
                                        $status = 'Ticket Approve';
                                    } elseif ($item->Subjek == 'Confirm New PO') {
                                        $badge = 'bg-warning';
                                        $status = 'Confirm New PO';
                                    } elseif ($item->Subjek == 'Cancel PO') {
                                        $badge = 'bg-danger';
                                        $status = 'Cancel PO';
                                    } elseif ($item->Subjek == 'Proforma') {
                                        $badge = 'bg-primary';
                                        $status = 'Proforma';
                                    } elseif ($item->Subjek == 'Approve PO') {
                                        $badge = 'bg-success';
                                        $status = 'Approve PO';
                                    } elseif ($item->Subjek == 'Revisi Proforma') {
                                        $badge = 'bg-info';
                                        $status = 'Revisi Proforma';
                                    } elseif ($item->Subjek == 'Request Proforma') {
                                        $badge = 'bg-secondary';
                                        $status = 'Request Proforma';
                                    } elseif ($item->Subjek == 'Vendor Negotiated PO') {
                                        $badge = 'bg-warning';
                                        $status = 'Vendor Negotiated PO';
                                    } else {
                                        $badge = 'bg-dark';
                                        $status = 'New Ticket';
                                        $link = '#';
                                    }
                                    if ($item->menu) {
                                        $link = $item->menu;
                                    } else {
                                        $link = 'ticket';
                                    }
                                @endphp
                                @if ($link != 'ticket')
                                    <a class="dropdown-item" href="{{ url($link . '?Number=' . $item->Number) }}"
                                        style="color: #000000">
                                    @else
                                        <a class="dropdown-item" onclick="getNotif('{{ $item->id }}')"
                                            style="color: #000000">
                                @endif
                                <span>
                                    <span>{{ $item->user_by }}</span>
                                    <span class="time"> {{ $date->format('d/m/Y') }}</span>
                                </span>
                                <span class="message">
                                    PO {{ $item->Number }} <span class="badge {{ $badge }}"
                                        style="color: #ebebeb">{{ $status }}</span>
                                </span>

                                <span class="message">
                                    {{ $item->comment }}
                                </span>

                                </a>
                            </li>
                        @endforeach

                        <li class="nav-item" style="background: #7107b3; border: 1px solid #7107b3;">
                            <div class="text-center">
                                <a href="{{ url('allnotification-potracking') }}">
                                    <strong style="color: #fff">See All</strong>

                                </a>
                            </div>
                        </li>
                    </ul>

                </li>
                <li role="presentation" class="nav-item dropdown open" data-toggle="tooltip" title="Chat"
                    style="margin-right: 5px;
                margin-top: 4px;">

                    <a href="#" class="info-number" data-toggle="dropdown" id="navbarDropdown2"
                        aria-expanded="false">
                        <i class="fa fa-envelope-o" style="padding: 5px; font-size:large;"></i>
                        <span class="badge bg-danger"
                            style="color: #f8f8f8; font-size:12px; font-weight: normal;
                            line-height: 12px;
                            padding: 2px 6px;
                            position: absolute;
                            right: -5px;
                            top: -11px; ">{{ $data['countnotifchat'] ?? 0 }}
                        </span>
                    </a>
                    <ul class="dropdown-menu list-unstyled msg_list" role="menu" aria-labelledby="navbarDropdown2"
                        style="overflow: auto;height:500px; width: 320px !important;">
                        <li class="nav-item"
                            style="padding: 15px 20px;
                        border-radius: 10px 10px 0 0;
                        background: #b32707; ">
                            <div class="text-center">
                                <strong style="color: #fff">Messenger</strong>
                            </div>
                        </li>

                        @foreach ($data['notifchat'] as $k => $item)
                            @php
                                $source = $item->created_at;
                                $date = new DateTime($source);
                                if ($item->is_read == 1) {
                                    $background = 'beige;';
                                } else {
                                    $background = '';
                                }
                            @endphp
                            <li class="nav-item" style="background:{{ $background }}">
                                <a class="dropdown-item"
                                    onclick="getChat('{{ $item->Number }}','{{ $item->ItemNumber }}')">
                                    <span>
                                        <span>{{ $item->user_by }}</span>
                                        <span class="time"> {{ $date->format('d/m/Y') }}</span>
                                    </span>
                                    <span class="message">
                                        PO {{ $item->Number }} Item Number {{ $item->ItemNumber }}
                                    </span>
                                    <span class="message">
                                        {{ $item->comment }}
                                    </span>
                                </a>
                            </li>
                        @endforeach

                        <li class="nav-item" style="background: #b32707; border: 1px solid red;">
                            <div class="text-center">
                                <a href="{{ url('allmesengger-potracking') }}">
                                    <strong style="color: #fff">See All</strong>

                                </a>
                            </div>
                        </li>
                    </ul>

                </li>
                <li role="presentation" class="nav-item dropdown open" data-toggle="tooltip" title="Kanban"
                    style="margin-right: 5px;
            margin-top: 4px;">

                    <a href="#" class="info-number" data-toggle="dropdown" id="navbarDropdown2"
                        aria-expanded="false">
                        <i class="fa fa-list-alt" style="padding: 5px; font-size:large;"></i>
                        <span class="badge bg-primary"
                            style="color: #f8f8f8; font-size:12px; font-weight: normal;
                        line-height: 12px;
                        padding: 2px 6px;
                        position: absolute;
                        right: -5px;
                        top: -12px; ">{{ $data['countnotifkanban'] ?? 0 }}
                        </span>
                    </a>
                    <ul class="dropdown-menu list-unstyled msg_list" role="menu" aria-labelledby="navbarDropdown2"
                        style="overflow: auto;height:500px; width: 320px !important;">
                        <li class="nav-item"
                            style="padding: 15px 20px;
                    border-radius: 10px 10px 0 0;
                    background: #0b12d5; ">
                            <div class="text-center">
                                <strong style="color: #fff">Memo</strong>
                            </div>
                        </li>

                        @foreach ($data['notifkanban'] as $s => $items)
                            @php
                                $source = $items->created_at;
                                $date = new DateTime($source);
                                if ($items->is_read == 1) {
                                    $background = 'beige;';
                                } else {
                                    $background = '';
                                }
                            @endphp
                            <li class="nav-item" style="background:{{ $background }}">
                                <a class="dropdown-item" onclick="getKanban('{{ $items->comment }}')">
                                    <span>
                                        <span>{{ $items->user_by }}</span>
                                        <span class="time"> {{ $date->format('d/m/Y') }}</span>
                                    </span>
                                    <span class="message">
                                        PO {{ $items->Number }}
                                    </span>
                                    <span class="message">
                                        {{ $items->comment }}
                                    </span>
                                </a>
                            </li>
                        @endforeach

                        <li class="nav-item" style="background: #0b12d5; border: 1px solid #0b12d5;">
                            <div class="text-center">
                                <a href="{{ url('allmesengger-potracking') }}">
                                    <strong style="color: #fff">See All</strong>

                                </a>
                            </div>
                        </li>
                    </ul>

                </li>


            </ul>
        </nav>
    </div>
</div>


<style>
    .centered {
        position: absolute;
        top: 42%;
        left: 73%;
        transform: translate(-50%, -50%);
        line-height: 5;
        border-radius: 50%;
        font-size: 10px;
        color: #fff;
        text-transform: uppercase;
        text-align: center;
        background: rgb(160, 6, 6);
        width: 50px;
        height: 50px;
        animation: anim 3s infinite;
    }

    @keyframes anim {
        0% {
            margin-top: 0;
        }

        16% {
            margin-top: -30px;

        }

    }

    .containers {
        max-width: 1170px;
    }

    img {
        max-width: 100%;
    }

    .inbox_people {
        background: #f8f8f8 none repeat scroll 0 0;
        float: left;
        overflow: hidden;
        width: 40%;
        border-right: 1px solid #c4c4c4;
    }

    .inbox_msg {
        border: 1px solid #c4c4c4;
        clear: both;
        overflow: hidden;
    }

    .top_spac {
        margin: 20px 0 0;
    }


    .recent_heading {
        float: left;
        width: 40%;
    }

    .srch_bar {
        display: inline-block;
        text-align: right;
        width: 60%;
    }

    .headind_srch {
        padding: 10px 29px 10px 20px;
        overflow: hidden;
        border-bottom: 1px solid #c4c4c4;
    }

    .recent_heading h4 {
        color: #05728f;
        font-size: 21px;
        margin: auto;
    }

    .srch_bar input {
        border: 1px solid #cdcdcd;
        border-width: 0 0 1px 0;
        width: 80%;
        padding: 2px 0 4px 6px;
        background: none;
    }

    .srch_bar .input-group-addon button {
        background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
        border: medium none;
        padding: 0;
        color: #707070;
        font-size: 18px;
    }

    .srch_bar .input-group-addon {
        margin: 0 0 0 -27px;
    }

    .chat_ib h5 {
        font-size: 15px;
        color: #464646;
        margin: 0 0 8px 0;
    }

    .chat_ib h5 span {
        font-size: 13px;
        float: right;
    }

    .chat_ib p {
        font-size: 14px;
        color: #989898;
        margin: auto
    }

    .chat_img {
        float: left;
        width: 11%;
    }

    .chat_ib {
        float: left;
        padding: 0 0 0 15px;
        width: 88%;
    }

    .chat_people {
        overflow: hidden;
        clear: both;
    }

    .chat_list {
        border-bottom: 1px solid #c4c4c4;
        margin: 0;
        padding: 18px 16px 10px;
    }

    .inbox_chat {
        height: 550px;
        overflow-y: scroll;
    }

    .active_chat {
        background: #ebebeb;
    }

    .incoming_msg_img {
        display: inline-block;
        width: 6%;
    }

    .received_msg {
        display: inline-block;
        padding: 0 0 0 10px;
        vertical-align: top;
        width: 92%;
    }

    .received_withd_msg p {
        background: #ebebeb none repeat scroll 0 0;
        border-radius: 3px;
        color: #646464;
        font-size: 14px;
        margin: 0;
        padding: 5px 10px 5px 12px;
        width: 100%;
    }

    .time_date {
        color: #747474;
        display: block;
        font-size: 12px;
        margin: 8px 0 0;
    }

    .received_withd_msg {
        width: 57%;
    }

    .mesgs {
        float: left;
        padding: 30px 15px 0 25px;
        width: 99%;
    }

    .sent_msg p {
        background: #05728f none repeat scroll 0 0;
        border-radius: 3px;
        font-size: 14px;
        margin: 0;
        color: #fff;
        padding: 5px 10px 5px 12px;
        width: 100%;
    }

    .outgoing_msg {
        overflow: hidden;
        margin: 26px 0 26px;
    }

    .sent_msg {
        float: right;
        width: 46%;
    }

    .input_msg_write input {
        background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
        border: medium none;
        color: #4c4c4c;
        font-size: 15px;
        min-height: 48px;
        width: 100%;
    }

    .type_msg {
        border-top: 1px solid #c4c4c4;
        position: relative;
    }

    .msg_send_btn {
        background: #05728f none repeat scroll 0 0;
        border: medium none;
        border-radius: 50%;
        color: #fff;
        cursor: pointer;
        font-size: 17px;
        height: 33px;
        position: absolute;
        right: 0;
        top: 11px;
        width: 33px;
    }

    .messaging {
        padding: 0 0 50px 0;
    }

    .msg_history {
        height: 516px;
        overflow-y: auto;
    }
</style>

@include('po-tracking.panel.cek_coment')
@include('po-tracking.panel.cek_notif')
@include('po-tracking.panel.kanban')
