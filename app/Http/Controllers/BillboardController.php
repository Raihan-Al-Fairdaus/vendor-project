<?php

namespace App\Http\Controllers;

use App\Models\Billboard;
use Illuminate\Http\Request;

class BillboardController extends Controller
{
    /**
     * Show list of available billboards.
     */
    public function index()
    {
        $billboards = Billboard::available()->orderBy('city')->paginate(12);
        return view('bilboard.index', compact('billboards'));
    }
}
?>
