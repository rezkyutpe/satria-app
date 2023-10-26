@extends('completeness-component.panel.master')
@section('mycss')
    <style type="text/css">
        .btn-modal:hover {
            position: relative;
            z-index: 8000;
        }

        hr {
            margin-top: 20px;
            margin-bottom: 5px;
            border: 0;
            border-top: 1px solid #FFFFFF;
        }

        a {
            color: #616161;
            text-decoration: none;
        }

        .blog-comment ul {
            list-style-type: none;
            padding: 0;
        }

        .blog-comment .post-comments {
            border: 1px solid #ccc;
            margin-bottom: 10px;
            margin-left: 0px;
            margin-right: 0px;
            padding: 10px 20px;
            position: relative;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            -o-border-radius: 4px;
            border-radius: 4px;
            color: #6b6e80;
            position: relative;
        }

        .blog-comment .meta {
            font-size: 14px;
            color: #7e7e7e;
            padding-bottom: 8px;
            margin-bottom: 5px !important;
            border-bottom: 1px solid #eee;
        }

        .blog-comment ul.comments ul {
            list-style-type: none;
            padding: 0;
            margin-left: 85px;
        }

        .judul {
            margin-top: 10px;
            margin-bottom: 10px;
            font-size: 15px;
            line-height: 10px;
            font-weight: 550;
            color: black;
        }

        .area {
            border-radius: 4px;
        }

        .myLabel {
            color: black;
            font-size: 13px;
            margin-bottom: 15px;
            font-weight: 500;
        }
    </style>
@endsection

