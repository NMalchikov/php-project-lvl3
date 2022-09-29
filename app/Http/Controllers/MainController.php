<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Url;

class MainController extends Controller
{
    public function home() {
        return view('home');
    }
    public function analyzerPage(Request $request) {
        $valid = $request->validate([
            'name' => 'required|url|max:255'
        ]);
        $url = new Url();
        $url->name = $request->input('url[name]');
        $url->name = $request->input('created_at');
        $url->save();
        return redirect()->route('/url{id}');
    }
}
