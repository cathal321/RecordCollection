@extends('layout')

@section('content')
    <h1>My Record Collection</h1>
    <p>You have {{ $albums->count() }} albums</p>
    <a href="{{ route('albums.create') }}">Add album</a>

    <div class="album-grid">
        @foreach ($albums as $album)
            <div class="album-card">
                <a href="{{ route('albums.show', $album) }}">
                    @if ($album->cover)
                        <img src="{{ asset('storage/' . $album->cover) }}" alt="{{ $album->title }} cover">
                    @else
                        <div class="no-cover">No cover</div>
                    @endif
                    <h3>{{ $album->title }}</h3>
                </a>
                <p>{{ $album->artist }}</p>
                <p>{{ $album->release_year }}</p>
            </div>
        @endforeach
    </div>

    <style>
        .album-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .album-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
        }
        .album-card img,
        .no-cover {
            width: 100%;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            border-radius: 4px;
        }
        .no-cover {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eee;
            color: #888;
        }
        .album-card a {
            text-decoration: none;
            color: inherit;
        }
    </style>
@endsection
