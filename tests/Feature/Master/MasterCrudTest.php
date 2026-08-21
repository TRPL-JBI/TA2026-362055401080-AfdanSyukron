<?php

namespace Tests\Feature\Master;

use App\Models\User;
use App\Models\Role;
use App\Models\Alat;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\Ormawa;
use App\Models\Mahasiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MasterCrudTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        // Setup authenticated admin user for master CRUD routes
        $role = Role::factory()->create(['role' => 'admin']);
        $this->adminUser = User::factory()->create([
            'role_id' => $role->id
        ]);
    }

    public function test_alat_crud()
    {
        // Create
        $response = $this->actingAs($this->adminUser)->post(route('alat.store'), [
            'nama' => 'Proyektor BenQ',
            'serial_number' => 'SN-12345',
            'stok' => '5',
            'deskripsi' => 'Proyektor untuk presentasi',
        ]);
        $response->assertRedirect(route('alat'));
        $this->assertDatabaseHas('alats', ['nama' => 'Proyektor BenQ']);

        $alat = Alat::where('nama', 'Proyektor BenQ')->first();

        // Read
        $response = $this->actingAs($this->adminUser)->get(route('alat'));
        $response->assertStatus(200);

        // Update
        $response = $this->actingAs($this->adminUser)->post(route('alat.update', $alat->id), [
            'nama' => 'Proyektor BenQ V2',
            'serial_number' => 'SN-12345',
            'stok' => '10',
            'deskripsi' => 'Proyektor upgraded',
        ]);
        $response->assertRedirect(route('alat'));
        $this->assertDatabaseHas('alats', ['id' => $alat->id, 'nama' => 'Proyektor BenQ V2']);

        // Delete (Soft Delete)
        $response = $this->actingAs($this->adminUser)->get(route('alat.delete', $alat->id));
        $response->assertRedirect(route('alat'));
        $this->assertSoftDeleted('alats', ['id' => $alat->id]);
    }

    public function test_jurusan_crud()
    {
        // Create
        $response = $this->actingAs($this->adminUser)->post(route('jurusan.store'), [
            'nama' => 'Bisnis dan Informatika',
        ]);
        $response->assertRedirect(route('jurusan'));
        $this->assertDatabaseHas('jurusans', ['jurusan' => 'Bisnis dan Informatika']);

        $jurusan = Jurusan::where('jurusan', 'Bisnis dan Informatika')->first();

        // Read
        $response = $this->actingAs($this->adminUser)->get(route('jurusan'));
        $response->assertStatus(200);

        // Update
        $response = $this->actingAs($this->adminUser)->post(route('jurusan.update', $jurusan->id), [
            'nama' => 'Teknik Informatika',
        ]);
        $response->assertRedirect(route('jurusan'));
        $this->assertDatabaseHas('jurusans', ['id' => $jurusan->id, 'jurusan' => 'Teknik Informatika']);

        // Delete
        $response = $this->actingAs($this->adminUser)->get(route('jurusan.delete', $jurusan->id));
        $response->assertRedirect(route('jurusan'));
        $this->assertSoftDeleted('jurusans', ['id' => $jurusan->id]);
    }

    public function test_prodi_crud()
    {
        $jurusan = Jurusan::factory()->create();

        // Create
        $response = $this->actingAs($this->adminUser)->post(route('prodi.store'), [
            'prodi' => 'D4 TRPL',
            'jurusan' => (string) $jurusan->id,
        ]);
        $response->assertRedirect(route('prodi'));
        $this->assertDatabaseHas('prodis', ['prodi' => 'D4 TRPL']);

        $prodi = Prodi::where('prodi', 'D4 TRPL')->first();

        // Read
        $response = $this->actingAs($this->adminUser)->get(route('prodi'));
        $response->assertStatus(200);

        // Update
        $response = $this->actingAs($this->adminUser)->post(route('prodi.update', $prodi->id), [
            'nama' => 'D4 Teknologi Rekayasa Perangkat Lunak',
            'jurusan' => (string) $jurusan->id,
        ]);
        $response->assertRedirect(route('prodi'));
        $this->assertDatabaseHas('prodis', ['id' => $prodi->id, 'prodi' => 'D4 Teknologi Rekayasa Perangkat Lunak']);

        // Delete
        $response = $this->actingAs($this->adminUser)->get(route('prodi.delete', $prodi->id));
        $response->assertRedirect(route('prodi'));
        $this->assertSoftDeleted('prodis', ['id' => $prodi->id]);
    }

    public function test_ormawa_crud()
    {
        // Create
        $response = $this->actingAs($this->adminUser)->post(route('ormawa.store'), [
            'nama' => 'HMTI',
        ]);
        $response->assertRedirect(route('ormawa'));
        $this->assertDatabaseHas('ormawas', ['ormawa' => 'HMTI']);

        $ormawa = Ormawa::where('ormawa', 'HMTI')->first();

        // Read
        $response = $this->actingAs($this->adminUser)->get(route('ormawa'));
        $response->assertStatus(200);

        // Update
        $response = $this->actingAs($this->adminUser)->post(route('ormawa.update', $ormawa->id), [
            'nama' => 'Himpunan Mahasiswa TI',
        ]);
        $response->assertRedirect(route('ormawa'));
        $this->assertDatabaseHas('ormawas', ['id' => $ormawa->id, 'ormawa' => 'Himpunan Mahasiswa TI']);

        // Delete
        $response = $this->actingAs($this->adminUser)->get(route('ormawa.delete', $ormawa->id));
        $response->assertRedirect(route('ormawa'));
        $this->assertSoftDeleted('ormawas', ['id' => $ormawa->id]);
    }

    public function test_role_crud()
    {
        // Create
        $response = $this->actingAs($this->adminUser)->post(route('role.store'), [
            'nama' => 'Staff Humas',
        ]);
        $response->assertRedirect(route('role'));
        $this->assertDatabaseHas('roles', ['role' => 'Staff Humas']);

        $role = Role::where('role', 'Staff Humas')->first();

        // Read
        $response = $this->actingAs($this->adminUser)->get(route('role'));
        $response->assertStatus(200);

        // Update
        $response = $this->actingAs($this->adminUser)->post(route('role.update', $role->id), [
            'nama' => 'Staff Humas Baru',
        ]);
        $response->assertRedirect(route('role'));
        $this->assertDatabaseHas('roles', ['id' => $role->id, 'role' => 'Staff Humas Baru']);

        // Delete
        $response = $this->actingAs($this->adminUser)->get(route('role.delete', $role->id));
        $response->assertRedirect(route('role'));
        $this->assertSoftDeleted('roles', ['id' => $role->id]);
    }

    public function test_user_crud()
    {
        $role = Role::factory()->create();

        // Create
        $response = $this->actingAs($this->adminUser)->post(route('user.store'), [
            'nama' => 'John Doe',
            'nip' => '199001012020121001',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'role' => (string) $role->id,
        ]);
        $response->assertRedirect(route('user'));
        $this->assertDatabaseHas('users', ['email' => 'johndoe@example.com']);

        $user = User::where('email', 'johndoe@example.com')->first();

        // Read
        $response = $this->actingAs($this->adminUser)->get(route('user'));
        $response->assertStatus(200);

        // Update
        $response = $this->actingAs($this->adminUser)->post(route('user.update', $user->id), [
            'nama' => 'John Doe Updated',
            'nip' => '199001012020121001',
            'email' => 'johndoe_updated@example.com',
            'password' => 'password123',
            'role' => (string) $role->id,
        ]);
        $response->assertRedirect(route('user'));
        $this->assertDatabaseHas('users', ['id' => $user->id, 'email' => 'johndoe_updated@example.com']);

        // Delete
        $response = $this->actingAs($this->adminUser)->get(route('user.delete', $user->id));
        $response->assertRedirect(route('user'));
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function test_mahasiswa_crud()
    {
        Storage::fake('public');

        $jurusan = Jurusan::factory()->create();
        $prodi = Prodi::factory()->create();
        $ormawa = Ormawa::factory()->create();

        // Create
        $response = $this->actingAs($this->adminUser)->post(route('mahasiswa.store'), [
            'nama' => 'Budi',
            'nim' => '362200001',
            'email' => 'budi@example.com',
            'whatsapp' => '08999999999',
            'jurusan' => $jurusan->id,
            'prodi' => $prodi->id,
            'ormawa' => $ormawa->id,
            'foto_profil' => UploadedFile::fake()->image('profile_budi.jpg'),
        ]);
        $response->assertRedirect(route('mahasiswa'));
        $this->assertDatabaseHas('mahasiswas', ['email' => 'budi@example.com']);

        $mhs = Mahasiswa::where('email', 'budi@example.com')->first();

        // Read
        $response = $this->actingAs($this->adminUser)->get(route('mahasiswa'));
        $response->assertStatus(200);

        // Update
        $response = $this->actingAs($this->adminUser)->post(route('mahasiswa.update', $mhs->id), [
            'nama' => 'Budi Updated',
            'nim' => '362200001',
            'email' => 'budi_up@example.com',
            'whatsapp' => '08999999999',
            'jurusan' => $jurusan->id,
            'prodi' => $prodi->id,
            'ormawa' => $ormawa->id,
        ]);
        $response->assertRedirect(route('mahasiswa'));
        $this->assertDatabaseHas('mahasiswas', ['id' => $mhs->id, 'email' => 'budi_up@example.com']);

        // Delete
        $response = $this->actingAs($this->adminUser)->get(route('mahasiswa.delete', $mhs->id));
        $response->assertRedirect(route('mahasiswa'));
        $this->assertSoftDeleted('mahasiswas', ['id' => $mhs->id]);
    }
}
