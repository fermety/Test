<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function __invoke()
    {
        return view('home');
    }
}
