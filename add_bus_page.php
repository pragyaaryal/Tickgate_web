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
            <form id="addBusForm" method="post" action="bus_management.php">
                <input type="text" name="busNumber" placeholder="Bus Number" required><br>
                <input type="text" name="busType" placeholder="Bus Type" required><br>
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
        fetch('bus_management.php')
            .then(response => response.json())
            .then(data => {
                const busTableBody = document.querySelector('#busTable tbody');
                busTableBody.innerHTML = ''; // Clear existing table rows

                data.forEach(bus => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${bus.BusNumber}</td>
                        <td>${bus.BusType}</td>
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
            });
    }

    // Call fetchBusInfo function initially to populate the table
    fetchBusInfo();

    // Function to handle editing of table row
    function editRow(row) {
        const cells = row.querySelectorAll('td:not(:last-child):not(:last-child)'); // Exclude the last cell with action buttons and the free seats cell
        cells.forEach(cell => {
            const input = document.createElement('input');
            input.type = 'text';
            input.value = cell.textContent;
            cell.textContent = '';
            cell.appendChild(input);
        });
        // Replace "Edit" button with "Save" button
        const editBtn = row.querySelector('.editBtn');
        editBtn.textContent = 'Save';
        editBtn.classList.remove('editBtn');
        editBtn.classList.add('saveBtn');
    }

   // Function to handle saving edited row data
function saveRow(row) {
    const cells = row.querySelectorAll('td:not(:last-child)');
    const newData = [];
    cells.forEach(cell => {
        const input = cell.querySelector('input');
        if (input) {
            newData.push(input.value);
        } else {
            newData.push(cell.textContent);
        }
    });
    const busNumber = newData[0]; // Get the bus number from the edited row

    // Perform save operation here, update the database with newData
    fetch('bus_management.php', {
        method: 'POST',
        body: JSON.stringify({
            editBus: true,
            busNumber: busNumber,
            busType: newData[1], // Bus Type
            numberOfSeats: newData[2], // Number of Seats
            bookedSeats: newData[3] // Booked Seats
        }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => response.text())
        .then(data => {
            alert(data); // Display the response message from PHP
            fetchBusInfo(); // Fetch and display updated bus information
        })
        .catch(error => console.error('Error:', error));
}


    // Function to handle deleting a bus record
    function deleteBus(busNumber) {
        if (confirm(`Are you sure you want to delete Bus Number ${busNumber}?`)) {
            fetch('bus_management.php', {
                method: 'POST',
                body: JSON.stringify({ deleteBus: true, busNumber: busNumber }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.text())
                .then(data => {
                    alert(data); // Display the response message from PHP
                    fetchBusInfo(); // Fetch and display updated bus information
                })
                .catch(error => console.error('Error:', error));
        }
    }

    // Event listener for edit and save buttons (event delegation)
    document.querySelector('#busTable').addEventListener('click', function (event) {
        if (event.target.classList.contains('editBtn')) {
            const row = event.target.closest('tr');
            editRow(row);
        } else if (event.target.classList.contains('saveBtn')) {
            const row = event.target.closest('tr');
            saveRow(row);
        } else if (event.target.classList.contains('deleteBtn')) {
            const busNumber = event.target.getAttribute('data-busnumber');
            deleteBus(busNumber);
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Function to calculate and update the free seats based on the number of seats and booked seats
        function updateFreeSeats() {
            const numberOfSeatsInput = document.getElementById('numberOfSeats');
            const bookedSeatsInput = document.getElementById('bookedSeats');

            const numberOfSeats = parseInt(numberOfSeatsInput.value);
            const bookedSeats = parseInt(bookedSeatsInput.value);

            const freeSeats = numberOfSeats - bookedSeats;

            // Update the value of free seats cell
            document.getElementById('freeSeats').textContent = freeSeats;
        }

        // Event listeners to update free seats when the number of seats or booked seats change
        document.getElementById('numberOfSeats').addEventListener('input', updateFreeSeats);
        document.getElementById('bookedSeats').addEventListener('input', updateFreeSeats);
    });
</script>


</body>

</html>