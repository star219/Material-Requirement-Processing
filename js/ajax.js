$(function(){
  $('#insertbtn').on('click', function(e){
    var dedate = $('#dedate').val();
    var pname = $('#pname').val();
    var pnum = $('#pnum').val();
    var rnum = $('#rnum').val();
    $.ajax({
      url: 'php/function.php',
      type: 'post',
      data: {'action': 'submit',"dedatee":dedate,"pnamee":pname,"pnumm":pnum,"rnumm":rnum},
      success: function(data, status) {
        if(data) {
          alert(data);
          location.reload();
          cache_clear();
          //document.write(data);
        }
      },
    }); // end ajax call
  });
});

