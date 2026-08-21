<?php

namespace Tests\Feature\Auth;

use App\Models\Alat;
use App\Models\Jurusan;
use App\Models\Mahasiswa;
use App\Models\Ormawa;
use App\Models\Pengajuan;
use App\Models\Prodi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected $mahasiswaRole;
    protected $adminRole;
    protected $kepalaHumasRole;

    protected $mahasiswaUser;
    protected $mahasiswaData;

    protected $adminUser;
    protected $kepalaHumasUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mahasiswaRole = Role::factory()->create(['role' => 'MAHASISWA']);
        $this->adminRole = Role::factory()->create(['role' => 'STAFF ADMIN']);
        $this->kepalaHumasRole = Role::factory()->create(['role' => 'KEPALA HUMAS']);

        $this->mahasiswaUser = User::factory()->create(['role_id' => $this->mahasiswaRole->id]);
        $this->mahasiswaData = Mahasiswa::factory()->create(['user_id' => $this->mahasiswaUser->id]);

        $this->adminUser = User::factory()->create(['role_id' => $this->adminRole->id]);
        $this->kepalaHumasUser = User::factory()->create(['role_id' => $this->kepalaHumasRole->id]);
    }

    public function test_mahasiswa_cannot_access_master_data_routes_via_url()
    {
        $restrictedRoutes = [
            route('jurusan'),
            route('jurusan.create'),
            route('prodi'),
            route('prodi.create'),
            route('ormawa'),
            route('ormawa.create'),
            route('alat'),
            route('alat.create'),
            route('role'),
            route('role.create'),
            route('user'),
            route('user.create'),
            route('mahasiswa'),
            route('mahasiswa.create'),
            route('pengajuan.report'),
        ];

        foreach ($restrictedRoutes as $url) {
            $response = $this->actingAs($this->mahasiswaUser)->get($url);
            $response->assertStatus(403);
        }
    }

    public function test_mahasiswa_cannot_access_verification_actions()
    {
        $pengajuan = Pengajuan::factory()->create([
            'user_id' => $this->mahasiswaUser->id,
            'status' => 'pending'
        ]);

        $responseVerif = $this->actingAs($this->mahasiswaUser)->get(route('pengajuan.verif', $pengajuan->id));
        $responseVerif->assertStatus(403);

        $responseDecline = $this->actingAs($this->mahasiswaUser)->get(route('pengajuan.decline', $pengajuan->id));
        $responseDecline->assertStatus(403);

        $responseFinish = $this->actingAs($this->mahasiswaUser)->get(route('pengajuan.finish', $pengajuan->id));
        $responseFinish->assertStatus(403);
    }

    public function test_kepala_humas_cannot_access_master_data_routes_via_url()
    {
        $restrictedRoutes = [
            route('jurusan'),
            route('jurusan.create'),
            route('prodi'),
            route('prodi.create'),
            route('ormawa'),
            route('ormawa.create'),
            route('alat'),
            route('alat.create'),
            route('role'),
            route('role.create'),
            route('user'),
            route('user.create'),
            route('mahasiswa'),
            route('mahasiswa.create'),
            route('pengajuan.create'),
        ];

        foreach ($restrictedRoutes as $url) {
            $response = $this->actingAs($this->kepalaHumasUser)->get($url);
            $response->assertStatus(403);
        }
    }

    public function test_kepala_humas_can_access_verification_and_report()
    {
        $responseReport = $this->actingAs($this->kepalaHumasUser)->get(route('pengajuan.report'));
        $responseReport->assertStatus(200);
    }

    public function test_mahasiswa_cannot_edit_other_mahasiswa_data()
    {
        $otherUser = User::factory()->create(['role_id' => $this->mahasiswaRole->id]);
        $otherMahasiswa = Mahasiswa::factory()->create(['user_id' => $otherUser->id]);

        // Attempting to edit other student's profile
        $responseEdit = $this->actingAs($this->mahasiswaUser)->get(route('mahasiswa.edit', $otherMahasiswa->id));
        $responseEdit->assertStatus(403);

        $responseUpdate = $this->actingAs($this->mahasiswaUser)->post(route('mahasiswa.update', $otherMahasiswa->id), [
            'nama' => 'Hacked Name',
            'nim' => $otherMahasiswa->nim,
            'email' => 'hacked@example.com',
            'whatsapp' => '0899999999',
            'jurusan' => 1,
            'prodi' => 1,
            'ormawa' => 1,
        ]);
        $responseUpdate->assertStatus(403);
    }

    public function test_mahasiswa_can_edit_own_profile()
    {
        $responseEdit = $this->actingAs($this->mahasiswaUser)->get(route('mahasiswa.edit', $this->mahasiswaData->id));
        $responseEdit->assertStatus(200);
    }

    public function test_mahasiswa_cannot_view_or_modify_other_student_pengajuan()
    {
        $otherUser = User::factory()->create(['role_id' => $this->mahasiswaRole->id]);
        $otherPengajuan = Pengajuan::factory()->create([
            'user_id' => $otherUser->id,
            'status' => 'pending'
        ]);

        $responseShow = $this->actingAs($this->mahasiswaUser)->get(route('pengajuan.show', $otherPengajuan->id));
        $responseShow->assertStatus(403);

        $responseEdit = $this->actingAs($this->mahasiswaUser)->get(route('pengajuan.edit', $otherPengajuan->id));
        $responseEdit->assertStatus(403);

        $responseDelete = $this->actingAs($this->mahasiswaUser)->get(route('pengajuan.delete', $otherPengajuan->id));
        $responseDelete->assertStatus(403);
    }

    public function test_staff_admin_can_access_master_data_routes()
    {
        $allowedRoutes = [
            route('jurusan'),
            route('jurusan.create'),
            route('prodi'),
            route('prodi.create'),
            route('ormawa'),
            route('ormawa.create'),
            route('alat'),
            route('alat.create'),
            route('role'),
            route('role.create'),
            route('user'),
            route('user.create'),
            route('mahasiswa'),
            route('mahasiswa.create'),
        ];

        foreach ($allowedRoutes as $url) {
            $response = $this->actingAs($this->adminUser)->get($url);
            $response->assertStatus(200);
        }
    }
}
