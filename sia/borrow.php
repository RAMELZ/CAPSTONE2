<?php
// Include any necessary functions or configurations
// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'];
    $bookTitle = $_POST['bookTitle'];

    // Perform borrowing logic
    $result = borrowBook($category, $bookTitle);

    // Send the result as a JSON response
    header('Content-Type: application/json');
    echo json_encode(['result' => $result]);
    exit();
}

// Other PHP or HTML code for your borrow.php file



function borrowBook($category, $bookTitle) {
    // Connect to your database (replace these values with your actual database credentials)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "sia";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update the quantity in the database
    $sql = "UPDATE books SET quantity = quantity - 1 WHERE category = '$category' AND title = '$bookTitle'";

    if ($conn->query($sql) === TRUE) {
        // Close the database connection
        $conn->close();
        return "Successfully borrowed $bookTitle from the $category category!";
    } else {
        // Handle the case where the update fails
        $conn->close();
        return "Error updating quantity: " . $conn->error;
    }
}
