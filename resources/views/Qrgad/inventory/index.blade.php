@extends('Qrgad/layout/qrgad-admin')

@section('content')
    <div class="card shadow">
        <div class="">
            <div class="card-header">
                <div class="d-flex">
                    <h4 class="mr-3">Table Inventory</h4>
                </div>    
            </div>
            
            <div class="card-body">

                <div class="row container">
                    <div class="form-group col-md-2 mr-3">
                        <label for="start_date">Stock Status</label>
                        <select id="status" class="form-control">
                            <option value="0" selected>All</option>
                            <option value="1">Available</option>
                            <option value="2">Not Available</option>
                        </select>
                    </div>
                    <div class="form-group col-md-1 mt-auto">
                        <a class="btn btn-primary text-white float-right" onclick="read()">
                            <div class="d-flex">
                                <div class="">
                                    <i class="fa fa-filter"></i>
                                </div>
                                <div class= "ml-2">
                                    <span>Filter</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div id="table_container" class="mt-3">
                    {{-- Ajax Table --}}
                </div>

            </div>
        </div>
    </div>

    {{-- modal delete --}}
    <div class="modal" id="modalDelete" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Hapus Data</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            
            <form id="formDelete" action="/konsumable" method="post" class="d-inline">
                @method('delete')
                @csrf
                <div class="modal-body" >
                    <div class="form-group">
                        <p class="mb-3">Yakin ingin menghapus data?</p>
                        <div class="inline">
                            <button class="btn btn-danger float-right" >Hapus</button>
                            <button class="btn btn-secondary float-right mr-1" data-dismiss="modal">Batal</button>
                        </div>
                        <br><br>
                    </div>
                </div>
            </form>
          </div>
        </div>
    </div>
 
@endsection

@section('script')
    
    @if (session()->has('alert'))
        @php
            $alert = session()->get('alert');
            $state = explode('-', $alert)[0];
            $action = explode('-', $alert)[1];
            $menu = explode('-', $alert)[2];
        @endphp

        <script>
            var state = @json($state);
            var action = @json($action);
            var menu = @json($menu);

            getAlert(state, action, menu);
        </script>
    @endif  
    
    {{-- <script>
        $(document).ready(function() {
            $('#basic-datatables').DataTable({
            });
        });
        $(function () {
            $("[rel='tooltip']").tooltip();
        });
    </script> --}}

    <script>

        $('document').ready(function(){
            read();
        });

        function read(){
            var status = $('#status').val();
                
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            });

            $.ajax({
                type:"get",
                url:"{{ url('/inventory-read') }}/"+status,
                success:function(data){
                    $('#table_container').html(data);
                },error: function(xhr, status, error) {
                    var err = eval("(" + xhr.responseText + ")");
                    // alert(err.Message);
                }
            });
        }

    </script>

 @endsection    