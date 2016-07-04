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
        var shouldSubmit = true;
        // Grab fields
        var email = $(".register-form-input input[name=email]").val();
        var name = $(".register-form-input input[name=name]").val();
        var password = $(".register-form-input input[name=password]").val();
        var confirmEmail = $(".register-form-input input[name=confirmEmail]").val();
        var confirmPassword = $(".register-form-input input[name=confirmPassword]").val();

        // Validate passwords match
        if (confirmPassword !== password) {
            shouldSubmit = false;
            $(".register-form-error p").html("Your passwords do not match");
        }

        // Validate presence of password
        if (password.length === 0) {
            shouldSubmit = false;
            $(".register-form-error p").html("Please enter a password");
        }
        
        // Validate emails match
        if (email !== confirmEmail) {
            shouldSubmit = false;
            $(".register-form-error p").html("Your email addresses do not match");
        }

        // Validate presence of email
        if (email.length === 0) {
            shouldSubmit = false;
            $(".register-form-error p").html("Please enter an email address");
        }

        // Validate presence of name
        if (name.length === 0) {
            shouldSubmit = false;
            $(".register-form-error p").html("Please enter your name");
        }

        var data = {
            "name": name,
            "password": password,
            "email": email
        };
        if (shouldSubmit)
            $.post('api/user/register.php', JSON.stringify(data),
                function(response) {
                    response = $.parseJSON(response);
                    // Redirect to index.php on registration success
                    if (response.success)
                        window.location.replace("index.php");
                    else
                        console.log("Error/account exists?");
                });
        else
            $(".register-form-error").fadeIn().effect("shake");

    });

});