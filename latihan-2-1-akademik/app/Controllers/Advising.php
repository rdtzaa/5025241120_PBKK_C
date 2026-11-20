<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\StudyPlanModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Advising extends BaseController
{
    public function index()
    {
        return view('wards', [
            'title' => 'Mahasiswa perwalian', 'section' => 'perwalian',
            'students' => model(StudentModel::class)->wards(session('academic_account')['id']),
        ]);
    }

    public function detail(int $studentId)
    {
        $student = $this->ward($studentId);
        $plans = model(StudyPlanModel::class);
        $plan = $plans->forStudent($studentId);

        return view('ward_detail', [
            'title' => 'Tinjau FRS', 'section' => 'perwalian',
            'student' => $student, 'plan' => $plan, 'classes' => $plan ? $plans->selected($plan['id']) : [],
        ]);
    }

    public function approve(int $studentId)
    {
        $this->ward($studentId);
        $saved = model(StudyPlanModel::class)->approve($studentId, session('academic_account')['id']);

        return redirect()->to(site_url('perwalian/' . $studentId))->with(
            $saved ? 'success' : 'error',
            $saved ? 'FRS mahasiswa disetujui.' : 'FRS ini sudah diproses atau belum diajukan.'
        );
    }

    private function ward(int $studentId): array
    {
        $students = model(StudentModel::class)->wards(session('academic_account')['id'], $studentId);

        return $students[0] ?? throw PageNotFoundException::forPageNotFound();
    }
}
