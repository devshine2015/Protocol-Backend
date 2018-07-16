$(document).ready(function () {
  $('.collapse.in').prev('.panel-heading').addClass('active');
  $('#accordion, #bs-collapse')
    .on('show.bs.collapse', function (a) {
        $(a.target).prev('.panel-heading').addClass('active');
    })
    .on('hide.bs.collapse', function (a) {
        $(a.target).prev('.panel-heading').removeClass('active');
    });
   //  size_p = $("#profileData p").length;
   //    if(size_p >=1){
   //        $("#loadData").removeClass('hidden');
   //    }
   // $("#profileData p").slice(0, 1).show();
   //    $("#loadMore").on('click', function (e) {
   //        e.preventDefault();
   //        $("#profileData p:hidden").slice(0, 1).slideDown();
   //        if ($("#profileData p:hidden").length == 0) {
   //            $("#load").fadeOut('slow');
   //        }
   //        $('html,body').animate({
   //            scrollTop: $(this).offset().top
   //        }, 1500);
   //    });
    size_p = $("#profileData p").size;
    x=3;
    $('#profileData p:lt('+x+')').show();
    $('#loadMore').click(function () {
        x= (x+5 <= size_p) ? x+5 : size_p;
        $('#profileData p:lt('+x+')').show();
    });
    // $('#showLess').click(function () {
    //     x=(x-5<0) ? 3 : x-5;
    //     $('#profileData li').not(':lt('+x+')').hide();
    // });
});