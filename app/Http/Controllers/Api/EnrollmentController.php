<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\EnrollmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class EnrollmentController extends Controller
{
    protected $enrollmentService;

    public function __construct(EnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }

    public function checkout(string $courseId)
    {
        try {
            $user = Auth::user();
            $course = Course::findOrFail((int)$courseId);
            $url = $this->enrollmentService->createCheckoutSession($user, $course);
            return response()->json(['checkout_url' => $url]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function success(string $courseId, Request $request)
    {
        try {
            $user = Auth::user();
            $this->enrollmentService->confirmEnrollment($user, (int)$courseId);
            return response()->json(['message' => 'Enrollment successful!']);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function withdraw(string $courseId)
    {
        try {
            $user = Auth::user();
            $success = $this->enrollmentService->withdraw($user, (int)$courseId);
            if ($success) {
                return response()->json(['message' => 'Successfully withdrawn from course.']);
            }
            return response()->json(['error' => 'Action allowed for students only.'], 403);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
