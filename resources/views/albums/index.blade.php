
@extends('layout')
<title>Your Records</title>
<h1>My Record Collection</h1>

<p>You have {{ $albums->count() }} albums</p>

<a href="{{ route('albums.create') }}">Add album</a>

@foreach ($albums as $album)
    <div>
        <a href="{{ route('albums.show', $album) }}">
            <h3>{{ $album->title }}</h3>
        </a>
        <p>{{ $album->artist }}</p>
        <p>{{ $album->release_year }}</p>
    </div>
@endforeach

@if ($album->cover)
    <img src="{{ asset('storage/' . $album->cover) }}" alt="{{ $album->title }} cover" width="150" height="150">
@endif
