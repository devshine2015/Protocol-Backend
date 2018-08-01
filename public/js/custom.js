//update profile
    $('#edit').click(function () {
        var dad = $(this).parent().parent();
        $('.username').css('display','none');
        $('#edit').css('display','none')
        dad.find('input[type="text"]').show().focus();
    });
    
    $('input[type=text]').focusout(function() {
        var dad = $(this).parent();
        $('.username').css('display','inline');
        $('#edit').css('display','inline')
        $(this).hide();
        dad.find('label').show();
        var name = $("#user_name").val();
        var image = '';
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN':csrfToken
              }
            });
            $.ajax({
                url: updateUserUrl,
                cache: false,
                data: {name:name},
                type: "post",
                success: function(html){
                $(".username").html($("#user_name").val());
              }
            });
    });
    function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#user-profile-pic').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
                var name = '';
                var image = input.files[0];
                updateUser(name,image)
            }
        }
        $("#featured_image").change(function () {
            readURL(this);
        });
        function updateUser(name,image){
            var form_data = new FormData();
            form_data.append('avatar', image);
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN':csrfToken
              }
            });
            $.ajax({
                url: updateUserUrl,
                cache: false,
                processData: false,
                data: form_data,
                type: "post",
                contentType: false,
              success: function(html){
                $(".username").html($("#user_name").val());
              }
            });
        }
    //update profile