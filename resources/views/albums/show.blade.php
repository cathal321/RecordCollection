@extends('layout')
<h1>{{ $album->title }}</h1>
<h2>{{ $album->artist }}</h2>
<p>{{ $album->release_year }}</p>
<p>{{ $album->run_time }} minutes</p>
<img src="{{ asset('storage/' . $album->cover) }}" alt="{{ $album->title }} cover" width="150" height="150">

<a href="{{ route('albums.index') }}">Back</a>
