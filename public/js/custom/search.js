$(document).ready(function () {
    //see more
      size_p = $("#profileData p").length;
      if(size_p >=5){
          $("#loadData").removeClass('hidden');
      }
       $("#profileData p").slice(0, 5).show();
          $("#loadMore").on('click', function (e) {
              e.preventDefault();
              $("#profileData p:hidden").slice(0, 5).slideDown();
              if ($("#profileData p:hidden").length == 0) {
                  $("#load").fadeOut('slow');
                  $("#loadData").addClass('hidden');
              }
          });
    // see more
});