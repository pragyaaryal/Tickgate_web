<?php
// Include the database connection file
include 'db_connection.php';

// Query to fetch customer information from the users table
$query = "SELECT username, user_id, email, password, created_at, user_type, contact_number FROM users WHERE user_type = 'customer'";
$result = $conn->query($query); // Using PDO query() method instead of mysqli_query()

if ($result) {
    // Check if there are any rows returned
    if ($result->rowCount() > 0) {
        // Output the table header
        echo "<table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>User ID</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Created At</th>
                        <th>User Type</th>
                        <th>Contact Number</th>
                        <th>Action</th> <!-- Added column for edit/delete buttons -->
                    </tr>
                </thead>
                <tbody>";

        // Output data of each row
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>" . $row["username"] . "</td>
                    <td>" . $row["user_id"] . "</td>
                    <td>" . $row["email"] . "</td>
                    <td>" . $row["password"] . "</td>
                    <td>" . $row["created_at"] . "</td>
                    <td>" . $row["user_type"] . "</td>
                    <td>" . $row["contact_number"] . "</td>
                    <td>
                        <button class='edit-btn btn' data-id='" . $row["user_id"] . "'>Edit</button>
                        <button class='delete-btn btn' data-id='" . $row["user_id"] . "'>Delete</button>
                    </td>
                </tr>";
        }

        // Close the table body
        echo "</tbody></table>";
    } else {
        echo "No customers found in the database.";
    }
} else {
    // Echo an error message if the query fails
    echo "Error fetching customer information: " . $conn->errorInfo()[2];
    
    // Log the error to a file
    error_log("Error fetching customer information: " . $conn->errorInfo()[2], 3, "error.log");
}

// Close the database connection
$conn = null;
?>
