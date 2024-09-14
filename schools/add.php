<?php
global $dbh;
require_once("../connection.php");
require_once("../authentication.php");

// If the user has completed the form:
if ($_SERVER['REQUEST_METHOD'] == 'POST'):
    try {
        // Add new school based on the form received
        $query = "INSERT INTO `schools`
            (`name`, `suburb`, `year_of_establishment`) VALUES 
            (:name,  :suburb,  :year_of_establishment)";
        $stmt = $dbh->prepare($query);

        // Execute the query
        $stmt->execute([
            'name' => $_POST['name'],
            'suburb' => $_POST['suburb'],
            'year_of_establishment' => $_POST['year_of_establishment'],
        ]);

        // And send the user back to where we were
        header('Location: index.php');
    } catch (PDOException $e) {
        displayPDOError($e);
    }
else:
    ?>

    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Add School</title>
    </head>
    <body>
    <h1>Add new school</h1>

    <form method="post">
        <label for="name">Name:</label><br>
        <input type="text" maxlength="128" id="name" name="name" required><br>

        <label for="suburb">Suburb:</label><br>
        <input type="text" maxlength="64" id="suburb" name="suburb" required><br>

        <label for="year-of-establishment">Year of Establishment:</label><br>
        <input type="number" max="9999" min="1000" step="1" id="year-of-establishment" name="year_of_establishment" required><br>

        <br>

        <input type="submit" value="Add">
    </form>
    </body>
    </html>
<?php endif; ?>