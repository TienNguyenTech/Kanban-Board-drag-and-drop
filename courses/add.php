<?php
global $dbh;
require_once("../connection.php");
require_once("../authentication.php");

// If the user has completed the form:
if ($_SERVER['REQUEST_METHOD'] == 'POST'):
    try {
        // Add new course based on the form received
        $query = "INSERT INTO `courses`
            (`name`, `description`, `fees`) VALUES 
            (:name,  :description,  :fees)";
        $stmt = $dbh->prepare($query);

        // Execute the query
        $stmt->execute([
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'fees' => $_POST['fees'],
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
        <title>Add Course</title>
    </head>
    <body>
    <h1>Add new course</h1>

    <form method="post">
        <label for="name">Name:</label><br>
        <input type="text" maxlength="128" id="name" name="name" required><br>

        <label for="description">Description:</label><br>
        <textarea maxlength="65535" id="description" name="description" cols="60" rows="10" required></textarea><br>

        <label for="fees">Fees:</label><br>
        <input type="number" max="99999.99" min="0" step="0.01" id="fees" name="fees" required><br>

        <br>

        <input type="submit" value="Add">
    </form>
    </body>
    </html>
<?php endif; ?>