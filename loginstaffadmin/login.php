<?php

include "dbconn.php";
session_start();
	if(isset($_SESSION['username']) && isset($_SESSION['role'])){
		switch ($_SESSION['role']) {
				case 'admin': header("Location: admin.php");
					break;
				
				case 'user': header("Location: user.php");
				break;
				case 'staff': header("Location: staff.php");

				break;
				default:
					// code...
					break;
			}
	}


if($_SERVER['REQUEST_METHOD'] == "POST"){

	$username = $_POST['fusername'];
	$password = $_POST['fpassword'];

	//SQL statement
	$stmt = $conn->prepare('SELECT * FROM tblauth WHERE username = ?');
	$stmt->bind_param("s", $username);

	$stmt->execute();
	$result = $stmt->get_result();

	if($result->num_rows == 1){
		$row = $result->fetch_assoc();
		$vpassword = $row["password"];
		$role = $row["role"];
		if($password == $vpassword){
			switch ($role) {
				case 'admin': header("Location: admin.php");
					// code...
					break;
				
				case 'user': header("Location: user.php");

				break;
				case 'staff': header("Location: staff.php");

				break;
				default:
					// code...
					break;
			}
		$_SESSION["username"] = $username;
		$_SESSION["role"] = $role;
		exit();

		}
		
		else{
			echo "Incorrect Username or Password";
		}
	}


$conn->close();
}



?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>

	<link rel="stylesheet" type="text/css" href="./css/index.css">
	<link rel="stylesheet" type="text/css" href="./css/login.css">
</head>
<body>


	<form action="" method="POST">
	<h2 class="form-title">Login</h2>
	<label for="fusername">Username</label>
	<input type="text" name="fusername" placeholder="username">
	<label for="fpassword">Password</label>
	<input type="password" name="fpassword" placeholder="password">
	<input type="submit" value="login" id="login">
	</form>


	
</body>
</html>