<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Management</title>
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
        }

        .status-select {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background-color: #fff;
            appearance: none;
            /* Remove default arrow */
            -webkit-appearance: none;
            /* Remove default arrow for Safari */
            -moz-appearance: none;
            /* Remove default arrow for Firefox */
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23000000" width="18px" height="18px"><path d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>');
            /* Custom arrow icon */
            background-repeat: no-repeat;
            background-position: right 10px center;
            cursor: pointer;
        }

        .status-select:focus {
            outline: none;
            border-color: #66A88C;
            /* Change border color on focus */
        }
    </style>
</head>

<body>
    <div class="navbar">
        <a href="admin_dashboard.php">Home</a>
        <span class="admin-dashboard">TickGate</span>
        <a class="logout" href="login_signup.html">Logout</a>
    </div>

    <h1>Booking Management</h1>

    <table id="reservationTable">
        <thead>
            <tr>
                <th>Reservation ID</th>
                <th>User ID</th>
                <th>Bus Number</th>
                <th>Route ID</th>
                <th>Number Of Seats Reserved</th>
                <th>From Location Reserved</th>
                <th>Destination Reserved</th>
                <th>Status</th>
                <td>Change Status</td>
            </tr>
        </thead>
        <tbody id="reservationTableBody">
            <!-- Reservation data will be populated here by JavaScript -->
        </tbody>
    </table>

    <?php
    // Include your database connection file here
    include 'db_connection.php';

    // Query to fetch reservation information along with corresponding route information
    $query = "SELECT r.ReservationID, r.user_id, r.BusNumber, r.RouteID, r.NumberOfSeatsReserved, 
                 rt.FromLocation, rt.Destination, r.status 
          FROM Reservation r
          INNER JOIN Route rt ON r.RouteID = rt.RouteID";

    $result = $conn->query($query);

    if ($result && $result->rowCount() > 0) {
        // Fetch all rows as associative arrays
        $reservationData = $result->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "No reservations found in the database.";
        exit; // Exit PHP script if no reservations found
    }

    // Close the database connection
    $conn = null;
    ?>

    <script>
        // Initialize the reservationData variable with PHP data
        const reservationData = <?php echo json_encode($reservationData); ?>;

        // Function to populate the table with reservation data
        function populateTable() {
            const reservationTableBody = document.getElementById('reservationTableBody');

            reservationData.forEach(reservation => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${reservation.ReservationID}</td>
                    <td>${reservation.user_id}</td>
                    <td>${reservation.BusNumber}</td>
                    <td>${reservation.RouteID}</td>
                    <td>${reservation.NumberOfSeatsReserved}</td>
                    <td>${reservation.FromLocation}</td>
                    <td>${reservation.Destination}</td>
                    <td>${reservation.status}</td>
                    <td>
                        <select class="status-select" onchange="changeStatus(this)">
                            <option value="" disabled selected>Change booking status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                        </select>
                    </td>
                `;
                reservationTableBody.appendChild(row);
            });
        }

        // Populate the table when the page loads
        populateTable();

        function changeStatus(select) {
            const reservationId = select.closest('tr').querySelector('td:first-child').textContent.trim();
            const newStatus = select.value;

            // Send AJAX request to update status in the database
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_booking_status.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Update status in the table
                        select.parentNode.previousElementSibling.textContent = newStatus;
                        // Show alert if status is changed to approved or pending
                        if (newStatus === 'approved') {
                            alert('Reservation status changed to Approved.');
                        } else if (newStatus === 'pending') {
                            alert('Reservation status changed to Pending.');
                        }
                    } else {
                        // Show error message
                        alert('Failed to update status: ' + response.message);
                    }
                } else {
                    // Show error message
                    alert('Failed to update status. Server responded with status: ' + xhr.status);
                }
            };
            xhr.onerror = function () {
                // Show error message
                alert('Request failed.');
            };
            xhr.send('reservationId=' + encodeURIComponent(reservationId) + '&status=' + encodeURIComponent(newStatus));
        }

    </script>
</body>

</html>