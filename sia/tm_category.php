<?php
session_start();
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
    $category = "Tourism";

    generateTable($category);
    ?>
</body>
</html>
