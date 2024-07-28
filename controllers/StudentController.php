<?php

require_once __DIR__ . '/../models/Student.php';

class StudentController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getStudents()
    {
        $student = new Student($this->db);
        return $student->getAllStudents();
    }

    public function getStudentsByClassAndDivision($class, $division)
    {
        $student = new Student($this->db);
        return $student->getStudentsByClassAndDivision($class, $division);
    }

    public function addStudent($name, $subject, $marks, $class, $division)
    {
        $student = new Student($this->db);
        return $student->addStudent($name, $subject, $marks, $class, $division);
    }

    public function updateStudent($id, $name, $subject, $marks, $class, $division)
    {
        $student = new Student($this->db);
        return $student->updateStudent($id, $name, $subject, $marks, $class, $division);
    }

    public function getStudentsCount()
    {
        $student = new Student($this->db);
        return $student->getStudentsCount();
    }

    public function deleteStudent($id)
    {
        $student = new Student($this->db);
        return $student->deleteStudent($id);
    }
}
