<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Role;
use App\Models\Mahasiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_mahasiswa_can_login_with_valid_credentials_and_redirect_to_dashboard()
    {
        $role = Role::factory()->create(['role' => 'mahasiswa']);
        $user = User::factory()->create([
            'nip' => '12345678',
            'password' => bcrypt('password123'),
            'role_id' => $role->id,
        ]);
        
        Mahasiswa::factory()->create([
            'user_id' => $user->id,
            'jurusan' => 1,
            'prodi' => 1,
            'ormawa' => 1,
        ]);

        $response = $this->post(route('actionlogin'), [
            'nip' => '12345678',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard2');
        $this->assertAuthenticatedAs($user);
    }

    public function test_mahasiswa_redirects_to_edit_profile_if_profile_is_incomplete()
    {
        $role = Role::factory()->create(['role' => 'mahasiswa']);
        $user = User::factory()->create([
            'nip' => '12345678',
            'password' => bcrypt('password123'),
            'role_id' => $role->id,
        ]);
        
        $mhs = Mahasiswa::factory()->create([
            'user_id' => $user->id,
            'jurusan' => null,
            'prodi' => null,
            'ormawa' => null,
        ]);

        $response = $this->post(route('actionlogin'), [
            'nip' => '12345678',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('mahasiswa.edit', $mhs->id));
        $this->assertAuthenticatedAs($user);
    }

    public function test_staff_admin_can_login_and_redirects_to_dashboard()
    {
        $role = Role::factory()->create(['role' => 'admin']);
        $user = User::factory()->create([
            'nip' => '99999999',
            'password' => bcrypt('password123'),
            'role_id' => $role->id,
        ]);

        $response = $this->post(route('actionlogin'), [
            'nip' => '99999999',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard2');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_invalid_credentials()
    {
        $role = Role::factory()->create(['role' => 'mahasiswa']);
        User::factory()->create([
            'nip' => '12345678',
            'password' => bcrypt('password123'),
            'role_id' => $role->id,
        ]);

        $response = $this->post(route('actionlogin'), [
            'nip' => '12345678',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('error', 'Email atau Password Salah');
        $this->assertGuest();
    }

    public function test_guest_cannot_access_dashboard_without_auth()
    {
        $response = $this->get(route('dashboard2'));
        $response->assertRedirect('/');
    }

    public function test_logout_authenticates_user_out()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('actionlogout'));
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
