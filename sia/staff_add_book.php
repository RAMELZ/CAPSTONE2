<?php
session_start();

// Debugging: Output session information
//var_dump($_SESSION);

// Check if the user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    header("Location: index.php"); // Redirect to the login page or another appropriate page
    exit();
}

// Now, the rest of your staff.php content goes here
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="staff.css">
    <title>Staff Page</title>
</head>
<body>
    <div class="header">
        <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
    </div>

    <div class="sidebar">
        <a href="#">Dashboard</a>
        <a href="#">Library</a>
        <a href="#">Add Books</a>
        <a href="logout.php">Logout</a> 
    </div>

    <div class="content">
        <?php include "./add_book.php"; ?>
    </div>
</body>
</html>
