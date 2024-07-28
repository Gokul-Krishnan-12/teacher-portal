<?php

require_once 'Migration.php';

class CreateUsersTable extends Migration
{
    public function up()
    {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        if ($this->db->query($sql) === TRUE) {
            echo "Table 'users' created successfully\n";
        } else {
            echo "Error creating table 'users': " . $this->db->error . "\n";
        }
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS users";

        if ($this->db->query($sql) === TRUE) {
            echo "Table 'users' dropped successfully\n";
        } else {
            echo "Error dropping table 'users': " . $this->db->error . "\n";
        }
    }
}