<?php
// Activate the session
session_start();

global $dbh;
require_once("connection.php");

// Test if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Check if the session-stored user ID still exist in the database
    $user_stmt = $dbh->prepare("SELECT * FROM `users` WHERE `id` = ?");
    $user_stmt->execute([$_SESSION['user_id']]);
    if ($user_stmt->rowCount() != 1) {
        // If user id is not found, or the statement is failed to run, log the user out
        header('Location: ' . substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT'])) . DIRECTORY_SEPARATOR . 'logout.php');
    }
} else {
    // If the user is not logged in (session doesn't have user id), send the user to log in page
    header('Location: ' . substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT'])) . DIRECTORY_SEPARATOR . 'login.php');
}