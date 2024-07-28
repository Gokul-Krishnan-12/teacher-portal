<?php
require_once 'config/db.php'; // Include your database connection setup

// Include all migration files
require_once 'create_students_table.php';
require_once 'create_student_marks_table.php';
require_once 'create_users_table.php';


// Run each migration
$migration1 = new CreateUsersTable($conn);
$migration1->up();

$migration2 = new CreateStudentsTable($conn);
$migration2->up();

$migration2 = new CreateMarksTable($conn);
$migration2->up();

// Close database connection
$conn->close();

echo "All migrations executed successfully.\n";
?>
