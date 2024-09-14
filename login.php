<?php
// Activate the session
session_start();

global $dbh;
require_once("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        //Run some SQL query here to find that user - password is hashed at database server
        $query = "SELECT * FROM `users` WHERE `username` = :username AND `password` = SHA2(:password, 0)";
        $stmt = $dbh->prepare($query);

        if ($stmt->execute([
                'username' => $_POST['username'],
                'password' => $_POST['password']
            ]) && $stmt->rowCount() == 1) {
            // When the user is found, grab its id and store it into the session for future reference
            $row = $stmt->fetchObject();
            $_SESSION['user_id'] = $row->id;
            //Successfully logged in, redirect user to dashboard
            header("Location: index.php");
        } else {
            $error_message = "Either username or password is incorrect!";
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login</title>

    <style>
        h3.error {
            color: red;
        }
    </style>
</head>
<body>
<h1>Log in</h1>

<h3 class="error"><?= $error_message ?? "" ?></h3>

<form method="post">
    <label for="username">Username</label>
    <input type="text" id="username" name="username" required><br>
    <br>
    <label for="password">Password</label>
    <input type="password" id="password" name="password" required><br>
    <br>
    <input type="submit" value="Login"/>
</form>
</body>
</html>