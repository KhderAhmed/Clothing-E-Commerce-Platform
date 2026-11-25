<?php

namespace Tests\Feature\Livewire\Auth;

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Logout;
use App\Livewire\Auth\Register;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_is_redirected_to_home()
    {
        Livewire::test(Register::class)
            ->set('name', 'خضر جابر أحمد')
            ->set('email', 'khder@example.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('register')
            ->assertHasNoErrors()
            ->assertRedirect('/home');

        $this->assertAuthenticated();
        
        $user = Auth::user();
        $this->assertEquals('خضر جابر أحمد', $user->name);
        $this->assertEquals('user', $user->role);
    }

    public function test_user_can_login_and_admin_redirects_to_admin_dashboard()
    {
        $admin = User::factory()->create([
            'email' => 'admin@site.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        Livewire::test(Login::class)
            ->set('email', 'admin@site.com')
            ->set('password', 'password123')
            ->call('login')
            ->assertHasNoErrors()
            ->assertRedirect('/admin/dashboard');

        $this->assertAuthenticatedAs($admin);
    }

    public function test_regular_user_can_login_and_redirects_to_home()
    {
        $user = User::factory()->create([
            'email' => 'user@site.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        Livewire::test(Login::class)
            ->set('email', 'user@site.com')
            ->set('password', 'password123')
            ->call('login')
            ->assertHasNoErrors()
            ->assertRedirect('/home');

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_invalid_credentials()
    {
        Livewire::test(Login::class)
            ->set('email', 'wrong@site.com')
            ->set('password', 'wrongpassword')
            ->call('login')
            ->assertHasErrors(['email']);

        $this->assertGuest();
    }

    public function test_user_can_logout_via_event()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->assertAuthenticated();

        Livewire::test(Logout::class)
            ->dispatch('trigger-logout')
            ->assertRedirect('/login');

        $this->assertGuest();
    }
}