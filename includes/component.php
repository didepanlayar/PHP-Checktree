<?php
    require "config.php";

    function ParentOptions($parent_id, $connect, $indent = 0) {
        $indentation = str_repeat('&nbsp;', $indent * 4);
        $query = "SELECT * FROM tasks WHERE parent_id = '$parent_id'";
        $result = mysqli_query($connect, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $task_id = $row['task_id'];
            $task_title = $row['task_title'];
            echo "<option value='$task_id'>$indentation $task_title</option>";
            ParentOptions($task_id, $connect, $indent + 1);
        }
    }

    function AllTask($connect) {
        $query = "SELECT * FROM tasks";
        $result = mysqli_query($connect, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $task_id = $row['task_id'];
            $task_title = $row['task_title'];
            echo "<option value='$task_id'>$task_title</option>";
        }
    }