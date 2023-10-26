@extends('panel.master')

@section('css')

<style>
    a.disabled {
        pointer-events: none;
        cursor: default;
    }
</style>
@endsection

@section('content')

<div class="loader" style="display:none;">
    <div class="loader-main"><i class="fa fa-spinner fa-pulse"></i></div>
</div>

<div class="content-body-white">

    <form method="post" action="{{url('insert-incentive')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="page-head">
            <div class="page-title">
                <h1>Preview Incentive </h1>
            </div>
            
        </div>
        <div class="wrapper">
            <div class="row">
                @if(session()->has('err_message'))
                <div class="alert alert-danger alert-dismissible" role="alert" auto-close="10000">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    {{ session()->get('err_message') }}
                </div>
                @endif
                @if(session()->has('succ_message'))
                <div class="alert alert-success alert-dismissible" role="alert" auto-close="10000">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    {{ session()->get('succ_message') }}
                </div>
                @endif
                <div class="col-md-12 element">
                    <div class="box-pencarian-family-tree" style=" background: #fff; ">
                        <div class="row">

                            <div class="col-xl-12 col-md-12 m-b-12px">
                                <div class="row">
                                    <div class="col-md-12 col-xl-12 m-b-10px">
                                        <fieldset>
                                        <div class="modal-body text-center">
                                            <h2>Warning</h2>
                                            <p>Are you sure to upload package?</p>
                                        </div>
                                           
                                            <table>
                                                
                                                <!-- <thead>
                                                    <tr>
                                                        <th>Inv</th>
                                                        <th>Inv Date</th>
                                                        <th>Cash Date</th>
                                                        <th>Sales</th>
                                                        <th>Sales Name</th>
                                                        <th>ID Cust</th>
                                                        <th>Cust</th>
                                                        <th>Cust Profile</th>
                                                        <th>Product</th>
                                                        <th>Segment</th>
                                                        <th>Qty</th>
                                                        <th>Cost</th>
                                                        <th>Price</th>
                                                    </tr>
                                                </thead> -->
                                                <!-- <tbody> -->
                                               
                                                @php ($total = 0)
                                                @php ($no = 0)
                                                @php ($dup = 0)
                                               
                                                @for($i=0;$i<=count($data['sales'])-1;$i++)
                                                
                                                @if($data['sales'][$i]!='')
                                                    @foreach($data['inc'][0] as $inc)
                                                    @if($inc[2]!='')
                                                    
                                                        @if($data['sales'][$i]==$inc[0])
                                                            
                                                            
                                                            <tr>
                                                            
                                                                <td> 
                                                                    <input type="hidden" name="cust_type[]" value="{{ Helper::CekCust($inc[5]) }}" class="form-control">
                                                                    <input type="hidden" name="no[]" value="{{ $no }}" class="form-control">
                                                                    <input type="hidden" name="inv[]" value="{{ $inc[2] }}" class="form-control">
                                                                    <input type="hidden" name="sales_val[]" value="{{ $i }}" class="form-control">
                                                                </td>
                                                                <td> 
                                                                <input type="hidden" name="inv_date[]" value="{{ $inc[3] }}" class="form-control">
                                                                </td>
                                                                <td> 
                                                                <input type="hidden" name="cash_date[]" value="{{ $inc[4] }}" class="form-control">
                                                                <!-- </td>
                                                                <td>  -->
                                                                <input type="hidden" name="sales[]" value="{{ $inc[0] }}" class="form-control">
                                                                <!-- </td>
                                                                <td>  -->
                                                                <input type="hidden" name="sales_profile[]" value="{{ $inc[1] }}" class="form-control">
                                                                <!-- </td>
                                                                <td>  -->
                                                                <input type="hidden" name="id_cust[]" value="{{ $inc[5] }}" class="form-control">
                                                                </td>
                                                                <td> 
                                                                <input type="hidden" name="cust[]" value="{{ $inc[6] }}" class="form-control">
                                                                </td>
                                                                <td> 
                                                                <input type="hidden" name="cust_profile[]" value="{{ $inc[7] }}" class="form-control">
                                                                <!-- </td>
                                                                <td> -->
                                                                <input type="hidden" name="product[]" value="{{ $inc[8] }}" class="form-control">
                                                                </td>
                                                                
                                                                <td> 
                                                                <input type="hidden" name="segment[]" value="{{ $inc[9] }}" class="form-control">
                                                                </td>
                                                                <td> 
                                                                <input type="hidden" name="qty[]" value="{{ $inc[10] }}" class="form-control">
                                                                </td>
                                                                <td> 
                                                                <input type="hidden" name="cost[]" value="{{ $inc[11] }}" class="form-control">
                                                                </td>
                                                                <td> 
                                                                <input type="hidden" name="price[]" value="{{ $inc[13]  }}" class="form-control">
                                                                </td>
                                                                <td> 
                                                                <input type="hidden" name="cash_in[]" value="{{ $inc[12]  }}" class="form-control">
                                                                </td>
                                                            </tr>
                                                            
                                                            @php ($total = $total+round($inc[13],0))
                                                        @php($no = $no+1)
                                                        @endif
                                                    @endif
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="5" style="border: none"></td>
                                                        <td style="border: none"> </td>
                                                        <td colspan="3" style="border: none"> <input type="hidden" name="total[]" value="{{ $total }}" class="form-control"> </td>
                                                        
                                                    </tr>
                                                    @endif
                                                    
                                                    @php ($total = 0)
                                                @endfor
                                                <!-- </tbody> -->
                                                
                                                <span><strong> Data tersedia : </strong>{{ $no }} invoice dari {{ count($data['sales']) }} sales, </span>
                                                <span><strong> Data Akan Disampan : </strong>{{ $no-$dup }} invoice, </span>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">No</button>
                                                    <input id="myBtn" type="submit" name=""
                                                                value="Yes" class="btn btn-success">
                                                </div>
                                               
                                            </table>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

         
        </div>
    </form>
</div>


@endsection

@section('myscript')



<script>
    $('[type=tel]').on('change', function (e) {
        $(e.target).val($(e.target).val().replace(/[^\d\.]/g, ''))
    });
    $('[type=tel]').on('keypress', function (e) {
        keys = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.']
        return keys.indexOf(event.key) > -1
    });
    $(document).on('submit', 'form', function () {
        $(".loader").attr("style", "display:block;");
    });
</script>
@endsection
