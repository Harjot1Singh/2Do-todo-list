$(document).ready(function() {
    // Tie Enter to submit
    $("input").keypress(function(event) {
        if (event.which == 13) {
            event.preventDefault();
            $(".register-form form").submit();
        }
    });

    // Send the registration form as JSON via POST
    $(".register-form form").submit(function(event) {
        event.preventDefault();
        var email = $(".register-form-input input[name=email]").val();
        var name = $(".register-form-input input[name=name]").val();
        var password = $(".register-form-input input[name=password]").val();
        var data = {
            "name": name,
            "password": password,
            "email": email
        };
        $.post('api/user/register.php', JSON.stringify(data),
            function(response) {
                response = $.parseJSON(response);
                // Redirect to index.php on registration success
                if (response.success)
                    window.location.replace("index.php");
            });
    });

});