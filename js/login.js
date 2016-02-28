$(document).ready(function() {
    // Tie Enter to submit
    $("input").keypress(function(event) {
        if (event.which == 13) {
            event.preventDefault();
            $(".login-form form").submit();
        }
    });

    // Submits form in JSON and handles the rest
    $(".login-form form").submit(function(event) {
        event.preventDefault();
        // Build request
        var email = $(".login-form-input input[name=email]").val();
        var password = $(".login-form-input input[name=password]").val();
        var data = {
            "email": email,
            "password": password,
            "service": "email"
        };
        // AJAX post the form data
        $.post("api/user/login.php", JSON.stringify(data), function(response) {
            response = $.parseJSON(response);
            if (response.success) {
                // Redirect to index.php if login was OK
                window.location.replace("index.php");
            }
            else {
                // Make error visible
                $(".login-form-error").fadeIn().effect("shake");
            }
        });
    });

});