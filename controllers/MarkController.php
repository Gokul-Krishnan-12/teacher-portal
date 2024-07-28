<?php

require_once __DIR__ . '/../models/Mark.php';

class MarkController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getMarks() {
        $marks = new Mark($this->db);
        $marksData = $marks->getAllMarks();
         // Group marks by class and division
        $classData = [];
        $studentIds =[];
        foreach ($marksData as $mark) {
            $class = $mark['class'];
            $division = $mark['division'];
          
            // Initialize class if not exists
            if (!isset($classData[$class])) {
                $classData[$class] = [
                    'totalStudents' => 0, // You can calculate total students dynamically if needed
                    'failedStudents' => 0, // You can calculate failed students dynamically if needed
                    'toppers' => [], // You can calculate toppers dynamically if needed
                    'divisions' => []
                ];
            }

            // Initialize division if not exists
            if (!isset($classData[$class]['divisions'][$division])) {
                $classData[$class]['divisions'][$division] = [];
            }
            // Add mark to the appropriate division
            $classData[$class]['divisions'][$division][] = [
                'id' => $mark['mark_id'],
                'name' => $mark['student_name'],
                'student_id' => $mark['student_id'],
                'subject' => $mark['subject'],
                'marks' => $mark['marks'],
                'class' => $mark['class'],
                'division' => $mark['division']
            ];

           

            // Calculate failedStudents count if needed
            if ($mark['failed'] == 1) {
                $classData[$class]['failedStudents']++;
            }

              // Check if student ID is not already in $studentIds array
              if (!in_array($mark['student_id'], $studentIds)) {
                $studentIds[] = $mark['student_id']; // Add student ID to the array
                 // Increment totalStudents count for the class
                $classData[$class]['totalStudents']++;
            }

        }
        


        return $classData;

    }

    public function getMarkById($id) {
        $marks = new Mark($this->db); 
        return $marks->getMarkById($id);

    }
    public function addMark($name, $subject, $marks, $class, $division) {
        $mark = new Mark($this->db);
        return $mark->addMark($name, $subject, $marks, $class, $division);
    }

    public function updateMark($id, $name, $subject, $marks, $class, $division) {
        $mark = new Mark($this->db);
        return $mark->updateMark($id, $name, $subject, $marks, $class, $division);
    }

    public function deleteMark($id) {
        $student = new Mark($this->db);
        return $student->deleteMark($id);
    }
}