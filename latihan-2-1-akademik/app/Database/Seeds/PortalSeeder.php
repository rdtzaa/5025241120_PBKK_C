<?php

namespace App\Database\Seeds;

use App\Models\StudyPlanModel;
use CodeIgniter\Database\Seeder;

class PortalSeeder extends Seeder
{
    public function run()
    {
        if (! $this->db->transException(true)->transStart()) {
            throw new \RuntimeException('Tidak dapat menyiapkan akun demo.');
        }

        $this->call('DemoSeeder');
        $accounts = $this->db->table('academic_users');
        $advisors = [];
        foreach (['dosen.wali' => 'Dr. Maya Santoso', 'dosen.nadia' => 'Dr. Arif Nugraha'] as $username => $name) {
            $this->account($username, $name, 'dosen');
            $advisors[$username] = $accounts->select('id')->where('username', $username)->get()->getRow()->id;
        }

        $students = $this->db->table('students')->whereIn('nim', ['5025231001', '5025231002', '5025231003'])->get()->getResultArray();
        foreach ($students as $student) {
            $this->account($student['nim'], $student['name'], 'mahasiswa', $student['id']);
            $advisor = $student['nim'] === '5025231003' ? 'dosen.nadia' : 'dosen.wali';
            $this->db->query('INSERT OR IGNORE INTO advising (student_id, advisor_id) VALUES (?, ?)', [$student['id'], $advisors[$advisor]]);
        }

        foreach ([
            ['IF184301', 'Basis Data', 3, 'A', 1, '08:00', '10:30', 'Lab Basis Data', 'Dr. Maya Santoso'],
            ['IF184401', 'Pemrograman Web', 3, 'A', 2, '10:00', '12:30', 'Lab Pemrograman', 'Dr. Arif Nugraha'],
            ['SI184210', 'Analisis Sistem', 2, 'A', 3, '08:00', '09:40', 'Ruang C-201', 'Sinta Dewi, M.T.'],
            ['IF184505', 'Rekayasa Perangkat Lunak', 3, 'A', 4, '13:00', '15:30', 'Ruang B-302', 'Dr. Maya Santoso'],
        ] as [$code, $name, $credits, $class, $day, $start, $end, $room, $lecturer]) {
            $this->db->query('INSERT OR IGNORE INTO courses (code, name, credits) VALUES (?, ?, ?)', [$code, $name, $credits]);
            $this->db->query('INSERT OR IGNORE INTO class_offerings
                (course_id, term, class_name, weekday, starts_at, ends_at, room, lecturer)
                SELECT id, ?, ?, ?, ?, ?, ?, ? FROM courses WHERE code = ?',
                [StudyPlanModel::TERM, $class, $day, $start, $end, $room, $lecturer, $code]);
        }

        if (! $this->db->transComplete()) {
            throw new \RuntimeException('Data demo gagal disimpan.');
        }
    }

    private function account(string $username, string $name, string $role, ?int $studentId = null): void
    {
        $this->db->query('INSERT OR IGNORE INTO academic_users (username, name, role, password_hash, student_id)
            VALUES (?, ?, ?, ?, ?)', [$username, $name, $role, password_hash('12345678', PASSWORD_DEFAULT), $studentId]);
    }
}
