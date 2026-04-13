// ==================== for the destination==========


// ==================== DESTINATION DROPDOWN ====================
const dropdown = document.querySelector(".custom-dropdown");
const selected = document.querySelector(".selected");
const options = document.querySelectorAll(".option");

selected.addEventListener("click", () => {
    const menu = dropdown.querySelector(".dropdown");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
});

document.addEventListener("click", (e) => {
    if (!dropdown.contains(e.target)) {
        dropdown.querySelector(".dropdown").style.display = "none";
    }
});

// ==================== DESTINATION IMAGE ====================
const previewCard = document.querySelector(".destination-preview-card");
const destinationImage = document.getElementById("destination-image");

const destinationImages = {
    "Lalibela": "images/lalibeladd2.jpg",
    "Omo Valley": "images/omov1.jpg",
    "Harar": "images/harar1.jpg",
    "Gondar": "images/gonder2.jpg",
    "Simien Mountains": "images/simien1.jpg"
};

// ==================== NUMBER OF DAYS ====================
const daysSlider = document.getElementById("days-slider");
const daysCount = document.getElementById("days-count");

daysSlider.addEventListener("input", () => {
    daysCount.textContent = daysSlider.value;
});

// ==================== BUDGET ====================
const budgetSlider = document.getElementById("budget-slider");
const budgetUSD = document.getElementById("budget-usd");
const budgetETB = document.getElementById("budget-etb");
const usdToEtbRate = 25;

budgetSlider.addEventListener("input", () => {
    const usd = budgetSlider.value;
    const etb = usd * usdToEtbRate;

    budgetUSD.textContent = `$${usd}`;
    budgetETB.textContent = `ETB ${etb}`;
});

// ==================== TRAVEL MODE ====================
const modeCards = document.querySelectorAll(".mode-card");
const destinationTravelModes = {
    "Lalibela": ["driving"],
    "Omo Valley": ["driving"],
    "Harar": ["driving", "flight"],
    "Gondar": ["driving", "flight"],
    "Simien Mountains": ["driving"]
};

function updateTravelModes(selectedDestination) {
    const allowedModes = destinationTravelModes[selectedDestination];
    document.querySelector(".travel-mode-cards").classList.add("active");

    modeCards.forEach(card => {
        const mode = card.dataset.mode;
        const disabledText = card.querySelector(".disabled-text");
        if (allowedModes.includes(mode)) {
            card.classList.remove("disabled");
            disabledText.classList.add("hidden");
        } else {
            card.classList.add("disabled");
            disabledText.classList.remove("hidden");
            card.classList.remove("selected");
        }
    });
}

modeCards.forEach(card => {
    card.addEventListener("click", () => {
        if (card.classList.contains("disabled")) return;
        modeCards.forEach(c => c.classList.remove("selected"));
        card.classList.add("selected");
    });
});

// ==================== ACTIVITIES ====================
const activityGroup = document.querySelector(".activity-group");
const activityContainer = document.getElementById("activity-container");
const showMoreBtn = document.getElementById("show-more-btn");

let currentDestination = null;
let showAll = false;

const allActivities = [
    { name: "Food", icon: "fa-utensils" },
    { name: "Nightlife", icon: "fa-moon" },
    { name: "Hiking", icon: "fa-person-hiking" },
    { name: "Religious Sites", icon: "fa-church" },
    { name: "Wildlife", icon: "fa-paw" },
    { name: "Beach", icon: "fa-umbrella-beach" },
    { name: "Culture", icon: "fa-masks-theater" },
    { name: "History", icon: "fa-landmark" },
    { name: "Adventure", icon: "fa-mountain" },
    { name: "Mountains", icon: "fa-mountain-sun" }
];

const destinationActivities = {
    "Lalibela": ["Religious Sites", "Culture", "History"],
    "Omo Valley": ["Culture", "Wildlife", "Adventure"],
    "Harar": ["Culture", "History", "Food"],
    "Gondar": ["History", "Culture", "Food"],
    "Simien Mountains": ["Hiking", "Wildlife", "Mountains", "Adventure"]
};

