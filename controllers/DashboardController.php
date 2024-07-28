<?php

require_once __DIR__ . '/../models/Student.php';

class DashboardController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getDashboardData() {
        $student = new Student($this->db);
        $totalStudents = $student->getTotalStudents();
        $failedStudents = $student->getFailedStudents();
        $toppers = $student->getToppers();

        return [
            'totalStudents' => $totalStudents,
            'failedStudents' => $failedStudents,
            'toppers' => $toppers
        ];
    }
}