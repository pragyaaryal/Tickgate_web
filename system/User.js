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

// Wait for the DOM content to be fully loaded
document.addEventListener('DOMContentLoaded', function () {
  // Get the search button
  const searchButton = document.querySelector('.Search');

  // Add event listener to the search button
  searchButton.addEventListener('click', function () {
    // Get the values entered by the user
    const from = document.getElementById('from').value;
    const to = document.getElementById('to').value;
    const date = document.getElementById('date').value;

    // Make a request to the server to fetch results based on user input
    fetchResults(from, to, date);
  });
});

function fetchResults(from, to, date) {
  // Make an AJAX request to your backend with the user input
  // Example using Fetch API
  fetch(`fetch_search_results.php?from=${from}&to=${to}&date=${date}`)
    .then(response => response.json())
    .then(data => {
      // Update the Results section with the fetched data
      updateResults(data);
    })
    .catch(error => {
      console.error('Error fetching results:', error);
    });
}

function updateResults(data) {
  console.log("Received data:", data);

  const resultsContainer = document.querySelector('.SearchedResults');
  console.log("Results container:", resultsContainer);

  // Assuming data is an array of objects containing the search results


  // Clear previous results
  resultsContainer.innerHTML = '';

  // Loop through the data and create HTML elements to display the results
  data.forEach(result => {
    const ticketDiv = document.createElement('div');
    ticketDiv.classList.add('ticket');

    // Create ticket info elements
    const ticketInfoDiv = document.createElement('div');
    ticketInfoDiv.classList.add('ticket-info');

    // Populate ticket info
    ticketInfoDiv.innerHTML = `
      <div class="date-time">
        <p><strong>Date:</strong> ${result.date}</p>
        <p><strong>Time:</strong> ${result.time}</p>
      </div>
      <div class="from-to">
        <p><strong>From:</strong> ${result.from}</p>
        <p><strong>To:</strong> ${result.to}</p>
        <p><strong>Driver Contact:</strong> ${result.driverContact}</p>
      </div>
      <div class="bus-details">
        <p><strong>Bus Number:</strong> ${result.busNumber}</p>
        <p><strong>Bus Type:</strong> ${result.busType}</p>
        <p><strong>Free Seats:</strong> ${result.freeSeats}</p>
      </div>
      <div class="bookingdetails">
        <label for="numbeofseatsreserved">Book Seats</label>
        <select name="numbeofseatsreserved" id="numbeofseatsreserved" aria-placeholder="Number of Seats">
          <!-- Options based on available seats -->
        </select>
        <button class="book-button">Book Ticket</button>
      </div>
    `;

    // Append ticket info to ticket div
    ticketDiv.appendChild(ticketInfoDiv);

    // Append ticket div to results container
    resultsContainer.appendChild(ticketDiv);
  });
}

