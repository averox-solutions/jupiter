<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the home page.
     */
    public function index(Request $request)
    {
        $page = Page::where('slug', 'home')->select('content')->first();
        return view('home', [ 'page' => $page]);
    }
}
