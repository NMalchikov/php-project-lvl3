<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DiDom\Document;

class CheckController extends Controller
{
    punlic function store(int ($id))
    {
// https://www.php.net/manual/en/language.exceptions.php
        try {
            $response = Http::get($url->name);
// https://github.com/Imangazaliev/DiDOM
            $document = new Document($response->body());
// https://laravel.com/docs/9.x/helpers#method-optional
            $h1 = optional($document->first('h1'))->text();
            $title = optional($document->first('title'))->text();
            $description = optional($document->first('meta[name=description]'))->getAttribute('content');
            DB::table('url_checks')->insert([
                'url_id' => $id,
// https://github.com/briannesbitt/Carbon
                'created_at' => Carbon::now(),
                'status_code' => $response->status(),
                'h1' => $h1,
                'title' => $title,
                'description' => $description
                ]);
                catch (\Exception $e) {
                    flash($e->getMessage())
                        ->error();
                }
        
                return redirect()->route('urls.show', $id);
            }
}


