<?php

namespace App\Controllers;

use App\Models\EnrollmentModel;
use App\Models\StudentModel;
use App\Models\StudyPlanModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Academic extends BaseController
{
    public function frs()
    {
        $student = $this->student();
        $plans = model(StudyPlanModel::class);
        $plan = $plans->forStudent($student['id']);
        $classes = $plan ? $plans->selected($plan['id']) : $plans->offerings();

        return view('frs', [
            'title' => 'Rencana Studi (FRS)', 'section' => 'frs',
            'student' => $student, 'plan' => $plan, 'classes' => $classes,
        ]);
    }

    public function submit()
    {
        $student = $this->student();
        $plans = model(StudyPlanModel::class);
        $target = redirect()->to(site_url('frs'));
        if (! $student['advisor']) {
            return $target->with('error', 'Dosen wali belum ditetapkan.');
        }
        if ($plans->forStudent($student['id'])) {
            return $target->with('error', 'FRS untuk semester ini sudah diajukan.');
        }

        $choices = $this->request->getPost('classes');
        if (! is_array($choices) || $choices === [] || count($choices) > StudyPlanModel::MAX_CREDITS) {
            return $target->with('error', 'Pilih minimal satu kelas sesuai batas SKS.');
        }

        $offerings = array_column($plans->offerings(), null, 'id');
        $selected = [];
        foreach ($choices as $choice) {
            $id = filter_var($choice, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            if ($id === false || ! isset($offerings[$id]) || isset($selected[$id])) {
                return $target->with('error', 'Pilihan kelas tidak valid. Periksa daftar kelas semester ini.');
            }
            $selected[$id] = $offerings[$id];
        }

        if (array_sum(array_column($selected, 'credits')) > StudyPlanModel::MAX_CREDITS) {
            return $target->with('error', 'Jumlah SKS melewati batas pengambilan.');
        }

        $dayEnds = [];
        foreach ($offerings as $id => $class) {
            if (! isset($selected[$id])) {
                continue;
            }
            $day = $class['weekday'];
            if (isset($dayEnds[$day]) && $class['starts_at'] < $dayEnds[$day]) {
                return $target->with('error', 'Ada kelas dengan jadwal yang bertabrakan.');
            }
            $dayEnds[$day] = $class['ends_at'];
        }

        if (! $plans->submit($student['id'], array_keys($selected))) {
            return $target->with('error', 'FRS belum tersimpan. Muat ulang halaman dan periksa status pengajuan.');
        }

        return $target->with('success', 'FRS diajukan dan menunggu persetujuan dosen wali.');
    }

    public function schedule()
    {
        $plans = model(StudyPlanModel::class);
        $plan = $plans->forStudent(session('academic_account')['student_id']);

        return view('schedule', [
            'title' => 'Jadwal kuliah', 'section' => 'jadwal',
            'classes' => ($plan['status'] ?? null) === 'disetujui' ? $plans->selected($plan['id']) : [],
        ]);
    }

    public function transcript()
    {
        return view('records', [
            'title' => 'Transkrip nilai', 'section' => 'nilai',
            'rows' => model(EnrollmentModel::class)->transcript(session('academic_account')['student_id']),
        ]);
    }

    private function student(): array
    {
        return model(StudentModel::class)->profile(session('academic_account')['student_id'])
            ?? throw PageNotFoundException::forPageNotFound();
    }
}
