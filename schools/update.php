<?php
global $dbh;
require_once("../connection.php");
require_once("../authentication.php");

// Test if school id has been provided. If not, take user back to the listing page
if (empty($_GET['id'])) {
    header('Location: index.php');
}

// If the user has completed the form:
if ($_SERVER['REQUEST_METHOD'] == 'POST'):
    try {
        // Update the record based on the form received
        $query = "UPDATE `schools` SET 
                  `name`                   = :name, 
                  `suburb`                 = :suburb,
                  `year_of_establishment`  = :year_of_establishment
            WHERE `id`                     = :id";
        $stmt = $dbh->prepare($query);

        // Execute the query
        $stmt->execute([
            'name' => $_POST['name'],
            'suburb' => $_POST['suburb'],
            'year_of_establishment' => $_POST['year_of_establishment'],
            'id' => $_GET['id']
        ]);

        // And send the user back to where we were
        header('Location: index.php');
    } catch (PDOException $e) {
        displayPDOError($e);
    }
else:
    // Otherwise read the record from database with ID and prefill the form
    $stmt = $dbh->prepare("SELECT * FROM `schools` WHERE `id` = :id");
    $stmt->execute(['id' => $_GET['id']]);

    if ($stmt->rowCount() == 1 && $row = $stmt->fetchObject()): ?>
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>Update School (<?= $row->name ?>)</title>
        </head>
        <body>
        <h1>Update school #<?= $row->id ?> (<?= $row->name ?>)</h1>

        <form method="post">
            <label for="name">Name:</label><br>
            <input type="text" maxlength="128" id="name" name="name" value="<?= $row->name ?>" required><br>

            <label for="suburb">Suburb:</label><br>
            <input type="text" maxlength="64" id="suburb" name="suburb" value="<?= $row->suburb ?>" required><br>

            <label for="year-of-establishment">Year of Establishment:</label><br>
            <input type="number" max="9999" min="1000" step="1" id="year-of-establishment" name="year_of_establishment" value="<?= $row->year_of_establishment ?>" required><br>

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