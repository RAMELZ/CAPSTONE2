<?php
session_start();
$category = "DEV"; // Set the category
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="user.css">
    <title>DEV Category</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'tables.php'; 
    $category = "Development Communication";

    generateTable($category);
    ?>
</body>
</html>
