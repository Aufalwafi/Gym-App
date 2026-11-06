<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FitTrackFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('FitTrack');
    }

    public function test_register_page_renders_successfully(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('FitTrack');
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::first();
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Total latihan minggu ini');
    }

    public function test_authenticated_user_can_view_workouts(): void
    {
        $user = User::first();
        $response = $this->actingAs($user)->get('/workouts');
        $response->assertStatus(200);
        $response->assertSee('Riwayat Sesi Latihan');
    }

    public function test_authenticated_user_can_view_routines(): void
    {
        $user = User::first();
        $response = $this->actingAs($user)->get('/routines');
        $response->assertStatus(200);
        $response->assertSee('Template Standar Gym');
    }

    public function test_authenticated_user_can_view_exercise_catalog(): void
    {
        $user = User::first();
        $response = $this->actingAs($user)->get('/exercises');
        $response->assertStatus(200);
        $response->assertSee('Katalog Latihan Gym');
    }

    public function test_authenticated_user_can_view_calculator(): void
    {
        $user = User::first();
        $response = $this->actingAs($user)->get('/calculator');
        $response->assertStatus(200);
        $response->assertSee('Kalkulator Indeks Massa Tubuh');
    }

    public function test_authenticated_user_can_view_progress(): void
    {
        $user = User::first();
        $response = $this->actingAs($user)->get('/progress');
        $response->assertStatus(200);
        $response->assertSee('Personal Records (PR) Hall of Fame');
    }
}
