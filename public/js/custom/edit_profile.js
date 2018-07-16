$(document).ready(function () {
    var isFollow =$('.follow').attr('data-follow');
    if(isFollow == 1){
        $('.follow').addClass('following')
    }else{
        $('.follow').removeClass('following')
    }
    //endfollow
    //see more
        size_p = $("#bridgeList p").length;
        if(size_p >=5){
            $("#loadData").removeClass('hidden');
        }
         $("#bridgeList p").slice(0, 5).show();
            $("#loadMore").on('click', function (e) {
                e.preventDefault();
                $("#bridgeList p:hidden").slice(0, 5).slideDown();
                if ($("#bridgeList p:hidden").length == 0) {
                    $("#load").fadeOut('slow');
                    $("#loadData").addClass('hidden');
                }
                $('html,body').animate({
                    scrollTop: $(this).offset().top
                }, 1500);
            });
    // see more
    $('.collapse.in').prev('.panel-heading').addClass('active');
    $('#accordion, #bs-collapse')
        .on('show.bs.collapse', function (a) {
            $(a.target).prev('.panel-heading').addClass('active');
        })
        .on('hide.bs.collapse', function (a) {
            $(a.target).prev('.panel-heading').removeClass('active');
        });
        $('.follow').click(function(){
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
        $('.edit-profile').click(function(){
            $('.uploadIcon').removeClass('hidden');
        })
        function readURL(input) {
            console.log('sdasdas');
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#user-profile-pic').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#featured_image").change(function () {
            readURL(this);
        });
});