$(document).ready(function () {
    if (authCheck != 1) {
      checkLoginUser();
    }
    //follow
     $('button[data-id]').each(function () {
      if ($(this).attr('data-follow') == 1) {
          $(this).addClass('following');
      }else{
           $(this).removeClass('following')
      }

    });
      //check login or not
    function checkLoginUser(){
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN':csrfToken,
          'Authorization':'Bearer '+bridgitToken
        }
      });
      $.ajax({
          url: checkLogin,
          type:"GET",
          dataType : 'json',
          success:function(data) {
            console.log(data);
            if(data){
              chekLoginTest = 1
            }
          }
      });
      //check login
    }
     $('button[data-id]').click(function(){
          var $this = $(this);
          $this.toggleClass('following')
          //start ajax
            var id = $(this).attr('data-id');
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN':csrfToken
              }
            });
            $.ajax({
                url: followUserUrl,
                type:"POST",
                data: {
                    user_id: id,
                },
                dataType : 'json',
                success:function(data) {
                  if (data=='') {
                    console.log('unfollow');
                    $('button[data-id = '+id+']').removeClass('following');
                  }else{
                    console.log('follow');
                    $('button[data-id = '+id+']').addClass('following');
                  }
                  // location.reload();
                }
            });
        //complete ajax
          if($this.is('.following')){
            $this.addClass('wait');
          }
        }).on('mouseleave',function(){
          $(this).removeClass('wait');
        })
    //unfollow
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
          });
    // see more
});