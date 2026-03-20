<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\EnrollmentService;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    protected $enrollmentService;

    public function __construct(EnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }

    public function courseStudents(string $courseId)
    {
        try {
            $user = Auth::user();
            $course = Course::findOrFail((int)$courseId);
            $students = $this->enrollmentService->getCourseStudents($user, $course);
            return response()->json($students);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

    public function statistics()
    {
        try {
            $user = Auth::user();
            $stats = $this->enrollmentService->getTeacherStatistics($user);
            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }
}
