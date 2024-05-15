<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Route Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 0;
            margin: 0;
            background-color: #f9f8f8;
        }

        .navbar {
            position: fixed;
            top: 0;
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
            margin-top: 70px;
            /* Adjusted to make space for fixed navbar */
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #2A2D3A;
            color: #fff;
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

        .route-form {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f5f5f5;
        }

        .route-form input[type="text"],
        .route-form input[type="number"],
        .route-form input[type="tel"],
        .route-form input[type="date"] {
            /* Added input type for date */
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .route-form input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .route-form input[type="submit"]:hover {
            background-color: #45a049;
        }

        h1 {
            margin-top: 90px;
            margin-bottom: 20px;
            text-align: center;
            /* Added text alignment */
        }
    </style>
</head>

<body>
    <div class="navbar">
        <a href="admin_dashboard.php">Home</a>
        <span class="admin-dashboard">TickGate</span>
        <a class="logout" href="login_signup.html">Logout</a>
    </div>

    <h1>Route Management</h1>

    <table>
        <thead>
            <tr>
                <th>Route Id</th>
                <th>Starting Point</th>
                <th>Dropping Point</th>
                <th>Departure Date</th>
                <th>Departure Time</th>
                <th>Bus Number</th>
                <th>Ticket Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="routeTableBody">
            <!-- Routes will be dynamically added here -->
        </tbody>
    </table>

    <!-- Route Form -->
    <div class="route-form">
        <h2>Add Route</h2>
        <form id="addRouteForm" method="post" action="add_route.php">
            <input type="text" name="RouteID" placeholder="RouteID" required><br>
            <input type="text" name="startingPoint" placeholder="Starting Point" required><br>
            <input type="text" name="droppingPoint" placeholder="Dropping Point" required><br>
            <input type="date" name="departureDate" placeholder="Departure Date" required><br>
            <input type="text" name="departureTime" placeholder="Departure Time" required><br>
            <input type="text" name="busNumber" placeholder="Bus Number" required><br>
            <input type="text" name="ticketPrice" placeholder="ticketPrice" required><br>

            <input type="submit" name="submit" value="Add Route">
        </form>
    </div>

    <?php
    // Include the database connection file
    include 'db_connection.php';

    // Query to fetch customer information from the users table
    $query = "SELECT RouteID, FromLocation, Destination, DepartureDate, DepartureTime, BusNumber, TicketPrice FROM Route";
    $result = $conn->query($query); // Using PDO query() method instead of mysqli_query()
    
    if ($result) {
        // Check if there are any rows returned
        if ($result->rowCount() > 0) {
            // Fetch all rows as associative arrays
            $routeData = $result->fetchAll(PDO::FETCH_ASSOC);
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
        const routeData = <?php echo json_encode($routeData); ?>;

        // Function to populate the table with customer data
        function populateTable() {
            const routeTableBody = document.getElementById('routeTableBody');

            routeData.forEach(Route => {
                const row = document.createElement('tr');
                row.setAttribute('data-id', Route.RouteID);

                row.innerHTML = `
                <td>${Route.RouteID}</td>
                <td>${Route.FromLocation}</td>
                <td>${Route.Destination}</td>
                <td>${Route.DepartureDate}</td>
                <td>${Route.DepartureTime}</td>
                <td>${Route.BusNumber}</td>
                <td>${Route.TicketPrice}</td>
                <td>
                    <button class="edit-btn btn" data-id="${Route.RouteID}">Edit</button>
                    <button class="delete-btn btn" data-id="${Route.RouteID}">Delete</button>
                </td>
            `;
                routeTableBody.appendChild(row);

            });
            // Add event listeners to delete buttons
            document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const RouteID = btn.getAttribute('data-id');
                if (confirm(`Are you sure you want to remove this Route ${RouteID}?`)) {
                    window.location.href = `delete_route.php?RouteID=${RouteID}`;
                }
            });
        });

        }
        // Populate the table when the page loads
        populateTable();
    </script>



</body>

</html>