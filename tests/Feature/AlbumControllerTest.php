<?php

namespace Tests\Feature;

use App\Models\Album;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AlbumControllerTest extends TestCase
{
    use RefreshDatabase;

    private function makeAlbum(array $overrides = []): Album
    {
        return Album::create(array_merge([
            'title' => 'Kind of Blue',
            'artist' => 'Miles Davis',
            'release_year' => 1959,
            'run_time' => 45,
        ], $overrides));
    }

    public function test_index_page_loads_and_lists_albums(): void
    {
        $this->makeAlbum();
        $this->makeAlbum(['title' => 'Abbey Road', 'artist' => 'The Beatles', 'release_year' => 1969]);

        $response = $this->get(route('albums.index'));

        $response->assertStatus(200);
        $response->assertViewIs('albums.index');
        $response->assertViewHas('albums', fn ($albums) => $albums->count() === 2);
        $response->assertSee('Kind of Blue');
        $response->assertSee('Abbey Road');
    }

    public function test_index_search_filters_by_title(): void
    {
        $this->makeAlbum();
        $this->makeAlbum(['title' => 'Abbey Road', 'artist' => 'The Beatles', 'release_year' => 1969]);

        $response = $this->get(route('albums.index', ['search' => 'Abbey Road']));

        $response->assertStatus(200);
        $response->assertViewHas('albums', fn ($albums) => $albums->count() === 1 && $albums->first()->title === 'Abbey Road');
    }

    public function test_index_search_filters_by_artist(): void
    {
        $this->makeAlbum();
        $this->makeAlbum(['title' => 'Abbey Road', 'artist' => 'The Beatles', 'release_year' => 1969]);

        $response = $this->get(route('albums.index', ['search' => 'Miles Davis']));

        $response->assertViewHas('albums', fn ($albums) => $albums->count() === 1 && $albums->first()->artist === 'Miles Davis');
    }

    public function test_index_search_filters_by_release_year(): void
    {
        $this->makeAlbum();
        $this->makeAlbum(['title' => 'Abbey Road', 'artist' => 'The Beatles', 'release_year' => 1969]);

        $response = $this->get(route('albums.index', ['search' => '1969']));

        $response->assertViewHas('albums', fn ($albums) => $albums->count() === 1 && $albums->first()->title === 'Abbey Road');
    }

    public function test_index_search_with_no_matches_returns_empty_list(): void
    {
        $this->makeAlbum();

        $response = $this->get(route('albums.index', ['search' => 'Nonexistent']));

        $response->assertViewHas('albums', fn ($albums) => $albums->count() === 0);
    }

    public function test_create_page_loads(): void
    {
        $response = $this->get(route('albums.create'));

        $response->assertStatus(200);
        $response->assertViewIs('albums.create');
    }

    public function test_store_creates_album_and_redirects(): void
    {
        $response = $this->post(route('albums.store'), [
            'title' => 'Kind of Blue',
            'artist' => 'Miles Davis',
            'release_year' => 1959,
            'run_time' => 45,
        ]);

        $response->assertRedirect(route('albums.index'));
        $this->assertDatabaseHas('albums', [
            'title' => 'Kind of Blue',
            'artist' => 'Miles Davis',
            'release_year' => 1959,
            'run_time' => 45,
        ]);
    }

    public function test_store_with_only_required_fields(): void
    {
        $response = $this->post(route('albums.store'), [
            'title' => 'Kind of Blue',
            'artist' => 'Miles Davis',
        ]);

        $response->assertRedirect(route('albums.index'));
        $this->assertDatabaseHas('albums', ['title' => 'Kind of Blue', 'artist' => 'Miles Davis']);
    }

    public function test_store_saves_uploaded_cover(): void
    {
        Storage::fake('public');

        $this->post(route('albums.store'), [
            'title' => 'Kind of Blue',
            'artist' => 'Miles Davis',
            'cover' => UploadedFile::fake()->image('cover.jpg'),
        ])->assertRedirect(route('albums.index'));

        $album = Album::first();

        $this->assertNotNull($album->cover);
        Storage::disk('public')->assertExists($album->cover);
    }

    public function test_store_requires_title(): void
    {
        $response = $this->post(route('albums.store'), ['artist' => 'Miles Davis']);

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseCount('albums', 0);
    }

    public function test_store_requires_artist(): void
    {
        $response = $this->post(route('albums.store'), ['title' => 'Kind of Blue']);

        $response->assertSessionHasErrors('artist');
        $this->assertDatabaseCount('albums', 0);
    }

    public function test_store_rejects_non_integer_release_year_and_run_time(): void
    {
        $response = $this->post(route('albums.store'), [
            'title' => 'Kind of Blue',
            'artist' => 'Miles Davis',
            'release_year' => 'abc',
            'run_time' => 'xyz',
        ]);

        $response->assertSessionHasErrors(['release_year', 'run_time']);
        $this->assertDatabaseCount('albums', 0);
    }

    public function test_store_rejects_non_image_cover(): void
    {
        Storage::fake('public');

        $response = $this->post(route('albums.store'), [
            'title' => 'Kind of Blue',
            'artist' => 'Miles Davis',
            'cover' => UploadedFile::fake()->create('notes.pdf', 100, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('cover');
        $this->assertDatabaseCount('albums', 0);
    }

    public function test_show_page_displays_album(): void
    {
        $album = $this->makeAlbum();

        $response = $this->get(route('albums.show', $album));

        $response->assertStatus(200);
        $response->assertViewIs('albums.show');
        $response->assertViewHas('album', fn ($a) => $a->is($album));
        $response->assertSee('Kind of Blue');
        $response->assertSee('Miles Davis');
    }

    public function test_show_returns_404_for_missing_album(): void
    {
        $this->get(route('albums.show', 999))->assertStatus(404);
    }

    public function test_edit_page_loads_with_album(): void
    {
        $album = $this->makeAlbum();

        $response = $this->get(route('albums.edit', $album));

        $response->assertStatus(200);
        $response->assertViewIs('albums.edit');
        $response->assertViewHas('album', fn ($a) => $a->is($album));
        $response->assertSee('Kind of Blue');
    }

    public function test_update_changes_album_and_redirects_with_message(): void
    {
        $album = $this->makeAlbum();

        $response = $this->put(route('albums.update', $album), [
            'title' => 'Updated Title',
            'artist' => 'Updated Artist',
            'release_year' => 2000,
            'run_time' => 60,
        ]);

        $response->assertRedirect(route('albums.index'));
        $response->assertSessionHas('success', 'Album updated successfully');
        $this->assertDatabaseHas('albums', [
            'id' => $album->id,
            'title' => 'Updated Title',
            'artist' => 'Updated Artist',
            'release_year' => 2000,
            'run_time' => 60,
        ]);
    }

    public function test_update_saves_new_cover(): void
    {
        Storage::fake('public');
        $album = $this->makeAlbum();

        $this->put(route('albums.update', $album), [
            'title' => 'Kind of Blue',
            'artist' => 'Miles Davis',
            'cover' => UploadedFile::fake()->image('new-cover.jpg'),
        ])->assertRedirect(route('albums.index'));

        $album->refresh();

        $this->assertNotNull($album->cover);
        Storage::disk('public')->assertExists($album->cover);
    }

    public function test_update_without_cover_keeps_existing_cover(): void
    {
        $album = $this->makeAlbum(['cover' => 'covers/existing.jpg']);

        $this->put(route('albums.update', $album), [
            'title' => 'New Title',
            'artist' => 'Miles Davis',
        ]);

        $this->assertDatabaseHas('albums', [
            'id' => $album->id,
            'title' => 'New Title',
            'cover' => 'covers/existing.jpg',
        ]);
    }

    public function test_update_requires_title_and_artist(): void
    {
        $album = $this->makeAlbum();

        $response = $this->put(route('albums.update', $album), [
            'title' => '',
            'artist' => '',
        ]);

        $response->assertSessionHasErrors(['title', 'artist']);
        $this->assertDatabaseHas('albums', ['id' => $album->id, 'title' => 'Kind of Blue']);
    }

    public function test_update_rejects_invalid_numbers_and_cover(): void
    {
        Storage::fake('public');
        $album = $this->makeAlbum();

        $response = $this->put(route('albums.update', $album), [
            'title' => 'Kind of Blue',
            'artist' => 'Miles Davis',
            'release_year' => 'abc',
            'run_time' => 'xyz',
            'cover' => UploadedFile::fake()->create('notes.pdf', 100, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors(['release_year', 'run_time', 'cover']);
    }

    public function test_destroy_deletes_album_and_redirects_with_message(): void
    {
        $album = $this->makeAlbum();

        $response = $this->delete(route('albums.destroy', $album));

        $response->assertRedirect(route('albums.index'));
        $response->assertSessionHas('success', 'Album deleted successfully');
        $this->assertDatabaseMissing('albums', ['id' => $album->id]);
    }

    public function test_destroy_returns_404_for_missing_album(): void
    {
        $this->delete(route('albums.destroy', 999))->assertStatus(404);
    }
}
