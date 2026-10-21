@extends('layout')

@section('content')
    <div class="page-header">
    <h1>My Record Collection</h1>
    <p>You have {{ $albums->count() }} albums</p>
    <a href="{{ route('albums.create') }}">Add album</a>
        <form action={{ URL ('albums')}}  method="GET">
            <div>
                <input type = "text" placeholder="Search" id="search" name="search">
                <button type="submit">Search</button>
            </div>
        </form>
    </div>


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
        .page-header {
            text-align: center;
        }

        .album-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .album-card {
            border: none;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
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

        .album-card{
            transition: transform 0.2s ease-in-out;
        }

        .album-card:hover {
            transform: scale(1.03);
        }
    </style>
@endsection
