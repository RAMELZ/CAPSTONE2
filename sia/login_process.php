<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sia";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_username = $_POST["username"];
    $entered_password = $_POST["password"];

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM tblstaff WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $entered_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if($row["deleted"]){
            echo "Account deleted. Please contact an administrator for assistance";
        } else {
            if (password_verify($entered_password, $row["password"])) {
                // Successful login
                $_SESSION['username'] = $row['username'];
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['role'] = $row['role']; // Add this line to store role in the session
                echo "Login successful! Welcome, " . $row["username"] . ". Your role is: " . $row["role"];
                // Debugging: Output session information
                var_dump($_SESSION);
                // Add logic to redirect or perform actions based on role
                // Redirect to a different page based on the role
                if ($row["role"] === "admin") {
                    header("Location: admin.php");
                    exit();
                } elseif ($row["role"] === "staff") {
                    header("Location: staff.php");
                    exit();
                } elseif ($row["role"] === "user") {
                    header("Location: user.php");
                    exit();
                } else {
                    echo "Unknown role!";
                }
            } else {
                // Incorrect password
                echo "Incorrect password!";
            }
        }
    } else {
        // User not found
        echo "User not found!";
    }

    $stmt->close();
}

$conn->close();
?>
