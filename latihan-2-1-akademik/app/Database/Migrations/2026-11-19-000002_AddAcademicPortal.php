<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAcademicPortal extends Migration
{
    public function up()
    {
        if (! $this->db->transException(true)->transStart()) {
            throw new \RuntimeException('Tidak dapat memulai migrasi portal.');
        }

        $this->db->query("CREATE TABLE academic_users (
            id INTEGER PRIMARY KEY, username TEXT NOT NULL UNIQUE, password_hash TEXT NOT NULL,
            name TEXT NOT NULL, role TEXT NOT NULL CHECK (role IN ('mahasiswa', 'dosen')),
            student_id INTEGER UNIQUE REFERENCES students(id),
            CHECK ((role = 'mahasiswa' AND student_id IS NOT NULL) OR (role = 'dosen' AND student_id IS NULL))
        )");
        $this->db->query('CREATE TABLE advising (
            student_id INTEGER PRIMARY KEY REFERENCES students(id),
            advisor_id INTEGER NOT NULL REFERENCES academic_users(id)
        )');
        $this->db->query('CREATE INDEX advising_advisor ON advising (advisor_id)');
        $this->db->query('CREATE TABLE class_offerings (
            id INTEGER PRIMARY KEY, course_id INTEGER NOT NULL REFERENCES courses(id), term TEXT NOT NULL,
            class_name TEXT NOT NULL, weekday INTEGER NOT NULL CHECK (weekday BETWEEN 1 AND 7),
            starts_at TEXT NOT NULL, ends_at TEXT NOT NULL CHECK (starts_at < ends_at),
            room TEXT NOT NULL, lecturer TEXT NOT NULL, UNIQUE (course_id, term)
        )');
        $this->db->query("CREATE TABLE study_plans (
            id INTEGER PRIMARY KEY, student_id INTEGER NOT NULL REFERENCES students(id),
            term TEXT NOT NULL, status TEXT NOT NULL CHECK (status IN ('menunggu', 'disetujui')),
            submitted_at TEXT NOT NULL, approved_at TEXT, approved_by INTEGER REFERENCES academic_users(id),
            UNIQUE (student_id, term)
        )");
        $this->db->query('CREATE TABLE study_plan_items (
            plan_id INTEGER NOT NULL REFERENCES study_plans(id) ON DELETE CASCADE,
            offering_id INTEGER NOT NULL REFERENCES class_offerings(id),
            PRIMARY KEY (plan_id, offering_id)
        )');

        if (! $this->db->transComplete()) {
            throw new \RuntimeException('Migrasi portal gagal disimpan.');
        }
    }

    public function down()
    {
        foreach (['study_plan_items', 'study_plans', 'class_offerings', 'advising', 'academic_users'] as $table) {
            $this->forge->dropTable($table);
        }
    }
}
