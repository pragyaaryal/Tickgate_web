<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
  <link rel="stylesheet" href="User.css" />
  <title>TickGate User</title>
</head>

<body>
  <nav class="navbar">
    <img src="logo.png" class="navbar-logo" alt="logo" />
    <ul class="navbar-list">
      <li><a href="#">Home</a></li>
      <li><a href="reservation.html" target="_blank">Reservation</a></li>
      <li><a href="#">FAQs</a></li>
      <li><a href="#">Contact</a></li>
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

  <div class="Homescreen">
    <div class="Bus_Search">
      <!-- Form for search -->
      <form id="searchForm" method="GET" action="fetch_search_results.php">
        <div class="from">
          <input type="text" name="from" id="from" placeholder="From" required>
          <i class="fa-solid fa-bus"></i>
        </div>
        <div class="to">
          <input type="text" name="to" id="to" placeholder="To" required>
          <i class="fa-solid fa-location-dot"></i>
        </div>
        <div class="date">
          <input type="date" name="date" id="date" placeholder="day_and_date" required>
        </div>
        <input class="Search" type="submit" value="Search">
      </form>
    </div>
  </div>
  <div class="SearchedResults">
    <h2>Search Results</h2>
    <div id="resultsContainer"></div>
  </div>
  <!-- <div class="Suggested bookings">
    <h2>Suggested bookings</h2>
    <div id="resultsContainer"></div>
  </div> -->


  <div class="FAQs">
    <h1>Frequently Asked Questions</h1>
    <div class="Question" onclick="toggleAnswer('Answer1')">
      1. What is TickGate?
      <i class="fa fa-chevron-down"></i>
    </div>
    <div id="Answer1" class="Answer">
      TickGate is an online platform/app designed for booking bus tickets, providing users with a seamless and
      convenient way to plan and purchase their bus travel.
    </div>

    <div class="Question" onclick="toggleAnswer('Answer2')">
      2. Do I Need to Register to Use TickGate?
      <i class="fa fa-chevron-down"></i>
    </div>
    <div id="Answer2" class="Answer">
      Yes, to utilize the services offered by TickGate, registration is required. Creating an account allows you to
      access various features and benefits provided by the platform.
    </div>

    <div class="Question" onclick="toggleAnswer('Answer3')">
      3. How Will I Get Confirmation That My Ticket is Purchased on TickGate?
      <i class="fa fa-chevron-down"></i>
    </div>
    <div id="Answer3" class="Answer">
      Upon successful completion of your booking on TickGate, you will await your status once its approved you'll be
      able to generate ticket.
    </div>

    <div class="Question" onclick="toggleAnswer('Answer4')">
      4. What Details Do I Need to Provide at the Time of Booking/Buying on TickGate?
      <i class="fa fa-chevron-down"></i>
    </div>
    <div id="Answer4" class="Answer">
      During the booking process on TickGate, you will need to provide details such as your name, contact information,
      journey details (origin, destination, date), and the number of seats.
    </div>

    <div class="Question" onclick="toggleAnswer('Answer5')">
      5.I entered the wrong mobile number while booking on TickGate. Can I get my mTicket on a different number?
      <i class="fa fa-chevron-down"></i>
    </div>
    <div id="Answer5" class="Answer">
      If you entered the wrong mobile number during booking on TickGate, please contact our customer support team as
      soon as possible with the correct mobile number. They will assist you in updating the information and ensuring
      that you receive your mTicket on the correct number.
    </div>

  </div>

  <div id="Contact" class="Contact">
    <h1>Contact</h1>
    <a href="callon:+9779761873901" target="_blank"><i class="fa fa-phone"></i></a>
    <a href="mailto:np03cs4a220022@heraldcollege.edu.np" target="_blank"><i class="fa fa-envelope"></i></a>
    <a href="https://www.instagram.com/" target="_blank"><i class="fa-brands fa-instagram"></i></a>
  </div>


  <footer>
    © 2023 Copyright: TickGate
  </footer>

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

    document.addEventListener('DOMContentLoaded', function () {
      const searchForm = document.getElementById('searchForm');

      searchForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(searchForm);
        const queryString = new URLSearchParams(formData).toString();

        fetch(`fetch_search_results.php?${queryString}`)
          .then(response => {
            if (!response.ok) {
              throw new Error(`Server error: ${response.status}`);
            }
            return response.json();
          })
          .then(data => {
            if (data.error) {
              console.error('Error fetching data:', data.error);
              return;
            }

            const resultsContainer = document.getElementById('resultsContainer');
            resultsContainer.innerHTML = ''; // Clear previous results

            if (data.length === 0) {
              resultsContainer.innerHTML = '<p>No results found.</p>';
              return;
            }

            data.forEach(ticket => {
              const seatOptions = Array.from({ length: ticket.FreeSeats }, (_, i) => `<option value="${i + 1}">${i + 1} Seat(s)</option>`).join('');
              const ticketHtml = `
            <div class="ticket">
              <div class="ticket-info">
                <div class="date-time">
                  <p><strong>Date:</strong> ${ticket.DepartureDate}</p>
                  <p><strong>Time:</strong> ${ticket.DepartureTime}</p>
                </div>
                <div class="from-to">
                  <p><strong>From:</strong> ${ticket.FromLocation}</p>
                  <p><strong>To:</strong> ${ticket.Destination}</p>
                  <p><strong>Driver Contact:</strong> ${ticket.ContactNumber}</p>
                </div>
                <div class="bus-details">
                  <p><strong>Bus Number:</strong> ${ticket.BusNumber}</p>
                  <p><strong>Bus Type:</strong> ${ticket.BusType}</p>
                  <p><strong>Free Seats:</strong> ${ticket.FreeSeats}</p>
                </div>
                <div class="bookingdetails">
                  <label for="numbeofseatsreserved_${ticket.RouteID}">Book Seats</label>
                  <select name="numbeofseatsreserved" id="numbeofseatsreserved_${ticket.RouteID}">
                    ${seatOptions}
                  </select>
                  <button class="book-button" data-routeid="${ticket.RouteID}" data-busnumber="${ticket.BusNumber}" data-from="${ticket.FromLocation}" data-to="${ticket.Destination}">Book Ticket</button>
                </div>
              </div>
            </div>`;

              resultsContainer.innerHTML += ticketHtml;
            });

            // Add event listeners to the book buttons
            document.querySelectorAll('.book-button').forEach(button => {
              button.addEventListener('click', function () {
                const routeID = this.dataset.routeid;
                const busNumber = this.dataset.busnumber;
                const fromLocation = this.dataset.from;
                const toLocation = this.dataset.to;
                const numSeats = document.getElementById(`numbeofseatsreserved_${routeID}`).value;

                // You can now handle the booking process using these values
                console.log('Booking ticket:', {
                  routeID,
                  busNumber,
                  fromLocation,
                  toLocation,
                  numSeats
                });

                // Implement the booking functionality here
              });
            });
          })
          .catch(error => console.error('Error:', error));
      });
    });
  </script>


  </script>
</body>


</html>