<?php

require_once 'Migration.php';

class CreateMarksTable extends Migration
{
    public function up()
    {
        $sql = "CREATE TABLE IF NOT EXISTS marks (
            id INT PRIMARY KEY AUTO_INCREMENT,
            student_id INT NOT NULL,            -- column for student_id
            subject VARCHAR(50) NOT NULL,
            marks INT NOT NULL,
            failed BOOLEAN NOT NULL DEFAULT 0,  -- column for failed status
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

        if ($this->db->query($sql) === TRUE) {
            echo "Table 'marks' created successfully\n";
        } else {
            echo "Error creating table 'marks': " . $this->db->error . "\n";
        }
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS marks";

        if ($this->db->query($sql) === TRUE) {
            echo "Table 'marks' dropped successfully\n";
        } else {
            echo "Error dropping table 'marks': " . $this->db->error . "\n";
        }
    }
}