<?php

namespace Tests\Feature\Pengajuan;

use App\Models\User;
use App\Models\Alat;
use App\Models\Pengajuan;
use App\Models\DetailPengajuan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PengajuanPeminjamanTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $role;

    protected function setUp(): void
    {
        parent::setUp();
        $this->role = \App\Models\Role::factory()->create(['role' => 'mahasiswa']);
        $this->user = User::factory()->create(['role_id' => $this->role->id]);
    }

    public function test_mahasiswa_can_submit_pengajuan_with_available_alat()
    {
        Storage::fake('public');

        $user = $this->user;
        $alat = Alat::factory()->create(['stok' => '5']);

        $response = $this->actingAs($user)->post(route('pengajuan.store'), [
            'nama_kegiatan' => 'Dies Natalis',
            'tanggal_peminjaman' => now()->addDays(1)->format('Y-m-d'),
            'tanggal_pengembalian' => now()->addDays(3)->format('Y-m-d'),
            'file' => UploadedFile::fake()->create('proposal.pdf', 500, 'application/pdf'),
            'ktm' => UploadedFile::fake()->image('ktm.jpg'),
            'alat' => [$alat->id],
            'qty' => [
                $alat->id => '2'
            ]
        ]);

        $response->assertRedirect(route('pengajuan'));
        $response->assertSessionHas('success', 'Pengajuan berhasil dikirim.');

        $this->assertDatabaseHas('pengajuans', [
            'user_id' => $user->id,
            'nama_kegiatan' => 'Dies Natalis',
            'status' => 'pending',
        ]);

        $pengajuan = Pengajuan::where('user_id', $user->id)->first();

        $this->assertDatabaseHas('detail_pengajuans', [
            'pengajuan_id' => $pengajuan->id,
            'alat_id' => $alat->id,
            'qty' => '2',
        ]);
    }

    public function test_pengajuan_validation_fails_when_required_fields_are_missing()
    {
        $user = $this->user;

        $response = $this->actingAs($user)->post(route('pengajuan.store'), [
            'nama_kegiatan' => '',
            'tanggal_peminjaman' => '',
            'tanggal_pengembalian' => '',
        ]);

        $response->assertSessionHasErrors(['nama_kegiatan', 'tanggal_peminjaman', 'tanggal_pengembalian', 'file', 'ktm']);
    }

    public function test_pengajuan_fails_when_no_alat_selected()
    {
        $user = $this->user;

        $response = $this->actingAs($user)->post(route('pengajuan.store'), [
            'nama_kegiatan' => 'Dies Natalis',
            'tanggal_peminjaman' => now()->addDays(1)->format('Y-m-d'),
            'tanggal_pengembalian' => now()->addDays(3)->format('Y-m-d'),
            'file' => UploadedFile::fake()->create('proposal.pdf', 500, 'application/pdf'),
            'ktm' => UploadedFile::fake()->image('ktm.jpg'),
        ]);

        $response->assertSessionHas('error', 'Pilih minimal satu alat.');
    }

    public function test_pengajuan_fails_when_requested_qty_exceeds_available_stock()
    {
        Storage::fake('public');

        $user = $this->user;
        $alat = Alat::factory()->create(['nama' => 'Camera', 'stok' => '3']);

        $response = $this->actingAs($user)->post(route('pengajuan.store'), [
            'nama_kegiatan' => 'Dies Natalis',
            'tanggal_peminjaman' => now()->addDays(1)->format('Y-m-d'),
            'tanggal_pengembalian' => now()->addDays(3)->format('Y-m-d'),
            'file' => UploadedFile::fake()->create('proposal_exceeds.pdf', 500, 'application/pdf'),
            'ktm' => UploadedFile::fake()->image('ktm_exceeds.jpg'),
            'alat' => [$alat->id],
            'qty' => [
                $alat->id => '4'
            ]
        ]);

        $response->assertSessionHas('error', "Stok alat 'Camera' tidak mencukupi. Tersedia: 3");
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $files = glob(public_path('uploads/pengajuan/*'));
        foreach ($files as $file) {
            if (is_file($file) && (str_contains($file, 'proposal') || str_contains($file, 'test_file'))) {
                @unlink($file);
            }
        }

        $ktms = glob(public_path('uploads/ktm/*'));
        foreach ($ktms as $ktm) {
            if (is_file($ktm) && (str_contains($ktm, 'ktm') || str_contains($ktm, 'test_ktm'))) {
                @unlink($ktm);
            }
        }
    }
}
