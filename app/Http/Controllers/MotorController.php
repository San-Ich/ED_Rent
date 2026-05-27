<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use Illuminate\Http\Request;

class MotorController extends Controller
{
    public function index(Request $request)
    {
        $query = Motor::query();

        $query->with(['specification', 'category']);

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('search')) {
            $query->where('model', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('rent_date') && $request->filled('duration')) {
            $query->where('status', true);
        }

        $motors = $query->paginate(8);

        return view('catalog-page', compact('motors'));
    }

    public function show($slug)
    {
        $motor = Motor::with(['specification', 'category'])->where('slug', $slug)->firstOrFail();

        $rekomendasiMotors = Motor::where('status', 'Tersedia')->where('id', '!=', $motor->id)->inRandomOrder()->take(8)->get();
        return view('detail-motor', compact('motor', 'rekomendasiMotors'));
    }
}
