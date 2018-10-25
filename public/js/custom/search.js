$(document).ready(function () {
var isReload = 2;
     var messageData = {
       "type": "BRIDGIT-WEB",
       "token":token
        // "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjRiMDA0ODU3NDU4ZDdkZWVjMzJmZTRmODY0OGMyN2IzNzRiZDZkYWYxYjkxMTU3MGExMWU5MTZmNmEyYjBmNjc3NWE1ODJlNjhhYTA3MmI2In0.eyJhdWQiOiIxIiwianRpIjoiNGIwMDQ4NTc0NThkN2RlZWMzMmZlNGY4NjQ4YzI3YjM3NGJkNmRhZjFiOTExNTcwYTExZTkxNmY2YTJiMGY2Nzc1YTU4MmU2OGFhMDcyYjYiLCJpYXQiOjE1NDAxOTIwMzcsIm5iZiI6MTU0MDE5MjAzNywiZXhwIjoxNTcxNzI4MDM3LCJzdWIiOiI3Iiwic2NvcGVzIjpbXX0.Xy9EIpryR2b73qnRowcuKc4C06-PwVLRh-kPE1unLtJcPZ_llLRT1Ec8YEaSFfOqPXZ0Dn6es7Rfn0FW1BpLaiTgLGfnRsdE22Ux_IbgxiKqACED1CcbvWczrT4iZUMSX0zqKtVuKjioRFUGu5nHmJ_TB-RH09mvRwsYu_mK5E8EQx8FbtYZMuAJxPxslqdthIbGE2vSGe66feoQX4rCR7fPOSzQH7FuJgTTank_yMjeJGYINxjenlvSMLjvtMggiHMTbvMfLK4bPA9DaQyJx2nilSgLcUW6tEIihXMZgdJxVJ6pMldxe9_WwnTveb-y5-5eKwaNb2EEi8-Qkjv6GBylVUml8W3pMqHI672L1Fr38Ae4OEXKKoAEeOLxqWVFXwPWAc-IQHCIMzqy0gS4f_qfhiUaE325qM1ii5mNyWaw1bPtbccdpu17mk9zs1ByvKaTADaF3Ney0o4Azf6UExrPpgSdWLJVB2kQFViEEEJ1kLGrda7O75NopQJG107a85oG9fc9_8m-x13Zj5WTYZsUfiML4vgVScLjBPbXx_WUTaEUMlzJL6tIKeSQX8MZ5gT1nKiflsT-yM_HHkEo62g5xCwWFz6_99mKveo0Bal1SK6EGeWjoG_YkGhI_5zUOOgQ4LhPa_vchTxXdYHsWft1HmwwJ2uYuRqc-3YNZNM"
     }
    var bridgitToken = messageData.token;
    if (authCheck == 1) {
      window.postMessage(messageData, '*');
    }
     //postmessage
    
    window.addEventListener('message', function(e) {
      var message = e.data;
      if(e.data.type === 'BRIDGIT-EXTENSION'){
        if (e.data.token != '' && authCheck != 1) {
          messageData.token = e.data.token;
          bridgitToken = e.data.token;
          if (authCheck != 1) {
            isReload = 0;
            checkLoginUser();
          }
        }else if(authCheck == 1 && e.data.token == ''){
          console.log('call logout');
            isReload = 0;
            userLogoutData();
        }
      };
    });
    if (authCheck != 1) {
      checkLoginUser();
    }
    //postmessage
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
              if(data){
                if (isReload==0) {
                    location.reload();
                }
              }
            }
        });
        //check login
      }
      //logout
    function userLogoutData(){
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN':csrfToken,
          'Authorization':'Bearer '+bridgitToken
        }
      });
      $.ajax({
          url: userLogout,
          type:"GET",
          dataType : 'json',
          success:function(data) {
            if (data.deleted == true && isReload==0) {
                location.reload();
            }
          }
      });
      //logout
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