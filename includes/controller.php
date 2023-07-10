<?php
    require "config.php";
    
    // Create
    if (isset($_POST['add-checktree'])) {
        $task_title = $_POST['task_title'];
        $parent_task = $_POST['parent_task'];
        $task_name = str_replace(' ', '-', $task_title);
        // Add Checktree
        $query_add = "INSERT INTO tasks (task_name, task_title, parent_id) VALUES ('$task_name', '$task_title', '$parent_task')";
        mysqli_query($connect, $query_add);
        mysqli_close($connect);
        // Redirect to Home
        header("Location: ../");
        exit();
    }

    // Update
    if (isset($_POST['is_checked'])) {
        // Get the task_id and is_checked from the AJAX request
        $task_id = $_POST['task_id'];
        $is_checked = $_POST['is_checked'];
        // Update the database with the new checkbox status
        $query_update = "UPDATE tasks SET task_status = '$is_checked' WHERE task_id = '$task_id'";
        $result_update = mysqli_query($connect, $query_update);
        if ($result_update) {
            echo "Updated successfully.";
        } else {
            echo "Error updating checkbox: " . mysqli_error($connect);
        }
        mysqli_close($connect);
    }

    if (isset($_GET['task_id'])) {
        $task_id = $_GET['task_id'];
        // Get the task_title and parent_id XMLHttpRequest
        $query_get = "SELECT task_title, parent_id FROM tasks WHERE task_id = '$task_id'";
        $result = mysqli_query($connect, $query_get);
        $row = mysqli_fetch_assoc($result);
        mysqli_close($connect);
        // Response the task_title and parent_id
        header("Content-type: application/json");
        echo json_encode($row);
    }

    if (isset($_POST['update-checktree'])) {
        $task_id = $_POST['edit_task_id'];
        $task_title = $_POST['edit_task_title'];
        $task_name = str_replace(' ', '-', $task_title);
        $parent_task = $_POST['edit_parent_task'];
        // Update Checktree
        $query_update = "UPDATE tasks SET task_name = '$task_name', task_title = '$task_title', parent_id = '$parent_task' WHERE task_id = '$task_id'";
        mysqli_query($connect, $query_update);
        mysqli_close($connect);
        // Redirect to Home
        header("Location: ../");
        exit();
    }

    // Delete
    if (isset($_POST['delete-checktree'])) {
        $task_id = $_POST['task_id'];
        $query_parent = "SELECT * FROM tasks WHERE parent_id = '$task_id'";
        $result_parent = mysqli_query($connect, $query_parent);
        // Delete Checktree
        if (mysqli_num_rows($result_parent) > 0 ) {
            $query_delete = "DELETE FROM tasks WHERE task_id = '$task_id' OR parent_id = '$task_id'";
            mysqli_query($connect, $query_delete);
        } else {
            $query_delete = "DELETE FROM tasks WHERE task_id = '$task_id'";
            mysqli_query($connect, $query_delete);
        }
        mysqli_close($connect);
        // Redirect to Home
        header("Location: ../");
        exit();
    }

    if (isset($_POST['delete-all-checktree'])) {
        $query_delete = "DELETE FROM tasks";
        mysqli_query($connect, $query_delete);
        mysqli_close($connect);
        // Redirect to Home
        header("Location: ../");
        exit();
    }