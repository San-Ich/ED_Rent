<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        $popularMotors = Motor::withCount('rentals') 
            ->orderBy('rentals_count', 'desc')       
            ->take(4)                                
            ->get();

        return view('landing-page', compact('popularMotors'));
    }
}

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Motor::where('status', 'Tersedia');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = $request->start_date;
            $end = $request->end_date;

            $query->whereDoesHave('rentals', function ($q) use ($start, $end) {
                $q->where(function ($sub) use ($start, $end) {
                    $sub->whereBetween('start_date', [$start, $end])
                        ->orWhereBetween('end_date', [$start, $end])
                        ->orWhere(function ($p) use ($start, $end) {
                            $p->where('start_date', '<=', $start)
                                ->where('end_date', '>=', $end);
                        });
                })->whereIn('status', ['ordered', 'active']);
            });
        }

        $motors = $query->get();

        return view('catalog', compact('motors'));
    }
}
