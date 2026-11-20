<?php

namespace App\Models;

use CodeIgniter\Model;

class StudyPlanModel extends Model
{
    public const TERM = 'Gasal 2026/2027';
    public const MAX_CREDITS = 24;
    public const DAYS = [1 => 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    protected $table = 'study_plans';
    protected $allowedFields = ['student_id', 'term', 'status', 'submitted_at', 'approved_at', 'approved_by'];

    public function forStudent(int $studentId): ?array
    {
        return $this->select('study_plans.*, academic_users.name AS approver')
            ->join('academic_users', 'academic_users.id = study_plans.approved_by', 'left')
            ->where('study_plans.student_id', $studentId)->where('study_plans.term', self::TERM)->first();
    }

    public function offerings(): array
    {
        return $this->classes()->where('class_offerings.term', self::TERM)->get()->getResultArray();
    }

    public function selected(int $planId): array
    {
        return $this->classes()->join('study_plan_items', 'study_plan_items.offering_id = class_offerings.id')
            ->where('study_plan_items.plan_id', $planId)->get()->getResultArray();
    }

    public function submit(int $studentId, array $offeringIds): bool
    {
        if (! $this->db->transStart()) {
            return false;
        }

        if ($this->db->table($this->table)->insert([
            'student_id' => $studentId, 'term' => self::TERM,
            'status' => 'menunggu', 'submitted_at' => date('Y-m-d H:i:s'),
        ])) {
            $planId = $this->db->insertID();
            $items = array_map(
                static fn (int $id) => ['plan_id' => $planId, 'offering_id' => $id],
                $offeringIds
            );
            $this->db->table('study_plan_items')->insertBatch($items);
        }

        return $this->db->transComplete();
    }

    public function approve(int $studentId, int $advisorId): bool
    {
        $wards = $this->db->table('advising')->select('student_id')->where('advisor_id', $advisorId);

        $saved = $this->db->table($this->table)->where([
            'student_id' => $studentId, 'term' => self::TERM, 'status' => 'menunggu',
        ])->whereIn('student_id', $wards)->update([
            'status' => 'disetujui', 'approved_by' => $advisorId, 'approved_at' => date('Y-m-d H:i:s'),
        ]);

        return $saved && $this->db->affectedRows() === 1;
    }

    private function classes(): \CodeIgniter\Database\BaseBuilder
    {
        return $this->db->table('class_offerings')
            ->select('class_offerings.*, courses.code, courses.name, courses.credits')
            ->join('courses', 'courses.id = class_offerings.course_id')
            ->orderBy('weekday')->orderBy('starts_at')->orderBy('courses.code');
    }
}
