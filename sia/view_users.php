<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sia";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user data from the database
$sql = "SELECT * FROM tblstaff WHERE deleted = 0";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="viewuser.css">
    <title>View Users</title>
    <!-- Include your CSS file if needed -->
</head>

<body>
    <div class="header">
        <h4>Dash<span>board</span></h4>
    </div>

    <div class="side-bar">
        <ul>
            <li><a href="admin.php"><i class="fas fa-home"></i>HOME</a></li>
            <li><a href="view_users.php"><i class="fas fa-user"></i>USER</a></li>
            <li><a href=""><i class="fa-solid fa-book"></i>ABOUT</a></li>
            <li class="dropdown">
                <a href="javascript:void(0)"><i class="fa fa-address-book-o"></i>MANAGE</a>
                <div class="dropdown-content">
                    <a href="addaccount.php">Add Account</a>
                    <a href="recoverAccount.php">Recover Account</a>
                </div>
            </li>
            <li><a href=""><i class="fa-solid fa-gear"></i>SETTING</a></li>
        </ul>

        <div class="logout">
            <a href="logout.php">LOGOUT</a>
        </div>
    </div>


   
    <div class="view">
         <h2>View Users</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                ?>
                        <form class="users-form" action="./deleteUsersQuery.php" method="POST">
                            <tr>
                               <td><?= $row["user_id"] ?><input type="hidden" name="id" value="<?= $row["user_id"] ?>"></td>
                                <td><?= $row["username"] ?><input type="hidden" name="username" value="<?= $row["username"] ?>"></td>
                                <td><?= $row["role"] ?><input type="hidden" name="role" value="<?= $row["role"] ?>"></td>

                                <td><button  type="submit" class='users-delete'>Delete</button></td>
                            </tr>
                        </form>
                <?php
                    }
                } else {
                ?>
                    <tr><td colspan='3'>No users found</td></tr>
                <?php
                }
                ?>
            </tbody>
        </table>

        <br>
        <a href="admin.php">Go back to Admin Page</a>
    </div>

    <?php
    $conn->close();
    ?>
</body>

<script type="text/javascript">
    const deleteBtn = document.querySelectorAll(".users-delete");

    deleteBtn.forEach((btn) => {
        btn.addEventListener('click', () => {
            // Your delete logic here
        })
    })
</script>

</html>
