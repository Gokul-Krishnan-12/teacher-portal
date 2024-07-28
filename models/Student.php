<?php

class Student
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Function to fetch all students
    public function getAllStudents()
    {
        $sql = "SELECT * FROM students";
        $result = $this->db->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    // Function to add a new student
    public function addStudent($name, $class, $division)
    {
        $stmt = $this->db->prepare("INSERT INTO students (student_name, class, division) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $name, $class, $division);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Function to fetch students by class and division
    //add this to prefill the add mark modal
    public function getStudentsByClassAndDivision($class, $division)
    {
        $stmt = $this->db->prepare("SELECT m.id AS mark_id, m.student_id, m.subject, m.marks, m.failed,
                                s.student_name, s.class, s.division
                                FROM marks m
                                JOIN students s ON m.student_id = s.id
                                ORDER BY m.updated_at");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    }

    // Function to update a student
    public function updateStudent($id, $name, $class, $division)
    {
        $stmt = $this->db->prepare("UPDATE students SET name = ?, class = ?, division = ? WHERE id = ?");
        $stmt->bind_param("sisi", $name, $class, $division, $id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    //Function to get the students count
    public function getStudentsCount()
    {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) AS total_students,
                class,
                COUNT(*) AS students_in_class
            FROM 
                students
            GROUP BY 
                class
        ");
    
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    // Function to check if a student exists by ID
    public function studentExists($id)
    {
    }

    // Close database connection
    public function __destruct()
    {
    }
}

?>