function renderActivities() {
    activityContainer.innerHTML = "";
    const allowed = destinationActivities[currentDestination] || [];
    const activitiesToShow = showAll ? allActivities : allActivities.slice(0, 3);

    activitiesToShow.forEach(activity => {
        const card = document.createElement("div");
        card.classList.add("activity-card");
        card.innerHTML = `<i class="fa-solid ${activity.icon}"></i><span>${activity.name}</span>`;

        if (!allowed.includes(activity.name)) {
            card.classList.add("disabled");
            const note = document.createElement("div");
            note.classList.add("note");
            note.textContent = "Not available";
            card.appendChild(note);
        }

        card.addEventListener("click", () => {
            if (card.classList.contains("disabled")) return;
            card.classList.toggle("selected");
        });

        activityContainer.appendChild(card);
    });
}

showMoreBtn.addEventListener("click", () => {
    showAll = !showAll;
    showMoreBtn.textContent = showAll ? "Show Less" : "More";
    renderActivities();
});

// ==================== DESTINATION SELECTION ====================
options.forEach(option => {
    option.addEventListener("click", () => {
        const selectedName = option.textContent;
        selected.textContent = selectedName;
        dropdown.querySelector(".dropdown").style.display = "none";

        destinationImage.src = destinationImages[selectedName] || "";
        destinationImage.alt = selectedName;
        previewCard.classList.remove("hidden");

        updateTravelModes(selectedName);
        currentDestination = selectedName;
        activityGroup.classList.remove("hidden");
        showAll = false;
        showMoreBtn.textContent = "More";
        renderActivities();
    });
});

// ==================== GENERATE BUTTON ====================
const generateBtn = document.getElementById("generate-btn");
const notification = document.getElementById("notification");
const noteSub = document.getElementById("note-sub");
const placeholder = document.getElementById("placeholder");
const resultContainer = document.getElementById("result-container");
const plannerRight = document.querySelector(".planner-right");
const timelineContent = document.querySelector(".timeline-content");

let timelineData = [];
let currentFocusDay = 0;

generateBtn.addEventListener("click", () => {
    if (!currentDestination) {
        alert("Please select a destination first.");
        return;
    }

    const selectedMode = document.querySelector(".mode-card.selected");
    if (!selectedMode) {
        alert("Please select a travel mode.");
        return;
    }

    const selectedActivities = document.querySelectorAll(".activity-card.selected");
    const tripData = {
        destination: currentDestination,
        days: parseInt(daysSlider.value),
        mode: selectedMode.dataset.mode,
        activities: Array.from(selectedActivities).map(c => c.innerText.replace("Not available", "").trim())
    };
    console.log("Generated Trip:", tripData);

    noteSub.innerText = `${tripData.days} day trip across 1 destination (${tripData.destination})`;
    notification.classList.remove("hidden");
    setTimeout(() => {
        notification.style.opacity = "0";
        setTimeout(() => {
            notification.classList.add("hidden");
            notification.style.opacity = "1";
        }, 500);
    }, 3000);

    placeholder.style.display = "none";
    resultContainer.classList.remove("hidden");
    plannerRight.classList.add("split");

    // ================= GENERATE BUTTON =================
const generateBtn = document.getElementById("generate-btn");

generateBtn.addEventListener("click", () => {

    if (!currentDestination) {
        alert("Please select a destination first.");
        return;
    }

    const selectedMode = document.querySelector(".mode-card.selected");
    if (!selectedMode) {
        alert("Please select a travel mode.");
        return;
    }

    const selectedActivities = document.querySelectorAll(".activity-card.selected");

    const tripData = {
        destination: currentDestination,
        days: parseInt(daysSlider.value),
        mode: selectedMode.dataset.mode,
        activities: Array.from(selectedActivities).map(c =>
            c.innerText.replace("Not available", "").trim()
        )
    };

    // SAVE DATA
    localStorage.setItem("tripData", JSON.stringify(tripData));

    // REDIRECT
    window.location.href = "triprr.html";
});

    // ==================== UPDATE DAY 1 CARD ====================
    const day1Card = document.querySelector(".plan-card-outer .plan-card-inner");
    if (day1Card) {
        day1Card.querySelector(".location-info span").textContent = tripData.destination;

        const today = new Date();
        const dateStr = today.toLocaleString("default", { month: "short" }) + " " +
                        today.getDate() + ", " + today.getFullYear();
        day1Card.querySelector(".date-card").textContent = dateStr;

        const toggleBtn = day1Card.querySelector(".toggle-plan");
        const planBody = day1Card.querySelector(".plan-body");
        toggleBtn.textContent = "v";
        planBody.style.display = "block";

        toggleBtn.onclick = () => {
            const isHidden = planBody.style.display === "none";
            planBody.style.display = isHidden ? "block" : "none";
            toggleBtn.textContent = isHidden ? "v" : "^";
        };
    }

    // ==================== INITIALIZE TIMELINE DATA ====================
    timelineData = Array.from(document.querySelectorAll(".plan-card"));
    currentFocusDay = 0;
    updateFocusView();
});