@section('contents')
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="x_title ml-3">
                    <h2 style="color: black">{{ $data['apps']->material_number . ' - ' . $data['apps']->material_description }}</h2>
                    <input type="hidden" id="material" value="{{ $data['apps']->material_number }}">
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="x_content">
                        <div class="col-md-12 col-sm-12" style="margin-top: -10px">
                            {{-- Tabel PO --}}
                            <div class="table-responsive" id="list-po">
                                <div class="judul">Purchase Order</div>
                                <table id="myTable" class="table table-striped table-bordered"
                                    style="text-align: center;width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>PO Number</th>
                                            <th>PO Item</th>
                                            <th>PO Quantity</th>
                                            <th>Open Quantity</th>
                                            <th>Delivery Date</th>
                                            <th>Vendor Name</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['data_po'] as $item)
                                            <tr>
                                                <td style="vertical-align: middle">{{ $loop->iteration }}</td>
                                                <td style="vertical-align: middle" class="po_number">
                                                    {{ $item->Number }}
                                                </td>
                                                <td style="vertical-align: middle" class="po_item">
                                                    {{ $item->ItemNumber }}
                                                </td>
                                                <td style="vertical-align: middle">
                                                    {{ $item->Quantity.' '.$data['apps']->base_unit }}
                                                </td>
                                                <td style="vertical-align: middle" class="total_order">
                                                    {{ $item->OpenQuantity.' '.$data['apps']->base_unit }}
                                                </td>
                                                <td style="vertical-align: middle" class="delivery_date">
                                                    {{ $item->ConfirmedDate == null ? '-' : date('d-m-Y', strtotime(substr($item->ConfirmedDate, 0, 10))) }}
                                                </td>
                                                <td style="vertical-align: middle" class="text-left">
                                                    {{ $item->Name }}
                                                </td>
                                                <td style="vertical-align: middle">
                                                    <button type="button" class="btn btn-sm btn-primary btn-edit"
                                                        data-toggle="modal" data-target="#exampleModal"
                                                        data-qty="{{ $item->Quantity.' '.$data['apps']->base_unit }}"
                                                        data-po-date="{{ $item->Date == null ? '-' : date('d-m-Y', strtotime($item->Date)) }}"
                                                        data-security-date="{{ $item->SecurityDate == null ? '-' : date('d-m-Y', strtotime($item->SecurityDate)) }}"
                                                        data-vendor="{{ $item->VendorCode . ' - ' . $item->Name }}"
                                                        data-po-release="{{ $item->ReleaseDate == null ? '-' : date('d-m-Y', strtotime($item->ReleaseDate)) }}"
                                                        data-creator="{{ $item->PurchaseOrderCreator == null ? '-' : $item->PurchaseOrderCreator }}">
                                                        <i class="fa fa-search fa-lg custom--1"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            {{-- Komentar --}}
                            @if ($data['actionmenu_komentar']->c == 1)
                                <div class="blog-comment" style="margin-top:30px;">
                                    <h3 class="judul">Comments</h3>
                                    <ul class="comments" id="komentar">
                                        @foreach ($data['vw_comment'] as $comment)
                                            <li class="clearfix">
                                                @if ($comment->user_by == Auth::user()->id)
                                                    <div class="post-comments shadow" style="float: right; width:95%;color:black;">
                                                        <p class="meta" style="color: black;">
                                                            <small>{{ date('d-m-Y H:i:s', strtotime($comment->created_at)) }}</small><i class="pull-right" style="color: black"><strong>{{ $comment->nama_pengirim }}</strong></i>
                                                        </p>
                                                        <p>
                                                            {{ $comment->comment }}
                                                        </p>
                                                    </div>
                                                @else
                                                    <div class="post-comments shadow-sm" style="width:95%">
                                                        <p class="meta">
                                                            <strong>{{ $comment->nama_pengirim }}</strong> <i
                                                                class="pull-right"><small>{{ date('d-m-Y H:i:s', strtotime($comment->created_at)) }}</small></i>
                                                        </p>
                                                        <p>
                                                            {{ $comment->comment }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @if ($data['actionmenu_komentar']->c == 1)
                                    <form>
                                        <label class="myLabel">Create a comment:</label>
                                        <textarea name="comment" id="comment" cols="30" rows="2" class="form-control area shadow-sm" required style="margin-top: -10px" required></textarea><br>
                                        <input type="button" id="btn-submit-komentar" class="btn btn-sm btn-primary" style="float: right" value="Send"></input>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('modal')
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Details PO</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table" style="margin-top: -17px">
                        <tr>
                            <td style="width: 28%">PO Date</td>
                            <td style="width: 1%">:</td>
                            <td id="po-date"></td>
                        </tr>
                        <tr>
                            <td>PO Release Date</td>
                            <td style="width: 1%">:</td>
                            <td id="po-release"></td>
                        </tr>
                        <tr>
                            <td>PO Creator</td>
                            <td>:</td>
                            <td id="po-creator"></td>
                        </tr>
                        <tr>
                            <td>PO Number</td>
                            <td>:</td>
                            <td id="po-number"></td>
                        </tr>
                        <tr>
                            <td>PO Item</td>
                            <td>:</td>
                            <td id="po-item"></td>
                        </tr>
                        <tr>
                            <td>Vendor</td>
                            <td>:</td>
                            <td id="po-vendor"></td>
                        </tr>
                        <tr>
                            <td>PO Quantity</td>
                            <td>:</td>
                            <td id="po-qty"></td>
                        </tr>
                        <tr>
                            <td>Open Quantity</td>
                            <td>:</td>
                            <td id="po-order"></td>
                        </tr>
                        <tr>
                            <td>Delivery Date</td>
                            <td>:</td>
                            <td id="po-delivery"></td>
                        </tr>
                        <tr>
                            <td>Security Date</td>
                            <td>:</td>
                            <td id="po-security"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('myscript')
    {{-- Campur --}}
    <script>
        $(document).ready(function() {
            // Komentar Update
            // setInterval(function(){
            //   $("#komentar").load(" #komentar > *");
            // }, 10000);

            // Modal Details PO
            $(document).on("click", ".btn-edit", function() {
                const parents = $(this).parents("tr");
                const poNumber = parents.find(".po_number").html().trim();
                const poItem = parents.find(".po_item").html().trim();
                const poTotal = parents.find(".total_order").html().trim();
                const poDelivery = parents.find(".delivery_date").html().trim();
                const poDate = $(this).attr("data-po-date").trim();
                const poRelease = $(this).attr("data-po-release").trim();
                const poVendor = $(this).attr("data-vendor").trim();
                const poQuantity = $(this).attr("data-qty").trim();
                const poCreator = $(this).attr("data-creator").trim();
                const poSecurity = $(this).attr("data-security-date").trim();
                $('#po-number').html(poNumber);
                $('#po-item').html(poItem);
                $('#po-order').html(poTotal);
                $('#po-qty').html(poQuantity);
                $('#po-delivery').html(poDelivery);
                $('#po-date').html(poDate);
                $('#po-vendor').html(poVendor);
                $('#po-creator').html(poCreator);
                $('#po-release').html(poRelease);
                $('#po-security').html(poSecurity);
            });

            $('#myTable').DataTable();

            var material = $('#material').val();
            
            $(document).on("click", "#btn-submit-komentar", function(){
                var data_komentar = $('#comment').val();
                if (data_komentar != '') {
                    $.ajax({
                        url         : "{{ url('comment') }}",
                        type        : "POST",
                        data        : {material:material, comment: data_komentar},
                        dataType    : "JSON",
                        success: function(data) {
                            if (data.kode == 1) {
                                $.ajax({
                                    url         : "{{ url('ccr-get-komentar-detail-material') }}",
                                    type        : "GET",
                                    data        : {material:material},
                                    dataType    : "JSON",
                                    success: function(data) {
                                        $('#komentar').empty();
                                        
                                        for (let a = 0; a < data.komentar.length; a++) {
                                            var date                  = new Date(data.komentar[a].created_at);
                                            var tanggal               = String(date.getDate()).padStart(2, '0');
                                            var bulan                 = String(date.getMonth() + 1).padStart(2,'0'); //January is 0!
                                            var tahun                 = date.getFullYear();
                                            var jam                 = date.getHours();
                                            var menit                 = date.getMinutes();
                                            var detik                 = date.getSeconds();
                                            var create_at             = tanggal + '-' + bulan + '-' + tahun + ' ' + jam + ':' + menit + ':' + detik ;
                                            create_at    = data.komentar[a].created_at == null ? '-' : create_at;

                                            if (data.komentar[a].user_by == {{ Auth()->user()->id }}) {
                                                $('#komentar').append(`
                                                    <li class="clearfix">
                                                        <div class="post-comments shadow" style="float: right; width:95%;color:black;">
                                                            <p class="meta" style="color: black;">
                                                                <small>`+create_at+`</small><i class="pull-right" style="color: black"><strong>`+data.komentar[a].nama_pengirim+`</strong></i>
                                                            </p>
                                                            <p>`+data.komentar[a].comment+`</p>
                                                        </div>
                                                    </li>
                                                `);
                                            } else {
                                                $('#komentar').append(`
                                                    <li class="clearfix">
                                                        <div class="post-comments shadow-sm" style="width:95%">
                                                            <p class="meta">
                                                                <strong>`+data.komentar[a].nama_pengirim+`</strong> <i class="pull-right"><small>`+create_at+`</small></i>
                                                            </p>
                                                            <p>`+data.komentar[a].comment+`</p>
                                                        </div>
                                                    </li>
                                                `);
                                            }

                                        }

                                        $('#comment').val('');
                                    }
                                });
                            }
                        }
                    });
                } else {
                    toastr.error("The comment field is empty", "Error !!");
                }
            })
        });
    </script>
@endsection
