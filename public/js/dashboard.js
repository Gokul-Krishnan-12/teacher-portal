function fetchMarksData() {
  fetch("/api/marks")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      populateClassCards(data);
    })
    .catch((error) => {
      console.error("Error fetching marks:", error);
      // Handle error scenario
    });
}

function fetchStudentsCount() {
  fetch("/api/studentsCount")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      console.log("data", data);
      populateTotalClassCards(data);
    })
    .catch((error) => {
      console.error("Error fetching marks:", error);
      // Handle error scenario
    });
}

// Function to populate the class cards dynamically
function populateTotalClassCards(data) {
  const dashboardCards = document.getElementById("dashboard-cards");

  data.forEach((classInfo) => {
    const className = classInfo.class;
    const totalStudents = classInfo.total_students;

    const card = document.createElement("div");
    card.classList.add("class-card");
    card.innerHTML = `
            <h3>Class: ${className}</h3>
            <p>Total Students: <span>${totalStudents}</span></p>
        `;
    card.onclick = () => navigateToClassDetails(className);
    dashboardCards.appendChild(card);
  });

  // Populate overall statistics
  document.getElementById("total-students").textContent =
    calculateTotalStudents(data);
  populateTotalPieChart(data);
}

// Function to populate the class cards dynamically
function populateClassCards(classData) {
  // Populate overall statistics
  document.getElementById("failed-students").textContent =
    calculateFailedStudents(classData);
  document.getElementById("total-teachers").textContent =
    calculateTotalTeachers(); // hardcoded right now

  // Populate pie chart
  populateFailedPieChart(classData);
}

// Function to calculate total students across all classes
function calculateTotalStudents(data) {
    let total = 0;
    data.forEach((classInfo) => {
      total += classInfo.total_students || 0; // Ensure to handle cases where total_students might be undefined
    });
    return total;
  }

// Function to calculate total failed students across all classes
function calculateFailedStudents(classData) {
  let total = 0;
  Object.keys(classData).forEach((className) => {
    total += classData[className].failedStudents || 0; // Handle cases where failedStudents might not be defined
  });
  return total;
}

// Function to calculate total number of teachers
function calculateTotalTeachers() {
  // Implement your logic to calculate total number of teachers
  return 5; // Example number, replace with actual logic
}

// Function to populate the pie chart
function populateFailedPieChart(classData) {
  const labels = Object.keys(classData);
  const dataFailed = labels.map(
    (className) => classData[className].failedStudents || 0
  );
  const backgroundColors = [
    "#E57373", // Dark Red
    "#64B5F6", // Dark Blue
    "#81C784", // Dark Green
    "#FFD54F", // Dark Yellow
    "#F06292", // Dark Pink
    "#B0BEC5", // Dark Grayish Blue
  ];

  new Chart(document.getElementById("pieFailedChart"), {
    type: "pie",
    data: {
      labels: labels,
      datasets: [
        {
          label: "Failed Students by Class",
          backgroundColor: backgroundColors.slice(0, labels.length),
          data: dataFailed,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      legend: {
        position: "right",
      },
    },
  });
}

function populateTotalPieChart(classData) {
  const labels = classData.map((classInfo) => classInfo.class);
  const data = classData.map((classInfo) => classInfo.total_students || 0);
  const backgroundColors = [
    "#FF6384",
    "#36A2EB",
    "#FFCE56",
    "#8e5ea2",
    "#3cba9f",
    "#e8c3b9",
  ];

  new Chart(document.getElementById("pieTotalChart"), {
    type: "pie",
    data: {
      labels: labels,
      datasets: [
        {
          label: "Total Students by Class",
          backgroundColor: backgroundColors.slice(0, labels.length),
          data: data,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      legend: {
        position: "right",
      },
    },
  });
}

// Function to navigate to class details page
function navigateToClassDetails(className) {
  // Store the selected class name in localStorage (you can use sessionStorage as well)
  localStorage.setItem("selectedClassName", className);
  // Redirect to class-details.html
  window.location.href = "class-details";
}

// Logout functionality
document.getElementById("logout-btn").addEventListener("click", function () {
  fetch("/logout", {
    method: "POST",
  })
    .then((response) => {
      if (response.ok) {
        window.location.href = "/login"; // Redirect to login page
      } else {
        console.error("Logout failed");
      }
    })
    .catch((error) => console.error("Error during logout:", error));
});

// Function to fetch students count and marks data on page load
function onPageLoad() {
  fetchStudentsCount();
  fetchMarksData();
}

// Assign onPageLoad function to window.onload
window.onload = onPageLoad;
