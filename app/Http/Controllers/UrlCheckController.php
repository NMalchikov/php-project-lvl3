<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use DiDom\Document;
use Illuminate\Support\Str;

class UrlCheckController extends Controller
{
    public function store(int $id)
    {
        $url = DB::table('urls')->find($id);
        abort_unless($url, 404);
        // https://www.php.net/manual/en/language.exceptions.php
        try {
            $response = Http::get($url->name);
            // https://github.com/Imangazaliev/DiDOM
            $document = new Document($response->body());
            // https://laravel.com/docs/9.x/helpers#method-optional
            $h1 = optional($document->first('h1'))->text();
            $title = optional($document->first('title'))->text();
            $description = optional($document->first('meta[name=description]'))->getAttribute('content');
            DB::table('url_checks')->insert(
                [
                    'url_id' => $id,
                    'status_code' => $response->status(),
                    'h1' => $h1,
                    'title' => $title,
                    'description' => $description,
                    'created_at' => Carbon::now()
                ]
            );
            flash(__('Страница успешно проверена'))->success();
        } catch (RequestException | HttpClientException | ConnectionException $exception) {
            flash(__('Произошла ошибка при проверке'))->error();
        }

        return redirect()->route('urls.show', ['url' => $id]);
    }
}
