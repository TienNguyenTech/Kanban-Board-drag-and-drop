<?php
global $dbh;
require_once("../connection.php");
require_once("../authentication.php");

$queryCourses = "SELECT
        `co`.`id`, `co`.`name`, `co`.`description`, `co`.`fees`,
        COUNT(`cs`.`student_id`) AS `number_students`
    FROM `courses` `co`
    LEFT JOIN `courses_students` `cs` ON `cs`.`course_id` = `co`.`id`
    GROUP BY `co`.`id`";

$stmt = $dbh->prepare($queryCourses);
$stmt->execute();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <title>List Courses</title>

    <style>
        table, tr, th, td {
            border: 1px black solid;
        }

        .course-description {
            max-width: 500px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: pointer;  /* So that when hover on the item, the cursor change to a hand indicates it is clickable */
        }
    </style>
</head>
<body>
<p><a href="../index.php">Back to Homepage</a> <a href="add.php">Add new course</a></p>
<table class="table">
    <thead>
    <tr>
        <th scope="col">ID</th>
        <th scope="col">Name</th>
        <th scope="col">Description</th>
        <th scope="col">Fees</th>
        <th scope="col"># Students Enrolled</th>
        <th scope="col">Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($row = $stmt->fetchObject()): ?>
        <tr>
            <th scope="row"><?= $row->id ?></th>
            <td class="course-name"><?= $row->name ?></td>
            <td class="course-description"><?= htmlentities($row->description) ?></td>
            <td>$<?= $row->fees ?></td>
            <td><?= $row->number_students ?></td>
            <td><a href="update.php?id=<?= $row->id ?>">Update</a> <a href="delete.php?id=<?= $row->id ?>" class="delete-course">Delete</a></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<script>
    // Add a confirmation box to all delete buttons
    Array.from(document.getElementsByClassName('delete-course')).forEach((element) => {
        element.addEventListener('click', (event) => {
            let buttonParent = event.target.parentNode.parentNode;
            let courseName = buttonParent.querySelector('.course-name').textContent;
            // Render the dialog box
            if (!confirm('Are you sure to delete the course "' + courseName + '"?'))
                event.preventDefault();  // If the user cancel the dialog box, do nothing
        })
    });

    // Show full description when clicking the description
    Array.from(document.getElementsByClassName('course-description')).forEach((element) => {
        element.addEventListener('click', (event) => {
            let cellContent = event.target.textContent;
            // Render the dialog box
            alert(cellContent);
        })
    });
</script>
</body>
</html>
