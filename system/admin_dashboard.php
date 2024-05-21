<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            background-color: #F9F8F8;
            font-family: Arial, sans-serif;
            margin: 0;
            padding-top: 0px;
            /* Adjust padding to accommodate fixed navbar */
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

        /* Additional CSS for main page */
        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .container {
            width: 1000px;
            margin: 0 auto;
            margin-top: 100px;
            background-color: rgba(255, 255, 255, 0.6);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            text-align: center;
            display: flex;
            flex-direction: column;
        }

        .general-information {
            width: calc(100% - 40px);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            margin-bottom: 20px;
            text-align: left;
            /* Align text to the left */
        }

        .sections-container {
            width: calc(100% - 40px);
            display: flex;
            justify-content: space-between;
        }

        .section {
            flex: 1;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            margin-right: 20px;
            font-size: 20px;
        }

        h1 {
            margin-bottom: 20px;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .todaysinfo {
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <a href="admin_dashboard.php">Home</a>
        <span class="admin-dashboard">TickGate</span>
        <a class="logout" href="login_signup.html">Logout</a>
    </div>

    <div class="header">
        <h1>Admin Dashboard</h1>
    </div>
    <div class="header">
        <h1>Admin Dashboard</h1>
    </div>
    <div class="container">
        <div class="general-information">
            <h2 id="currentDateTime"></h2>
            <div class="todaysinfo">
                <h2>Today's Information</h2>
                <p>No of Buses on route today: <span id="todaybusCountPlaceholder"></span></p>
                <p>No of Customer travelling: <span id="todaycustomerCountPlaceholder"></p>
                <p>No of Route being travelled:<span id="todayrouteCountPlaceholder"></p> </span></p>
                <p>No of Bookings Today:<span id="todaybookingCountPlaceholder">
            </div>

        </div>
        <div class="sections-container">
            <div class="section">
                <h2>Bus</h2>
                <p>Total Buses: <span id="totalbusCountPlaceholder"></span></p>
                <button onclick="window.location.href='manage_bus.php'">Manage Bus</button>
            </div>
            <div class="section">
                <h2>Customer</h2>
                <p>Total Customers: <span id="totalcustomerCountPlaceholder"></span></p>
                <button onclick="window.location.href='manage_customer.php'">Manage Customer</button>
            </div>
            <div class="section">
                <h2>Route</h2>
                <p>Total Routes: <span id="totalrouteCountPlaceholder"></p>
                <button onclick="window.location.href='manage_route.php'">Manage Route</button>
            </div>
            <div class="section">
                <h2>Bookings</h2>
                <p>Total bookings: <span id="totalbookingCountPlaceholder"></p>
                <button onclick="window.location.href='manage_bookings.php'">Manage Bookings</button>
            </div>
        </div>
    </div>

    <script>
        // JavaScript to fetch and display the today's number of customers (users) from the server
        fetch('get_today_customer_count.php')
            .then(response => response.text())
            .then(data => {
                // Update the total number of customers in the DOM
                document.getElementById('todaycustomerCountPlaceholder').textContent = data;
            })
            .catch(error => console.error('Error:', error));

        // JavaScript to fetch and display the total number of customers (users) from the server
        fetch('get_total_customer_count.php')
            .then(response => response.text())
            .then(data => {
                // Update the total number of customers in the DOM
                document.getElementById('totalcustomerCountPlaceholder').textContent = data;
            })
            .catch(error => console.error('Error:', error));

        

        // JavaScript to fetch and display today's bus count
        fetch('get_today_bus_count.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('todaybusCountPlaceholder').textContent = data;
            })
            .catch(error => console.error('Error:', error));

        // JavaScript to fetch and display total number of the bus count
        fetch('get_total_bus_count.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('totalbusCountPlaceholder').textContent = data;
            })
            .catch(error => console.error('Error:', error));


        // JavaScript to fetch and display the number of routes from the server
        fetch('get_today_route_count.php')
            .then(response => response.text())
            .then(data => {
                // Update the total number of customers in the DOM
                document.getElementById('todayrouteCountPlaceholder').textContent = data;
            })
            .catch(error => console.error('Error:', error));

         // JavaScript to fetch and display the number of routes from the server
        fetch('get_total_route_count.php')
            .then(response => response.text())
            .then(data => {
                // Update the total number of customers in the DOM
                document.getElementById('totalrouteCountPlaceholder').textContent = data;
            })
            .catch(error => console.error('Error:', error));

        
        
        // JavaScript to fetch and display the today's number of bookings from the server
        fetch('get_today_booking_count.php')
            .then(response => response.text())
            .then(data => {
                // Update the total number of customers in the DOM
                document.getElementById('todaybookingCountPlaceholder').textContent = data;
                // document.getElementById('bookingCountPlaceholder2').textContent = data;
            })
            .catch(error => console.error('Error:', error));
        
        // JavaScript to fetch and display the total number of bookings from the server
        fetch('get_total_booking_count.php')
            .then(response => response.text())
            .then(data => {
                // Update the total number of customers in the DOM
                document.getElementById('totalbookingCountPlaceholder').textContent = data;
                // document.getElementById('bookingCountPlaceholder2').textContent = data;
            })
            .catch(error => console.error('Error:', error));





        // JavaScript to display current date and time
        function updateDateTime() {
            const currentDate = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' };
            document.getElementById('currentDateTime').textContent = currentDate.toLocaleDateString('en-US', options);
        }

        // Update date and time initially and then every second
        updateDateTime();
        setInterval(updateDateTime, 1000);
    </script>

</html>