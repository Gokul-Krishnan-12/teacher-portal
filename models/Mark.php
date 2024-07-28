<?php

class Mark
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllMarks()
    {
        $stmt = $this->db->prepare("SELECT m.id AS mark_id, m.student_id, m.subject, m.marks, m.failed,
                                s.student_name, s.class, s.division
                                FROM marks m
                                JOIN students s ON m.student_id = s.id
                                ORDER BY m.updated_at desc");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getMarkById($id)
    {
        $stmt = $this->db->prepare("SELECT m.id AS mark_id, m.student_id, m.subject, m.marks, m.failed,
                                s.student_name, s.class, s.division
                                FROM marks m
                                JOIN students s ON m.student_id = s.id
                                WHERE m.id = ?
                                ");
        // Bind the parameter and execute the statement
        $stmt->bind_param('s', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function addMark($student_name, $subject, $marks, $class, $division)
    {
        // Check if the student exists and get their ID
        $student_id = $this->getOrCreateStudent($student_name, $class, $division);
        if (!$student_id) {
            return false; // Student creation or retrieval failed
        }

        // Determine if mark is a fail
        $failed = ($marks < 30) ? 1 : 0;

        // Check if a mark already exists for this student and subject
        $stmt_check = $this->db->prepare("SELECT id FROM marks WHERE student_id = ? AND subject = ?");
        $stmt_check->bind_param("is", $student_id, $subject);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($result->num_rows > 0) {
            // Update existing mark
            $stmt_update = $this->db->prepare("UPDATE marks SET marks = ?, failed = ? WHERE student_id = ? AND subject = ?");
            $stmt_update->bind_param("iiis", $marks, $failed, $student_id, $subject);
            $success = $stmt_update->execute();
        } else {
            // Insert new mark
            $stmt_insert = $this->db->prepare("INSERT INTO marks (student_id, subject, marks, failed) VALUES (?, ?, ?, ?)");
            $stmt_insert->bind_param("isii", $student_id, $subject, $marks, $failed);
            $success = $stmt_insert->execute();
        }

        return $success;
    }

    public function updateMark($id, $student_name, $subject, $marks, $class, $division)
    {
        // Retrieve the student_id associated with the given mark ID
        $stmt_get_student_id = $this->db->prepare("SELECT student_id FROM marks WHERE id = ?");
        $stmt_get_student_id->bind_param("i", $id);
        $stmt_get_student_id->execute();
        $result = $stmt_get_student_id->get_result();

        if ($result->num_rows === 0) {
            return false; // No mark found with the given ID
        }

        // Fetch student_id from the result
        $mark = $result->fetch_assoc();
        $student_id = $mark['student_id'];
        // Update the student's name in the students table
        $stmt_update_student = $this->db->prepare("UPDATE students SET student_name = ?, class = ?, division = ? WHERE id = ?");
        $stmt_update_student->bind_param("sssi", $student_name, $class, $division, $student_id);
        $update_student_success = $stmt_update_student->execute();

        if (!$update_student_success) {
            return false; // Failed to update student name
        }

        // Update the mark details
        $stmt_update_mark = $this->db->prepare("UPDATE marks SET subject = ?, marks = ?, failed = ? WHERE id = ?");
        $failed = ($marks < 30) ? 1 : 0; // Determine if mark is a fail
        $stmt_update_mark->bind_param("siii", $subject, $marks, $failed, $id);
        $update_mark_success = $stmt_update_mark->execute();

        return $update_mark_success;
    }


    public function deleteMark($id)
    {
        $stmt = $this->db->prepare("DELETE FROM marks WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getTotalMarks()
    {
    }

    public function getFailedMarks()
    {
    }

    public function getToppers()
    {
    }

    private function getOrCreateStudent($student_name, $class, $division)
    {
        // Check if the student already exists
        $stmt = $this->db->prepare("SELECT id FROM students WHERE student_name = ? AND class = ? AND division = ?");
        $stmt->bind_param("sss", $student_name, $class, $division);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if ($result) {
            return $result['id']; // Return existing student id
        } else {
            // Insert new student and return the generated id
            $stmt = $this->db->prepare("INSERT INTO students (student_name, class, division) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $student_name, $class, $division);
            if ($stmt->execute()) {
                return $stmt->insert_id; // Return the id of the newly inserted student
            } else {
                return false; // Insertion failed
            }
        }
    }
}
