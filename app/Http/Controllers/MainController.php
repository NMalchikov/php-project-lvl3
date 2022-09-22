<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function home() {
        return view('home');
    }
    public function analyzerPage(Request $request) {
        $valid = $request->validate([
            'name' => 'required|max:255'
        ]);
    }
}
