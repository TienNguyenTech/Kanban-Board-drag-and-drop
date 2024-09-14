<?php
global $dbh;
require_once("../connection.php");
require_once("../authentication.php");

// Test if course id has been provided. If not, take user back to the listing page
if (empty($_GET['id'])) {
    header('Location: index.php');
}

// If the user has completed the form:
if ($_SERVER['REQUEST_METHOD'] == 'POST'):
    try {
        // Update the record based on the form received
        $query = "UPDATE `courses` SET 
                  `name`        = :name, 
                  `description` = :description,
                  `fees`        = :fees
            WHERE `id`          = :id";
        $stmt = $dbh->prepare($query);

        // Execute the query
        $stmt->execute([
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'fees' => $_POST['fees'],
            'id' => $_GET['id']
        ]);

        // And send the user back to where we were
        header('Location: index.php');
    } catch (PDOException $e) {
        displayPDOError($e);
    }
else:
    // Otherwise read the record from database with ID and prefill the form
    $stmt = $dbh->prepare("SELECT * FROM `courses` WHERE `id` = :id");
    $stmt->execute(['id' => $_GET['id']]);

    if ($stmt->rowCount() == 1 && $row = $stmt->fetchObject()): ?>
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>Update Course (<?= $row->name ?>)</title>
        </head>
        <body>
        <h1>Update course #<?= $row->id ?> (<?= $row->name ?>)</h1>

        <form method="post">
            <label for="name">Name:</label><br>
            <input type="text" maxlength="128" id="name" name="name" value="<?= $row->name ?>" required><br>

            <label for=" description">Description:</label><br>
            <textarea maxlength="65535" id="description" name="description" cols="60" rows="10" required><?= $row->description ?></textarea><br>

            <label for=" fees">Fees:</label><br>
            <input type="number" max="99999.99" min="0" step="0.01" id="fees" name="fees" value="<?= $row->fees ?>" required><br>

            <br>

            <input type="submit" value="Update">
        </form>
        </body>
        </html>
    <?php else:
        // If the record is not found (rowcount is not 1), send user back to listing page (invalid ID)
        header('Location: index.php');
    endif;
endif; ?>