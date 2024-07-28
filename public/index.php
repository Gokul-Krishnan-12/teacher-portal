<?php
session_start();

// Include necessary files
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/StudentController.php';
require_once __DIR__ . '/../controllers/MarkController.php';
require_once __DIR__ . '/../controllers/DashboardController.php';

// Initialize controllers
$authController = new AuthController($conn);
$studentController = new StudentController($conn);
$markController = new MarkController($conn);
$dashboardController = new DashboardController($conn);

// Define your routes and their handlers
$routes = [
    '/' => function () use ($authController) {
        // Home route handler
        if (isset($_SESSION['user_id'])) {
            require __DIR__ . '/../views/home.php';
        } else {
            header('Location: /login');
        }
    },
    '/login' => [
        'GET' => function () {
            // Login GET handler
            require __DIR__ . '/../views/login.php';
        },
        'POST' => function () use ($authController) {
            // Login POST handler
            $data = json_decode(file_get_contents('php://input'), true);

            // Validate input
            if (isset($data['username']) && isset($data['password'])) {
                $result = $authController->login($data['username'], $data['password']);
                if (!$result) {
                    http_response_code(401); // Unauthorized
                    echo json_encode(['error' => 'Invalid username or password']);
                }
            } else {
                http_response_code(400); // Bad request
                echo json_encode(['error' => 'Username and password are required']);
            }
        }
    ],
    '/logout' => function () use ($authController) {
        // Logout handler
        $authController->logout();
        header('Location: /login');
    },
    '/class-details' => function () {
        // Class details handler
        if (isset($_SESSION['user_id'])) {
            require __DIR__ . '/../views/class-details.php';
        } else {
            header('Location: /login');
        }
    },
    '/api/students' => [
        'GET' => function () use ($studentController) {
            // API endpoint for getting students
            echo json_encode($studentController->getStudents());
        },
        'POST' => function () use ($studentController) {
            // API endpoint for adding a student
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $studentController->addStudent($data['name'], $data['subject'], $data['marks'], $data['class'], $data['division']);
            echo json_encode(['success' => $result]);
        },
        'PUT' => function () use ($studentController) {
            // API endpoint for updating a student
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $studentController->updateStudent($data['id'], $data['name'], $data['subject'], $data['marks'], $data['class'], $data['division']);
            echo json_encode(['success' => $result]);
        }
    ],
    '/api/marks' => [
        'GET' => function () use ($markController) {
            // API endpoint for getting marks
            echo json_encode($markController->getMarks());
        },
    ],
    '/api/fetchStudents' => [
        'POST' => function () use ($studentController) {
            // API endpoint for fetching students by class and division
            $json_data = file_get_contents('php://input');
            $data = json_decode($json_data, true);

            // Retrieve class and division from payload
            $class = isset($data['class']) ? $data['class'] : null;
            $division = isset($data['division']) ? $data['division'] : 'A';

            // Example validation for class (assuming it should be a numeric value)
            if ($class !== null && !is_numeric($class)) {
                http_response_code(400); // Bad Request
                echo json_encode(['error' => 'Class should be a numeric value.']);
                exit;
            }

            // Fetch students from controller based on class and division
            $students = $studentController->getStudentsByClassAndDivision($class, $division);

            // Check if students are retrieved successfully
            if ($students === null) {
                http_response_code(404); // Not Found
                echo json_encode(['error' => 'No students found for the given parameters.']);
                exit;
            }

            // Respond with JSON data
            echo json_encode($students);
        },
    ],
    
    '/api/addMarks' => [
        'POST' => function () use ($markController) {
            // API endpoint for fetching students by class and division
            $json_data = file_get_contents('php://input');
            $data = json_decode($json_data, true);

            // Retrieve class and division from payload
            $name = isset($data['name']) ? $data['name'] : null;
            $subject = isset($data['subject']) ? $data['subject'] : null;
            $marks = isset($data['marks']) ? $data['marks'] : null;
            $class = isset($data['class']) ? $data['class'] : null;
            $division = isset($data['division']) ? $data['division'] : 'A';


            // Fetch students from controller based on class and division
            $marks = $markController->addMark($name, $subject, $marks, $class, $division);

            // Check if students are retrieved successfully
            if ($marks === null) {
                http_response_code(404); // Not Found
                echo json_encode(['error' => 'Data not found.']);
                exit;
            }

            // Respond with JSON data
            echo json_encode($marks);
        },
    ],
    '/api/marks/{id}' => [
        'DELETE' => function ($params) use ($markController) {
            // API endpoint for deleting a mark by ID
            $id = $params['id'];
            $result = $markController->deleteMark($id);

            // Check result and respond accordingly
            if ($result === true) {
                echo json_encode(['success' => true]);
            } elseif ($result === false) {
                http_response_code(404); // Not Found
                echo json_encode(['error' => 'Mark not found']);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(['error' => 'Failed to delete mark']);
            }
        },
    ],
    '/api/mark/{id}' => [
        'GET' => function ($params) use ($markController) {
            // API endpoint for deleting a mark by ID
            $id = $params['id'];
            $result = $markController->getMarkById($id);

            echo  json_encode($result);
        },
    ],
    '/api/updateMarks' => [
        'POST' => function ($params) use ($markController) {
            // API endpoint for fetching students by class and division
            $json_data = file_get_contents('php://input');
            $data = json_decode($json_data, true);

            // Retrieve class and division from payload
            $id = isset($data['mark_id']) ? $data['mark_id'] : null;
            $name = isset($data['name']) ? $data['name'] : null;
            $subject = isset($data['subject']) ? $data['subject'] : null;
            $marks = isset($data['marks']) ? $data['marks'] : null;
            $class = isset($data['class']) ? $data['class'] : null;
            $division = isset($data['division']) ? $data['division'] : 'A';
            

             // Fetch students from controller based on class and division
             $marks = $markController->updateMark($id, $name, $subject, $marks, $class, $division);

            echo  json_encode($marks);
        },
    ],
    '/api/studentsCount' => [
        'GET' => function ($params) use ($studentController) {

             // Fetch students from controller based on class and division
             $studentsCount = $studentController->getStudentsCount();

            echo  json_encode($studentsCount);
        },
    ],
];


// Retrieve request URI and method
$request = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Remove query string from the request URI
$request_uri_parts = explode('?', $request);
$request_uri = $request_uri_parts[0];

// Flag to check if a route was matched
$routeMatched = false;

foreach ($routes as $route => $handler) {
    // Convert route to regex pattern
    $pattern = str_replace('/', '\/', $route);
    $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^\/]+)', $pattern);
    $pattern = '/^' . $pattern . '$/';

    if (preg_match($pattern, $request_uri, $matches)) {
        $routeMatched = true;
        
        // Remove numeric keys from matches
        $params = array_filter($matches, function($key) {
            return !is_numeric($key);
        }, ARRAY_FILTER_USE_KEY);

        if (is_callable($handler)) {
            $handler($params);
        } elseif (is_array($handler) && isset($handler[$method]) && is_callable($handler[$method])) {
            $handler[$method]($params);
        } else {
            // Method Not Allowed
            http_response_code(405);
            echo json_encode(['error' => 'Method Not Allowed']);
        }
        break;
    }
}

if (!$routeMatched) {
    // Not Found
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
}

?>