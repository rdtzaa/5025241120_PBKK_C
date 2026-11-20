<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAcademic extends Migration
{
    public function up()
    {
        $this->db->query('CREATE TABLE students (
            id INTEGER PRIMARY KEY, nim TEXT NOT NULL UNIQUE, name TEXT NOT NULL, program TEXT NOT NULL
        )');
        $this->db->query('CREATE TABLE courses (
            id INTEGER PRIMARY KEY, code TEXT NOT NULL UNIQUE, name TEXT NOT NULL,
            credits INTEGER NOT NULL CHECK (credits BETWEEN 1 AND 6)
        )');
        $this->db->query('CREATE TABLE enrollments (
            id INTEGER PRIMARY KEY, student_id INTEGER NOT NULL REFERENCES students(id),
            course_id INTEGER NOT NULL REFERENCES courses(id),
            score INTEGER NOT NULL CHECK (score BETWEEN 0 AND 100), UNIQUE (student_id, course_id)
        )');
    }

    public function down()
    {
        $this->forge->dropTable('enrollments');
        $this->forge->dropTable('courses');
        $this->forge->dropTable('students');
    }
}
