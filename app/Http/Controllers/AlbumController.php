<?php

namespace App\Http\Controllers;
use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $albums = Album::when($request->search, function ($query) use ($request) {
            return $query->whereAny([
                'title','artist','release_year'
            ],$request->search);
        })->get();
        return view('albums.index', compact('albums'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('albums.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'artist' => 'required',
            'release_year' => 'nullable|integer',
            'run_time' => 'nullable|integer',
            'cover' => 'nullable|image',
        ]);
        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }
        Album::create($data);

        return redirect()->route('albums.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Album $album)
    {
        return view('albums.show', compact('album'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Album $album)
    {
        return view('albums.edit', compact('album'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Album $album)
    {
        $data = $request->validate([
            'title' => 'required',
            'artist' => 'required',
            'release_year' => 'nullable|integer',
            'run_time' => 'nullable|integer',
            'cover' => 'nullable|image',
        ]);

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $album->update($data);

        return redirect()->route('albums.index');
    }

    public function destroy(Album $album)
    {
        $album->delete();
        return redirect()->route('albums.index');
    }
}
