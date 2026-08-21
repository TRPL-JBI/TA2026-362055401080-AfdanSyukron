<?php

namespace Tests\Feature\Verifikasi;

use App\Models\User;
use App\Models\Pengajuan;
use App\Models\Mahasiswa;
use App\Mail\SendEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class VerifikasiPengajuanTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_verify_pengajuan_successfully()
    {
        Mail::fake();
        Http::fake([
            'https://api.fonnte.com/send' => Http::response(['status' => true], 200)
        ]);

        $user = User::factory()->create();
        $mahasiswa = Mahasiswa::factory()->create([
            'user_id' => $user->id,
            'whatsapp' => '081234567890',
            'email' => 'mahasiswa@example.com',
        ]);

        $pengajuan = Pengajuan::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $role = \App\Models\Role::factory()->create(['role' => 'admin']);
        $admin = User::factory()->create(['role_id' => $role->id]);
        $response = $this->actingAs($admin)->get(route('pengajuan.verif', $pengajuan->id));

        $response->assertRedirect(route('pengajuan'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pengajuans', [
            'id' => $pengajuan->id,
            'status' => 'verified',
        ]);

        Mail::assertSent(SendEmail::class, function ($mail) use ($mahasiswa) {
            return $mail->hasTo($mahasiswa->email) && 
                   $mail->data['status'] === 'DISETUJUI';
        });

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.fonnte.com/send' &&
                   $request['target'] === '6281234567890';
        });
    }

    public function test_staff_can_decline_pengajuan_successfully()
    {
        Mail::fake();

        $user = User::factory()->create();
        $mahasiswa = Mahasiswa::factory()->create([
            'user_id' => $user->id,
            'email' => 'mahasiswa@example.com',
        ]);

        $pengajuan = Pengajuan::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $role = \App\Models\Role::factory()->create(['role' => 'admin']);
        $admin = User::factory()->create(['role_id' => $role->id]);
        $response = $this->actingAs($admin)->get(route('pengajuan.decline', $pengajuan->id));

        $response->assertRedirect(route('pengajuan'));
        $response->assertSessionHas('success', 'Status berhasil ditolak dan email pemberitahuan telah terkirim.');

        $this->assertDatabaseHas('pengajuans', [
            'id' => $pengajuan->id,
            'status' => 'decline',
        ]);

        Mail::assertSent(SendEmail::class, function ($mail) use ($mahasiswa) {
            return $mail->hasTo($mahasiswa->email) && 
                   $mail->data['status'] === 'DITOLAK';
        });
    }
}
