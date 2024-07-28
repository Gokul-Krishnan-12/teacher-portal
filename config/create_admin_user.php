<?php
require_once __DIR__ . '/db.php';

// Function to securely hash the password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Check if command-line arguments are provided
if (isset($argv[1]) && isset($argv[2])) {
    $username = $argv[1];
    $password = $argv[2];

    // Hash the password
    $hashedPassword = hashPassword($password);

    // SQL query to insert admin user into the database
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $hashedPassword);

    // Execute the query
    if ($stmt->execute()) {
        echo "Admin user '$username' created successfully.\n";
    } else {
        echo "Error creating admin user: " . $stmt->error . "\n";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Usage: php create_admin_user.php <username> <password>\n";
}
?>
