<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\EnrollmentService;
use App\Services\GroupService;
use Illuminate\Support\Facades\Auth;
use Exception;

class TeacherController extends Controller
{
    protected $enrollmentService;
    protected $groupService;

    public function __construct(EnrollmentService $enrollmentService, GroupService $groupService)
    {
        $this->enrollmentService = $enrollmentService;
        $this->groupService = $groupService;
    }

    public function courseStudents(string $courseId)
    {
        try {
            $user = Auth::user();
            $course = Course::findOrFail((int)$courseId);
            $students = $this->enrollmentService->getCourseStudents($user, $course);
            return response()->json($students);
        } catch (Exception $e) {
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

    public function courseGroups(string $courseId)
    {
        try {
            $user = Auth::user();
            $course = Course::findOrFail((int)$courseId);
            $groups = $this->groupService->getCourseGroups($user, $course);
            return response()->json($groups);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

   
}
