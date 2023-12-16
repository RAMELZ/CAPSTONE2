<?php
$servername = "localhost";
$uname = "root";
$pass = "";
$dbname = "sia";

$conn = new mysqli($servername, $uname, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];

$sql = "UPDATE tblstaff SET deleted = false WHERE username = ? ";
$stmt = $conn->prepare($sql);
if($stmt){
	$stmt->bind_param("s", $username);
	$stmt->execute();

	if($stmt->affected_rows > 0){
	echo "Records Updated";
}
else{
	echo "Error Updating Records";
}
$stmt->close();
}
$conn->close();






?>