// ==================== FOCUS ON ONE DAY ====================
const focusToggle = document.getElementById("focus-toggle");

// Create Day Navigation elements dynamically
const dayNavContainer = document.createElement("div");
dayNavContainer.id = "day-nav";
dayNavContainer.style.display = "none";
dayNavContainer.style.justifyContent = "center";
dayNavContainer.style.alignItems = "center";
dayNavContainer.style.gap = "10px";
dayNavContainer.innerHTML = `
    <button class="prev-day">&#8592;</button>
    <span class="current-day-label">Day 1 of 1</span>
    <button class="next-day">&#8594;</button>
`;
const adjustSection = document.querySelector(".adjust-section");
adjustSection.parentNode.insertBefore(dayNavContainer, adjustSection.nextSibling);

const prevBtn = dayNavContainer.querySelector(".prev-day");
const nextBtn = dayNavContainer.querySelector(".next-day");
const dayLabel = dayNavContainer.querySelector(".current-day-label");

function updateFocusView() {
    if (!focusToggle.classList.contains("active")) {
        dayNavContainer.style.display = "none";
        timelineData.forEach(card => card.style.display = "block");
        return;
    }

    dayNavContainer.style.display = "flex";
    timelineData.forEach((card, index) => {
        card.style.display = index === currentFocusDay ? "block" : "none";
    });

    dayLabel.textContent = `Day ${currentFocusDay + 1} of ${timelineData.length}`;
    prevBtn.disabled = currentFocusDay === 0;
    nextBtn.disabled = currentFocusDay === timelineData.length - 1;

    // Color arrows
    prevBtn.style.color = "#f57c00";
    nextBtn.style.color = "#f57c00";
}

focusToggle.addEventListener("click", () => {
    focusToggle.classList.toggle("active");
    currentFocusDay = 0;
    updateFocusView();
});

prevBtn.addEventListener("click", () => {
    if (currentFocusDay > 0) currentFocusDay--;
    updateFocusView();
});

nextBtn.addEventListener("click", () => {
    if (currentFocusDay < timelineData.length - 1) currentFocusDay++;
    updateFocusView();
});

// ==================== ADJUST PLAN BUTTONS ====================
const adjustButtons = document.querySelectorAll(".adjust-btn");
adjustButtons.forEach(button => {
    button.addEventListener("click", () => {
        adjustButtons.forEach(btn => btn.classList.remove("active"));
        button.classList.add("active");
        console.log("Selected plan mode:", button.dataset.mode);
    });
});







//===============day 1 card=================

