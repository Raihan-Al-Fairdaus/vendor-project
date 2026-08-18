<?php

namespace App\Http\Controllers;

use App\Models\Billboard;
use Illuminate\Http\Request;

class BillboardController extends Controller
{
    /**
     * Show list of available billboards.
     */
    public function index(Request $request)
    {
        $query = Billboard::available();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('city', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('city')) {
            $query->where('city', $request->input('city'));
        }

        $billboards = $query->orderBy('city')->paginate(12);
        
        // Fetch all unique cities that have available billboards
        $cities = Billboard::available()->distinct()->pluck('city');

        return view('bilboard.index', compact('billboards', 'cities'));
    }
}
?>
