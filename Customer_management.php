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
        <!-- <thead>
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
        </thead> -->
        <tbody id="userTableBody">
            <!-- Customer data will be populated here -->
        </tbody>
    </table>

    <!-- Include PHP script to fetch customer information -->
    <?php include 'fetch_customer_info.php'; ?>

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

        // Function to handle editing a user
        function editUser(userId) {
            const row = document.querySelector(`tr[data-id="${userId}"]`);
            const user = userData.find(user => user.user_id === userId);
            if (!row || !user) return;

            const cells = row.querySelectorAll('td');

            cells.forEach(cell => {
                const originalContent = cell.textContent.trim();
                cell.innerHTML = `<input type="text" value="${originalContent}">`;
            });

            const actionCell = row.querySelector('.action-cell');
            actionCell.innerHTML = `
                <button class="save-btn btn" data-id="${userId}">Save</button>
            `;
        }

        // Function to handle saving edited user data
        function saveUser(userId) {
            const row = document.querySelector(`tr[data-id="${userId}"]`);
            const user = userData.find(user => user.user_id === userId);
            if (!row || !user) return;

            const cells = row.querySelectorAll('td');

            cells.forEach(cell => {
                const input = cell.querySelector('input');
                user[cell.dataset.field] = input.value.trim();
                cell.textContent = input.value.trim();
            });

            const actionCell = row.querySelector('.action-cell');
            actionCell.innerHTML = `
                <button class="edit-btn btn" data-id="${userId}">Edit</button>
                <button class="delete-btn btn" data-id="${userId}">Delete</button>
            `;
        }

        // Function to handle deleting a user
        function deleteUser(userId) {
            // Code to delete user from database
            const row = document.querySelector(`tr[data-id="${userId}"]`);
            if (row) {
                row.remove();
            }
        }

        // Event listener to handle button clicks
        document.addEventListener('click', function (event) {
            const target = event.target;

            if (target.classList.contains('edit-btn')) {
                const userId = target.getAttribute('data-id');
                editUser(userId);
            }

            if (target.classList.contains('save-btn')) {
                const userId = target.getAttribute('data-id');
                saveUser(userId);
            }

            if (target.classList.contains('delete-btn')) {
                const userId = target.getAttribute('data-id');
                deleteUser(userId);
            }
        });

        // Populate the table when the page loads
        populateTable();
    </script>

</body>

</html>