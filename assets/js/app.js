$(document).ready(function() {
    $("ul.checktree").checktree();

    // Function to handle checkbox change event
    $('.checktree :checkbox').change(function() {
        var task_id = $(this).data('task-id');
        var is_checked = $(this).is(':checked') ? 1 : 0;

        // AJAX request to update the database
        $.ajax({
            url: 'includes/controller.php',
            method: 'POST',
            data: {
                task_id: task_id,
                is_checked: is_checked
            },
            success: function(response) {
                console.log(response);
            }
        });
    });

    // Call check parent status
    checkParentStatus();

    // Check cild status change
    $('.checktree :checkbox').change(function() {
        checkParentStatus();
    });

    // Check parent status
    function checkParentStatus() {
        $('.checktree ul').each(function() {
            var parentCheckbox = $(this).closest('li').children(':checkbox');
            var childCheckboxes = $(this).find(':checkbox');
            var allChecked = true;

            childCheckboxes.each(function() {
                if (!$(this).is(':checked')) {
                    allChecked = false;
                    return false;
                }
            });

            parentCheckbox.prop('checked', allChecked);
        });
    }

    // CRUD Click Button
    var addTreeDiv = document.querySelector('.add-tree');
    var editTreeDiv = document.querySelector('.edit-tree');
    var removeTreeDiv = document.querySelector('.remove-tree');

    function toggleDivVisibility(elementToShow) {
        addTreeDiv.style.display = (elementToShow === 'add') ? 'block' : 'none';
        editTreeDiv.style.display = (elementToShow === 'edit') ? 'block' : 'none';
        removeTreeDiv.style.display = (elementToShow === 'remove') ? 'block' : 'none';
    }

    document.getElementById('create-tree').addEventListener('click', function(event) {
        event.preventDefault();
        toggleDivVisibility('add');
    });

    document.getElementById('update-tree').addEventListener('click', function(event) {
        event.preventDefault();
        toggleDivVisibility('edit');
    });

    document.getElementById('delete-tree').addEventListener('click', function(event) {
        event.preventDefault();
        toggleDivVisibility('remove');
    });

    document.getElementById('close-tree').addEventListener('click', function(event) {
        event.preventDefault();
        addTreeDiv.style.display = 'none';
        editTreeDiv.style.display = 'none';
        removeTreeDiv.style.display = 'none'
    });
});

// Update
function getTaskDetails() {
    var task_id = document.getElementById("edit_task_id").value;

    if (task_id !== "0") {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                document.getElementById("edit_task_title").value = response.task_title;

                var parentTaskSelect = document.getElementById("edit_parent_task");
                var parentTaskOptions = parentTaskSelect.options;

                for (var i = 0; i < parentTaskOptions.length; i++) {
                    if (parentTaskOptions[i].value === response.parent_id) {
                        parentTaskOptions[i].selected = true;
                        break;
                    }
                }
            }
        };
        xhr.open("GET", "includes/controller.php?task_id=" + task_id, true);
        xhr.send();
    } else {
        document.getElementById("edit_task_title").value = "";
        document.getElementById("edit_parent_task").value = "0";
    }
}