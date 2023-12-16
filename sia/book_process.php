<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "sia";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get data from the form
    $user_id = $_SESSION["user_id"]; // Assuming you have the user_id in the session
    $bookId = $_POST["bookId"];
    $operationType = $_POST["type"]; // Added line to get the type of operation

    if ($operationType == "borrow") {
        // Check if book_quantity is greater than 0 before decrementing
        if (isBookAvailable($conn, $bookId)) {
            // Insert borrower information into the borrowed_books table
            if (insertBorrowerInfo($conn, $user_id, $bookId)) {
                // Assuming the borrowing was successful, update the book_quantity
                $sql = "UPDATE books SET book_quantity = book_quantity - 1 WHERE book_id = ?";

                // Use prepared statement to avoid SQL injection
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $bookId);

                if ($stmt->execute()) {
                    // Update successful
                    $response = [
                        'success' => true,
                        'result' => 'Book borrowed successfully.',
                        'newQuantity' => getUpdatedQuantity($conn, $bookId),  // Get the actual new quantity
                    ];
                } else {
                    // Update failed
                    $response = [
                        'success' => false,
                        'error' => 'Failed to borrow the book.',
                    ];
                }

                // Close statement
                $stmt->close();
            } else {
                // Inserting borrower information failed
                $response = [
                    'success' => false,
                    'error' => 'You already borrowed a book',
                ];
            }
        } else {
            // Book not available (quantity is 0)
            $response = [
                'success' => false,
                'error' => 'Book is not available. Quantity is 0.',
            ];
        }
    } elseif ($operationType == "return") {
        // Increment book_quantity by 1 when the book is returned
        $sql = "UPDATE books SET book_quantity = book_quantity + 1 WHERE book_id = ?";

        // Use prepared statement to avoid SQL injection
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $bookId);

        if ($stmt->execute()) {
            // Update successful
            $response = [
                'success' => true,
                'result' => 'Book returned successfully.',
                'newQuantity' => getUpdatedQuantity($conn, $bookId),  // Get the actual new quantity
            ];
        } else {
            // Update failed
            $response = [
                'success' => false,
                'error' => 'Failed to return the book.',
            ];
        }

        // Close statement
        $stmt->close();
    } else {
        // Invalid operation type
        $response = [
            'success' => false,
            'error' => 'Invalid operation type.',
        ];
    }

    // Close connection
    $conn->close();

    echo json_encode($response);
} else {
    // Invalid request
    $response = [
        'success' => false,
        'error' => 'Invalid request.',
    ];
    echo json_encode($response);
}


// Function to check if a book is available (quantity is greater than 0)
function isBookAvailable($conn, $bookId) {
    $sql = "SELECT book_quantity FROM books WHERE book_id = ?";
    
    // Use prepared statement to avoid SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['book_quantity'] > 0;
    } else {
        return false;  // Assuming the book is not found
    }
}

// Function to get the updated book_quantity
function getUpdatedQuantity($conn, $bookId) {
    $sql = "SELECT book_quantity FROM books WHERE book_id = ?";
    
    // Use prepared statement to avoid SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['book_quantity'];
    } else {
        return 0;  // Assuming the book is not found
    }
}

// Function to insert borrower information into borrowed_books table
function insertBorrowerInfo($conn, $user_id, $bookId) {
    // Check if the user_id and book_id combination already exists
    $checkSql = "SELECT * FROM borrower WHERE user_id = ? AND book_id = ?";
    
    // Use prepared statement to avoid SQL injection
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ii", $user_id, $bookId);
    $checkStmt->execute();
    
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows > 0) {
        // Record already exists, return false
        return false;
    } else {
        // Record does not exist, proceed with the insertion
        $insertSql = "INSERT INTO borrower (user_id, book_id) VALUES (?, ?)";
        
        // Use prepared statement to avoid SQL injection
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("ii", $user_id, $bookId);
        
        return $insertStmt->execute();
    }
}
?>
