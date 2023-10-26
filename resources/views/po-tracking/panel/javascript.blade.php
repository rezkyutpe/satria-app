<!-- jQuery -->
<script src="{{ asset("public/assetss/datatables/js/jquery-3.5.1.js") }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('public/assetss/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('public/assetss/vendors/fastclick/lib/fastclick.js') }}"></script>
<!-- NProgress -->
<script src="{{ asset('public/assetss/vendors/nprogress/nprogress.js') }}"></script>
<!-- Chart.js -->
<script src="{{ asset('public/assetss/vendors/Chart.js/dist/Chart.min.js') }}"></script>
<!-- gauge.js -->
<script src="{{ asset('public/assetss/vendors/gauge.js/dist/gauge.min.js') }}"></script>
<!-- bootstrap-progressbar -->
<script src="{{ asset('public/assetss/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ asset('public/assetss/vendors/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('public/assetss/vendors/dropzone/dist/min/dropzone.min.js') }}"></script>
<!-- Skycons -->

<script src="{{ asset('public/assetss/vendors/skycons/skycons.js') }}"></script>
 <!-- PNotify -->
 <script src="{{ asset('public/assetss/vendors/pnotify/dist/pnotify.js') }}"></script>
 <script src="{{ asset('public/assetss/vendors/pnotify/dist/pnotify.buttons.js') }}"></script>
 <script src="{{ asset('public/assetss/vendors/pnotify/dist/pnotify.nonblock.js') }}"></script>
<!-- Flot -->
<script src="{{ asset('public/assetss/vendors/Flot/jquery.flot.js') }}"></script>
<script src="{{ asset('public/assetss/vendors/Flot/jquery.flot.pie.js') }}"></script>
<script src="{{ asset('public/assetss/vendors/Flot/jquery.flot.time.js') }}"></script>
<script src="{{ asset('public/assetss/vendors/Flot/jquery.flot.stack.js') }}"></script>
<script src="{{ asset('public/assetss/vendors/Flot/jquery.flot.resize.js') }}"></script>
<!-- Flot plugins -->
<script src="{{ asset('public/assetss/vendors/flot.orderbars/js/jquery.flot.orderBars.js') }}"></script>
<script src="{{ asset('public/assetss/vendors/flot-spline/js/jquery.flot.spline.min.js') }}"></script>
<script src="{{ asset('public/assetss/vendors/flot.curvedlines/curvedLines.js') }}"></script>
<!-- DateJS -->
<script src="{{ asset('public/assetss/vendors/DateJS/build/date.js') }}"></script>
<!-- JQVMap -->

<script src="{{ asset('public/assetss/vendors/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.js') }}"></script>

<script src="{{ asset('public/assetss/vendors/jqvmap/dist/jquery.vmap.js') }}"></script>
<script src="{{ asset('public/assetss/vendors/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
<script src="{{ asset('public/assetss/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js') }}"></script>

