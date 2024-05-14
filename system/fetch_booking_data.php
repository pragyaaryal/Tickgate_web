<?php
// Include your database connection file here
include 'db_connection.php';

// Check if the connection is successful
if ($connection) {
    // Fetch data from Reservation table
    $query = "SELECT * FROM Reservation";
    $result = mysqli_query($connection, $query);

    if ($result) {
        $reservations = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $reservations[] = $row;
        }

        // Return data as JSON
        echo json_encode($reservations);
    } else {
        // Return an empty array if there's an error fetching data
        echo json_encode([]);
    }

    // Close the connection
    mysqli_close($connection);
} else {
    // Return an error message if the connection fails
    echo json_encode(["error" => "Connection failed"]);
    error_log("Failed to connect to database: " . mysqli_connect_error());

}



// Include the database connection file
include 'db_connection.php';

// Query to fetch customer information from the users table
$query = "SELECT username, user_id, email, password, created_at, user_type, contact_number FROM users WHERE user_type = 'customer'";
$result = $conn->query($query); // Using PDO query() method instead of mysqli_query()

if ($result) {
    // Check if there are any rows returned
    if ($result->rowCount() > 0) {
        // Fetch all rows as associative arrays
        $userData = $result->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "No customers found in the database.";
        exit; // Exit PHP script if no customers found
    }
} else {
    // Echo an error message if the query fails
    echo "Error fetching customer information: " . $conn->errorInfo()[2];

    // Log the error to a file
    error_log("Error fetching customer information: " . $conn->errorInfo()[2], 3, "error.log");
    exit; // Exit PHP script if error occurs
}

// Close the database connection
$conn = null;
?>

<script>
    // Initialize the userData variable with PHP data
    const userData = <?php echo json_encode($userData); ?>;

    // Function to populate the table with customer data
    function populateTable() {
        const userTableBody = document.getElementById('userTableBody');

        userData.forEach(user => {
            const row = document.createElement('tr');
            row.setAttribute('data-id', user.user_id);
            row.innerHTML = `
            <td>${user.username}</td>
            <td>${user.user_id}</td>
            <td>${user.email}</td>
            <td>${user.password}</td>
            <td>${user.created_at}</td>
            <td>${user.user_type}</td>
            <td>${user.contact_number}</td>
            <td>
                <button class="edit-btn btn" data-id="${user.user_id}">Edit</button>
                <button class="delete-btn btn" data-id="${user.user_id}">Delete</button>
            </td>
        `;
            userTableBody.appendChild(row);
        });
    }

?>
