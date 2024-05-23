<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bus Reservation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <link rel="stylesheet" href="reservation.css" />
</head>

<body>

    <nav class="navbar" style=" z-index: 3; position: fixed;">
        <img src="logo.png" class="navbar-logo" alt="logo" />
        <ul class="navbar-list">
            <li><a href="user_dashboard.php">Home</a></li>
            <li><a href="reservation.php">Reservation</a></li>
            <li><a href="user_dashboard.php">FAQs</a></li>
            <li><a href="user_dashboard.php">Contact</a></li>
        </ul>

        <div class="profile-dropdown">
            <div onclick="toggle()" class="profile-dropdown-btn">
                <div class="profile-img">
                    <i class="fa-solid fa-circle"></i>
                </div>

                <span>Profile
                    <i class="fa-solid fa-angle-down"></i>
                </span>
            </div>

            <ul class="profile-dropdown-list">
                <!-- <li class="profile-dropdown-list-item">
                    <a href="#">
                        <i class="fa-regular fa-user"></i>
                        Edit Profile
                    </a>
                </li> -->

                <li class="profile-dropdown-list-item">
                    <a href="setting.html" target="_blank">
                        <i class="fa-solid fa-sliders"></i>
                        Settings
                    </a>
                </li>

                <li class="profile-dropdown-list-item">
                    <a href="support.html" target="_blank">
                        <i class="fa-regular fa-circle-question"></i>
                        Help & Support
                    </a>
                </li>
                <hr />

                <li class="profile-dropdown-list-item">
                    <a href="login_signup.html">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        Log out
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="BookedTicketsContainer" style="width: 80%; margin-left: 170px; margin-top: 200px;">
        <div class="ticket" id="reservationDetails">
            <!-- Reservation details will be dynamically loaded here -->
        </div>
    </div>

    <script>
        let profileDropdownList = document.querySelector(".profile-dropdown-list");
        let btn = document.querySelector(".profile-dropdown-btn");

        let classList = profileDropdownList.classList;

        const toggle = () => classList.toggle("active");

        window.addEventListener("click", function (e) {
            if (!btn.contains(e.target)) classList.remove("active");
        });

        function toggleAnswer(answerId) {
            var answer = document.getElementById(answerId);
            if (answer.style.display === "none") {
                answer.style.display = "block";
            } else {
                answer.style.display = "none";
            }
        }

        // Function to fetch user reservations using AJAX
        function fetchUserReservations() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_user_reservation.php', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    console.log('AJAX request completed. Status:', xhr.status); // Debugging line
                    if (xhr.status == 200) {
                        console.log('Response received:', xhr.responseText); // Debugging line
                        try {
                            var response = JSON.parse(xhr.responseText);
                            console.log('Parsed response:', response); // Debugging line
                            if (response.error) {
                                console.error('Error:', response.error);
                                document.getElementById('reservationDetails').innerHTML = '<p>Error fetching reservations.</p>';
                            } else if (response.message) {
                                console.log('Message:', response.message);
                                document.getElementById('reservationDetails').innerHTML = '<p>' + response.message + '</p>';
                            } else {
                                displayReservations(response);
                            }
                        } catch (e) {
                            console.error('Parsing error:', e);
                            document.getElementById('reservationDetails').innerHTML = '<p>Error parsing response.</p>';
                        }
                    } else {
                        console.error('AJAX request failed. Status:', xhr.status);
                        document.getElementById('reservationDetails').innerHTML = '<p>Failed to fetch reservations.</p>';
                    }
                }
            };
            console.log('Sending AJAX request to fetch_user_reservation.php'); // Debugging line
            xhr.send();
        }

        // Call the function to fetch reservations when the page loads or when required
        document.addEventListener('DOMContentLoaded', fetchUserReservations);
        // Function to display reservations
        function displayReservations(reservations) {
            var reservationDetails = document.getElementById('reservationDetails');
            // Clear previous content
            reservationDetails.innerHTML = '';

            // Check if there are reservations
            if (reservations.length > 0) {
                // Loop through reservations
                reservations.forEach(function (reservation) {
                    // Create ticket element
                    var ticketDiv = document.createElement('div');
                    ticketDiv.classList.add('ticket-info');

                    // Populate ticket content
                    ticketDiv.innerHTML = `
                <div class="date-time">
                    <p><strong>Name:</strong> ${reservation['BusNumber']}</p>
                    <p><strong>Date:</strong> ${reservation['DepartureDate']}</p>
                    <p><strong>Time:</strong> ${reservation['DepartureTime']}</p>
                </div>
                <div class="from-to">
                    <p><strong>From:</strong> ${reservation['FromLocation']}</p>
                    <p><strong>To:</strong> ${reservation['Destination']}</p>
                </div>
                <div class="bus-details">
                    <p><strong>Bus Number:</strong> ${reservation['BusNumber']}</p>
                    <p><strong>Bus Type:</strong> ${reservation['BusType']}</p>
                </div>
                <div class="bookingdetails">
                    <p><strong>Driver Contact:</strong> ${reservation['ContactNumber']}</p>
                    <p><strong>Number of Seats reserved:</strong> ${reservation['NumberOfSeatsReserved']}</p>
                </div>
                <div class="status">
                    <p><strong>Status:</strong> ${reservation['status']}</p>
                </div>
                <div class="downloadPDF">
                    ${reservation['status'] === 'Approved' ? `<button class="btn" onclick="downloadPDF()">Download PDF</button>` : ''}
                </div>
            `;

                    // Append ticket to reservation details
                    reservationDetails.appendChild(ticketDiv);
                });
            } else {
                // No reservations found
                reservationDetails.innerHTML = '<p>No reservations found.</p>';
            }
        }
        // Call the function to fetch reservations when the page loads or when required
        document.addEventListener('DOMContentLoaded', function () {
            // Log the user ID before making the AJAX request
            var userId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>;
            console.log('User ID:', userId);

            // Check if the user ID is valid before making the AJAX request
            if (userId !== null) {
                fetchUserReservations();
            } else {
                console.error('User ID is null or undefined.');
            }
        });

        // Function to download PDF (you may keep this function as it is)
        function downloadPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            doc.text("Bus Reservation Details", 20, 20);
            doc.text(`Date: ${document.getElementById('date').innerText}`, 20, 30);
            doc.text(`Time: ${document.getElementById('time').innerText}`, 20, 40);
            doc.text(`From: ${document.getElementById('from').innerText}`, 20, 50);
            doc.text(`To: ${document.getElementById('to').innerText}`, 20, 60);
            doc.text(`Bus Number: ${document.getElementById('busNumber').innerText}`, 20, 70);
            doc.text(`Bus Type: ${document.getElementById('busType').innerText}`, 20, 80);
            doc.text(`Driver Contact: ${document.getElementById('driverContact').innerText}`, 20, 90);
            doc.text(`Number of Seats: ${document.getElementById('numberOfSeats').innerText}`, 20, 100);
            doc.text(`Status: ${document.getElementById('status').innerText}`, 20, 110);

            doc.save("reservation-details.pdf");
        }
    </script>
</body>

</html>