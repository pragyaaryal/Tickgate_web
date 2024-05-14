<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 60px 0px;
            margin: 0;
        }

        .navbar {
            position: fixed;
            top: 0;
            /* Ensure navbar stays at the top of the screen */
            left: 0;
            width: 100%;
            background-color: #2A2D3A;
            overflow: hidden;
            box-shadow: 0 2px 8px 0 rgba(127, 209, 174, 0.7);
            display: flex;
            align-items: center;
            padding: 10px 20px;
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .navbar a:hover {
            background-color: #ADE1CA;
        }

        .admin-dashboard {
            color: #fff;
            font-size: 18px;
            text-align: center;
            flex-grow: 1;
        }

        .logout {
            background-color: #66A88C;
            margin-left: auto;
            margin-right: 50px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .btn {
            padding: 6px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 5px;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .customer-form {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f5f5f5;
        }

        .customer-form input[type="text"],
        .customer-form input[type="number"],
        .customer-form input[type="tel"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .customer-form input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .customer-form input[type="submit"]:hover {
            background-color: #45a049;
        }

        h1 {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <a href="admin_dashboard.php">Home</a>
        <span class="admin-dashboard">TickGate</span>
        <a class="logout" href="login_signup.html">Logout</a>
    </div>

    <h1>Customer Management</h1>

    <!-- Table to display customer data -->
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>User ID</th>
                <th>Email</th>
                <th>Password</th>
                <th>Created At</th>
                <th>User Type</th>
                <th>Contact Number</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="userTableBody">
            <!-- Customer data will be populated here -->
        </tbody>
    </table>



    <?php
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
        

        // Add event listeners to delete buttons
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const userID = btn.getAttribute('data-id');
                if (confirm(`Are you sure you want to remove this User ${userID}?`)) {
                    window.location.href = `delete_customer.php?userId=${userID}`;
                }
            });
        });
    }
        // Populate the table when the page loads
        populateTable();
    </script>


</body>

</html>