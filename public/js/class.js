// Function to fetch marks data from the backend API
async function fetchMarksData() {
  try {
    const response = await fetch("/api/marks");
    if (!response.ok) {
      throw new Error("Network response was not ok");
    }
    const data = await response.json();
    classData = data; // Update classData with fetched data
    populateClassDetails(); // Populate class details with fetched data
  } catch (error) {
    console.error("Error fetching marks:", error);
    // Handle error scenario, e.g., display an error message
  }
}

// Function to get the selected class name from localStorage
function getSelectedClassName() {
  return localStorage.getItem("selectedClassName") || "Class 1"; // Default value for testing
}

// Function to add a new mark
async function addMark() {
  const studentName = document.getElementById("student-name").value.trim();
  const studentSubject = document
    .getElementById("student-subject")
    .value.trim();
  const studentMarks = document.getElementById("student-marks").value.trim();
  const className = document.getElementById("student-class").value.trim();
  const division = document.getElementById("student-division").value.trim();

  // Validate input data
  if (
    !studentName ||
    !studentSubject ||
    isNaN(studentMarks) ||
    !className ||
    !division
  ) {
    alert("Please fill in all fields with valid data.");
    return;
  }

  const data = {
    name: studentName,
    subject: studentSubject,
    marks: parseInt(studentMarks, 10),
    class: className,
    division: division,
  };

  try {
    const response = await fetch("/api/addMarks", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    });

    if (!response.ok) {
      throw new Error("Network response was not ok");
    }

    const result = await response.json();
    console.log("New mark added successfully:", result);
    fetchMarksData(); // Refresh the data after adding a new mark
    closeAddStudentPopup(); // Close popup after adding new mark
  } catch (error) {
    console.error("Error adding new mark:", error);
    // Handle error scenario, e.g., display an error message
  }
}

// Function to update an existing mark
async function updateMark(markId) {
  if (!markId) {
    console.error("Invalid mark ID");
    return;
  }

  const studentName = document.getElementById("student-name").value.trim();
  const studentSubject = document
    .getElementById("student-subject")
    .value.trim();
  const studentMarks = document.getElementById("student-marks").value.trim();
  const className = document.getElementById("student-class").value.trim();
  const division = document.getElementById("student-division").value.trim();

  // Validate input data
  if (
    !studentName ||
    !studentSubject ||
    isNaN(studentMarks) ||
    !className ||
    !division
  ) {
    alert("Please fill in all fields with valid data.");
    return;
  }

  const data = {
    mark_id: markId,
    name: studentName,
    subject: studentSubject,
    marks: parseInt(studentMarks, 10),
    class: className,
    division: division,
  };

  try {
    const response = await fetch("/api/updateMarks", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    });

    if (!response.ok) {
      throw new Error("Network response was not ok");
    }

    await response.json();
    fetchMarksData(); // Refresh the data after updating a mark
    closeAddStudentPopup(); // Close popup after updating mark
  } catch (error) {
    console.error("Error updating mark:", error);
    // Handle error scenario, e.g., display an error message
  }
}

// Function to populate class details based on the selected class name
function populateClassDetails() {
  const className = getSelectedClassName();
  const classInfo = classData[className];
  const classTitle = document.getElementById("class-name");
  const divisionSelect = document.getElementById("division");

  classTitle.textContent = `Class ${className}`; // Set class title

  // Populate divisions dropdown
  divisionSelect.innerHTML = "";
  Object.keys(classInfo.divisions).forEach((division) => {
    const option = document.createElement("option");
    option.value = division;
    option.textContent = division;
    divisionSelect.appendChild(option);
  });

  // Initially show students from the first division
  const firstDivision = Object.keys(classInfo.divisions)[0];
  if (firstDivision) {
    showStudentsByDivision(firstDivision);
  }
}

// Function to show students by division
function showStudentsByDivision(division) {
  const className = getSelectedClassName();
  const classInfo = classData[className];
  const students = classInfo.divisions[division];
  const studentTableBody = document.getElementById("student-table-body");

  studentTableBody.innerHTML = ""; // Clear previous content

  students.forEach((student) => {
    const row = document.createElement("tr");
    const firstLetter = student.name.charAt(0).toUpperCase();
    row.innerHTML = `
            <td data-initial="${firstLetter}">${student.name}</td>
            <td>${student.subject}</td>
            <td>${student.marks}</td>
            <td>${student.class}</td>
            <td>${student.division}</td>
            <td>
                <button onclick="editMark(${student.id})">Edit</button>
                <button onclick="deleteMark(${student.id})">Delete</button>
            </td>
        `;
    studentTableBody.appendChild(row);
  });
}

// Function to navigate back to the dashboard
function navigateBack() {
  window.location.href = "/";
}

// Function to open the add new student popup
function openAddStudentPopup(edit = false, markId = null) {
  const popup = document.getElementById("add-student-popup");
  popup.style.display = "block";

  // Prefill class and division fields
  const className = getSelectedClassName();
  document.getElementById("student-class").value = className;
  document.getElementById("student-division").value =
    document.getElementById("division").value;

  const addButton = document.getElementById("add-mark-btn");
  addButton.textContent = edit ? "Update" : "Add";

  // Remove existing event listeners
  addButton.removeEventListener("click", addMark);
  addButton.removeEventListener("click", updateMark);

  // Add appropriate event listener based on edit mode
  if (edit) {
    addButton.addEventListener("click", function (event) {
      event.preventDefault();
      updateMark(markId);
    });
  } else {
    addButton.addEventListener("click", function (event) {
      event.preventDefault();
      addMark();
    });
  }
}

// Function to close the add new student popup
function closeAddStudentPopup() {
  const popup = document.getElementById("add-student-popup");
  popup.style.display = "none";
}

// Function to edit a student's mark
async function editMark(markId) {
  try {
    const response = await fetch(`/api/mark/${markId}`);
    if (!response.ok) {
      throw new Error("Network response was not ok");
    }

    const mark = await response.json();
    // Prefill the form with the selected student's data
    document.getElementById("student-name").value = mark[0].student_name;
    document.getElementById("student-subject").value = mark[0].subject;
    document.getElementById("student-marks").value = mark[0].marks;

    openAddStudentPopup(true, markId);
  } catch (error) {
    console.error("Error fetching mark details:", error);
    // Handle error scenario, e.g., display an error message
  }
}

// Function to delete a student's mark
async function deleteMark(markId) {
  if (!confirm("Are you sure you want to delete this mark?")) {
    return; // Exit if user cancels the confirmation
  }

  try {
    const response = await fetch(`/api/marks/${markId}`, {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json",
      },
    });

    if (!response.ok) {
      throw new Error("Network response was not ok");
    }

    await response.json();
    fetchMarksData(); // Refresh data after deletion
  } catch (error) {
    console.error("Error deleting mark:", error);
    // Handle error scenario, e.g., display an error message
  }
}

// Populate class details on page load
window.onload = function () {
  fetchMarksData(); // Fetch and display data on page load
};
