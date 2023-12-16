<?php
session_start(); // Start the session

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php"); // Redirect to the login page or another appropriate page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="user.css">
    <title>User Page</title>
</head>
<body>
    <div class="header">
        <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
    </div>

    <div class="center-content">
        <h3>LIBRARY</h3>
        <p>Choose a category:</p>
        <select id="categoryDropdown">
            <option value="" disabled selected>Select Category</option>
            <option value="IT">IT</option>
            <option value="DEV">DEV</option>
            <option value="EDUC">EDUC</option>
            <option value="TM">TM</option>
            <option value="HM">HM</option>
        </select>
    </div>

    <script>
        // Add an event listener to the dropdown
        document.getElementById('categoryDropdown').addEventListener('change', function() {
            // Get the selected value
            var selectedCategory = this.value;

            // Redirect to the corresponding page based on the selected category
            switch (selectedCategory) {
                case 'IT':
                    window.location.href = 'it_category.php';
                    break;
                case 'DEV':
                    window.location.href = 'dev_category.php';
                    break;
                case 'EDUC':
                    window.location.href = 'educ_category.php';
                    break;
                case 'TM':
                    window.location.href = 'tm_category.php';
                    break;
                case 'HM':
                    window.location.href = 'hm_category.php';
                    break;
                default:
                    // Handle other cases or provide a default redirect
                    break;
            }
        });
    </script>
</body>
</html>
