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

            // Validate input
            if (isset($data['name']) && isset($data['subject']) && isset($data['marks']) && isset($data['class']) && isset($data['division'])) {
                // Further validation can be done here (e.g., ensuring marks is a number)
                $result = $studentController->addStudent($data['name'], $data['subject'], $data['marks'], $data['class'], $data['division']);
                echo json_encode(['success' => $result]);
            } else {
                http_response_code(400); // Bad Request
                echo json_encode(['error' => 'All fields are required']);
            }
        },
        'PUT' => function () use ($studentController) {
            // API endpoint for updating a student
            $data = json_decode(file_get_contents('php://input'), true);

            // Validate input
            if (isset($data['id']) && isset($data['name']) && isset($data['subject']) && isset($data['marks']) && isset($data['class']) && isset($data['division'])) {
                // Further validation can be done here
                $result = $studentController->updateStudent($data['id'], $data['name'], $data['subject'], $data['marks'], $data['class'], $data['division']);
                echo json_encode(['success' => $result]);
            } else {
                http_response_code(400); // Bad Request
                echo json_encode(['error' => 'All fields are required']);
            }
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
            $data = json_decode(file_get_contents('php://input'), true);

            // Validate input
            $class = isset($data['class']) ? $data['class'] : null;
            $division = isset($data['division']) ? $data['division'] : 'A';

            if ($class !== null && !is_numeric($class)) {
                http_response_code(400); // Bad Request
                echo json_encode(['error' => 'Class should be a numeric value.']);
                exit;
            }

            $students = $studentController->getStudentsByClassAndDivision($class, $division);

            if ($students === null) {
                http_response_code(404); // Not Found
                echo json_encode(['error' => 'No students found for the given parameters.']);
                exit;
            }

            echo json_encode($students);
        },
    ],
    '/api/addMarks' => [
        'POST' => function () use ($markController) {
            // API endpoint for adding marks
            $data = json_decode(file_get_contents('php://input'), true);

            // Validate input
            if (isset($data['name']) && isset($data['subject']) && isset($data['marks']) && isset($data['class']) && isset($data['division'])) {
                // Further validation can be done here
                $result = $markController->addMark($data['name'], $data['subject'], $data['marks'], $data['class'], $data['division']);
                echo json_encode(['success' => $result]);
            } else {
                http_response_code(400); // Bad Request
                echo json_encode(['error' => 'All fields are required']);
            }
        },
    ],
    '/api/marks/{id}' => [
        'DELETE' => function ($params) use ($markController) {
            // API endpoint for deleting a mark by ID
            $id = $params['id'];

            if (!is_numeric($id)) {
                http_response_code(400); // Bad Request
                echo json_encode(['error' => 'Invalid ID']);
                exit;
            }

            $result = $markController->deleteMark($id);

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
            // API endpoint for getting a mark by ID
            $id = $params['id'];

            if (!is_numeric($id)) {
                http_response_code(400); // Bad Request
                echo json_encode(['error' => 'Invalid ID']);
                exit;
            }

            $result = $markController->getMarkById($id);
            echo json_encode($result);
        },
    ],
    '/api/updateMarks' => [
        'POST' => function () use ($markController) {
            // API endpoint for updating a mark
            $data = json_decode(file_get_contents('php://input'), true);

            // Validate input
            if (isset($data['mark_id']) && isset($data['name']) && isset($data['subject']) && isset($data['marks']) && isset($data['class']) && isset($data['division'])) {
                // Further validation can be done here
                $result = $markController->updateMark($data['mark_id'], $data['name'], $data['subject'], $data['marks'], $data['class'], $data['division']);
                echo json_encode(['success' => $result]);
            } else {
                http_response_code(400); // Bad Request
                echo json_encode(['error' => 'All fields are required']);
            }
        },
    ],
    '/api/studentsCount' => [
        'GET' => function () use ($studentController) {
            // API endpoint for getting student count
            $studentsCount = $studentController->getStudentsCount();
            echo json_encode($studentsCount);
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
        $params = array_filter($matches, function ($key) {
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
