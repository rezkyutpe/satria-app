<div class="modal fade" id="ShowCalendar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h5 class="modal-title" id="exampleModalCenterTitle"><i class="fa fa-calendar-o"></i> Calendar</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <small class="ml-2 text-danger">*Click days to disable/enable create ticket date</small>
        <div class="modal-body">
            <div class="container">
                <div id="showFullCalendar" class="row">
                    <div id="showcalendar"></div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <form id="form_update_fullcalendar" method="post" action="{{url('update_disabled_date_fullcalendar')}}" enctype="multipart/form-data">
            {{csrf_field()}}
            <div id="i_date_disabled"></div>
          </form>
          <button type="button" id="submitFullCalendar" class="btn btn-primary">Save</button>
        </div>
      </div>
    </div>
</div>

<script>
    $(document).ready(function() {

      var tanggal_disabled = [];
      var tanggal_onclick = [];
      $('.fullcalendarclass').on('click',function(){
        $.ajax({
          url : "{{url('view_disabled_date_fullcalendar')}}",
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            if(data == ''){
              
            }else{
              tanggal_disabled = data;
              tanggal_onclick = data;
            }
              $('#showFullCalendar').html('<div id="showcalendar"></div>');
              $("#showcalendar").fullCalendar({
                defaultView: 'month',
                dayClick: function(date, jsEvent, view) {
                    // console.log(date);
                    // console.log(jsEvent);
                    // console.log(view);
                    // console.log($(this).data('date'));
                    if(tanggal_onclick.includes($(this).data('date'))){
                      var index = tanggal_onclick.indexOf($(this).data('date'));
                      if (index >= 0) {
                        tanggal_onclick.splice(index, 1 );
                      }
                      $(this).css('background', '');
                      console.log(tanggal_onclick);
                    }else{
                      tanggal_onclick.push($(this).data('date'));
                      $(this).css('background', '#F00');
                      console.log(tanggal_onclick);
                    }
                },
                dayRender: function (date, cell) {
                  for(i = 0; i < tanggal_onclick.length; i++ )
                  {
                    if(date.format('YYYY-MM-DD') == tanggal_onclick[i])
                    {
                      cell.css("background-color", "red");
                    }
                  }
                }
              });
            $('#ShowCalendar').modal('show');
          }
        });
      });

      $('#ShowCalendar').on('shown.bs.modal', function () {
        $('.fc-today-button').trigger('click');
      });

      $('#ShowCalendar').on('hiddden.bs.modal', function () {
        $('#showcalendar').remove();
      });

      $('#submitFullCalendar').on('click',function(){
        for(i = 0; i < tanggal_onclick.length; i++ )
        {
          $('#i_date_disabled').append(`<input type="hidden" name="date_disabled[]" value="`+tanggal_onclick[i]+`"/>`);
        }
        $('#form_update_fullcalendar').submit();
      });
    });
</script>