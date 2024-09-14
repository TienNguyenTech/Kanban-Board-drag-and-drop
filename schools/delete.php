<?php
global $dbh;
require_once("../connection.php");
require_once("../authentication.php");

// Delete the record with the given record ID
if (!empty($_GET["id"])) {
    $query = "DELETE FROM `schools` WHERE `id` = :id";
    $stmt = $dbh->prepare($query);

    try {
        // Execute the query
        $stmt->execute([
            'id' => $_GET["id"]
        ]);

        // And send the user back to where we were
        header('Location: index.php');
    } catch (PDOException $e) {
        displayPDOError($e);
    }
}