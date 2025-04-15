<?php

namespace Tests\Feature;

use App\Models\Kategori;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KategoriTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create()
    {
        $data = ['nama_kategori' => 'Elektronik'];

        $this->withoutMiddleware();

        $response = $this->post('/kategori', $data);

        $response->assertStatus(302);
        $this->assertDatabaseHas('kategori', ['nama_kategori' => 'Elektronik']);
    }

    /** @test */
    public function udate()
    {
        $kategori = Kategori::create(['nama_kategori' => 'Buku']);

        $this->withoutMiddleware();

        $response = $this->put("/kategori/{$kategori->id}", [
            'nama_kategori' => 'Novel'
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('kategori', ['nama_kategori' => 'Novel']);
        $this->assertDatabaseMissing('kategori', ['nama_kategori' => 'Buku']);
    }

    /** @test */
    public function test_delete_kategori()
    {
        $kategori = Kategori::create(['nama_kategori' => 'Mainan']);

        $this->withoutMiddleware();

        $response = $this->delete("/kategori/{$kategori->id}");

        $response->assertStatus(302);
        $this->assertDatabaseMissing('kategori', ['nama_kategori' => 'Mainan']);
    }
}