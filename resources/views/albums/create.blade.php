<h2>Add New Album</h2>
<title>Add new album</title>
    <form action="{{ route('albums.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}">
        </div>
        <div class="mb-3">
            <label>Artist</label>
            <input type="text" name="artist" class="form-control" value="{{ old('artist') }}">
        </div>
        <div class="mb-3">
            <label>Release Year</label>
            <input type="number" name="release_year" class="form-control" value="{{ old('release_year') }}">
        </div>
        <div class="mb-3">
            <label>Run Time (minutes)</label>
            <input type="number" name="run_time" class="form-control" value="{{ old('run_time') }}">
        </div>
        <div class="mb-3">
            <label>Cover</label>
            <input type="file" name="cover" class="form-control" accept="image/*">
        </div>
        <button {{ route('albums.store') }}>Save Album</button>
    </form>

