<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Portal - Home</title>
    <link rel="stylesheet" href="/css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header h1 {
            margin: 0;
            font-size: 1.5rem;
        }

        button#logout-btn {
            background-color: #f44336;
            /* Red background color */
            color: #fff;
            /* White text color */
            border: none;
            padding: 12px 20px;
            /* Increased padding for larger clickable area */
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
            /* Smooth transition */
            font-size: 16px;
            /* Adjust font size for readability */
            font-weight: bold;
            /* Bold font for emphasis */
            text-transform: uppercase;
            /* Uppercase text */
            outline: none;
            /* Remove default focus outline */
        }

        /* Hover state */
        button#logout-btn:hover {
            background-color: #d32f2f;
            /* Darken background color on hover */
        }

        /* Focus state */
        button#logout-btn:focus {
            box-shadow: 0 0 0 3px rgba(244, 67, 54, 0.5);
            /* Custom focus outline */
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

        .dashboard-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5%;
            gap: 10px;
        }

        @media (max-width: 768px) {
            .dashboard-stats {
                flex-direction: column;
                margin-bottom: 15%;
            }
        }

        .stat-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            flex: 1 0 30%;
            text-align: center;
            display: flex;
            flex-direction: row;
            justify-content: space-around;
            align-items: center;

        }

        .stat-card h4 {
            margin-bottom: 15px;
            font-size: 1.2rem;
            color: #555;
        }

        .stat-number {
            font-size: 4rem;
            /* Increased size as requested */
            color: #333;
            /* Darker color for better visibility */
            font-weight: bold;
            margin: 0;
        }

        .pie-chart {
            text-align: center;
            margin-top: 20px;
        }

        .pie-chart canvas {
            max-width: 100%;
            height: auto;
            margin: 0 auto;
        }

        .class-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            flex: 1 0 30%;
            cursor: pointer;
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }

        .dashboard-cards {
            display: flex;
            gap: 10px;
            flex-direction: column;

        }

        @media (min-width: 768px) {
            .dashboard-cards {
                flex-direction: row;
                flex-wrap: wrap;
            }

            .class-card {
                max-width: 33%;
            }
        }

        .class-card:hover {
            transform: translateY(-5px);
        }

        .class-card h3 {
            margin-bottom: 10px;
            font-size: 1.3rem;
            color: #333;
        }

        .class-card p {
            font-size: 1rem;
            color: #666;
            margin-bottom: 0;
        }

        .section-container {
            display: flex;
            flex: 1;
            gap: 10px;
        }

        .class-container {
            margin-top: 50px;
        }

        .chart-section {
            background-color: #fff;
            border-radius: 9px;
            border-radius: 9px;
            padding: 20px;
            width: 50%;
            flex: 1;
        }
    </style>
</head>

<body>
    <header>
        <h1>Teacher Portal</h1>
        <button id="logout-btn">Logout</button>
    </header>
    <main class="container">
        <section id="dashboard">
            <h2>Dashboard</h2>
            <div class="dashboard-stats">
                <div class="stat-card">
                    <p id="total-students" class="stat-number">0</p>
                    <h4>Total Students</h4>
                </div>
                <div class="stat-card">
                    <p id="failed-students" class="stat-number">0</p>
                    <h4>Failed Students</h4>
                </div>
                <div class="stat-card">
                    <p id="total-teachers" class="stat-number">0</p>
                    <h4>Teachers</h4>
                </div>
            </div>
            <div class="section-container">
                <div class="chart-section">
                    <h3>Total Count</h3>
                    <div class="pie-chart">
                        <canvas id="pieTotalChart"></canvas>
                    </div>
                </div>
                <div class="chart-section">
                    <h3>Failed Count</h3>
                    <div class="pie-chart">
                        <canvas id="pieFailedChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="class-container">
                <h3>Class</h3>
                <div class="dashboard-cards" id="dashboard-cards">
                    <!-- Class cards will be dynamically generated here -->
                </div>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/js/dashboard.js"></script>
</body>

</html>