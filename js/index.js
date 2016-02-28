$(document).ready(function() {
    // Show completed items
    $(".lists-list-completed-bar a").click(function(event) {
        var self = $(this);
        event.preventDefault();
        $(".lists-list-completed ul").toggle(500)
        if (self.html() == "Show Completed Items") {
            self.html("Hide Completed Items");
        }
        else {
            self.html("Show Completed Items");
        }
    });

    $()

    // Insert new list AJAX
    $(".lists-new").click(function(event) {
        event.preventDefault();
        // Get list HTMl
        $.get("templates/list.html", function(data) {
            var list = $(data);
            $(".lists-new").parent().prepend(list);
            // TODO post to DB
        });
    });

    // Change list heading to edit on click
    $(".lists-list-title-heading").click(function(event) {
        $(this).hide();
        $(".lists-list-title input[name=name]").show();
    });

    
    $(".lists-list-title input[name=name]").bind("focusout keydown", function(event) {
        // Focus out 
        if (event.which == 13 || event.which == 0) {
            event.preventDefault();
            var title = $(this).val();
             $(".lists-list-title-heading h2").html(title);
             $(".lists-list-title-heading").show();
             $(this).hide();
             // TODO Update title
        }
    });
});