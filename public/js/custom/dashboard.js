$(document).ready(function () {
  $('.collapse.in').prev('.panel-heading').addClass('active');
  $('#accordion, #bs-collapse')
    .on('show.bs.collapse', function (a) {
        $(a.target).prev('.panel-heading').addClass('active');
    })
    .on('hide.bs.collapse', function (a) {
        $(a.target).prev('.panel-heading').removeClass('active');
    });
    //see more
      size_p = $("#profileData p").length;
      if(size_p >7){
          $("#loadData").removeClass('hidden');
      }
       $("#profileData p").slice(0, 7).show();
          $("#loadMore").on('click', function (e) {
              e.preventDefault();
              $("#profileData p:hidden").slice(0, 7).slideDown();
              if ($("#profileData p:hidden").length == 0) {
                  $("#load").fadeOut('slow');
                  $("#loadData").addClass('hidden');
              }
              $('html,body').animate({
                  scrollTop: $(this).offset().top
              }, 1500);
          });
    // see more
    //see more notification
      size_p = $("#notificationData tr").length;
      if(size_p >7){
          $("#loadNotification").removeClass('hidden');
      }
       $("#notificationData tr").slice(0, 7).show();
          $("#loadnotify").on('click', function (e) {
              e.preventDefault();
              $("#notificationData tr:hidden").slice(0, 7).slideDown();
              if ($("#notificationData tr:hidden").length == 0) {
                  $("#load").fadeOut('slow');
                  $("#loadNotification").addClass('hidden');
              }
              $('html,body').animate({
                  scrollTop: $(this).offset().top
              }, 1500);
          });
    // see more
});