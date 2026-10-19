@extends('layout')

@section('title', 'Add new album')

@section('content')
    <a href="{{ route('albums.index') }}">&larr; Back</a>

    <h2 class="form-title">Add New Album</h2>

    @if ($errors->any())
        <div class="form-errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="album-form" action="{{ route('albums.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}">
        </div>

        <div class="mb-3">
            <label for="artist">Artist</label>
            <input type="text" id="artist" name="artist" class="form-control" value="{{ old('artist') }}">
        </div>

        <div class="mb-3">
            <label for="release_year">Release Year</label>
            <input type="number" id="release_year" name="release_year" class="form-control" value="{{ old('release_year') }}">
        </div>

        <div class="mb-3">
            <label for="run_time">Run Time (minutes)</label>
            <input type="number" id="run_time" name="run_time" class="form-control" value="{{ old('run_time') }}">
        </div>

        <div class="mb-3">
            <label for="cover">Cover</label>
            <input type="file" id="cover" name="cover" class="form-control" accept="image/*">
        </div>

        <button type="submit">Save Album</button>
    </form>

    <style>
        .form-title { text-align: center; }
        .album-form {
            max-width: 450px;
            margin: 20px auto;
        }
        .mb-3 { margin-bottom: 16px; }
        .mb-3 label {
            display: block;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;

        }
        .album-form button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background: #333;
            color: #fff;
            cursor: pointer;
        }
        .form-errors {
            max-width: 450px;
            margin: 0 auto;
            padding: 10px;
            background: #fdecea;
            color: #b71c1c;
            border-radius: 4px;
        }
    </style>
@endsection
