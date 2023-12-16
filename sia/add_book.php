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

// If the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the form
    $bookName = $_POST["book-name"];
    $quantity = $_POST["quantity"];
    $description = $_POST["description"];
    $department = $_POST["department"];

    // Handle file upload
    $targetDirectory = "uploads/"; // Specify the directory where you want to store the uploaded files
    $targetFile = $targetDirectory . basename($_FILES["book-image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the file is an actual image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["book-image"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if the file already exists
    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["book-image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["book-image"]["tmp_name"], $targetFile)) {
            echo "The file ". htmlspecialchars( basename( $_FILES["book-image"]["name"])). " has been uploaded.";
            
            // Insert data into the database
            $sql = "INSERT INTO books (book_name, book_quantity, book_description, department, book_image) VALUES ('$bookName', '$quantity', '$description', '$department', '". basename($_FILES["book-image"]["name"]) ."')";

            if ($conn->query($sql) === TRUE) {
                echo "New book added successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Close the database connection
$conn->close();
?>



<h2>Add a New Book</h2>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
    <label for="book_name">Book Name:</label>
    <input type="text" name="book-name" required><br>

    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" required><br>

    <label for="description">Description:</label>
    <textarea name="description" rows="4" required></textarea><br>

    <label for="department">Department:</label>
    <select name="department" required>
        <option value="" disabled selected>Select Department</option>
        <option value="Hotel Management">Hotel Management</option>
        <option value="Tourism">Tourism</option>
        <option value="Information Technology">Information Technology</option>
        <option value="Education">Education</option>
        <option value="Development Communication">Development Communication</option>
    </select><br>

    <label for="book-image">Book Image:</label>
    <input type="file" name="book-image" accept="image/*" required><br>

    <input type="submit" value="Add Book">
</form>

