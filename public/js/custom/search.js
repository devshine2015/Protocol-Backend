$(document).ready(function () {
    setTimeout(function(){ uniformLogin() }, 3000);
      var isReload = 2;
      var messageData = {
         "type": "BRIDGIT-WEB",
         "token":token
       }
      if(isLoggedOut != 1 && token != ''){
          isReload = 0;
          userLogoutData();
          return;
      }
    function uniformLogin(){
      if(!token){
        bridgitToken = localStorage.getItem('bridgit-token');
      }
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
              isReload = 0;
              userLogoutData();
          }
        };
      });
      //return;
      if (authCheck != 1 && bridgitToken != '') {
          checkLoginUser();
          isReload = 0;
      }
      //postmessage
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
        var token = localStorage.getItem('bridgit-token');
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN':csrfToken,
            'Authorization':'Bearer '+token
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
      var token = localStorage.getItem('bridgit-token');
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN':csrfToken,
          'Authorization':'Bearer '+token
        }
      });
      $.ajax({
          url: userLogout,
          type:"GET",
          dataType : 'json',
          success:function(data) {
            if (data.deleted == true && isReload==0) {
              location.reload();
                //window.location.href = window.location.href;
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
                    $('button[data-id = '+id+']').removeClass('following');
                  }else{
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