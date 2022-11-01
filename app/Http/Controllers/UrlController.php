<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\View\View;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UrlController extends Controller
{
    public function index(): View
    {
        $urls = DB::table('urls')
            ->orderBy('id')
            ->paginate(15);

        $urlIds = collect($urls->items())->pluck('id');

        $checks = DB::table('url_checks')
            ->select(['url_id', DB::raw('MAX(created_at) as check_date'), 'status_code'])
            ->whereIn('url_id', $urlIds)
            ->groupBy('url_id', 'status_code')
            ->get()
            ->keyBy('url_id');

        return view('index', compact('urls', 'checks'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url.name' => 'required|url|max:255'
        ]);

        if ($validator->fails()) {
            flash('Некорректный URL')
                ->error();

                return response()->view('welcome', $request, 422);
        }

        $validated = $validator->validated();

        $urlParts = parse_url(mb_strtolower($validated['url']['name']));
        $urlNormalized = implode('', [$urlParts['scheme'], '://', $urlParts['host']]);

        $url = DB::table('urls')
            ->where('name', $urlNormalized)
            ->first();

        if ($url) {
            $id = $url->id;

            flash('Страница уже существует')
                ->warning();
        } else {
            $id = DB::table('urls')->insertGetId([
                'name' => $urlNormalized,
                'created_at' => now()
            ]);

            flash('Страница успешно добавлена')
                ->success();
        }

        return redirect()->route('urls.show', $id);
    }

    public function show(int $id)
    {
        $url = DB::table('urls')->find($id);

        $checks = DB::table('url_checks')
            ->where('url_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('show', compact('url', 'checks'));
    }
}
