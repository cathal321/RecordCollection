@extends('layout')

@section('content')
    <a href="{{ route('albums.index') }}">&larr; Back</a>

    <div class="album-detail">
        @if ($album->cover)
            <img src="{{ asset('storage/' . $album->cover) }}" alt="{{ $album->title }} cover">
        @else
            <div class="no-cover">No cover</div>
        @endif

        <div class="album-info">
            <h1>{{ $album->title }}</h1>
            <h2>{{ $album->artist }}</h2>

            <dl>
                @if ($album->release_year)
                    <dt>Released</dt>
                    <dd>{{ $album->release_year }}</dd>
                @endif

                @if ($album->run_time)
                    <dt>Run time</dt>
                    <dd>{{ $album->run_time }} minutes</dd>
                @endif
            </dl>
            <form class="album-delete" action="{{ route('albums.destroy', $album) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('DELETE')
                <button type="submit">Delete Album</button>
            </form>
        </div>


        </div>


    </div>

    <style>
        .album-detail {
            display: grid;
            grid-template-columns: minmax(200px, 320px) 1fr;
            gap: 30px;
            margin-top: 100px;
            align-items: start;
        }
        .album-detail img,
        .no-cover {
            width: 100%;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 3, 10);
        }
        .no-cover {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eee;
            color: #888;
        }
        .album-info h1 { margin: 0 0 5px; }
        .album-info h2 { margin: 0 0 20px; font-weight: normal; color: #555; }
        .album-info dl {
            display: grid;
            grid-template-columns: max-content 1fr;
            gap: 8px 16px;
        }
        .album-info dt { font-weight: bold; }
        .album-info dd { margin: 0; }

        @media (max-width: 600px) {
            .album-detail { grid-template-columns: 1fr; }
        }

        .album-detail:hover{
            transition: transform 0.25s ease-in-out;
        }

        .album-detail:hover {
            transform: scale(1.02);
        }

        .album-delete {
            margin-top: 40px;
        }
    </style>
@endsection
