$(function() {
  // Datepicker only
  $('.datepicker').datetimepicker({
    format: 'yyyy-mm-dd hh:ii',
    todayBtn: true,
    showMeridian: true,
    autoclose: true
  });

  // Datetime picker
  // @link http://www.malot.fr/bootstrap-datetimepicker/
  $('.datetimepicker').datetimepicker({
    format: 'yyyy-mm-dd hh:ii',
    todayBtn: true,
    showMeridian: true,
    autoclose: true
  });
});