document.addEventListener("DOMContentLoaded", function () {

    const resetBtn = document.getElementById("reset-btn");
    const modeCards = document.querySelectorAll(".mode-card");

    // Custom dropdown
    const dropdown = document.querySelector(".custom-dropdown");
    const selected = dropdown.querySelector(".selected");
    const options = dropdown.querySelectorAll(".option");

    let currentDestination = "Lalibela";
    let currentMode = "driving";
    let originalActivities = [];

    const destinationData = {
        "Lalibela": [
            { name: "Visit Rock-Hewn Churches", time: "09:30 • 2h", icon: "fa-church" },
            { name: "Lunch at local restaurant", time: "11:30 • 1h", icon: "fa-utensils" },
            { name: "Market exploration", time: "12:30 • 1.5h", icon: "fa-shop" },
            { name: "Evening sightseeing", time: "14:00 • 2h", icon: "fa-binoculars" }
        ],
        

        "Omo Valley": [
            { name: "Cultural village visit", time: "09:30 • 2h", icon: "fa-people-group" },
            { name: "River tour", time: "11:30 • 1.5h", icon: "fa-water" },
            { name: "Lunch at local hut", time: "13:00 • 1h", icon: "fa-utensils" },
            { name: "Wildlife spotting", time: "14:00 • 2h", icon: "fa-paw" }
        ],
        "Harar": [
            { name: "City wall tour", time: "09:30 • 1.5h", icon: "fa-landmark" },
            { name: "Cafe visit", time: "11:00 • 1h", icon: "fa-mug-hot" },
            { name: "Market walk", time: "12:00 • 1h", icon: "fa-shop" },
            { name: "Hyena feeding", time: "13:00 • 2h", icon: "fa-paw" }
        ]
    };

    const modeIcons = {
        "driving": "fa-car",
        "flight": "fa-plane"
    };

    // Render timeline
    function renderTimeline() {
        const planBody = document.querySelector(".plan-body");
        const timelineCardsContainer = planBody.querySelector(".timeline-cards");
        const locationName = planBody.querySelector(".location-name");

        timelineCardsContainer.innerHTML = "";
        locationName.textContent = currentDestination;

        // Arrival card
        const arrivalCard = document.createElement("div");
        arrivalCard.classList.add("timeline-card-item");
        arrivalCard.innerHTML = `
            <div class="icon-container">
                <i class="fa-solid ${modeIcons[currentMode]}"></i>
            </div>
            <div class="card-content">
                <span class="activity-name">Arrival ${currentMode === "flight" ? "to" : "drive to"} ${currentDestination}</span>
                <span class="activity-time">07:00 • 2h</span>
                <div class="activity-note"><i class="fa-solid fa-circle-info"></i> Fixed, cannot be modified</div>
            </div>
        `;
        timelineCardsContainer.appendChild(arrivalCard);

        // Other activities
        const activities = destinationData[currentDestination];
        originalActivities = JSON.parse(JSON.stringify(activities));

        activities.forEach(act => {
            const card = document.createElement("div");
            card.classList.add("timeline-card-item");
            card.innerHTML = `
                <div class="icon-container">
                    <i class="fa-solid ${act.icon}"></i>
                </div>
                <div class="card-content">
                    <span class="activity-name">${act.name}</span>
                    <span class="activity-time">${act.time}</span>
                </div>
                <div class="card-actions">
                    <i class="fa-solid fa-trash delete-card"></i>
                </div>
            `;
            timelineCardsContainer.appendChild(card);
        });

        // Add delete functionality
        timelineCardsContainer.querySelectorAll(".delete-card").forEach(btn => {
            btn.onclick = () => btn.closest(".timeline-card-item").remove();
        });
    }

   // Toggle button logic
// Inside your DOMContentLoaded function
// --- UPDATED TOGGLE LOGIC ---
// We attach the listener to the document because the card might be hidden or re-rendered
document.addEventListener("click", function (e) {
    // Check if the clicked element is our toggle button
    if (e.target && e.target.classList.contains("toggle-plan")) {
        const btn = e.target;
        // Find the plan-body inside the same card
        const planCard = btn.closest(".plan-card-inner");
        const planBody = planCard.querySelector(".plan-body");

        // Toggle the 'active' class
        const isNowVisible = planBody.classList.toggle("active");

        // Update the icon based on visibility
        if (isNowVisible) {
            btn.textContent = "^";
            // Ensure display is block
            planBody.style.display = "block";
        } else {
            btn.textContent = "v";
            // Ensure display is none
            planBody.style.display = "none";
        }
    }
});



    // Reset button
    resetBtn.addEventListener("click", renderTimeline);

    // Mode selection
    modeCards.forEach(card => {
        card.addEventListener("click", () => {
            modeCards.forEach(c => c.classList.remove("selected"));
            card.classList.add("selected");
            currentMode = card.dataset.mode;
            renderTimeline();
        });
    });

    // Custom dropdown selection
    options.forEach(option => {
        option.addEventListener("click", () => {
            selected.textContent = option.textContent;
            currentDestination = option.textContent;
            renderTimeline();
        });
    });

    // Initial render
    renderTimeline();

});