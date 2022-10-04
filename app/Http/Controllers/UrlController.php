<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\View\View;

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

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'url.name' => 'required|url|max:255'
        ]);

        $validated = $validator->validated();

        $urlParts = parse_url(mb_strtolower($validated['url']['name']));
        $urlNormalized = implode('', [$urlParts['scheme'], '://', $urlParts['host']]);

        $url = DB::table('urls')
            ->where('name', $urlNormalized)
            ->first();

        if ($url) {
            $id = $url->id;

            flash('Такой URL уже добавлен')
                ->warning();
        } else {
            $id = DB::table('urls')->insertGetId([
                'name' => $urlNormalized,
                'created_at' => now()
            ]);

            flash('URL успешно добавлен')
                ->success();
        }

        return redirect()->route('urls.show', $id);
    }

    public function show(int $id)
    {
        $url = DB::table('urls')->find($id);

        abort_unless($url, 404);

        $checks = DB::table('url_checks')
            ->where('url_id', $url->id)
            ->latest()
            ->get();

        return view('show', compact('url', 'checks'));
    }
}