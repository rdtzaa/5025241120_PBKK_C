<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run()
    {
        $this->db->query("INSERT OR IGNORE INTO students (nim, name, program) VALUES
            ('5025231001', 'Ayu Pratama', 'Teknik Informatika'),
            ('5025231002', 'Bagas Wibowo', 'Teknik Informatika'),
            ('5025231003', 'Nadia Putri', 'Sistem Informasi')");
        $this->db->query("INSERT OR IGNORE INTO courses (code, name, credits) VALUES
            ('IF184301', 'Basis Data', 3), ('IF184401', 'Pemrograman Web', 3), ('SI184210', 'Analisis Sistem', 2)");
        $this->db->query("INSERT OR IGNORE INTO enrollments (student_id, course_id, score)
            SELECT students.id, courses.id, 88 FROM students CROSS JOIN courses
            WHERE students.nim = '5025231001' AND courses.code = 'IF184301'");
    }
}
