<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sia";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if all required data is set
if (isset($_POST['bookId'], $_POST['book_name'], $_POST['book_description'], $_POST['book_quantity'], $_POST['department'])) {
    // Assuming you have received bookId and editedData from the AJAX request
    $bookId = $_POST['bookId'];
    $editedName = $_POST['book_name'];
    $editedDescription = $_POST['book_description'];
    $editedQuantity = $_POST['book_quantity'];
    $editedDepartment = $_POST['department'];

    // SQL query to update book information
    $sql = "UPDATE books SET book_name = ?, book_description = ?, book_quantity = ?, department = ? WHERE book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisi", $editedName, $editedDescription, $editedQuantity, $editedDepartment, $bookId);
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        $response = array("success" => true, "result" => "Book updated successfully");
    } else {
        $response = array("success" => false, "error" => "You didnt change any table");
    }

    // Close statement
    $stmt->close();
} else {
    $response = array("success" => false, "error" => "Missing data");
}

// Close connection
$conn->close();

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
