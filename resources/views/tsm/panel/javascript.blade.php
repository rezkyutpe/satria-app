<!-- jQuery -->
<script src="{{ asset('public/assets-tsm/vendors/jquery/dist/jquery.min.js') }}"></script>
<!-- <script src="{{ asset('public/assets-tsm/datatables/js/jquery-3.5.1.js') }}"></script> -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<!-- Select2 -->
<script src="{{ asset('public/assets-tsm/vendors/select2/js/select2.full.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
<!-- Bootstrap -->
<script src="{{ asset('public/assets-tsm/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('public/assets-tsm/vendors/fastclick/lib/fastclick.js') }}"></script>
<!-- NProgress -->
<script src="{{ asset('public/assets-tsm/vendors/nprogress/nprogress.js') }}"></script>
<!-- Chart.js -->
<script src="{{ asset('public/assets-tsm/vendors/Chart.js/dist/Chart.min.js') }}"></script>
<!-- gauge.js -->
<script src="{{ asset('public/assets-tsm/vendors/gauge.js/dist/gauge.min.js') }}"></script>
<!-- bootstrap-progressbar -->
<script src="{{ asset('public/assets-tsm/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ asset('public/assets-tsm/vendors/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('public/assets-tsm/vendors/dropzone/dist/min/dropzone.min.js') }}"></script>
<!-- Skycons -->
<script src="{{ asset('public/assets-tsm/vendors/skycons/skycons.js') }}"></script>
<!-- Flot -->
<script src="{{ asset('public/assets-tsm/vendors/Flot/jquery.flot.js') }}"></script>
<script src="{{ asset('public/assets-tsm/vendors/Flot/jquery.flot.pie.js') }}"></script>
<script src="{{ asset('public/assets-tsm/vendors/Flot/jquery.flot.time.js') }}"></script>
<script src="{{ asset('public/assets-tsm/vendors/Flot/jquery.flot.stack.js') }}"></script>
<script src="{{ asset('public/assets-tsm/vendors/Flot/jquery.flot.resize.js') }}"></script>
<!-- Flot plugins -->
<script src="{{ asset('public/assets-tsm/vendors/flot.orderbars/js/jquery.flot.orderBars.js') }}"></script>
<script src="{{ asset('public/assets-tsm/vendors/flot-spline/js/jquery.flot.spline.min.js') }}"></script>
<script src="{{ asset('public/assets-tsm/vendors/flot.curvedlines/curvedLines.js') }}"></script>
<!-- DateJS -->
<script src="{{ asset('public/assets-tsm/vendors/DateJS/build/date.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('public/assets-tsm/vendors/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.js') }}">
</script>
<script src="{{ asset('public/assets-tsm/vendors/jqvmap/dist/jquery.vmap.js') }}"></script>
<script src="{{ asset('public/assets-tsm/vendors/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
<script src="{{ asset('public/assets-tsm/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js') }}"></script>

<!-- Datatables -->
<script src="{{ asset('public/assets-tsm/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/assets-tsm/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('public/assets-tsm/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/assets-tsm/vendors/datatables.net-scroller/js/dataTables.scroller.min.js') }}"></script>
<script src="{{ asset('public/assets-tsm/datatable/js/dataTables.fixedColumns.min.js') }}"></script>
<script src="{{ asset('public/assets-tsm/datatable/js/dataTables.fixedHeader.min.js') }}"></script>
<script src="{{ asset('public/assets-tsm/datatable/js/dataTables.select.min.js') }}"></script>
<script src="{{ asset('public/assets-tsm/datatable/js/dataTables.keyTable.min.js') }}"></script>
<script src="{{ asset('public/assets-tsm/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('public/assets-tsm/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('public/assets-tsm/datatable/js/buttons.html5.js') }}"></script>
<script src="{{ asset('public/assets-tsm/datatable/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('public/assets-tsm/datatable/js/jszip.min.js') }}"></script>
<script src="{{ asset('public/assets-tsm/datatable/js/vfs_fonts.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<!-- End Datatables -->

<!-- MomentJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.3/moment.min.js"></script>


<script src="{{ asset('public/assets-tsm/vendors/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('public/assets-tsm/vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<!-- Custom Theme Scripts -->
<script src="{{ asset('public/assets-tsm/build/js/custom.min.js') }}"></script>

<!-- Sweeetalert -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- AutoComplete -->
<script src="{{ asset('public/assets-tsm/vendors/jquery.autocomplete.min.js') }}"></script>

<!-- CheckBox Datatables -->
<script type="text/javascript"
    src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>

<!-- Semantic UI -->
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js"></script> -->

<script>
$(document).ready(function() {

    $('#datatable-visibility').DataTable({
        stateSave: true,
        dom: 'Bfrtip',
        buttons: [
            'pageLength', 'colvis', 'copy', 'csv', 'excel', [{
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            }], 'print'
        ]
    });

    $(document).on('click', '#Logout', function() {
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

    if ($(".data_update_time").length > 0) {
        var UpdateDate = moment($(".data_update_time").text());
        var CurrentDate = moment();
        var Diff = UpdateDate.from(CurrentDate);
        if (CurrentDate.diff(UpdateDate, 'years') >= 1) {
            $(".update_time").html("Database not updated yet, please refresh database first!");
        } else {
            $(".update_time").html("Database updated " + Diff);
        };
    }

    $(document).on('click', '.btn-refresh', function() {
        $(".btn-refresh").removeClass("btn-success");
        $(".btn-refresh").addClass("btn-secondary");
        $(".btn-refresh").addClass("disabled");
        $(".btn-refresh").html("Updating Database...");
    });

    $('.data-time-update').css({
        "border-top": "2px solid #E6E9ED"
    });

    $(".datepicker").datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
    });

});
</script>
