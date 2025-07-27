<?php

// app/Http/Controllers/LandingController.php

namespace App\Http\Controllers;

use App\Models\LandingContent;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        $content = LandingContent::first(); // hanya satu data

        return view('landing', compact('content'));
    }
}


