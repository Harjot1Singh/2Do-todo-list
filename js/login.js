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
        var shouldSubmit = true;
        // Grab fields
        var email = $(".login-form-input input[name=email]").val();
        var password = $(".login-form-input input[name=password]").val();
        
        // Validate presence of password
        if (password.length === 0) {
            shouldSubmit = false;
            $(".login-form-error p").html("Please enter your password");

        }
        
        // Validate presence of email
        if (email.length === 0) {
            shouldSubmit = false;
            $(".login-form-error p").html("Please enter an email address");
        }

        // Build request
        var data = {
            "email": email,
            "password": password,
            "service": "email"
        };
        // AJAX post the form data
        if (shouldSubmit)
            $.post("api/user/login.php", JSON.stringify(data), function(response) {
                response = $.parseJSON(response);
                if (response.success) {
                    // Redirect to index.php if login was OK
                    window.location.replace("index.php");
                }
                else {
                    // Make error visible
                    $(".login-form-error p").html("Your email or password was not recognised. Try again?");
                    $(".login-form-error").fadeIn().effect("shake");
                }
            });
        else
            $(".login-form-error").fadeIn().effect("shake");
    });

});