//update profile
// alert("sdasd");
    $('#edit').click(function () {
        var dad = $(this).parent().parent();
        $('.username').css('display','none');
        $('#edit').css('display','none')
        dad.find('input[type="text"]').show().focus();
    });
    
    $('.edit-input').focusout(function() {
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
    $("[id*='modal-right']").on("hidden.bs.modal", function(e){
        $(this).removeData("bs.modal").find(".modal-content").empty().html("");
    }).on("show.bs.modal",function(e){
        var url = $(e.relatedTarget).prop("href");
        console.log(url);
            if (url) {
              $(e.target).find(".modal-content").load(url);
          }
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
        $(document).delegate("a.confirm-delete", "click", function (e) {

            e.preventDefault();
        var title = $(this).attr("name");
        text = "Are you sure want to delete this "+title+" ?";
        var url = $(this).attr("href"),
        successCallback = $(this).data("success-callback"),
            errorCallback = $(this).data("error-callback"),
            dltAction = swal({
              title: "",
              text: text,
              showCancelButton: true,
              confirmButtonText: "Confirm",
              cancelButtonText: "Cancel",
              showLoaderOnConfirm: true,
              confirmButtonColor:"#ee467a",
              preConfirm() {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": csrfToken
                    }
                });
                $.ajax({
                    url: url,
                    type: "DELETE",
                    dataType: "json",
                    data: {method: "_DELETE", submit: true},
                    success(response) {
                        window[successCallback](response);
                    },
                    error(jqXHR, textStatus, error) {
                        window[errorCallback](jqXHR, textStatus, error);
                    }
                });
              },
              allowOutsideClick: false
            });
    });