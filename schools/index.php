<?php
global $dbh;
require_once("../connection.php");
require_once("../authentication.php");

$querySchools = "SELECT
        `sc`.`id`, `sc`.`name`, `sc`.`suburb`, `sc`.`year_of_establishment`,
        COUNT(`st`.`id`) AS `number_students`
    FROM `schools` `sc`
    LEFT JOIN `students` `st` ON `st`.`school_id` = `sc`.`id`
    GROUP BY `sc`.`id`";

$stmt = $dbh->prepare($querySchools);
$stmt->execute();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <title>List Schools</title>

    <style>
        table, tr, th, td {
            border: 1px black solid;
        }
    </style>
</head>
<body>
<p><a href="../index.php">Back to Homepage</a> <a href="add.php">Add new school</a></p>
<table class="table">
    <thead>
    <tr>
        <th scope="col">ID</th>
        <th scope="col">Name</th>
        <th scope="col">Suburb</th>
        <th scope="col">Year of Establishment</th>
        <th scope="col"># Students</th>
        <th scope="col">Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($row = $stmt->fetchObject()): ?>
        <tr>
            <th scope="row"><?= $row->id ?></th>
            <td class="school-name"><?= $row->name ?></td>
            <td><?= $row->suburb ?></td>
            <td><?= $row->year_of_establishment ?></td>
            <td><?= $row->number_students ?></td>
            <td><a href="update.php?id=<?= $row->id ?>">Update</a> <a href="delete.php?id=<?= $row->id ?>" class="delete-school">Delete</a></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<script>
    // Add a confirmation box to all delete buttons
    Array.from(document.getElementsByClassName('delete-school')).forEach((element) => {
        element.addEventListener('click', (event) => {
            let buttonParent = event.target.parentNode.parentNode;
            let schoolName = buttonParent.querySelector('.school-name').textContent;
            // Render the dialog box
            if (!confirm('Are you sure to delete the school "' + schoolName + '"?'))
                event.preventDefault();  // If the user cancel the dialog box, do nothing
        })
    });
</script>
</body>
</html>
