<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WishlistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    protected $wishlistService;

    public function __construct(WishlistService $wishlistService)
    {
        $this->wishlistService = $wishlistService;
    }

    public function index()
    {
        $user = Auth::user();
        $wishlist = $this->wishlistService->getWishlist($user);

        return response()->json($wishlist);
    }

    public function toggle(string $courseId)
    {
        $user = Auth::user();
        
        $success = $this->wishlistService->toggleWishlist($user, (int)$courseId);

        if (!$success) {
            return response()->json(['error' => 'Action not allowed'], 403);
        }

        return response()->json([
            'message' => 'Wishlist updated successfully'
        ]);
    }
}
