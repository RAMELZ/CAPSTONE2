<?php

// Function to generate table
function generateTable($department = null) {
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

    if ($department === null) {
        // Perform another logic or query when $department is null
        $sql = "SELECT book_id, book_image, book_name, book_description, book_quantity, department FROM books";
        $result = $conn->query($sql);
    } else {
        // SQL query to retrieve data based on department
        $sql = "SELECT book_id, book_image, book_name, book_description, book_quantity, department FROM books WHERE department = ?";
        
        // Use prepared statement to avoid SQL injection
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $department);
        $stmt->execute();
        $result = $stmt->get_result();
    }
    


    // Check if there are any results
    if ($result->num_rows > 0) {
        // Output table header
        ?>
        <table>
            <thead>
                <tr>
                    <th>Book ID</th>
                    <th>Book Image</th>
                    <th>Book Name</th>
                    <th>Book Description</th>
                    <th>Book Quantity</th>
                    <th>Department</th>
                    <th>Action</th>
                </tr>
    
            </thead>
            <tbody>
        <?php

        // Output data
       while ($row = $result->fetch_assoc()) {
    ?>
    <tr>
        <td><?php echo $row['book_id']; ?></td>
        <td><img src='./uploads/<?php echo $row['book_image']; ?>' alt='<?php echo $row['book_name']; ?>' style='max-width: 100px; max-height: 100px;'></td>
        <td class="editable" data-field="book_name"><?php echo $row['book_name']; ?></td>
        <td class="editable" data-field="book_description"><?php echo $row['book_description']; ?></td>
        <td class="editable" data-field="book_quantity"><?php echo $row['book_quantity']; ?></td>
        <td class="editable" data-field="department"><?php echo $row['department']; ?></td>
        <td>
            <?php
            $userRole = $_SESSION['role']; // Replace this with the actual role obtained from the session
            if ($userRole === "staff") {
                ?>
                <button onclick="editRow(this)">Edit</button>
                <?php
            } else {
                ?>
                <button onclick="borrowBook('<?php echo $row['department']; ?>', '<?php echo $row['book_name']; ?>', '<?php echo $row['book_id']; ?>', this)">Borrow</button>
                <button onclick="returnBook('<?php echo $row['book_id']; ?>', this)">Return</button>
                <?php
            }
            ?>
        </td>
    </tr>
    <?php
}

        // Close table body and table
        ?>
        </tbody></table>
        <?php
    } else {
        // No results
        echo "0 results";
    }

    // Close statement and connection
    // $stmt->close();
}

?>
<script>
    function borrowBook(category, bookTitle, bookId, buttonElement) {
    // Send a request to book_process.php to handle the borrowing logic
    fetch('./book_process.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `type=borrow&category=${category}&bookTitle=${bookTitle}&bookId=${bookId}`,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.result);
            // Update the UI to reflect the borrowed book
            updateQuantity(buttonElement, data.newQuantity);
        } else {
            alert(data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}

function returnBook(bookId, buttonElement) {
    // Send a request to book_process.php to handle the return logic
    fetch('./book_process.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `type=return&bookId=${bookId}`,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.result);
            // Update the UI to reflect the returned book
            updateQuantity(buttonElement, data.newQuantity);
        } else {
            alert(data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}

    function updateQuantity(buttonElement, newQuantity) {
        // Update the quantity displayed on the UI
        const row = buttonElement.closest('tr');
        const quantityCell = row.querySelector('td:nth-child(5)');
        quantityCell.textContent = newQuantity;
    }

     function editRow(buttonElement) {
    const row = buttonElement.closest('tr');
    const editableCells = row.querySelectorAll('.editable');
    
    // Change each editable cell to an input field
    editableCells.forEach(cell => {
        const field = cell.dataset.field;
        const value = cell.textContent;
        
        // Replace the cell's content with an input field
        cell.innerHTML = `<input type="text" name="${field}" value="${value}">`;
    });

    // Change the "Edit" button to a "Save" button
    buttonElement.textContent = 'Save';
    buttonElement.onclick = function () {
        saveRow(row);
    };
}

function saveRow(row) {
    const editableCells = row.querySelectorAll('.editable');

    // Collect the edited values from input fields
    const editedData = {};
    editableCells.forEach(cell => {
        const field = cell.dataset.field;
        const input = cell.querySelector('input');
        const value = input.value;
        editedData[field] = value;
    });

    // Send the edited data to the server using AJAX
    const bookId = row.querySelector('td:first-child').textContent; // Assuming the first column is the book_id
    updateBookInDatabase(bookId, editedData);
}


function updateRow(row, editedData) {
    const editableCells = row.querySelectorAll('.editable');
    editableCells.forEach(cell => {
        const field = cell.dataset.field;
        cell.textContent = editedData[field];
    });

    // Change the "Save" button back to "Edit"
    const editButton = row.querySelector('button');
    editButton.textContent = 'Edit';
    editButton.onclick = function () {
        editRow(this);
    };
}

function updateBookInDatabase(bookId, editedData) {
    console.log(editedData);
    fetch('./update_book.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `bookId=${bookId}&book_name=${editedData.book_name}&book_description=${editedData.book_description}&book_quantity=${editedData.book_quantity}&department=${editedData.department}`,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.result);
        } else {
            alert(data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}


</script>
