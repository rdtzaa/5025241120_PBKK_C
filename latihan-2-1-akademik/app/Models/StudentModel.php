<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table = 'students';
    protected $allowedFields = ['nim', 'name', 'program'];

    public function profile(int $id): ?array
    {
        return $this->select('students.*, academic_users.name AS advisor')
            ->join('advising', 'advising.student_id = students.id', 'left')
            ->join('academic_users', 'academic_users.id = advising.advisor_id', 'left')
            ->find($id);
    }

    public function wards(int $advisorId, ?int $studentId = null): array
    {
        $students = $this->select('students.*, study_plans.status')
            ->join('advising', 'advising.student_id = students.id')
            ->join('study_plans', 'study_plans.student_id = students.id AND study_plans.term = ' . $this->db->escape(StudyPlanModel::TERM), 'left')
            ->where('advising.advisor_id', $advisorId);

        if ($studentId !== null) {
            $students->where('students.id', $studentId);
        }

        return $students->orderBy('students.name')->findAll();
    }
}
