<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Details</title>
    <link rel="stylesheet" href="/css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            color: #333;
            margin: 0;
            padding: 0;
        }

        main.container {
            padding: 20px;
        }

        h2 {
            color: #333;
            font-size: 1.8rem;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .class-info {
            margin-top: 20px;
        }

        #division-select {
            margin-bottom: 10px;
        }

        select#division {
            width: 70px;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: white;
            transition: border-color 0.3s ease-in-out;
            box-sizing: border-box;
        }

        select#division:hover,
        select#division:focus {
            outline: none;
            border-color: #4CAF50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        th,
        td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        .popup {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1;
            overflow: auto;
        }

        .popup-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 8px;
            position: relative;
        }

        .popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .popup-header h3 {
            margin: 0;
            font-size: 1.5rem;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group select {
            width: 100%;
            padding: 8px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group.inline {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .form-group.inline>* {
            flex: 1;
            margin-right: 10px;
        }

        .form-group.inline>*:last-child {
            margin-right: 0;
        }

        th {
            background-color: #c7b7b7;
            font-weight: bold;
        }

        /* Styling for first letter in circle */
        td:first-child::before {
            content: attr(data-initial);
            display: inline-block;
            width: 1.5em;
            height: 1.5em;
            line-height: 1.5em;
            text-align: center;
            border-radius: 50%;
            background-color: #4CAF50;
            color: white;
            margin-right: 0.5em;
            font-weight: bold;
        }

        /* Padding for add new mark button */
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        /* Styling for dropdowns */
        .select-style select {
            width: 100%;
            padding: 8px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .header-info {
            width: 100%;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body>
    <header>
        <h1>Class Details</h1>
        <button onclick="navigateBack()">Back to Dashboard</button>
    </header>
    <main class="container">
        <section id="class-details">
            <h2 id="class-name">Class</h2>
            <div class="class-info">
                <div class="header-info">
                    <div id="division-select">
                        <label for="division">Select Division:</label>
                        <select id="division" onchange="showStudentsByDivision(this.value)">
                            <!-- Options will be dynamically populated -->
                        </select>
                    </div>
                    <button onclick="openAddStudentPopup()">Add New Mark</button>
                </div>
                <table id="student-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Subject</th>
                            <th>Marks</th>
                            <th>Class</th>
                            <th>Division</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="student-table-body">
                        <!-- Data will be dynamically populated -->
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- Add New Student Popup -->
    <div id="add-student-popup" class="popup">
        <div class="popup-content">
            <div class="popup-header">
                <h3>Add New Mark</h3>
                <span class="close" onclick="closeAddStudentPopup()">&times;</span>
            </div>
            <form id="add-student-form" onsubmit="addStudent(event)">
                <div class="form-group">
                    <label for="student-name">Name:</label>
                    <input type="text" id="student-name" name="student-name" required>
                </div>
                <div class="form-group inline">
                    <div>
                        <label for="student-class">Class:</label>
                        <input type="text" id="student-class" name="student-class" disabled>
                    </div>
                    <div>
                        <label for="student-division">Division:</label>
                        <input type="text" id="student-division" name="student-division" required>
                    </div>
                </div>
                <div class="form-group inline">
                    <div>
                        <label for="student-subject">Subject:</label>
                        <input type="text" id="student-subject" name="student-subject" required>
                    </div>
                    <div>
                        <label for="student-marks">Marks:</label>
                        <input type="number" id="student-marks" name="student-marks" required min="0">
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" id="add-mark-btn">Add</button>
                </div>
            </form>
        </div>
    </div>
    <script src="/js/class.js"></script>
</body>

</html>
