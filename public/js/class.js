// Function to fetch marks data from PHP backend
function fetchMarksData() {
    fetch('/api/marks')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Data successfully fetched, update classData with fetched data
            classData = data; // Update classData with fetched dat

            // Call any function that uses classData (e.g., populateClassDetails())
            populateClassDetails(); // Example function call

        })
        .catch(error => {
            console.error('Error fetching marks:', error);
            // Handle error scenario
        });
}


// Function to get the selected class name from localStorage
function getSelectedClassName() {
    return localStorage.getItem('selectedClassName') || "Class 1"; // Default value for testing
}

function addMark() {
    const studentName = document.getElementById('student-name').value;
    const studentSubject = document.getElementById('student-subject').value;
    const studentMarks = document.getElementById('student-marks').value;
    const className = document.getElementById('student-class').value;
    const division = document.getElementById('student-division').value;

    // Data validation (optional)
    if (!studentName || !studentSubject || !studentMarks || !className || !division) {
        alert('Please fill in all fields.');
        return;
    }

    // Prepare data for POST request
    const data = {
        name: studentName,
        subject: studentSubject,
        marks: parseInt(studentMarks),
        class: className,
        division: division
    };

    // Send POST request to backend API
    fetch('/api/addMarks', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(result => {
            console.log('New mark added successfully:', result);
            // Optionally, update UI or refresh student list
            fetchMarksData();
            closeAddStudentPopup(); // Close popup after adding new mark
        })
        .catch(error => {
            console.error('Error adding new mark:', error);
            // Handle error scenario
        });
}

function updateMark(markId) {

    const studentName = document.getElementById('student-name').value;
    const studentSubject = document.getElementById('student-subject').value;
    const studentMarks = document.getElementById('student-marks').value;
    const className = document.getElementById('student-class').value;
    const division = document.getElementById('student-division').value;

    // Data validation (optional)
    if (!markId) {
        return;
    }

    // Prepare data for POST request
    const data = {
        mark_id: markId,
        name: studentName,
        subject: studentSubject,
        marks: parseInt(studentMarks),
        class: className,
        division: division
    };

    // Send POST request to backend API
    fetch('/api/updateMarks', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(result => {
            // Optionally, update UI or refresh student list
            fetchMarksData();
            closeAddStudentPopup(); // Close popup after adding new mark
        })
        .catch(error => {
            console.error('Error adding new mark:', error);
            // Handle error scenario
        });
}

// Function to populate class details based on selected class name
function populateClassDetails() {
    const className = getSelectedClassName();
    const classInfo = classData[className];
    const classTitle = document.getElementById('class-name');
    const divisionSelect = document.getElementById('division');

    // Populate class name in the title
    classTitle.textContent = `Class ${className}`;

    // Clear and populate divisions dropdown
    divisionSelect.innerHTML = '';
    Object.keys(classInfo.divisions).forEach(division => {
        const option = document.createElement('option');
        option.value = division;
        option.textContent = division;
        divisionSelect.appendChild(option);
    });

    // Initially show students from the first division (if available)
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
    const studentTableBody = document.getElementById('student-table-body');
    studentTableBody.innerHTML = ''; // Clear previous content

    // Populate student list based on selected division
    students.forEach(student => {
        const row = document.createElement('tr');
        // Create a td element for student name
        const nameCell = document.createElement('td');
        nameCell.textContent = student.name;

        // Add first letter as data attribute to the nameCell
        const firstLetter = student.name.trim().charAt(0).toUpperCase(); // Get first letter and capitalize
        nameCell.setAttribute('data-first-letter', firstLetter);

        // Append nameCell to row
        row.appendChild(nameCell);

        row.innerHTML = `
        
            <td>${student.name}</td>
            <td>${student.subject}</td>
            <td>${student.marks}</td>
            <td>${student.class}</td>
            <td>${student.division}</td>
            <td><button onclick="editMark(${student.id})">Edit</button>&nbsp;<button onclick="deleteMark(${student.id})">Delete</button></td>
        `;
        studentTableBody.appendChild(row);
    });
}

// Function to navigate back to dashboard
function navigateBack() {
    window.location.href = '/';
}

// Function to open add new student popup
function openAddStudentPopup(edit = false, markId = null) {
    const popup = document.getElementById('add-student-popup');
    popup.style.display = 'block';

    // Prefill class and division fields
    const className = getSelectedClassName();
    const classSelect = document.getElementById('student-class');
    classSelect.value = className;

    const division = document.getElementById('division').value;
    const divisionSelect = document.getElementById('student-division');
    divisionSelect.value = division;

    // Conditionally update button behavior
    const addButton = document.getElementById('add-mark-btn');
    // Remove both event listeners for 'click' event
    addButton.removeEventListener('click', updateMark);
    addButton.removeEventListener('click', addMark);
    if (edit) {
        addButton.textContent = 'Update';
        addButton.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default form submission
            updateMark(markId); // Call updateMark function with markId
        });
    } else {
        addButton.textContent = 'Add';  
        addButton.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default form submission
            addMark(); // Call addMark function
        });
    }

}

// Function to fetch students from API based on class and division
function fetchStudents(className, division) {
    const url = '/api/fetchStudents';

    fetch(url, {
            method: 'POST', // Use POST method to send payload in the body
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                class: className,
                division: division
            }) // Convert payload to JSON string
        })
        .then(response => {
            // Assuming students is an array of student objects
            console.log('Fetched Students:', response);

            // Here you can handle the fetched data, e.g., update UI or perform further operations
        })
        .catch(error => {
            console.error('Error fetching students:', error);
            // Handle error scenario
        });
}

// Function to close add new student popup
function closeAddStudentPopup() {
    const popup = document.getElementById('add-student-popup');
    popup.style.display = 'none';
}

// Function to edit a student's mark
function editMark(markId) {

    fetch(`/api/mark/${markId}`, {
            method: 'GET',
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(mark => {
            console.log(mark);
            // Prefill the form with the selected student's data
            document.getElementById('student-name').value = mark[0].student_name; // Assuming mark.id is the student ID
            document.getElementById('student-subject').value = mark[0].subject;
            document.getElementById('student-marks').value = mark[0].marks;


            // Show the popup
            openAddStudentPopup(true, markId);
        })
        .catch(error => {
            console.error('Error fetching mark details:', error);
            // Handle error scenario
        });
}

// Function to delete a student's mark
function deleteMark(markId) {
    const confirmDelete = confirm('Are you sure you want to delete this mark?');
    if (confirmDelete) {


        fetch(`/api/marks/${markId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                // Reload the students after successful deletion
                fetchMarksData();
                showStudentsByDivision(division);
            })
            .catch(error => {
                console.error('Error deleting mark:', error);
                // Handle error scenario
            });
    }
}


// Populate class details on page load
window.onload = function() {
    fetchMarksData()
        .then(data => {
            classData = data;
            populateClassDetails();
        })
        .catch(error => {
            console.error('Error fetching marks:', error);
            // Handle error scenario
        });
};