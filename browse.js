function toggleDropdown() {
  const menu = document.getElementById("dropdown-menu");
  menu.style.display = menu.style.display === "block" ? "none" : "block";
}

// Optional: close dropdown if clicked outside
window.onclick = function (e) {
  if (!e.target.matches(".profile-icon")) {
    const menu = document.getElementById("dropdown-menu");
    if (menu && menu.style.display === "block") {
      menu.style.display = "none";
    }
  }
};

// Example JSON array (if loading locally replace this with fetch)
fetch("events.json")
  .then((response) => response.json())
  .then((events) => {
    // Same code as above inside here

    // Select the event list container
    const eventList = document.querySelector(".events-list");

    // Clear existing "no events" message
    eventList.innerHTML = "";

    // Loop through events and add them to the DOM
    events.forEach((event) => {
      const eventItem = document.createElement("div");
      eventItem.classList.add("event-item");

      eventItem.innerHTML = `
      <h3>${event.event}</h3>
      <p><strong>Date:</strong> ${event.date}</p>
      <p><strong>Location:</strong> ${event.location}</p>
      <p>${event.organizing_club}</p>
    `;

      eventList.appendChild(eventItem);
    });

    // Remove the "empty" class now that we have events
    eventList.classList.remove("empty");
  });
