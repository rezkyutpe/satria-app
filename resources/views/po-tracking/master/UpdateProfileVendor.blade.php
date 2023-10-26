@extends('po-tracking.panel.master')
@section('content')
    <div class="row">
        <div class="col-md-12 ">
            <div class="x_panel">

                <div>
                    @if (session()->has('err_message'))
                        <div class="alert alert-danger alert-dismissible" role="alert" auto-close="10000">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            {{ session()->get('err_message') }}
                        </div>
                    @endif
                    @if (session()->has('suc_message'))
                        <div class="alert alert-success alert-dismissible" role="alert" auto-close="10000">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            {{ session()->get('suc_message') }}
                        </div>
                    @endif
                </div>
                <div class="x_title">
                    <h2>Profile Vendor</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                aria-expanded="false"><i class="fa fa-wrench"></i></a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#">Settings 1</a>
                                <a class="dropdown-item" href="#">Settings 2</a>
                            </div>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <small style="color:red ; font-size: 100%;">* Upload File Hanya bisa dengan file PDF</small>
                <br>
                <a class="btn btn-primary btn-sm" href="{{ url('docpernyataan/1') }}">Pernyataan Rekening Vendor <i
                        class="fa fa-download" aria-hidden="true"></i></a>
                <a class="btn btn-primary btn-sm" href="{{ url('docpernyataan/2') }}">Pernyataan Taat Pajak <i
                        class="fa fa-download" aria-hidden="true"></i></a>
                <a class="btn btn-primary btn-sm" href="{{ url('docpernyataan/3') }}">Pernyataan Etika Transaksi <i
                        class="fa fa-download" aria-hidden="true"></i></a>
                <a class="btn btn-danger btn-sm" href="{{ url('docpernyataan/4') }}">Petunjuk Penamaan File <i
                        class="fa fa-download" aria-hidden="true"></i></a>
                <a class="btn btn-danger btn-sm" href="{{ url('docpernyataan/5') }}">Panduan AHU <i class="fa fa-download"
                        aria-hidden="true"></i></a>
                <div class="x_content">

                    <br />
                    <form method="post" action="{{ url('update-vendor') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class='col-sm-4'>
                                Name
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='text' class="form-control" value="{{ $data->Name }}" readonly>
                                        <span class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                Vendor Code
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='text' name="VendorCode" class="form-control"
                                            value="{{ $data->VendorCode }}" readonly>
                                        <span class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                Vendor Code New
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='text' class="form-control" value="{{ $data->VendorCode_new }}"
                                            readonly>
                                        <span class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                Email Perusahaan
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='text' name="Email" class="form-control"
                                            value="{{ $data->Email }}" autocomplete="off" required>
                                        <span class="input-group-addon">
                                            <span class="fa fa-envelope"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                Email PO <small style="color:red ; ">* Email Active Notifikasi</small>
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='text' name="EmailPO" class="form-control"
                                            value="{{ $data->EmailPO }}" autocomplete="off" required>
                                        <span class="input-group-addon">
                                            <span class="fa fa-envelope"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                Phone No
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='text' name="PhoneNo" class="form-control"
                                            value="{{ $data->PhoneNo }}" autocomplete="off" required>
                                        <span class="input-group-addon">
                                            <span class="fa fa-phone"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class='col-sm-12'>
                                Alamat
                                <div class="form-group">
                                    <div class='input-group '>
                                        <textarea name="Address" class="form-control" cols="30" rows="2" required>{{ $data->Address }}</textarea>
                                        <span class="input-group-addon">
                                            <span class="fa fa-home"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                CountryCode
                                <div class="form-group">
                                    <div class='input-group '>
                                        <select name="CountryCode" class="form-control" >
                                            <option value="{{ $data->CountryCode }}" selected>{{ $data->CountryCode }}</option>
                                            @foreach ($CountryCode as $k )
                                                @if($data->CountryCode != $k->CountryCode)
                                                <option value="{{ $k->CountryCode }}" >{{ $k->CountryCode }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <span class="input-group-addon">
                                            <span class="fa fa-map-marker" aria-hidden="true"></span>

                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-3'>
                                Provinsi
                                <div class="form-group">
                                    <div class='input-group '>

                                        <select name="Province" class="form-control" id="province" required>
                                            <option value=""></option>
                                            @foreach ($dataprovince as $p)
                                                @if ($p->name == $data->Province)
                                                    <option selected value="{{ $p->name }}">{{ $p->name }}
                                                    </option>
                                                @else
                                                    <option value="{{ $p->name }}">{{ $p->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <span class="input-group-addon">
                                            <span class="fa fa-map-marker" aria-hidden="true"></span>

                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-3'>
                                Kota
                                <div class="form-group">
                                    <div class='input-group '>
                                        @if ($data->Province == null)
                                            <select name="Kota" class="form-control" id="kota" required>

                                            </select>
                                        @else
                                            <select name="Kota" class="form-control" id="kota">
                                                <option selected value="{{ $data->City }}">{{ $data->City }}
                                                </option>
                                            </select>
                                        @endif
                                        <span class="input-group-addon">
                                            <span class="fa fa-map-marker"></span>
                                        </span>

                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-3'>
                                Kode Pos
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='text' name="PostalCode" class="form-control"
                                            value="{{ $data->PostalCode }}" autocomplete="off" required>
                                        <span class="input-group-addon">
                                            <span class="fa fa-code"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-6'>
                                Status Penanaman modal
                                <div class="form-group">
                                    <div class='input-group '>
                                        @php
                                            $statusmodal = ['Penanaman Modal Asing (PMA)', 'Penanaman Modal Dalam Negeri (PMDN)', 'Kantor Perwakilan Perusahaan Asing (KPPA)'];
                                        @endphp
                                        <select name="StatusPenanamanModal" class="form-control" required>
                                            @foreach ($statusmodal as $q)
                                                @if ($q == $data->StatusPenanamanModal)
                                                    <option selected value="{{ $q }}">{{ $q }}
                                                    </option>
                                                @else
                                                    <option value="{{ $q }}">{{ $q }}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                        <span class="input-group-addon" required>
                                            <span class="fa fa-list"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-6'>
                                Jenis Usaha <small style="color:red ; ">* contoh : pertambangan, perkebunan, otomotif,
                                    manufaktur, dll</small>
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='text' name="JenisUsaha" class="form-control"
                                            value="{{ $data->JenisUsaha }}" autocomplete="off" required>
                                        <span class="input-group-addon">
                                            <span class="fa fa-list-alt"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (COUNT($Datakbli) == 0)
                            <div class="row">
                                <div class='col-sm-4'>
                                    No KBLI
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='text' name="NoKbli[]" class="form-control" maxlength="5"
                                                autocomplete="off" required>
                                            <span class="input-group-addon">
                                                <span class="fa fa-user"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-sm-4'>
                                    Description KBLI
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='text' name="Description[]" class="form-control"
                                                autocomplete="off" required>
                                            <span class="input-group-addon">
                                                <span class="fa fa-list-alt"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-sm-4'>
                                    Upload KBLI
                                    <div class="form-group">
                                        <div class='input-group '>

                                            <input name="KBLIfile[]" type='file' accept=".pdf,.PDF"
                                                class="form-control" required>
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            @foreach ($Datakbli as $d)
                                <div class="row">
                                    <div class='col-sm-4'>
                                        No KBLI
                                        <div class="form-group">
                                            <div class='input-group '>
                                                <input type='text' name="NoKbli[{{ $d->ID }}]"
                                                    class="form-control" maxlength="5" value="{{ $d->NoKbli }}"
                                                    required>
                                                <span class="input-group-addon">
                                                    <span class="fa fa-user"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='col-sm-4'>
                                        Description KBLI
                                        <div class="form-group">
                                            <div class='input-group '>
                                                <input type='text' name="Description[{{ $d->ID }}]"
                                                    class="form-control" value="{{ $d->Description }}" required>
                                                <input type='hidden' name="ID[]" class="form-control"
                                                    value="{{ $d->ID }}">
                                                <span class="input-group-addon">
                                                    <span class="fa fa-list-alt"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='col-sm-3'>
                                        Upload KBLI
                                        <div class="form-group">
                                            <div class='input-group '>

                                                <input name="KBLIfile[{{ $d->ID }}]" type='file'
                                                    accept=".pdf,.PDF" class="form-control">
                                                <span class="input-group-addon">
                                                    <span class="fa fa-upload" aria-hidden="true"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='col-sm-1'>
                                        File
                                        <div class="form-group">
                                            <div class='input-group '>

                                                <a class="btn btn-danger btn-sm"
                                                    href="{{ url("vendor/KBLIFILE/$d->FileKbli") }}"
                                                    data-toggle="tooltip" title="{{ $d->FileKbli }}"><i
                                                        class="fa fa-download" aria-hidden="true"></i></a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <div id="KBLI">
                        </div>
                        <div class="row">
                            <div class="col-md-11">

                            </div>
                            <div class="col-md-1 mb-3 btn-group ms-auto me-5">
                                <a type="button" class="btn btn-primary me-3" id="addrows"><small
                                        style="color: #fff">+</small></a>
                                <a type="button" class="btn btn-light" id="delrows"><small>-</small></a>
                            </div>
                        </div>
                        <div class="row">
                            <div class='col-sm-6'>
                                No NIB
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='text' name="NoNIB" class="form-control" maxlength="13"
                                            value="{{ $data->NoNib }}" autocomplete="off" required>
                                        <span class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            @if ($data->FileNib == null)
                                <div class='col-sm-6'>
                                    Upload NIB
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="NibFile" class="form-control"
                                                required>
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class='col-sm-5'>
                                    Upload NIB
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="NibFile" class="form-control"
                                                maxlength="13" value="{{ $data->FileNib }}">
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-sm-1'>
                                    File
                                    <div class="form-group">

                                        <div class='input-group '>
                                            <a class="btn btn-danger btn-sm"
                                                href="{{ url("vendor/NIB/$data->FileNib") }}" data-toggle="tooltip"
                                                title="{{ $data->FileNib }}"><i class="fa fa-download"
                                                    aria-hidden="true"></i></a>

                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class='col-sm-6'>
                                No NPWP
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='text' name="NoNpwp" id="npwp" class="form-control"
                                            value="{{ $data->NoNpwp }}" autocomplete="off" required>
                                        <span class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            @if ($data->FileNpwp == null)
                                <div class='col-sm-6'>
                                    Upload NPWP
                                    <div class="form-group">

                                        <div class='input-group '>

                                            <input type='file' accept=".pdf,.PDF" name="NpwpFile"
                                                class="form-control" required>
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class='col-sm-5'>
                                    Upload NPWP
                                    <div class="form-group">

                                        <div class='input-group '>

                                            <input type='file' accept=".pdf,.PDF" name="NpwpFile"
                                                class="form-control">
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-sm-1'>
                                    File
                                    <div class="form-group">

                                        <div class='input-group '>
                                            <a class="btn btn-danger btn-sm"
                                                href="{{ url("vendor/NPWP/$data->FileNpwp") }}" data-toggle="tooltip"
                                                title="{{ $data->FileNpwp }}"><i class="fa fa-download"
                                                    aria-hidden="true"></i></a>

                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class='col-sm-3'>
                                Nama Bank
                                <div class="form-group">
                                    <div class='input-group '>
                                        <select name="NamaBank" class="form-control select2" required>
                                            @foreach ($databank as $q)
                                                @if ($q->name == $data->NameBank)
                                                    <option selected value="{{ $p->name }}">{{ $q->name }}
                                                    </option>
                                                @else
                                                    <option value="{{ $q->name }}">{{ $q->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <span class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-3'>
                                NO Rekening
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='text' name="NoRekening" class="form-control"
                                            value="{{ $data->NoRekening }}" autocomplete="off" required>
                                        <span class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @if ($data->AtasNamaRekening == null)
                                <div class='col-sm-3'>
                                    Atas Nama Pemilik
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='text' name="AtasNamaBank" class="form-control"
                                                value="{{ $data->AtasNamaRekening }}" autocomplete="off" required>
                                            <span class="input-group-addon">
                                                <span class="fa fa-user"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-sm-3'>
                                    Upload Halaman Depan Buku Rekening
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="FileRekening"
                                                class="form-control" required>
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class='col-sm-2'>
                                    Atas Nama Pemilik
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='text' name="AtasNamaBank" class="form-control"
                                                value="{{ $data->AtasNamaRekening }}" required>
                                            <span class="input-group-addon">
                                                <span class="fa fa-user"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-sm-3'>
                                    Upload Halaman Depan Buku Rekening
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="FileRekening"
                                                class="form-control">
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-sm-1'>
                                    File
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <a class="btn btn-danger btn-sm"
                                                href="{{ url("vendor/Rekening/$data->FileRekening") }}"
                                                data-toggle="tooltip" title="{{ $data->FileRekening }}"><i
                                                    class="fa fa-download" aria-hidden="true"></i></a>

                                        </div>
                                    </div>
                                </div>
                            @endif


                            @if ($data->FileAktaPendirian == null)
                                <div class='col-sm-4'>
                                    Upload Akta Pendirian
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="FileAktaPendirian"
                                                class="form-control" required>
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class='col-sm-3'>
                                    Upload Akta Pendirian
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="FileAktaPendirian"
                                                class="form-control">
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-sm-1'>
                                    File
                                    <div class="form-group">

                                        <div class='input-group '>
                                            <a class="btn btn-danger btn-sm"
                                                href="{{ url("vendor/AktaPendirian/$data->FileAktaPendirian") }}"
                                                data-toggle="tooltip" title="{{ $data->FileAktaPendirian }}"><i
                                                    class="fa fa-download" aria-hidden="true"></i></a>

                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($data->FileAnggaranDasar == null)
                                <div class='col-sm-4'>
                                    Upload Anggaran Dasar
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="FileAnggaranDasar"
                                                class="form-control" required>
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class='col-sm-3'>
                                    Upload Anggaran Dasar
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="FileAnggaranDasar"
                                                class="form-control">
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-sm-1'>
                                    File
                                    <div class="form-group">

                                        <div class='input-group '>
                                            <a class="btn btn-danger btn-sm"
                                                href="{{ url("vendor/AnggaranDasar/$data->FileAnggaranDasar") }}"
                                                data-toggle="tooltip" title="{{ $data->FileAnggaranDasar }}"><i
                                                    class="fa fa-download" aria-hidden="true"></i></a>

                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($data->FileIjinUsaha == null)
                                <div class='col-sm-4'>
                                    Upload Ijin Usaha
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="FileIjinUsaha"
                                                class="form-control" required>
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class='col-sm-3'>
                                    Upload Ijin Usaha
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="FileIjinUsaha"
                                                class="form-control" value="{{ $data->FileIjinUsaha }}">
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-sm-1'>
                                    File
                                    <div class="form-group">

                                        <div class='input-group '>
                                            <a class="btn btn-danger btn-sm"
                                                href="{{ url("vendor/IjinUsaha/$data->FileIjinUsaha") }}"
                                                data-toggle="tooltip" title="{{ $data->FileIjinUsaha }}"><i
                                                    class="fa fa-download" aria-hidden="true"></i></a>

                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($data->FileSkdp == null)
                                <div class='col-sm-4'>
                                    Upload SKDP
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="FileSKDP"
                                                class="form-control" required>
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class='col-sm-3'>
                                    Upload SKDP
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="FileSKDP"
                                                class="form-control" value="{{ $data->FileSKDP }}">
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-sm-1'>
                                    File
                                    <div class="form-group">

                                        <div class='input-group '>
                                            <a class="btn btn-danger btn-sm"
                                                href="{{ url("vendor/SKDP/$data->FileSkdp") }}" data-toggle="tooltip"
                                                title="{{ $data->FileSkdp }}"><i class="fa fa-download"
                                                    aria-hidden="true"></i></a>

                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($data->FileKtpDireksi == null)
                                <div class='col-sm-4'>
                                    Upload KTP Direksi
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="FileKtpDireksi"
                                                class="form-control" required>
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class='col-sm-3'>
                                    Upload KTP Direksi
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="FileKtpDireksi"
                                                class="form-control" value="{{ $data->FileKtpDireksi }}">
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-sm-1'>
                                    File
                                    <div class="form-group">

                                        <div class='input-group '>
                                            <a class="btn btn-danger btn-sm"
                                                href="{{ url("vendor/KTPDireksi/$data->FileKtpDireksi") }}"
                                                data-toggle="tooltip" title="{{ $data->FileKtpDireksi }}"><i
                                                    class="fa fa-download" aria-hidden="true"></i></a>

                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($data->FileSkt == null)
                                <div class='col-sm-4'>
                                    Upload SKT
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="FileSKT" class="form-control"
                                                required>
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class='col-sm-3'>
                                    Upload SKT
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="FileSKT" class="form-control"
                                                value="{{ $data->FileSKT }}">
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-sm-1'>
                                    File
                                    <div class="form-group">

                                        <div class='input-group '>
                                            <a class="btn btn-danger btn-sm"
                                                href="{{ url("vendor/SKT/$data->FileSkt") }}" data-toggle="tooltip"
                                                title="{{ $data->FileSkt }}"><i class="fa fa-download"
                                                    aria-hidden="true"></i></a>

                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class='col-sm-6'>
                                No SKPKP
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='TEXT' name="Skpkp" class="form-control"
                                            value="{{ $data->Skpkp }}" autocomplete="off">
                                        <span class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @if ($data->FileSkpkp == null)
                                <div class='col-sm-6'>
                                    Upload SKPKP
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="FileSkpkp"
                                                class="form-control">
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class='col-sm-5'>
                                    Upload SKPKP
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="FileSkpkp"
                                                class="form-control" value="{{ $data->FileSkpkp }}">
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-sm-1'>
                                    File
                                    <div class="form-group">

                                        <div class='input-group '>
                                            <a class="btn btn-danger btn-sm"
                                                href="{{ url("vendor/SKPKP/$data->FileSkpkp") }}" data-toggle="tooltip"
                                                title="{{ $data->FileSkpkp }}"><i class="fa fa-download"
                                                    aria-hidden="true"></i></a>

                                        </div>
                                    </div>
                                </div>
                            @endif


                            <div class='col-sm-6'>
                                No Surat Keagenan
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='text' name="SuratAgen" autocomplete="off" class="form-control"
                                            value="{{ $data->SuratAgen }}" >
                                        <span class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            @if ($data->FileSuratAgen == null)
                                <div class='col-sm-6'>
                                    Upload Surat Keagenan
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="FileSuratAgen"
                                                class="form-control" >
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class='col-sm-5'>
                                    Upload Surat Keagenan
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="FileSuratAgen"
                                                class="form-control" value="{{ $data->FileSuratAgen }}">
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-sm-1'>
                                    File
                                    <div class="form-group">

                                        <div class='input-group '>
                                            <a class="btn btn-danger btn-sm"
                                                href="{{ url("vendor/Keagenan/$data->FileSuratAgen") }}"
                                                data-toggle="tooltip" title="{{ $data->FileSuratAgen }}"><i
                                                    class="fa fa-download" aria-hidden="true"></i></a>

                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($data->FileAhu == null)
                                <div class='col-sm-6'>
                                    Upload File AHU
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="FileAhu" class="form-control"
                                                required>
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class='col-sm-5'>
                                    Upload File AHU
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="FileAhu" class="form-control"
                                                value="{{ $data->FileAhu }}">
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-sm-1'>
                                    File
                                    <div class="form-group">

                                        <div class='input-group '>
                                            <a class="btn btn-danger btn-sm"
                                                href="{{ url("vendor/AHU/$data->FileAhu") }}" data-toggle="tooltip"
                                                title="{{ $data->FileAhu }}"><i class="fa fa-download"
                                                    aria-hidden="true"></i></a>

                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($data->FilePernyataanRekening == null)
                                <div class='col-sm-6'>
                                    Upload Pernyataan Rekening Bank Vendor
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="FilePernyataanRekeningVendor"
                                                class="form-control" required>
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class='col-sm-5'>
                                    Upload Pernyataan Rekening Bank Vendor
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="FilePernyataanRekeningVendor"
                                                class="form-control" value="{{ $data->FilePernyataanRekening }}">
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-sm-1'>
                                    File
                                    <div class="form-group">

                                        <div class='input-group '>
                                            <a class="btn btn-danger btn-sm"
                                                href="{{ url("vendor/PernyataanRekening/$data->FilePernyataanRekening") }}"
                                                data-toggle="tooltip" title="{{ $data->FilePernyataanRekening }}"><i
                                                    class="fa fa-download" aria-hidden="true"></i></a>

                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($data->FilePernyataanPajak == null)
                                <div class='col-sm-6'>
                                    Upload Surat Pernyataan Taat Pajak
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="FileTaatPajak"
                                                class="form-control" required>
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class='col-sm-5'>
                                    Upload Surat Pernyataan Taat Pajak
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="FileTaatPajak"
                                                class="form-control" value="{{ $data->FilePernyataanPajak }}">
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-sm-1'>
                                    File
                                    <div class="form-group">

                                        <div class='input-group '>
                                            <a class="btn btn-danger btn-sm"
                                                href="{{ url("vendor/TaatPajak/$data->FilePernyataanPajak") }}"
                                                data-toggle="tooltip" title="{{ $data->FilePernyataanPajak }}"><i
                                                    class="fa fa-download" aria-hidden="true"></i></a>

                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($data->FileEtikaBertransaksi == null)
                                <div class='col-sm-6'>
                                    Upload Surat Pernyataan Etika Bertransaksi
                                    <div class="form-group">
                                        <div class='input-group'>
                                            <input type='file' accept=".pdf,.PDF" name="FileEtikaTransaksi"
                                                class="form-control" required>
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class='col-sm-5'>
                                    Upload Surat Pernyataan Etika Bertransaksi
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='file' accept=".pdf,.PDF" name="FileEtikaTransaksi"
                                                class="form-control" value="{{ $data->FileEtikaTransaksi }}">
                                            <span class="input-group-addon">
                                                <span class="fa fa-upload" aria-hidden="true"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class='col-sm-1'>
                                    File
                                    <div class="form-group">

                                        <div class='input-group '>
                                            <a class="btn btn-danger btn-sm"
                                                href="{{ url("vendor/EtikaTransaksi/$data->FileEtikaBertransaksi") }}"
                                                data-toggle="tooltip" title="{{ $data->FileEtikaBertransaksi }}"><i
                                                    class="fa fa-download" aria-hidden="true"></i></a>

                                        </div>
                                    </div>
                                </div>
                            @endif



                        </div>
                        <div class="form-group row">
                            <div class="col-md-9 col-sm-9  offset-md-5">
                                <button type="submit" class="btn btn-warning"> <i class="fa fa-save"></i>
                                    Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="{{ asset('public/assetss/vendors/jquery/dist/jquery.min.js') }}"></script>

    <script>
        const NPWP = document.getElementById("npwp")

        NPWP.oninput = (e) => {
            e.target.value = autoFormatNPWP(e.target.value);
        }

        function autoFormatNPWP(NPWPString) {
            try {
                var cleaned = ("" + NPWPString).replace(/\D/g, "");
                var match = cleaned.match(/(\d{0,2})?(\d{0,3})?(\d{0,3})?(\d{0,1})?(\d{0,3})?(\d{0,3})$/);
                return [
                    match[1],
                    match[2] ? "." : "",
                    match[2],
                    match[3] ? "." : "",
                    match[3],
                    match[4] ? "." : "",
                    match[4],
                    match[5] ? "-" : "",
                    match[5],
                    match[6] ? "." : "",
                    match[6]
                ].join("")

            } catch (err) {
                return "";
            }
        }
    </script>

    <script>
        $('#province').on('change', function(e) {

            var id = document.getElementById("province").value;

            $.ajax({
                url: "{{ url('cekkota') }}?id=" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#kota').empty();
                    for (i = 0; i < data.length; i++) {

                        $('#kota').append(`
                                    <option value="` + data[i] + `">` + data[i] + `</option>
                                   `);
                    }
                }
            });
        });
        var j = 0;

        $('#addrows').on("click", function() {
            j++;

            $('#KBLI').append(`
            <div class='row'>
                    <div class='col-sm-4'>
                                       No KBLI
                                        <div class="form-group">
                                            <div class='input-group '>
                                                <input type='text' name="NoKbli[]" class="form-control" maxlength="5" >
                                                <span class="input-group-addon">
                                                    <span class="fa fa-user"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                     <div class='col-sm-4'>
                                    Description KBLI
                                    <div class="form-group">
                                        <div class='input-group '>
                                            <input type='text' name="Description[]" class="form-control">
                                            <span class="input-group-addon">
                                                <span class="fa fa-list-alt"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                    <div class='col-sm-4'>
                                        Upload KBLI
                                        <div class="form-group">
                                            <div class='input-group '>
                                                <input type='file' accept=".pdf,.PDF"  name="KBLIfile[]" class="form-control"
                                                    >
                                                <span class="input-group-addon">
                                                    <span class="fa fa-upload" aria-hidden="true"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                            </div>`);
            $('#n_row').val(j);
        });

        $('#delrows').on("click", function() {
            if (j != 0) {
                $('#KBLI').children().eq(j).remove();
                j--;
                $('#n_row').val(j);
            }
        });
    </script>

@endsection
