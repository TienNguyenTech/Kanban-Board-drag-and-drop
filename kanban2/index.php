<?php
require_once "ProjectManagement.php";

$projectName = "StartTuts";
$projectManagement = new ProjectManagement();
$statusResult = $projectManagement->getAllStatus();
?>
<html>

<head>
    <title>Kanban Board</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- Page style -->
    <style>
        body {
            background: black;
            font-family: arial;
            color: white;
        }

        h1 {
            font-weight: normal;
        }

        .task-board {
            background: #282b30;
            display: block;
            padding: 12px;
            border-radius: 3px;
            width: 100vw;
            min-height: 100vh;
            white-space: nowrap;
            overflow-x: scroll;
        }


        .status-card {
            width: 250px;
            margin-right: 8px;
            background: #36393e;
            border-radius: 3px;
            display: inline-block;
            vertical-align: top;
            font-size: 0.9em;
        }

        .status-card:last-child {
            margin-right: 0px;
        }

        .card-header {
            width: 100%;
            padding: 10px 10px 0px 10px;
            box-sizing: border-box;
            border-radius: 3px;
            display: block;
            font-weight: bold;
        }

        .card-header-text {
            display: block;
        }

        ul.sortable {
            padding-bottom: 10px;
        }

        ul.sortable li:last-child {
            margin-bottom: 0px;
        }

        ul {
            list-style: none;
            margin: 0;
            padding: 0px;
        }

        .text-row {
            padding: 8px 10px;
            margin: 10px;
            background: #424549;
            box-sizing: border-box;
            border-radius: 3px;
            border-bottom: 1px solid #ccc;
            cursor: pointer;
            font-size: 0.8em;
            white-space: normal;
            line-height: 20px;
        }

        .ui-sortable-placeholder {
            visibility: inherit !important;
            background: transparent;
            border: #666 2px dashed;
        }
    </style>

    <!-- Add task button style -->
    <style>
        .add-task-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            margin: 10px;
            cursor: pointer;
            border-radius: 3px;
            font-size: 0.9em;
        }

        .add-task-form {
            padding: 10px;
            background-color: #e2e4e6;
            border-radius: 3px;
            margin: 10px;
        }

        .add-task-form input {
            padding: 8px;
            width: 80%;
            margin-bottom: 10px;
        }

        .save-task-btn {
            background-color: #2c7cbc;
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 3px;
        }
    </style>

</head>

<body>
    <h1>Kanban Board</h1>
    <div class="task-board">
        <?php
        foreach ($statusResult as $statusRow) {
            $taskResult = $projectManagement->getProjectTaskByStatus($statusRow["id"], $projectName);
            ?>
            <div class="status-card">
                <div class="card-header">
                    <span class="card-header-text"><?php echo $statusRow["status_name"]; ?></span>
                </div>
                <ul class="sortable ui-sortable" id="sort<?php echo $statusRow["id"]; ?>"
                    data-status-id="<?php echo $statusRow["id"]; ?>">
                    <?php
                    if (!empty($taskResult)) {
                        foreach ($taskResult as $taskRow) {
                            ?>

                            <li class="text-row ui-sortable-handle" data-task-id="<?php echo $taskRow["id"]; ?>">
                                <?php echo $taskRow["title"]; ?>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
            <?php
        }
        ?>
    </div>
    <a href="../kanban/add-task.php">Add Task</a>

  

    <!-- Connect with the edit-status.php to drag and drop -->
    <script>
        $(function () {
            var url = 'edit-status.php'
            $('ul[id^="sort"]').sortable(
                {
                    connectWith: ".sortable",
                    receive: function (e, ui) {
                        var status_id = $(ui.item).parent(".sortable").data(
                            "status-id");
                        var task_id = $(ui.item).data("task-id");
                        $.ajax({
                            url: url + '?status_id=' + status_id + '&task_id='
                                + task_id,
                            success: function (response) {
                            }
                        });
                    }

                }).disableSelection();
        });
    </script>



</body>

</html>