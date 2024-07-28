<?php
require_once 'config/db.php'; // Include your database connection setup

require_once __DIR__ . '/../../models/Mark.php';
require_once __DIR__ . '/../../models/Student.php';


class DatabaseSeeder {
    
    private $db;
    private $mark;
    private $student;

    public function __construct($db) {

        $this->db = $db;

        $this->mark = new Mark($this->db);
        $this->student = new Student($this->db);
    }

    public function seed() {
        // Seed students
        $this->seedStudents();

        // Seed marks
        $this->seedMarks();

        echo "Seeding completed successfully.\n";
    }

    private function seedStudents() {
        $studentsData = [
             // Class 8
            ['student_name' => 'John Doe', 'class' => '8', 'division' => 'A'],
            ['student_name' => 'Jane Smith', 'class' => '8', 'division' => 'A'],
            ['student_name' => 'Michael Johnson', 'class' => '8', 'division' => 'B'],
            ['student_name' => 'Emily Davis', 'class' => '8', 'division' => 'B'],
            ['student_name' => 'William Brown', 'class' => '8', 'division' => 'B'],
            
            // Class 9
            ['student_name' => 'Sophia Wilson', 'class' => '9', 'division' => 'A'],
            ['student_name' => 'Daniel Martinez', 'class' => '9', 'division' => 'A'],
            ['student_name' => 'Olivia Garcia', 'class' => '9', 'division' => 'B'],
            ['student_name' => 'James Lopez', 'class' => '9', 'division' => 'B'],
            ['student_name' => 'Isabella Rodriguez', 'class' => '9', 'division' => 'B'],
            
            // Class 10
            ['student_name' => 'Alexander Thomas', 'class' => '10', 'division' => 'A'],
            ['student_name' => 'Mia Hernandez', 'class' => '10', 'division' => 'A'],
            ['student_name' => 'Ethan Moore', 'class' => '10', 'division' => 'B'],
            ['student_name' => 'Emma Lee', 'class' => '10', 'division' => 'B'],
            ['student_name' => 'Noah Walker', 'class' => '10', 'division' => 'B'],
        ];

        foreach ($studentsData as $data) {
            $this->student->addStudent($data['student_name'], $data['class'], $data['division']);
        }
    }

    private function seedMarks() {
        // Example data: marks for each subject
        $marksData = [
            ['subject' => 'Math', 'marks' => [75, 82, 60, 88, 95]],
            ['subject' => 'Science', 'marks' => [25, 72, 85, 79, 91]],
            ['subject' => 'English', 'marks' => [80, 77, 65, 70, 84]],
        ];
    
        // Get all students
        $students = $this->student->getAllStudents();
    
        foreach ($students as $student) {
            // Assign random marks for each subject
            foreach ($marksData as $mark) {
                // Select a random mark from the marks array
                $randomMark = $mark['marks'][array_rand($mark['marks'])];
    
                // Insert mark into database
                $this->mark->addMark($student['student_name'], $mark['subject'], $randomMark,$student['class'],$student['division']);
            }
        }
    }

    public function __destruct() {
        $this->db->close();
    }
}

// Usage example
$seeder = new DatabaseSeeder($conn);
$seeder->seed();

?>
