<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table = 'enrollments';
    protected $allowedFields = ['student_id', 'course_id', 'score'];

    public function transcript(int $studentId): array
    {
        return $this->select('courses.code, courses.name, courses.credits, enrollments.score')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->where('enrollments.student_id', $studentId)->orderBy('courses.code')->findAll();
    }
}