<!-- Datatables -->
<script src="{{ asset('public/assetss/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/assetss/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('public/assetss/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/assetss/vendors/datatables.net-scroller/js/dataTables.scroller.min.js') }}"></script>
<script src="{{ asset('public/assetss/datatable/js/dataTables.fixedColumns.min.js') }}"></script>
<script src="{{ asset('public/assetss/datatable/js/dataTables.fixedHeader.min.js') }}"></script>
<script src="{{ asset('public/assetss/datatable/js/dataTables.select.min.js') }}"></script>
<script src="{{ asset('public/assetss/datatable/js/dataTables.keyTable.min.js') }}"></script>
<script src="{{ asset('public/assetss/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('public/assetss/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('public/assetss/datatable/js/buttons.html5.js') }}"></script>
<script src="{{ asset('public/assetss/datatable/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('public/assetss/datatable/js/jszip.min.js') }}"></script>
<script src="{{ asset('public/assetss/datatable/js/vfs_fonts.js') }}"></script>
<script src="{{ asset('public/assetss/js/pdfmake.min.js') }}"></script>
<script src="{{ asset('public/assetss/js/vfs_fonts.js') }}"></script>
<script src="{{ asset('public/assetss/js/buttons.print.min.js') }}"></script>
<script>
    $(document).ready(function() {

        $('.datatable').DataTable({
            // pagingType: 'full_numbers',
        });

        $('#datatable-visibility').DataTable( {
            stateSave: true,
            dom: 'Bfrtip',
            buttons: [
                'pageLength','colvis','copy', 'csv',{
                    extend: 'excelHtml5',
                    text: 'Excel',
                    customizeData: function(data) {
                        for(var i = 0; i < data.body.length; i++) {
                            for(var j = 0; j < data.body[i].length; j++) {
                                data.body[i][j] = '\u200C' + data.body[i][j];
                            }
                        }
                    }
                    // exportOptions: {
                    //     columns: ':visible',
                    //     format: {
                    //         body: function(data, row, column, node) {
                    //             let node_text = '';
                    //             const spacer = node.childNodes.length > 1 ? ' ' : '';
                    //             node.childNodes.forEach(child_node => {
                    //                 const temp_text = child_node.nodeName == "SELECT" ? child_node.selectedOptions[0].textContent.trim() : child_node.textContent.trim();
                    //                 node_text += temp_text ? `${temp_text}${spacer}` : '';
                    //             });
                    //             // console.log(node_text);
                    //             return $.isNumeric(node_text.replace(',', '.').replace(',000', '.000')) ? node_text.replace(',', '.').replace(',000', '.000') : node_text;
                    //         }
                    //     }
                    // }
                }
                , 'pdf', 'print'
            ],
            "columnDefs": [
                { "orderable": false, "targets": -1 }
            ]

        } );
        // $('#datatable-visibility-price').DataTable( {
        //     stateSave: true,
        //     dom: 'Bfrtip',
        //     buttons: [
        //         'pageLength','colvis','copy', 'csv', 'excel', 'pdf', 'print'
        //     ],
        //     columnDefs: [{
        //     targets: 6,
        //     type: 'num',
        //     render: $.fn.dataTable.render.number('.', ',', 2,'')
        // }]

        // } );
    } );
</script>

{{-- End Datatables --}}


<script src="{{ asset('public/assetss/vendors/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('public/assetss/vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<!-- Custom Theme Scripts -->
<script src="{{ asset('public/assetss/build/js/custom.min.js') }}"></script>
<script src="{{ asset('public/assetss/js/select2.min.js') }}"></script>
{{-- Sweeetalert --}}
<script src="{{ asset('public/assetss/js/sweetalert2@11.js') }}"></script>
<script src="{{ asset('public/assetss/js/toastr.min.js') }}"></script>
<script>
    @if(Session::has('error'))
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-custom",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "50000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        toastr.error("{{ session('error') }}", "{{ session('title') }}");
    @endif
</script>
<script>
     $('.select2').select2({
        placeholder: 'Select an option'
    });
    $('.select2-multiple').select2({
        placeholder: 'Select an option',
        multiple: true
    });
</script>
<script>
    $(document).ready(function() {
        $(document).on('click', '#btn-delete', function() {
            event.preventDefault();
            const url = $(this).attr("data-url");
            Swal.fire({
                title: 'Are you sure?',
                text: "You are attempting to log out of system",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, log out'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#logout-form').submit();
                } else {
                    return false;
                }
            })
        });
    });
</script>

<script>
    $(document).ready(function() {
        $(".datepicker").datepicker({
            format: "dd/mm/yyyy",
            autoclose: true,
        });
    });
</script>

<!-- FullCalendar -->
<script src="{{ asset('public/assetss/vendors/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('public/assetss/vendors/fullcalendar/dist/fullcalendar.min.js') }}"></script>

<!-- jquery.inputmask -->
<script src="{{ asset('public/assetss/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js') }}"></script>