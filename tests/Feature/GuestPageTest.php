<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tamu (belum login) dapat mengakses halaman informasi.
     */
    public function test_guest_can_access_information_page(): void
    {
        $this->withoutVite();

        $response = $this->get('/info');
        $response->assertStatus(200);
        $response->assertSee('Informasi');
        $response->assertSee('Simple POS');

        $rootResponse = $this->get('/');
        $rootResponse->assertStatus(200);
        $rootResponse->assertSee('Simple POS');
    }

    /**
     * Pengguna yang sudah login diarahkan ke /pos saat membuka halaman tamu.
     */
    public function test_authenticated_user_is_redirected_to_pos(): void
    {
        $user = User::factory()->create([
            'role' => 'cashier',
            'is_active' => true,
        ]);

        $this->actingAs($user);

        // Akses halaman info diarahkan ke /pos
        $responseInfo = $this->get('/info');
        $responseInfo->assertRedirect('/pos');

        // Akses halaman / diarahkan ke /pos
        $responseRoot = $this->get('/');
        $responseRoot->assertRedirect('/pos');

        // Akses halaman login diarahkan ke /pos
        $responseLogin = $this->get('/login');
        $responseLogin->assertRedirect('/pos');
    }
}
