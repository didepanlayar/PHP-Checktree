<?php
    require "includes/component.php";
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Di Depan Layar - Checkbox Tree</title>
    <meta charset="utf-8" />
    <meta name="robots" content="noindex" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="container">
        <div>
            <h2>Checktree: Checkbox Tree</h2>
            <ul class="checktree">
                <?php
                    function buildTree($parent_id, $connect) {
                        $query = "SELECT * FROM tasks WHERE parent_id = '$parent_id'";
                        $result = mysqli_query($connect, $query);
                        if (mysqli_num_rows($result) > 0) {
                            echo "<ul>";
                            while ($row = mysqli_fetch_assoc($result)) {
                                $task_id = $row['task_id'];
                                $task_name = $row['task_name'];
                                $task_title = $row['task_title'];
                                $task_status = ($row['task_status'] == 1) ? 'checked' : '';
                                echo "<li>";
                                echo '<input id="' . $task_name . '" type="checkbox" data-task-id="' . $task_id . '" ' . $task_status . ' /><label for="' . $task_name . '">' . $task_title . '</label>';
                                // Recursive call to build child tree
                                buildTree($task_id, $connect);
                                echo "</li>";
                            }
                            echo "</ul>";
                        }
                    }
                    $get_parents = "SELECT * FROM tasks WHERE parent_id = 0";
                    $result_parents = mysqli_query($connect, $get_parents);
                    if (mysqli_num_rows($result_parents) > 0) {
                        while ($row_parent = mysqli_fetch_assoc($result_parents)) {
                            $parent_id = $row_parent['task_id'];
                            $parent_name = $row_parent['task_name'];
                            $parent_title = $row_parent['task_title'];
                            $parent_status = ($row_parent['task_status'] == 1) ? 'checked' : '';
                            echo "<li>";
                            echo '<input id="' . $parent_name . '" type="checkbox" data-task-id="' . $parent_id . '" ' . $parent_status . ' /><label for="' . $parent_name . '">' . $parent_title . '</label>';
                            // Call the function to build child tree
                            buildTree($parent_id, $connect);
                            echo "</li>";
                        }
                    } else {
                        echo 'No data available.';
                    }
                ?>
            </ul>
        </div>
        <div>
            <table class="table-crud">
                <tr>
                    <td><a href="#create" id="create-tree">Add</a></td>
                    <td><a href="#update" id="update-tree">Edit</a></td>
                    <td><a href="#delete" id="delete-tree">Delete</a></td>
                    <td><a href="#close" id="close-tree">Close</a></td>
                </tr>
            </table>
        </div>
        <div class="add-tree">
            <h2>Add Checkbox Tree</h2>
            <form method="POST" action="includes/controller.php">
                <div>
                    <table>
                        <tr>
                            <td><label for="task_title">Title</label></td>
                            <td><input type="text" id="task_title" name="task_title" required></td>
                        </tr>
                        <tr>
                            <td><label for="parent_task">Select Parent</label></td>
                            <td>
                                <select id="parent_task" name="parent_task">
                                    <option value="0">None</option>
                                    <?php            
                                        ParentOptions(0, $connect);
                                    ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="submit-button">
                    <button type="submit" name="add-checktree">Save</button>
                </div>
            </form>
        </div>
        <div class="edit-tree">
            <h2>Edit Checkbox Tree</h2>
            <form method="POST" action="includes/controller.php">
                <div>
                    <table>
                        <tr>
                            <td><label for="edit_task_id">Select Title</label></td>
                            <td>
                                <select id="edit_task_id" name="edit_task_id" onchange="getTaskDetails()">
                                    <option value="0">Select Title</option>
                                    <?php
                                        AllTask($connect);
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="edit_task_title">Title</label></td>
                            <td><input type="text" id="edit_task_title" name="edit_task_title" required></td>
                        </tr>
                        <tr>
                            <td><label for="edit_parent_task">Select Parent</label></td>
                            <td>
                                <select id="edit_parent_task" name="edit_parent_task">
                                    <option value="0">None</option>
                                    <?php            
                                        ParentOptions(0, $connect);
                                    ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="submit-button">
                    <button type="submit" name="update-checktree">Update</button>
                </div>
            </form>
        </div>
        <div class="remove-tree">
            <h2>Delete Checkbox Tree</h2>
            <form method="POST" action="includes/controller.php">
                <div>
                    <table>
                        <tr>
                            <td><label for="task_id">Select Title</label></td>
                            <td>
                                <select id="task_id" name="task_id">
                                    <option>Select Title</option>
                                    <?php
                                        AllTask($connect);
                                    ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="submit-button">
                    <button type="submit" name="delete-checktree">Delete</button>
                    <button type="submit" name="delete-all-checktree">Delete All</button>
                </div>
            </form>
        </div>
    </div>
    <?php mysqli_close($connect); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="assets/js/checktree.js"></script>
    <script src="assets/js/app.js"></script>
</body>

</html>