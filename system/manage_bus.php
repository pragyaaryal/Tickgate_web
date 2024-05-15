<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 0px;
            margin: 0;
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

        .container {
            max-width: auto;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
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
            background-color: #e8301b;
        }

        .bus-form input[type="text"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .bus-form input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .bus-form input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <a href="admin_dashboard.php">Home</a>
        <span class="admin-dashboard">TIkGate</span>
        <a class="logout" href="login_signup.html">Logout</a>
    </div>
    <div class="container">
        <h1>Bus Management</h1>

        <!-- Bus Table -->
        <table id="busTable">
            <!-- Table headers -->
            <thead>
                <tr>
                    <th>Bus Number</th>
                    <th>Bus Type</th>
                    <th>Contact Number</th>
                    <th>Number of Seats</th>
                    <th>Booked Seats</th>
                    <th>Free Seats</th>
                    <th>Action</th>
                </tr>
            </thead>
            <!-- Table body will be filled dynamically using JavaScript -->
            <tbody>
            </tbody>
        </table>

        <!-- Add Bus Form -->
        <div class="bus-form">
            <h2>Add Bus</h2>
            <form id="addBusForm" method="post" action="add_bus.php">
                <input type="text" name="busNumber" placeholder="Bus Number" required><br>
                <input type="text" name="busType" placeholder="Bus Type" required><br>
                <input type="text" name="contactNumber" placeholder="Contact Number" required><br>
                <input type="text" name="numberOfSeats" id="numberOfSeats" placeholder="Number of Seats" required><br>
                <input type="text" name="bookedSeats" id="bookedSeats" placeholder="Booked Seats" required><br>
                <!-- Add the readonly attribute for the Free Seats input -->
                <input type="hidden" name="freeSeats" id="freeSeats"><br>
                <input type="submit" name="addBus" value="Add Bus">
            </form>
        </div>
    </div>

    <!-- JavaScript to fetch and display bus information -->
    <script>
    // Function to fetch bus information from the server
    function fetchBusInfo() {
        fetch('fetch_bus_info.php')
            .then(response => response.json())
            .then(data => {
                const busTableBody = document.querySelector('#busTable tbody');
                busTableBody.innerHTML = ''; // Clear existing table rows

                data.forEach(bus => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${bus.BusNumber}</td>
                        <td>${bus.BusType}</td>
                        <td>${bus.ContactNumber}</td>
                        <td>${bus.NumberOfSeats}</td>
                        <td>${bus.BookedSeats}</td>
                        <td>${bus.FreeSeats}</td>
                        <td>
                            <button class="btn editBtn" data-busnumber="${bus.BusNumber}">Edit</button>
                            <button class="btn deleteBtn" data-busnumber="${bus.BusNumber}">Delete</button>
                        </td>
                    `;
                    busTableBody.appendChild(row);
                });

                // Add event listeners to delete buttons
                document.querySelectorAll('.deleteBtn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const busNumber = btn.getAttribute('data-busnumber');
                        if (confirm(`Are you sure you want to delete bus ${busNumber}?`)) {
                            window.location.href = `delete_bus.php?busNumber=${busNumber}`;
                        }
                    });
                });

                // Add event listeners to edit buttons
                document.querySelectorAll('.editBtn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const busNumber = btn.getAttribute('data-busnumber');
                        const row = btn.closest('tr');
                        const cells = row.querySelectorAll('td');

                        // Display editable fields
                        cells[1].innerHTML = `<input type="text" name="contactNumber" value="${cells[1].textContent}">`;
                        cells[2].innerHTML = `<input type="text" name="busType" value="${cells[2].textContent}">`;
                        cells[3].innerHTML = `<input type="text" name="numberOfSeats" value="${cells[3].textContent}">`;
                        cells[4].innerHTML = `<input type="text" name="bookedSeats" value="${cells[4].textContent}">`;

                        // Replace edit button with save and cancel buttons
                        cells[5].innerHTML = `
                            <button class="btn saveBtn" data-busnumber="${busNumber}">Save</button>
                            <button class="btn cancelBtn">Cancel</button>
                        `;

                        // Add event listener to save button
                        row.querySelector('.saveBtn').addEventListener('click', () => {
                            const editedData = {
                                busNumber: busNumber,
                                contactNumber: row.querySelector('input[name="contactNumber"]').value,
                                busType: row.querySelector('input[name="busType"]').value,
                                numberOfSeats: row.querySelector('input[name="numberOfSeats"]').value,
                                bookedSeats: row.querySelector('input[name="bookedSeats"]').value
                            };

                            // Send edited data to PHP script for update
                            fetch('edit_bus.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify(editedData)
                            })
                            .then(response => {
                                if (response.ok) {
                                    // Refresh bus information after update
                                    fetchBusInfo();
                                } else {
                                    console.error('Error updating bus information');
                                }
                            });
                        });

                        // Add event listener to cancel button
                        row.querySelector('.cancelBtn').addEventListener('click', () => {
                            // Reload the page to revert changes
                            location.reload();
                        });
                    });
                });
            });
    }

    // Call fetchBusInfo function initially to populate the table
    fetchBusInfo();
</script>





</body>

</html>