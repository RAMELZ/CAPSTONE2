<?php
$servername = "localhost";
$username ="root";
$password ="";
$dbname = "sia";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $role = $_POST["role"];

    $sql = "INSERT INTO tblstaff (username, password, role, deleted) VALUES ('$username', '$password', '$role', 0)";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" type="text/css" href="register.css"> <!-- Include your CSS file -->
</head>
<body>
    <h2>Register</h2>
    <form action="register.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="role">Role:</label>
        <select id="role" name="role">
            <option value="user" selected>User</option>
            <option value="staff" selected>Staff</option>
        
            

            <!-- Add more roles if needed -->
        </select>

        <button type="submit">Register</button>
    </form>
</body>
</html>
