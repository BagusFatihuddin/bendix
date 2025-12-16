<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Reel;

class ReelController extends Controller
{
    public function index()
    {
        $reels = Reel::where('is_active', true)
            ->latest()
            ->get();

        return view('front.reels.index', compact('reels'));
    }
}
