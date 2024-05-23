<!DOCTYPE html>
<html>

<head>
    <title>Bus Reservation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <link rel="stylesheet" href="reservation.css">
</head>

<body>

    <nav class="navbar" style="z-index: 3; position: fixed;">
        <img src="logo.png" class="navbar-logo" alt="logo" />
        <ul class="navbar-list">
            <li><a href="user_dashboard.php">Home</a></li>
            <li><a href="reservation.html">Reservation</a></li>
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
                <li class="profile-dropdown-list-item">
                    <a href="#">
                        <i class="fa-regular fa-user"></i>
                        Edit Profile
                    </a>
                </li>

                <li class="profile-dropdown-list-item">
                    <a href="setting.html">
                        <i class="fa-solid fa-sliders"></i>
                        Settings
                    </a>
                </li>

                <li class="profile-dropdown-list-item">
                    <a href="support.html">
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

        function fetchUserReservations() {
            fetch('fetch_user_reservation.php')
                .then(response => response.json())
                .then(reservations => {
                    if (reservations.error) {
                        console.error('Error fetching data:', reservations.error);
                        return;
                    }
                    displayReservations(reservations);
                })
                .catch(error => console.error('Error:', error));
        }

        function displayReservations(reservations) {
            const reservationDetails = document.getElementById('reservationDetails');
            reservationDetails.innerHTML = '';

            if (reservations.length === 0) {
                reservationDetails.innerHTML = '<p>No reservations found.</p>';
                return;
            }

            reservations.forEach(reservation => {
                const ticketDiv = `
                <div class="ticket-info">
                    <div class="date-time">
                        <p><strong>Name:</strong> ${reservation.Username}</p>
                        <p><strong>Date:</strong> ${reservation.DepartureDate}</p>
                        <p><strong>Time:</strong> ${reservation.DepartureTime}</p>
                    </div>
                    <div class="from-to">
                        <p><strong>From:</strong> ${reservation.FromLocation}</p>
                        <p><strong>To:</strong> ${reservation.Destination}</p>
                    </div>
                    <div class="bus-details">
                        <p><strong>Bus Number:</strong> ${reservation.BusNumber}</p>
                        <p><strong>Bus Type:</strong> ${reservation.BusType}</p>
                    </div>
                    <div class="bookingdetails">
                        <p><strong>Driver Contact:</strong> ${reservation.ContactNumber}</p>
                        <p><strong>Number of Seats reserved:</strong> ${reservation.NumberOfSeatsReserved}</p>
                    </div>
                    <div class="status">
                        <p><strong>Status:</strong> ${reservation.status}</p>
                    </div>
                    <div class="downloadPDF">
                        ${reservation.status === 'approved' ? `<button class="btn" onclick="downloadPDF(${JSON.stringify(reservation)})">Download PDF</button>` : ''}
                    </div>
                </div>`;
                
                reservationDetails.innerHTML += ticketDiv;
            });
        }

        function downloadPDF(reservation) {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            doc.text(`Name: ${reservation.Username}`, 10, 10);
            doc.text(`Date: ${reservation.DepartureDate}`, 10, 20);
            doc.text(`Time: ${reservation.DepartureTime}`, 10, 30);
            doc.text(`From: ${reservation.FromLocation}`, 10, 40);
            doc.text(`To: ${reservation.Destination}`, 10, 50);
            doc.text(`Bus Number: ${reservation.BusNumber}`, 10, 60);
            doc.text(`Bus Type: ${reservation.BusType}`, 10, 70);
            doc.text(`Driver Contact: ${reservation.ContactNumber}`, 10, 80);
            doc.text(`Seats Reserved: ${reservation.NumberOfSeatsReserved}`, 10, 90);
            doc.text(`Status: ${reservation.status}`, 10, 100);

            doc.save('reservation.pdf');
        }

        document.addEventListener('DOMContentLoaded', function () {
            fetchUserReservations();
        });

    </script>

</body>

</html>
