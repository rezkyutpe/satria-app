@extends('po-tracking.panel.master')
@section('content')
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Detail Vendor
                    </h2>
                    <a class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#modal-add-appsmenu"
                        href="#"><i class="fa fa-file-text-o"></i>  Form Vendor</a>
                    <div class="clearfix"></div>
                </div>

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

                <div class="x_content">
                    <br>
                    
                    <form enctype="multipart/form-data">
                        @foreach($data['vendor'] as $value)
                        <input type="hidden" name="_token" value="ERifNEZVduHMf7faM8f0c0OGmqDiLHP4YZReB6a1">
                        <div class="row">
                            <div class="col-sm-4">
                                @if($value->Name == '')
                                <div style="color:red ; "> Name </div>
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" class="form-control"  readonly="">
                                        <span class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </span>
                                    </div>
                                </div>
                                @else
                                 Name 
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" class="form-control" value="{{$value->Name}}" readonly="">
                                        <span class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                @if($value->VendorCode == '')
                                <div style="color:red ; "> Vendor Code </div>
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" class="form-control"  readonly="">
                                        <span class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </span>
                                    </div>
                                </div>
                                @else
                                Vendor Code
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" name="VendorCode" class="form-control" value="{{$value->VendorCode ?  $value->VendorCode : '-'}}" readonly="">
                                        <span class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="col-sm-4" >
                                @if($value->VendorCode_new == '')
                                <div style="color:red ; "> Vendor Code New </div>
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" class="form-control"  readonly="">
                                        <span class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </span>
                                    </div>
                                </div>
                                @else
                                Vendor Code New
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" class="form-control" value="{{$value->VendorCode_new ?  $value->VendorCode_new : '-'}}" readonly="">
                                        <span class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="col-sm-4" >
                                @if($value->Email == '')
                                <div style="color:red ; "> Email Perusahaan </div>
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" name="Email" class="form-control"  readonly>
                                        <span class="input-group-addon">
                                            <span class="fa fa-envelope"></span>
                                        </span>
                                    </div>
                                </div>
                               @else
                               Email Perusahaan 
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" name="Email" class="form-control" value="{{$value->Email }}" readonly>
                                        <span class="input-group-addon">
                                            <span class="fa fa-envelope"></span>
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                @if($value->EmailPO == '')
                                <div style="color:red ; "> Email PO </div>
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" class="form-control"  readonly="">
                                        <span class="input-group-addon">
                                            <span class="fa fa-envelope"></span>
                                        </span>
                                    </div>
                                </div>
                                @else
                                Email PO 
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" name="EmailPO" class="form-control" value="{{$value->EmailPO }}" readonly>
                                        <span class="input-group-addon">
                                            <span class="fa fa-envelope"></span>
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                @if($value->PhoneNo == '')
                                <div style="color:red ; "> Phone No </div>
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" class="form-control"  readonly="">
                                        <span class="input-group-addon">
                                            <span class="fa fa-phone"></span>
                                        </span>
                                    </div>
                                </div>
                                @else
                                Phone No
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" name="PhoneNo" class="form-control" value="{{$value->PhoneNo}}" readonly>
                                        <span class="input-group-addon">
                                            <span class="fa fa-phone"></span>
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                @if($value->Address == '')
                                <div style="color:red ; "> Alamat </div>
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" class="form-control"  readonly="">
                                        <span class="input-group-addon">
                                            <span class="fa fa-home"></span>
                                        </span>
                                    </div>
                                </div>
                                @else
                                Alamat
                                <div class="form-group">
                                    <div class="input-group ">
                                        <textarea name="Address" class="form-control" cols="30" rows="2" readonly readonly>{{$value->Address}}</textarea>
                                        <span class="input-group-addon">
                                            <span class="fa fa-home"></span>
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="col-sm-3">
                                @if($value->CountryCode == '')
                                <div style="color:red ; "> Kode Negara </div>
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" class="form-control"  readonly="">
                                        <span class="input-group-addon">
                                            <span class="fa fa-home"></span>
                                        </span>
                                    </div>
                                </div>
                                @else
                               Kode Negara
                                <div class="form-group">
                                    <div class="input-group ">
                                        <textarea name="Address" class="form-control" cols="30" rows="2" readonly readonly>{{$value->CountryCode}}</textarea>
                                        <span class="input-group-addon">
                                            <span class="fa fa-home"></span>
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="col-sm-3">
                                @if($value->Province == '')
                                <div style="color:red ; "> Provinsi </div>
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" class="form-control"  readonly="">
                                        <span class="input-group-addon">
                                            <span class="fa fa-home"></span>
                                        </span>
                                    </div>
                                </div>
                                @else
                                Provinsi
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" name="Province" class="form-control" value="{{$value->Province}}" readonly>
                                        <span class="input-group-addon">
                                            <span class="fa fa-map-marker" aria-hidden="true"></span>
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="col-sm-3">
                                @if($value->City == '')
                                <div style="color:red ; "> Kota </div>
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" class="form-control"  readonly="">
                                        <span class="input-group-addon">
                                            <span class="fa fa-home"></span>
                                        </span>
                                    </div>
                                </div>
                                @else
                                Kota
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" name="City" class="form-control" value="{{$value->City}}" readonly>    
                                        <span class="input-group-addon">
                                            <span class="fa fa-map-marker"></span>
                                        </span>

                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="col-sm-3">
                                @if($value->PostalCode == '')
                                <div style="color:red ; "> Kode Pos </div>
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" class="form-control"  readonly="">
                                        <span class="input-group-addon">
                                            <span class="fa fa-code"></span>
                                        </span>
                                    </div>
                                </div>
                                @else
                                Kode Pos
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" name="PostalCode" class="form-control" value="{{$value->PostalCode}}" readonly>
                                        <span class="input-group-addon">
                                            <span class="fa fa-code"></span>
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="col-sm-6">
                                @if($value->StatusPenanamanModal == '')
                                <div style="color:red ; "> Status Penanaman Modal </div>
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" class="form-control"  readonly="">
                                        <span class="input-group-addon">
                                            <span class="fa fa-list"></span>
                                        </span>
                                    </div>
                                </div>
                                @else
                                Status Penanaman modal
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" name="StatusPenanamanModal" class="form-control" value="{{$value->StatusPenanamanModal}}" readonly>
                                        <span class="input-group-addon" readonly>
                                            <span class="fa fa-list"></span>
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="col-sm-6">
                                @if($value->JenisUsaha == '')
                                <div style="color:red ; "> Jenis Usaha </div>
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" class="form-control"  readonly="">
                                        <span class="input-group-addon">
                                            <span class="fa fa-list-alt"></span>
                                        </span>
                                    </div>
                                </div>
                                @else
                                Jenis Usaha 
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" name="JenisUsaha" class="form-control" value="{{$value->JenisUsaha}}" readonly>
                                        <span class="input-group-addon">
                                            <span class="fa fa-list-alt"></span>
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                            @if(count($data['data_kbli']) > 0 )
                                    @foreach($data['data_kbli'] as $kbli)
                                        No KBLI
                                            <div class="form-group">
                                                <div class="input-group ">
                                                    <input type="text" name="NoKbli[2]" class="form-control" maxlength="5" value="{{$kbli->NoKbli}}" readonly>
                                                  
                                                    <span class="input-group-addon">
                                                        <span class="fa fa-user"></span>
                                                    </span>
                                                </div>
                                            </div>
                                    @endforeach
                                @else
                                <div style="color:red ; "> No KBLI </div>
                                    <div class="form-group">
                                        <div class="input-group ">
                                            <input type="text" class="form-control"  readonly="">
                                            <span class="input-group-addon">
                                                <span class="fa fa-user"></span>
                                            </span>
                                        </div>
                                    </div>
                                @endif

                            </div>
                            <div class="col-sm-3">
                                @if(count($data['data_kbli']) > 0 )
                                    @foreach($data['data_kbli'] as $kbli)
                                        Description KBLI
                                            <div class="form-group">
                                                <div class="input-group ">
                                                    <input type="text" name="NoKbli[2]" class="form-control" maxlength="5" value="{{$kbli->Description}}" readonly>
                                                  
                                                    <span class="input-group-addon">
                                                        <span class="fa fa-user"></span>
                                                    </span>
                                                </div>
                                            </div>
                                    @endforeach
                                @else
                                <div style="color:red ; "> Description KBLI </div>
                                    <div class="form-group">
                                        <div class="input-group ">
                                            <input type="text" class="form-control"  readonly="">
                                            <span class="input-group-addon">
                                                <span class="fa fa-user"></span>
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-sm-3">
                                @if($value->NoNib == '')
                                <div style="color:red ; "> No NIB </div>
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" class="form-control"  readonly="">
                                        <span class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </span>
                                    </div>
                                </div>
                                @else
                                No NIB
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" name="NoNIB" class="form-control" value="{{$value->NoNib}}" autocomplete="off" readonly>
                                        <span class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="col-sm-3">
                                @if($value->NoNpwp == '')
                                <div style="color:red ; "> No NPWP </div>
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" class="form-control"  readonly="">
                                        <span class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </span>
                                    </div>
                                </div>
                                @else
                                No NPWP
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" name="NoNpwp" id="npwp" class="form-control" value="{{$value->NoNpwp}}" autocomplete="off" readonly>
                                        <span class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="col-sm-3">
                                @if($value->NameBank == '')
                                <div style="color:red ; "> Nama Bank </div>
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" class="form-control"  readonly="">
                                        <span class="input-group-addon">
                                            <span class="fa fa-cc-visa"></span>
                                        </span>
                                    </div>
                                </div>
                                @else
                                Nama Bank
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" name="NameBank" id="NameBank" class="form-control" value="{{$value->NameBank}}" readonly>
                                        <span class="input-group-addon">
                                            <span class="fa fa-cc-visa"></span>
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="col-sm-3">
                                @if($value->NoRekening == '')
                                <div style="color:red ; "> No Rekening </div>
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" class="form-control"  readonly="">
                                        <span class="input-group-addon">
                                            <span class="fa fa-cc-visa"></span>
                                        </span>
                                    </div>
                                </div>
                                @else
                                NO Rekening
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" name="NoRekening" class="form-control" value="{{$value->NoRekening}}" autocomplete="off" readonly>
                                        <span class="input-group-addon">
                                            <span class="fa fa-cc-visa"></span>
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="col-sm-3">
                                @if($value->AtasNamaRekening == '')
                                <div style="color:red ; "> Atas Nama Pemilik </div>
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" class="form-control"  readonly="">
                                        <span class="input-group-addon">
                                            <span class="fa fa-cc-visa"></span>
                                        </span>
                                    </div>
                                </div>
                                @else
                                Atas Nama Pemilik
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" name="AtasNamaBank" class="form-control" value="{{$value->AtasNamaRekening}}" readonly>
                                        <span class="input-group-addon">
                                            <span class="fa fa-cc-visa"></span>
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="col-sm-3">
                                @if($value->SuratAgen == '')
                                <div style="color:red ; "> No Surat Agen </div>
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" class="form-control"  readonly="">
                                        <span class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </span>
                                    </div>
                                </div>
                                @else
                                No Surat Agen
                                <div class="form-group">
                                    <div class="input-group ">
                                        <input type="text" name="SuratAgen" autocomplete="off" class="form-control" value="{{$value->SuratAgen}}" readonly>
                                        <span class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <form>
                            <div class="row">
                                <div class="col-sm-12 mb-3">
                                    <div style="text-align:center" class="col-md-12 mt-2">
                                        <strong >FILE VENDOR</strong>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            
                            <div class="col-sm-2 border p-2">                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
                                
                                <div class="form-group">

                                    <div class="input-group ">
                                        @foreach($data['data_kbli'] as $key => $kbli)
                                            @if($value->VendorCode == $kbli->VendorCode)
                                                @if($kbli->FileKbli != '')
                                                    <a class="btn btn-block btn-success btn-sm" href="{{ url('file-kbli/'.$value->VendorCode)  }}"  target="_blank" data-toggle="tooltip" title="" data-original-title=""><i class="fa fa-download" aria-hidden="true"> File KBLI-{{$key+1}}</i></a>
                                                @else
                                                    <a class="btn btn-block btn-danger btn-sm" style="color:rgb(255, 255, 255) ; " data-toggle="tooltip" title="" data-original-title=""><i class="fa fa-download" aria-hidden="true"> File KBLI</i></a>
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 border p-2">
                               
                                <div class="form-group">

                                    <div class="input-group ">
                                        @if($value->FileNib != '')
                                        <a class="btn btn-block btn-success btn-sm" name="nib" id="kbli" href="{{ url('file-nib/'.$value->VendorCode)  }}" target="_blank" data-toggle="tooltip" title="" data-original-title=""><i class="fa fa-download" aria-hidden="true"> File NIB</i></a>
                                        @else
                                        <a class="btn btn-block btn-danger btn-sm" style="color:rgb(255, 255, 255) ; "><i class="fa fa-download" aria-hidden="true"> File NIB</i></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 border p-2">
                                
                                <div class="form-group">

                                    <div class="input-group ">
                                        @if($value->FileNpwp != '')
                                        <a class="btn btn-block btn-success btn-sm" href="{{ url('file-npwp/'.$value->VendorCode)  }}" data-toggle="tooltip" target="_blank" title="" data-original-title=""><i class="fa fa-download" aria-hidden="true"> File NPWP</i></a>
                                        @else
                                        <a class="btn btn-block btn-danger btn-sm" style="color:rgb(255, 255, 255) ; "><i class="fa fa-download" aria-hidden="true"> File NPWP</i></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 border p-2">
                                
                                <div class="form-group">
                                   
                                    <div class="input-group ">
                                        @if($value->FileSkdp != '')
                                        <a class="btn btn-block btn-success btn-sm" href="{{ url('file-skdp/'.$value->VendorCode)  }}" target="_blank" data-toggle="tooltip" title="" data-original-title=""><i class="fa fa-download" aria-hidden="true"> File SKDP</i></a>
                                        @else
                                        <a class="btn btn-block btn-danger btn-sm" style="color:rgb(255, 255, 255) ; "><i class="fa fa-download" aria-hidden="true"> File SKDP</i></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 border p-2">
                               
                                <div class="form-group">

                                    <div class="input-group ">
                                        @if($value->FileSkt != '')
                                        <a class="btn btn-block btn-success btn-sm" href="{{ url('file-skt/'.$value->VendorCode)  }}" target="_blank" data-toggle="tooltip" title="" data-original-title=""><i class="fa fa-download" aria-hidden="true"> File SKT</i></a>
                                        @else
                                        <a class="btn btn-block btn-danger btn-sm" style="color:rgb(255, 255, 255) ; "><i class="fa fa-download" aria-hidden="true"> File SKT</i></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 border p-2">
                               
                                <div class="form-group">

                                    <div class="input-group ">
                                        @if($value->FileSkpkp != '')
                                        <a class="btn btn-block btn-success btn-sm" href="{{ url('file-skpkp/'.$value->VendorCode)  }}" target="_blank" data-toggle="tooltip" title="" data-original-title=""><i class="fa fa-download" aria-hidden="true"> File SKPKP</i></a>
                                        @else
                                        <a class="btn btn-block btn-danger btn-sm" style="color:rgb(255, 255, 255) ; "><i class="fa fa-download" aria-hidden="true"> File SKPKP</i></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 border p-2">
                               
                                <div class="form-group">

                                    <div class="input-group ">
                                        @if($value->FileAhu != '')
                                        <a class="btn btn-block btn-success btn-sm" href="{{ url('file-ahu/'.$value->VendorCode)  }}" target="_blank" data-toggle="tooltip" title="" data-original-title=""><i class="fa fa-download" aria-hidden="true"> File AHU</i></a>
                                        @else
                                        <a class="btn btn-block btn-danger btn-sm" style="color:rgb(255, 255, 255) ; "><i class="fa fa-download" aria-hidden="true"> File AHU</i></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 border p-2">
                               
                                <div class="form-group">

                                    <div class="input-group ">
                                        @if($value->FileKtpDireksi != '')
                                        <a class="btn btn-block btn-success btn-sm" href="{{ url('file-ktp-direksi/'.$value->VendorCode)  }}" target="_blank" data-toggle="tooltip" title="" data-original-title=""><i class="fa fa-download" aria-hidden="true"> File KTP Direksi</i></a>
                                        @else
                                        <a class="btn btn-block btn-danger btn-sm" style="color:rgb(255, 255, 255) ; "><i class="fa fa-download" aria-hidden="true"> File KTP Direksi</i></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 border p-2">
                             
                                <div class="form-group">

                                    <div class="input-group ">
                                        @if($value->FileRekening != '')
                                        <a class="btn btn-block btn-success btn-sm" href="{{ url('file-buku-rekening/'.$value->VendorCode)  }}" target="_blank" data-toggle="tooltip" title="" data-original-title=""><i class="fa fa-download" aria-hidden="true"> File Buku Rekening</i></a>
                                        @else
                                        <a class="btn btn-block btn-danger btn-sm" style="color:rgb(255, 255, 255) ; "><i class="fa fa-download" aria-hidden="true"> File Buku Rekening</i></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 border p-2">
                                
                                <div class="form-group">

                                    <div class="input-group ">
                                        @if($value->FileAktaPendirian != '')
                                        <a class="btn btn-block btn-success btn-sm" href="{{ url('file-akte-pendirian/'.$value->VendorCode)  }}" target="_blank" data-toggle="tooltip" title="" data-original-title=""><i class="fa fa-download" aria-hidden="true"> File Akta Pendirian</i></a>
                                        @else
                                        <a class="btn btn-block btn-danger btn-sm" style="color:rgb(255, 255, 255) ; "><i class="fa fa-download" aria-hidden="true"> File Akta Pendirian</i></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 border p-2">
                                
                                <div class="form-group">

                                    <div class="input-group ">
                                        @if($value->FileAnggaranDasar != '')
                                        <a class="btn btn-block btn-success btn-sm" href="{{ url('file-anggaran-dasar/'.$value->VendorCode)  }}" target="_blank" data-toggle="tooltip" title="" data-original-title=""><i class="fa fa-download" aria-hidden="true"> File Anggaran Dasar</i></a>
                                        @else
                                        <a class="btn btn-block btn-danger btn-sm" style="color:rgb(255, 255, 255) ; "><i class="fa fa-download" aria-hidden="true"> File Anggaran Dasar</i></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 border p-2">
                                
                                <div class="form-group">

                                    <div class="input-group ">
                                        @if($value->FileIjinUsaha != '')
                                        <a class="btn btn-block btn-success btn-sm" href="{{ url('file-izin-usaha/'.$value->VendorCode)  }}" target="_blank" data-toggle="tooltip" title="" data-original-title=""><i class="fa fa-download" aria-hidden="true"> File Izin Usaha</i></a>
                                        @else
                                        <a class="btn btn-block btn-danger btn-sm" style="color:rgb(255, 255, 255) ; "><i class="fa fa-download" aria-hidden="true"> File Izin Usaha</i></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 border p-2">
                               
                                <div class="form-group">

                                    <div class="input-group ">
                                        @if($value->FileSuratAgen != '')
                                        <a class="btn btn-block btn-success btn-sm" href="{{ url('file-surat-agen/'.$value->VendorCode)  }}" target="_blank" data-toggle="tooltip" title="" data-original-title=""><i class="fa fa-download" aria-hidden="true"> File Surat Agen</i></a>
                                        @else
                                        <a class="btn btn-block btn-danger btn-sm" style="color:rgb(255, 255, 255) ; "><i class="fa fa-download" aria-hidden="true"> File Surat Agen</i></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 border p-2">
                               
                                <div class="form-group">

                                    <div class="input-group ">
                                        @if($value->FilePernyataanRekening != '')
                                        <a class="btn btn-block btn-success btn-sm" href="{{ url('file-pernyataan-rekening/'.$value->VendorCode)  }}" target="_blank" data-toggle="tooltip" title="" data-original-title=""><i class="fa fa-download" aria-hidden="true"> File Pernyataan Rekening</i></a>
                                        @else
                                        <a class="btn btn-block btn-danger btn-sm" style="color:rgb(255, 255, 255) ; "><i class="fa fa-download" aria-hidden="true"> File Pernyataan Rekening</i></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 border p-2">
                               
                                <div class="form-group">

                                    <div class="input-group ">
                                        @if($value->FilePernyataanPajak != '')
                                        <a class="btn btn-block btn-success btn-sm" href="{{ url('file-pernyataan-pajak/'.$value->VendorCode)  }}" target="_blank" data-toggle="tooltip" title="" data-original-title=""><i class="fa fa-download" aria-hidden="true"> File Pernyataan Pajak</i></a>
                                        @else
                                        <a class="btn btn-block btn-danger btn-sm" style="color:rgb(255, 255, 255) ; "><i class="fa fa-download" aria-hidden="true"> File Pernyataan Pajak</i></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 border p-2">
                               
                                <div class="form-group">

                                    <div class="input-group ">
                                        @if($value->FileEtikaBertransaksi != '')
                                        <a class="btn btn-block btn-success btn-sm" href="{{ url('file-etika-bertransaksi/'.$value->VendorCode)  }}" target="_blank" data-toggle="tooltip" title="" data-original-title=""><i class="fa fa-download" aria-hidden="true"> File Etika Bertransaksi</i></a>
                                        @else
                                        <a class="btn btn-block btn-danger btn-sm" style="color:rgb(255, 255, 255) ; "><i class="fa fa-download" aria-hidden="true"> File Etika Bertransaksi</i></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <div class="modal-footer">
                            <a tclass="btn btn-danger " href="{{url('kelengkapan-data-vendor')}}" class="btn btn-danger btn-sm pull-left" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Back </a>
                        </div>
                    </form>
                </div>
           
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-add-appsmenu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Form Vendor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
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
                    <br>
                    <form enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class='col-sm-4'>
                                Name
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='text' class="form-control"  >
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
                                            >
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
                                        <input type='text' class="form-control" 
                                            >
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
                                        <input type='email' name="Email" class="form-control"
                                            autocomplete="off" required>
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
                                        <input type='email' name="EmailPO" class="form-control"
                                            autocomplete="off" required>
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
                                            autocomplete="off" required>
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
                                        <textarea name="Address" class="form-control" cols="30" rows="2" required></textarea>
                                        <span class="input-group-addon">
                                            <span class="fa fa-home"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-3'>
                                Kode Negara
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='text' name="PhoneNo" class="form-control"
                                            autocomplete="off" required>
                                      
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
                                                @if ($p->name == old('Province'))
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
                                        @if (old('Kota'))
                                            <select name="Kota" class="form-control" id="kota">
                                                <option selected value="{{ old('Kota') }}">{{ old('Kota') }}
                                                </option>
                                            </select>
                                        @else
                                            <select name="Kota" class="form-control" id="kota"
                                                value="{{ old('Kota') }}" required>
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
                                            value="{{ old('PostalCode') }}" autocomplete="off" required>
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
                                                @if ($q == old('StatusPenanamanModal'))
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
                                            value="{{ old('JenisUsaha') }}" autocomplete="off" required>
                                        <span class="input-group-addon">
                                            <span class="fa fa-list-alt"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                            value="{{ old('NoNIB') }}" autocomplete="off" required>
                                        <span class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-6'>
                                Upload NIB
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='file' accept=".pdf,.PDF" name="NibFile"
                                            value="{{ old('NibFile') }}" class="form-control" required>
                                        <span class="input-group-addon">
                                            <span class="fa fa-upload" aria-hidden="true"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-6'>
                                No NPWP
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='text' name="NoNpwp" id="npwp" class="form-control"
                                            value="{{ old('NoNpwp') }}" autocomplete="off" required>
                                        <span class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-6'>
                                Upload NPWP
                                <div class="form-group">

                                    <div class='input-group '>

                                        <input type='file' accept=".pdf,.PDF" name="NpwpFile"
                                            value="{{ old('NpwpFile') }}" class="form-control" required>
                                        <span class="input-group-addon">
                                            <span class="fa fa-upload" aria-hidden="true"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class='col-sm-3'>
                                Nama Bank
                                <div class="form-group">
                                    <div class='input-group '>
                                        <select  name="NamaBank" class="form-control select2" required>
                                            @foreach ($databank as $q)
                                                @if ($q->name == old('NamaBank'))
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
                                            value="{{ old('NoRekening') }}" autocomplete="off" required>
                                        <span class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class='col-sm-3'>
                                Atas Nama Pemilik
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='text' name="AtasNamaBank" value="{{ old('AtasNamaBank') }}"
                                            class="form-control" 
                                            autocomplete="off" required>
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
                                            value="{{ old('FileRekening') }}" class="form-control" required>
                                        <span class="input-group-addon">
                                            <span class="fa fa-upload" aria-hidden="true"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class='col-sm-4'>
                                Upload Akta Pendirian
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='file' accept=".pdf,.PDF" name="FileAktaPendirian"
                                            value="{{ old('FileAktaPendirian') }}" class="form-control" required>
                                        <span class="input-group-addon">
                                            <span class="fa fa-upload" aria-hidden="true"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>


                            <div class='col-sm-4'>
                                Upload Anggaran Dasar
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='file' accept=".pdf,.PDF" name="FileAnggaranDasar"
                                            value="{{ old('FileAnggaranDasar') }}" class="form-control" required>
                                        <span class="input-group-addon">
                                            <span class="fa fa-upload" aria-hidden="true"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>



                            <div class='col-sm-4'>
                                Upload Ijin Usaha
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='file' accept=".pdf,.PDF" name="FileIjinUsaha"
                                            value="{{ old('FileIjinUsaha') }}" class="form-control" required>
                                        <span class="input-group-addon">
                                            <span class="fa fa-upload" aria-hidden="true"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>


                            <div class='col-sm-4'>
                                Upload SKDP
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='file' accept=".pdf,.PDF" name="FileSKDP"
                                            value="{{ old('FileSKDP') }}" class="form-control" required>
                                        <span class="input-group-addon">
                                            <span class="fa fa-upload" aria-hidden="true"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>



                            <div class='col-sm-4'>
                                Upload KTP Direksi
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='file' accept=".pdf,.PDF" name="FileKtpDireksi"
                                            value="{{ old('FileKtpDireksi') }}" class="form-control" required>
                                        <span class="input-group-addon">
                                            <span class="fa fa-upload" aria-hidden="true"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>


                            <div class='col-sm-4'>
                                Upload SKT
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='file' accept=".pdf,.PDF" name="FileSKT" class="form-control"
                                            value="{{ old('FileSKT') }}" required>
                                        <span class="input-group-addon">
                                            <span class="fa fa-upload" aria-hidden="true"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class='col-sm-6'>
                                No SKPKP
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='TEXT' name="Skpkp" class="form-control"
                                            value="{{ old('Skpkp') }}" autocomplete="off">
                                        <span class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-6'>
                                Upload SKPKP
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='file' accept=".pdf,.PDF" name="FileSkpkp" class="form-control">
                                        <span class="input-group-addon">
                                            <span class="fa fa-upload" aria-hidden="true"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class='col-sm-6'>
                                No Surat Keagenan
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='text' name="SuratAgen" autocomplete="off" class="form-control"
                                            value="{{ old('SuratAgen') }}" required>
                                        <span class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>


                            <div class='col-sm-6'>
                                Upload Surat Keagenan
                                <div class="form-group">
                                    <div class='input-group '>
                                        <input type='file' accept=".pdf,.PDF" name="FileSuratAgen"
                                            class="form-control" required>
                                        <span class="input-group-addon">
                                            <span class="fa fa-upload" aria-hidden="true"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

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


                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
                                                <input type='text' name="NoKbli[]"  class="form-control" maxlength="5" >
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

