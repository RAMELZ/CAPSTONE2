<?php 
	session_start();
	if(!isset($_SESSION['username']) && !isset($_SESSION['role'])){
		header("Location: login.php");
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin</title>
	<link rel="stylesheet" type="text/css" href="./css/account.css">
		<link rel="stylesheet" type="text/css" href="./css/index.css">
</head>
<body>
	<div class="hero">
 <h1>User</h1>
 <p>Welcome <?php echo $_SESSION['username'] ?></p>
<a href="logout.php">Logout</a>
</div>
</body>
</html>