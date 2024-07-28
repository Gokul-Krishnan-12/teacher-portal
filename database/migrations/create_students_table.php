<?php

require_once 'Migration.php';

class CreateStudentsTable extends Migration
{
    public function up()
    {
        $sql = "CREATE TABLE IF NOT EXISTS students (
            id INT PRIMARY KEY AUTO_INCREMENT,
            student_name VARCHAR(100) NOT NULL,
            class VARCHAR(10) NOT NULL,
            division VARCHAR(5) NOT NULL,
            parent_id INT NULL,            -- for future scalability
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

        if ($this->db->query($sql) === TRUE) {
            echo "Table 'students' created successfully\n";
        } else {
            echo "Error creating table 'students': " . $this->db->error . "\n";
        }
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS students";

        if ($this->db->query($sql) === TRUE) {
            echo "Table 'students' dropped successfully\n";
        } else {
            echo "Error dropping table 'students': " . $this->db->error . "\n";
        }
    }
}