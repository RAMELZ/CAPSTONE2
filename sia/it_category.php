<?php
session_start();
$category = "IT"; // Set the category
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="user.css">
    <title><?php echo ucfirst($category); ?> Category</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'tables.php'; 
    $category = "Information Technology";

    generateTable($category);
    ?>


    <!-- <script>
        function borrowBook(category, bookTitle, quantityElement) {
            // Send a request to borrow.php to handle the borrowing logic
            fetch('borrow.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `category=${category}&bookTitle=${bookTitle}`,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.result);
                    // Update the UI to reflect the borrowed book
                    updateQuantity(quantityElement, data.newQuantity);
                } else {
                    alert(data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function updateQuantity(element, newQuantity) {
            // Update the quantity displayed on the UI
            element.textContent = `Quantity: ${newQuantity}`;
        }
    </script> -->
</body>
</html>
