<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        $popularMotors = Motor::withCount('rentals')->where('status', 'tersedia')->orderBy('rentals_count', 'desc')->take(4)->get();
        return view('landing-page', compact('popularMotors'));
    }
}
