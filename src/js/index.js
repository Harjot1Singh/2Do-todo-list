$(document).ready(function() {
    // Grab templates before doing anything
    var listTemplate;
    var itemTemplate;

    // Get list template
    $.ajax({
        url: "templates/list.html",
        async: false,
        success: function(data) {
            listTemplate = $(data).hide();
        }
    });

    // Get item template
    $.ajax({
        url: "templates/item.html",
        async: false,
        success: function(data) {
            itemTemplate = $(data);
            itemTemplate.find(".lists-list-items-item-actions-due-datepicker").datepicker();
            itemTemplate.find(".lists-list-items-item").hide();
        }
    });

    // Onload, grab all the lists and insert them
    $.get("api/lists/getLists.php", function(data) {
        data = $.parseJSON(data);
        console.log(data);
        if (data.success) {
            // Add each list and items to the page
            data.lists.forEach(function(list) {
                // Get jQuery list selector when it's been inserted
                var listSelector = insertListIntoDOM(list.id, list.name);
                // Adds each due item to the list
                list.due.forEach(function(item) {
                    var prepend = false
                    if (item.urgent)
                        prepend = true;
                    insertDueItem(listSelector, escapeHtml(item.name), item, false, prepend);
                });
                // Add each completed item
                list.completed.forEach(function(item) {
                    insertCompletedItem(listSelector, escapeHtml(item.name), item);
                });
            });
        }
    });

    /* List functions */

    // Inserts HTML for list and appends data attribute
    function insertListIntoDOM(id, name, edit) {
        // Set name to new list if empty
        name = name ? name : 'New List';
        var list = itemTemplate.clone(true);
        list.find(".lists-list-title-heading h2").html(name);
        list.data("id", id);
        list.data("name", name);
        $(".lists-new").parent().before(list);
        list.fadeIn(1300).draggable();
        return list;
    }

    // Inserts HTML for due item
    function insertDueItemIntoDOM(listSelector, item, prepend) {
        item.find(".lists-list-items-item-actions-priority").show();
        if (prepend)
            listSelector.find(".lists-list-items ul").prepend(item);
        else
            listSelector.find(".lists-list-items ul").append(item);
    }

    // Inserts due item and appends data attribute
    function insertDueItem(listSelector, name, data, edit, prepend) {
        // Set name to new item if empty
        name = name ? name : 'New Item';
        var item = itemTemplate.clone(true);
        item.find(".lists-list-items-item h3").html(name);
        item.data("id", data.id);
        item.data("name", data.name);
        item.data("completed", data.completed);
        item.data("due", data.due);
        item.data("urgent", data.urgent);
        if (data.urgent)
            item.find(".lists-list-items-item-actions-priority").toggleClass("toggled");
        insertDueItemIntoDOM(listSelector, item, prepend);
        // Edit box for new items
        if (edit)
            item.find(".lists-list-items-item-name").trigger("click");
        item.find(".lists-list-items-item").fadeIn();
        return item;
    }

    // Inserts HTML for completed item and appends data attribute
    function insertCompletedItemIntoDOM(listSelector, item) {
        listSelector.find(".lists-list-completed ul").append(item);
        item.find(".lists-list-items-item-actions-priority").hide();
        item.find(".lists-list-items-item").fadeIn();
        item.show();
        return item;
    }

    // Inserts elementcompleted item and appends data attribute
    function insertCompletedItem(listSelector, name, data) {
        // Set name to new item if empty
        name = name ? name : 'New Item';
        var item = itemTemplate.clone(true);
        item.find(".lists-list-items-item h3").html(name);
        item.data("id", data.id);
        item.data("name", data.name);
        item.data("completed", data.completed);
        item.data("due", data.due);
        item.data("urgent", data.urgent);
        insertCompletedItemIntoDOM(listSelector, item);
        return item;
    }

    /* Lists */

    // Show completed items
    listTemplate.find(".lists-list-completed-bar a").on("click", function(event) {
        var self = $(this);
        event.preventDefault();
        self.parents(".lists-list-completed").find("ul").toggle(500);
        if (self.html() == "Show Completed Items") {
            self.html("Hide Completed Items");
        }
        else {
            self.html("Show Completed Items");
        }
    });


    // Change list heading to edit on click
    listTemplate.find(".lists-list-title-heading").on("click", function(event) {
        var self = $(this);
        self.hide();
        self.parent().find("input[name=listName]").val(self.find("h2").html()).show().focus();
    });


    listTemplate.find(".lists-list-title input[name=listName]").on("focusout keypress", function(event) {
        var self = $(this);
        // Focus out 
        if (event.which == 13 || event.which == 0) {
            event.preventDefault();
            var title = self.val();
            // Change title if it's non empty
            if (title) {
                self.parent().find(".lists-list-title-heading h2").html(escapeHtml(title));
                var list = self.parents("li").data("name", title);
                var data = {
                    "name": title,
                    "id": list.data("id")
                };
                $.post("api/lists/updateList.php", JSON.stringify(data), function(response) {
                    console.log("Update list:", response);
                });
            }
            self.parent().find(".lists-list-title-heading").show();
            self.hide();
        }
    });

    // Insert new list AJAX
    $(".lists-new").click(function(event) {
        event.preventDefault();
        // Get list HTMl
        $.post("api/lists/addList.php", "{}", function(response) {
            response = $.parseJSON(response);
            console.log(response);
            insertListIntoDOM(response.listID, "New List", true);
        });
    });

    // Add new list
    listTemplate.find(".lists-list-actions-add").on("click", function(event) {
        var self = $(this);
        event.preventDefault();
        var listSelector = self.parents(".lists-list");
        var listID = self.parents("li").data("id");
        var data = {
            listID: listID
        };
        $.post("api/items/addItem.php", JSON.stringify(data), function(response) {
            console.log(response);
            response = $.parseJSON(response);
            insertDueItem(listSelector, "newItem", {
                id: response.itemID,
                completed: false,
                due: new Date(),
                urgent: false
            }, true, true);
        });
    });

    // Delete list
    listTemplate.find(".lists-list-actions-delete").on("click", function(event) {
        var self = $(this);
        event.preventDefault();
        var listSelector = self.parents(".lists-list").closest("li");
        // Todo remove from DB
        if (confirm("Are you sure you want to delete this list?")) {
            var data = {
                "listID": listSelector.data("id")
            };
            // Delete it via post
            $.post("api/lists/deleteList.php", JSON.stringify(data), function(response) {
                console.log(response);
                response = $.parseJSON(response);
                if (response.success) {
                    listSelector.fadeOut(function() {
                        listSelector.remove();
                    });
                }
            });
        }
    });

    /* Items */
    // Change item name to edit on click
    itemTemplate.find(".lists-list-items-item-name").on("click", function(event) {
        var self = $(this);
        // Check if it's in due items section first
        if (self.closest(".lists-list-items").length) {
            self.hide();
            self.parent().find("input[name=itemName]").val(self.find("h3").html()).show().focus();
        }
    });

    // Editing of item has finished
    itemTemplate.find(".lists-list-items-item input[name=itemName]").on("focusout keypress", function(event) {
        var self = $(this);
        // Focus out 
        if (event.which == 13 || event.which == 0) {
            event.preventDefault();
            var name = self.val();
            // Change title if it's non empty
            if (name) {
                self.parent().find(".lists-list-items-item-name h3").html(escapeHtml(name));
                var item = self.closest("li");
                item.data("name", name);
                updateItem(item);
            }
            self.parent().find(".lists-list-items-item-name").show();
            self.hide();
        }
    });

    // Posts an update of the item
    function updateItem(item) {
        var date = item.data("due");
        if (!date)
            date = "0";
        var data = {
            "itemID": item.data("id"),
            "name": item.data("name"),
            "due": date,
            "urgent": item.data("urgent"),
            "completed": item.data("completed")
        };
        console.log(data);
        $.post("api/items/updateItem.php", JSON.stringify(data), function(response) {
            console.log(response);
            response = $.parseJSON(response);
        });
    }

    // When the delete is pressed
    itemTemplate.find(".lists-list-items-item-actions-delete").on("click", function(event) {
        var self = $(this);
        event.preventDefault();
        if (confirm("Are you sure you want to delete this item?")) {
            // Grab item
            var item = self.closest("li");
            var data = {
                "itemID": item.data("id"),
            };
            item.find(".lists-list-items-item").fadeOut(function() {
                item.remove();
                $.post("api/items/deleteItem.php", JSON.stringify(data), function(response) {
                    console.log(response);
                    response = $.parseJSON(response);
                });
            });
        }
    });

    // When the star is pressed
    itemTemplate.find(".lists-list-items-item-actions-priority").on("click", function(event) {
        var self = $(this);
        event.preventDefault();
        // Grab item
        var item = self.closest("li");
        var list = self.closest("ul");
        // Append to top of list if toggled urgent
        // TODO Update DB
        item.find(".lists-list-items-item").fadeOut(function() {
            item.detach();
            self.toggleClass("toggled");
            // TODO replace with insertItemIntoDom?
            if (!item.data("urgent")) {
                item.data("urgent", true);
                list.prepend(item).find(".lists-list-items-item").fadeIn();
            }
            else {
                item.data("urgent", false);
                list.append(item).find(".lists-list-items-item").fadeIn();
            }
            updateItem(item);
        });
    });

    // On tick click
    itemTemplate.find(".lists-list-items-item-actions-done").on("click", function(event) {
        var self = $(this);
        event.preventDefault();
        // Grab item
        var item = self.closest("li");
        var dueList = self.closest(".lists-list").find(".lists-list-items ul");
        var completedList = self.closest(".lists-list").find(".lists-list-completed ul");
        // append to completed items or vice-versa
        // TODO Update DB
        item.find(".lists-list-items-item").fadeOut(function() {
            item.detach();
            if (!item.data("completed")) {
                self.addClass("toggled");
                item.data("completed", true);
                item.find(".lists-list-items-item-actions-priority").hide();
                completedList.append(item).find(".lists-list-items-item").fadeIn();
            }
            else {
                self.removeClass("toggled");
                item.data("completed", false);
                dueList.append(item).find(".lists-list-items-item").fadeIn();
                item.find(".lists-list-items-item-actions-priority").show();
            }
            updateItem(item);
        });
    });

    // When calendar is pressed
    itemTemplate.find(".lists-list-items-item-actions-due").on("click", function(event) {
        var self = $(this);
        var item = self.closest("li");
        var due = item.data("due");
        console.log(due);
        item.find(".lists-list-items-item-actions-due-datepicker").datepicker("option", "onSelect",
            function(dateText, datepicker) {
                item.data("due", new Date(dateText));
                updateItem(item);
            });
        $(".lists-list-items-item-actions-due-datepicker").datepicker("setDate", new Date(due));
        $(".lists-list-items-item-actions-due-datepicker").datepicker("show");
        event.preventDefault();
        // Place datepicker in correct position
        $(".ui-datepicker").css({
            "top": self.offset().top + 40 + "px",
            "left": self.offset().left
        });
    });



    // http://stackoverflow.com/questions/17105595/uncaught-typeerror-cannot-read-property-left-of-undefined
    $.datepicker._findPos = function(obj) {
        var position;
        while (obj && (obj.type === "hidden" || obj.nodeType !== 1 || $.expr.filters.visible(obj))) {
            obj = obj["nextSibling"];
        }
        position = $(obj).offset();
        // if element type isn't hidden, use show and hide to find offset
        if (!position) {
            position = $(obj).show().offset();
            $(obj).hide();
        }
        // or set position manually
        if (!position) position = {
            left: 999,
            top: 999
        };
        return [position.left, position.top];
    };
    
    function escapeHtml(text) {
  var map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };

  return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}
    
});
