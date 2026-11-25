<?php

namespace Tests\Feature\Livewire\Auth;

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AuthValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_requires_all_mandatory_fields()
    {
        Livewire::test(Register::class)
            ->set('name', '')
            ->set('email', '')
            ->set('password', '')
            ->set('password_confirmation', '')
            ->call('register')
            ->assertHasErrors([
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
            ]);
    }

    public function test_register_validates_email_format_and_password_match()
    {
        Livewire::test(Register::class)
            ->set('name', 'خضر')
            ->set('email', 'invalid-email-format')
            ->set('password', '12345') // أقل من 6 أحرف
            ->set('password_confirmation', 'different_password')
            ->call('register')
            ->assertHasErrors([
                'email' => 'email',
                'password' => 'min', // أو same حسب ترتيب الـ rules لديك
            ]);
    }

    public function test_login_requires_email_and_password()
    {
        Livewire::test(Login::class)
            ->set('email', '')
            ->set('password', '')
            ->call('login')
            ->assertHasErrors([
                'email' => 'required',
                'password' => 'required',
            ]);
    }

    public function test_login_validates_email_format()
    {
        Livewire::test(Login::class)
            ->set('email', 'not-a-valid-email')
            ->set('password', 'password123')
            ->call('login')
            ->assertHasErrors([
                'email' => 'email',
            ]);
    }
}