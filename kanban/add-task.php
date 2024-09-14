<?php
// Database connection setup
$host = 'localhost'; // Replace with your database host
$dbname = 'fit2101_draganddrop'; // Replace with your database name
$user = 'root'; // Replace with your database username
$pass = ''; // Replace with your database password

try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// If the form is submitted:
if ($_SERVER['REQUEST_METHOD'] == 'POST'):
    try {
        // Insert a new task based on the form received
        $query = "INSERT INTO `tbl_task` (`title`, `description`, `project_name`, `status_id`) 
                  VALUES (:title, :description, :project_name, :status_id)";
        $stmt = $dbh->prepare($query);

        // Execute the query with form data
        $stmt->execute([
            ':title' => $_POST['title'],
            ':description' => $_POST['description'],
            ':project_name' => $_POST['project_name'],
            ':status_id' => $_POST['status_id']
        ]);

        // Redirect the user back to the main page (or wherever needed)
        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        // Handle any PDO-related errors
        echo "Error: " . $e->getMessage();
    }
else:
    ?>

    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Add Task</title>
    </head>

    <body>
        <h1>Add a New Task</h1>

        <!-- Task Creation Form -->
        <form method="post">
            <label for="title">Task Name:</label><br>
            <input type="text" maxlength="128" id="title" name="title" required><br>

            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50"></textarea><br>

            <label for="project_name">Project Name:</label><br>
            <input type="text" maxlength="128" id="project_name" name="project_name"><br>

            <label for="status_id">Status ID:</label><br>
            <input type="number" id="status_id" name="status_id" min="1" required><br>

            <input type="submit" value="Add Task">
        </form>
    </body>

    </html>

<?php endif; ?>