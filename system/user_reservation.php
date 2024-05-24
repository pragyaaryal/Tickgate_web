<?php
session_start();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connection.php'; // Include your database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login_signup.html'); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Query to fetch reservation details for the logged-in user
$sql = "SELECT r.ReservationID, r.BusNumber, r.NumberOfSeatsReserved, r.status, 
               b.BusType, b.ContactNumber as driverContact, 
               ro.FromLocation, ro.Destination, ro.DepartureDate, ro.DepartureTime 
        FROM Reservation r 
        JOIN Bus b ON r.BusNumber = b.BusNumber
        JOIN Route ro ON r.RouteID = ro.RouteID
        WHERE r.user_id = :user_id";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->errorInfo()));
}

$stmt->execute(['user_id' => $user_id]);

$reservations = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $reservations[] = $row;
}

$stmt->closeCursor();
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

<nav class="navbar" style="z-index: 3; position: fixed;">
    <img src="logo.png" class="navbar-logo" alt="logo" />
    <ul class="navbar-list">
        <li><a href="user_dashboard.php">Home</a></li>
        <li><a href="user_reservation.php">Reservation</a></li>
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
                <a href="logout.php">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    Log out
                </a>
            </li>
        </ul>
    </div>
</nav>

<div class="BookedTicketsContainer" style="width: 80%; margin-left: 170px; margin-top: 200px;">
    <?php foreach ($reservations as $index => $reservation): ?>
    <div class="ticket <?= $reservation['status'] == 'approved' ? 'approved-ticket' : '' ?>" id="reservationDetails<?= $index + 1 ?>">
        <div class="ticket-info">
            <div class="date-time">
                <p><strong>Date:</strong> <span class="date"><?= htmlspecialchars($reservation['DepartureDate']) ?></span></p>
                <p><strong>Time:</strong> <span class="time"><?= htmlspecialchars($reservation['DepartureTime']) ?></span></p>
            </div>
            <div class="from-to">
                <p><strong>From:</strong> <span class="from"><?= htmlspecialchars($reservation['FromLocation']) ?></span></p>
                <p><strong>To:</strong> <span class="to"><?= htmlspecialchars($reservation['Destination']) ?></span></p>
            </div>
            <div class="bus-details">
                <p><strong>Bus Number:</strong> <span class="bus-number"><?= htmlspecialchars($reservation['BusNumber']) ?></span></p>
                <p><strong>Bus Type:</strong> <span class="bus-type"><?= htmlspecialchars($reservation['BusType']) ?></span></p>
            </div>
            <div class="bookingdetails">
                <p><strong>Driver Contact:</strong> <span class="driver-contact"><?= htmlspecialchars($reservation['driverContact']) ?></span></p>
                <p><strong>Number of Seats reserved:</strong> <span class="num-seats"><?= htmlspecialchars($reservation['NumberOfSeatsReserved']) ?></span></p>
            </div>
            <div class="status">
                <p><strong>Status:</strong> <?= htmlspecialchars($reservation['status']) ?></p>
            </div>
            <?php if ($reservation['status'] == 'approved'): ?>
            <div class="downloadPDF">
                <button class="btn" onclick="downloadPDF('reservationDetails<?= $index + 1 ?>')">Download PDF</button>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<script>
    let profileDropdownList = document.querySelector(".profile-dropdown-list");
    let btn = document.querySelector(".profile-dropdown-btn");

    let classList = profileDropdownList.classList;

    const toggle = () => classList.toggle("active");

    window.addEventListener("click", function(e) {
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

    // Function to download PDF for approved ticket
    function downloadPDF(ticketId) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        let ticketInfo = document.querySelector(`#${ticketId} .ticket-info`);

        // Extract ticket information using the IDs
        let date = ticketInfo.querySelector('.date').innerText;
        let time = ticketInfo.querySelector('.time').innerText;
        let from = ticketInfo.querySelector('.from').innerText;
        let to = ticketInfo.querySelector('.to').innerText;
        let busNumber = ticketInfo.querySelector('.bus-number').innerText;
        let busType = ticketInfo.querySelector('.bus-type').innerText;
        let driverContact = ticketInfo.querySelector('.driver-contact').innerText;
        let numSeats = ticketInfo.querySelector('.num-seats').innerText;
        let status = ticketInfo.querySelector('.status').innerText;

        // Generate PDF content
        doc.text("Bus Reservation Details", 20, 20);
        doc.text(`Date: ${date}`, 20, 30);
        doc.text(`Time: ${time}`, 20, 40);
        doc.text(`From: ${from}`, 20, 50);
        doc.text(`To: ${to}`, 20, 60);
        doc.text(`Bus Number: ${busNumber}`, 20, 70);
        doc.text(`Bus Type: ${busType}`, 20, 80);
        doc.text(`Driver Contact: ${driverContact}`, 20, 90);
        doc.text(`Number of Seats: ${numSeats}`, 20, 100);
        doc.text(`Status: ${status}`, 20, 110);

        // Save the PDF
        doc.save("reservation-details.pdf");
    }
</script>
</body>
</html>
