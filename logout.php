<?php
// Activate the session
session_start();

// Clean up the session (removing user id)
session_destroy();

//redirect user back to home page once logged out
header("Location: index.php");