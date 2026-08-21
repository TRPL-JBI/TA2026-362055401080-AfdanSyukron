# Implementation Plan — Testing SIPMAS
**Sistem Peminjaman Alat Humas (SIPMAS) — Politeknik Negeri Banyuwangi**
Metode: Personal Extreme Programming (PXP) | Pengujian: Unit Testing (PHPUnit) + Acceptance Testing (UAT manual)

---

## 0. Tujuan Dokumen

Dokumen ini menjadi panduan kerja untuk mengimplementasikan dua tahap pengujian yang disebutkan pada BAB 3.7 proposal:

1. **Unit Testing** — menguji fungsi/modul secara individual (login, pengajuan, verifikasi, persetujuan, pengembalian, pengecekan kondisi alat, notifikasi).
2. **Acceptance Testing** — validasi end-to-end oleh 3 kelompok pengguna (Mahasiswa, Staff Admin Humas, Kepala Humas) berdasarkan user story/use case yang sudah dirancang.

Karena codebase Laravel sudah berjalan, plan ini disusun sebagai **checklist eksekusi**, bukan tahap pembangunan fitur dari nol.

---

## 1. Persiapan Environment Testing

### 1.1 Konfigurasi PHPUnit + Database Testing

- [ ] Pastikan `phpunit.xml` di root project sudah ada (default Laravel sudah menyediakan).
- [ ] Buat database testing terpisah agar tidak mengotori data produksi/development:
  ```
  DB_CONNECTION=mysql
  DB_DATABASE=sipmas_testing
  ```
  Tambahkan environment variable ini di blok `<php>` pada `phpunit.xml`, **atau** gunakan SQLite in-memory untuk kecepatan:
  ```xml
  <env name="DB_CONNECTION" value="sqlite"/>
  <env name="DB_DATABASE" value=":memory:"/>
  ```
  > Catatan: jika ada query yang spesifik MySQL (misal `JSON_CONTAINS`, raw SQL tertentu), tetap pakai MySQL testing database agar perilaku query konsisten.

- [ ] Jalankan migrasi di database testing:
  ```bash
  php artisan migrate --env=testing
  ```
- [ ] Cek `RefreshDatabase` trait tersedia (bawaan Laravel) untuk reset state antar test.

### 1.2 Struktur Folder Test

Laravel sudah menyediakan struktur dasar. Sesuaikan/lengkapi menjadi:

```
tests/
├── Unit/
│   ├── Auth/
│   │   └── LoginTest.php
│   ├── Pengajuan/
│   │   ├── PengajuanPeminjamanTest.php
│   │   ├── ValidasiKetersediaanAlatTest.php
│   │   └── DetailPengajuanTest.php
│   ├── Verifikasi/
│   │   └── VerifikasiPengajuanTest.php
│   ├── Persetujuan/
│   │   └── PersetujuanPengajuanTest.php
│   ├── Pengembalian/
│   │   ├── PengembalianAlatTest.php
│   │   └── PengecekanKondisiAlatTest.php
│   ├── Master/
│   │   ├── AlatTest.php
│   │   ├── MahasiswaTest.php
│   │   ├── JurusanTest.php
│   │   ├── ProdiTest.php
│   │   ├── OrmawaTest.php
│   │   └── RoleUserTest.php
│   └── Notifikasi/
│       ├── EmailNotificationTest.php
│       └── WhatsAppNotificationTest.php
├── Feature/        (opsional, untuk uji HTTP/route + middleware role)
│   ├── AuthFlowTest.php
│   ├── PengajuanFlowTest.php
│   └── DashboardAccessTest.php
└── Acceptance/
    └── (dokumen skenario UAT — lihat Bagian 3, bukan kode otomatis)
```

> Pembagian folder ini mengikuti pemetaan fungsi yang disebutkan eksplisit di BAB 2.1.5 dan BAB 2.1.15 proposal: *fungsi login, fungsi pengajuan peminjaman, fungsi verifikasi, fungsi persetujuan, fungsi pengembalian alat, fungsi pengecekan kondisi alat*.

### 1.3 Tooling Tambahan

- [ ] Install Faker (biasanya sudah include via `laravel/framework` dev dependency) untuk generate data dummy alat/mahasiswa/pengajuan.
- [ ] Jika perlu factory yang belum ada, buat di `database/factories/`:
  - `UserFactory` (sudah default Laravel — sesuaikan field role)
  - `MahasiswaFactory`
  - `AlatFactory`
  - `PengajuanFactory`
  - `DetailPengajuanFactory`
- [ ] Untuk testing notifikasi (Email & Fonnte WhatsApp API), gunakan **mocking/fake**, jangan kirim notifikasi sungguhan saat test:
  ```php
  Mail::fake();
  Http::fake(); // untuk mock request ke Fonnte API
  ```

---

## 2. Unit Testing — Rencana Eksekusi per Modul

Setiap modul mengikuti siklus **Red–Green–Refactor** sesuai TDD yang dijelaskan di BAB 2.1.15:
1. Tulis test case (gagal dulu / Red) — *karena codebase sudah ada, tahap ini menjadi "tulis test berdasarkan perilaku yang diharapkan, jalankan, lihat mana yang gagal"*.
2. Perbaiki kode agar test lulus (Green).
3. Refactor kode tanpa mengubah perilaku, pastikan test tetap hijau.

### 2.1 Modul Autentikasi (Login)

| No | Skenario Uji | Jenis | Expected Result |
|----|--------------|-------|------------------|
| U1 | Login dengan email/username & password valid (role Mahasiswa) | Positive | Berhasil, redirect ke dashboard mahasiswa |
| U2 | Login dengan email/username & password valid (role Staff Admin) | Positive | Berhasil, redirect ke dashboard admin |
| U3 | Login dengan email/username & password valid (role Kepala Humas) | Positive | Berhasil, redirect ke dashboard kepala humas |
| U4 | Login dengan password salah | Negative | Gagal, pesan error kredensial salah |
| U5 | Login dengan akun tidak terdaftar | Negative | Gagal, pesan error akun tidak ditemukan |
| U6 | Login dengan akun nonaktif/disabled (jika ada status user) | Negative | Gagal, pesan akun tidak aktif |
| U7 | Akses halaman dashboard tanpa login (middleware auth) | Negative | Redirect ke halaman login |
| U8 | Akses halaman role lain tanpa izin (middleware role, mis. mahasiswa akses dashboard admin) | Negative | Forbidden / redirect |

Contoh kerangka test:
```php
public function test_mahasiswa_dapat_login_dengan_kredensial_valid()
{
    $user = User::factory()->create([
        'password' => Hash::make('password123'),
        'role' => 'mahasiswa',
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($user);
}
```

### 2.2 Modul Pengajuan Peminjaman

| No | Skenario Uji | Expected Result |
|----|--------------|------------------|
| U9 | Mahasiswa mengajukan peminjaman dengan data lengkap & alat tersedia | Pengajuan tersimpan, status = "menunggu verifikasi" |
| U10 | Mahasiswa mengajukan tanpa upload surat pengantar ormawa | Validasi gagal, pesan dokumen wajib diunggah |
| U11 | Mahasiswa mengajukan tanpa upload KTM | Validasi gagal |
| U12 | Mahasiswa mengajukan dengan jumlah alat melebihi stok tersedia | Validasi gagal, pesan stok tidak cukup |
| U13 | Mahasiswa mengajukan dengan tanggal pengembalian < tanggal peminjaman | Validasi gagal |
| U14 | Sistem mengecek ketersediaan alat otomatis saat pengajuan dibuat | Stok alat ter-reservasi/berkurang sesuai logika sistem |
| U15 | Detail pengajuan tersimpan relasi yang benar ke tabel `alat` & `pengajuan` | Data integritas relasional benar |

### 2.3 Modul Verifikasi (oleh Staff Admin Humas)

| No | Skenario Uji | Expected Result |
|----|--------------|------------------|
| U16 | Staff admin memverifikasi pengajuan dengan dokumen lengkap | Status berubah jadi "menunggu persetujuan" |
| U17 | Staff admin menolak pengajuan karena dokumen tidak valid | Status berubah jadi "ditolak verifikasi", alasan tersimpan |
| U18 | Staff admin mencoba verifikasi pengajuan yang sudah diverifikasi sebelumnya | Sistem mencegah duplikasi aksi / menampilkan status terkini |

### 2.4 Modul Persetujuan (oleh Kepala Humas)

| No | Skenario Uji | Expected Result |
|----|--------------|------------------|
| U19 | Kepala Humas menyetujui pengajuan yang sudah terverifikasi | Status = "disetujui", stok alat resmi terkunci/dipinjam |
| U20 | Kepala Humas menolak pengajuan | Status = "ditolak", alat dikembalikan ke stok tersedia |
| U21 | Kepala Humas mencoba menyetujui pengajuan yang belum diverifikasi staff | Aksi ditolak sistem (alur tidak boleh dilompati) |
| U22 | Keputusan persetujuan/penolakan memicu trigger notifikasi | Job/Event notifikasi terpanggil (cek dengan `Notification::fake()` / `Mail::fake()`) |

### 2.5 Modul Pengembalian Alat & Pengecekan Kondisi

| No | Skenario Uji | Expected Result |
|----|--------------|------------------|
| U23 | Staff admin mencatat pengembalian alat tepat waktu, kondisi baik | Status pengajuan = "selesai", stok alat bertambah kembali |
| U24 | Staff admin mencatat pengembalian dengan kondisi alat rusak/hilang | Status alat berubah jadi "rusak"/"tidak tersedia", tercatat di histori |
| U25 | Pengembalian melewati tanggal jatuh tempo | Sistem menandai status terlambat (jika ada logika ini) |
| U26 | Update status alat tersinkron dengan dashboard ketersediaan | Query dashboard menunjukkan data terbaru |

### 2.6 Modul Data Master (CRUD)

Untuk setiap entitas master (Alat, Mahasiswa, Jurusan, Prodi, Ormawa, User & Role) — uji pola CRUD standar:

| No | Skenario Uji | Expected Result |
|----|--------------|------------------|
| U27 | Tambah data master dengan input valid | Data tersimpan, muncul di listing |
| U28 | Tambah data master dengan field wajib kosong | Validasi gagal |
| U29 | Edit data master | Data terupdate sesuai input baru |
| U30 | Hapus data master yang sedang dipakai relasi aktif (mis. alat yang sedang dipinjam) | Sistem mencegah penghapusan / soft delete |
| U31 | Hapus data master tanpa relasi aktif | Data terhapus / soft delete berhasil |

> Modul ini diulang untuk: `AlatTest`, `MahasiswaTest`, `JurusanTest`, `ProdiTest`, `OrmawaTest`, `RoleUserTest` — tidak perlu unique logic kecuali ada validasi khusus per entitas (misal nomor seri alat harus unik).

### 2.7 Modul Notifikasi (Email & WhatsApp via Fonnte)

| No | Skenario Uji | Expected Result |
|----|--------------|------------------|
| U32 | Notifikasi email terkirim saat status pengajuan berubah | `Mail::fake()` mendeteksi mail terkirim ke email mahasiswa yang benar |
| U33 | Notifikasi WhatsApp (Fonnte API) terpanggil saat status berubah | `Http::fake()` mendeteksi request ke endpoint Fonnte dengan payload benar |
| U34 | Kegagalan kirim notifikasi (API down) tidak menggagalkan proses utama | Proses approval/verifikasi tetap berhasil walau notifikasi gagal (cek exception handling / queue retry) |

### 2.8 Target Cakupan & Definition of Done Unit Testing

- [ ] Semua fungsi inti pada Tabel 2.1–2.7 di atas memiliki minimal 1 test case positive & 1 negative.
- [ ] Jalankan dengan code coverage report:
  ```bash
  php artisan test --coverage
  ```
  atau jika pakai Xdebug/PCOV:
  ```bash
  XDEBUG_MODE=coverage php artisan test --coverage --min=70
  ```
- [ ] Target awal: **minimal 70% coverage** pada folder `app/Http/Controllers`, `app/Models`, `app/Services` (jika ada service layer).
- [ ] Semua test **hijau** sebelum lanjut ke Acceptance Testing.
- [ ] Dokumentasikan hasil run terakhir (screenshot/log) untuk dilampirkan di BAB 4 laporan TA.

---

## 3. Acceptance Testing — Rencana Eksekusi (UAT Manual)

Sesuai BAB 2.1.16 & 3.7 proposal: dilakukan langsung oleh 3 kelompok pengguna nyata berdasarkan skenario yang disusun dari use case. Berikut rencana pelaksanaannya.

### 3.1 Persiapan UAT

- [ ] Siapkan environment **staging** (bukan production, bukan local dev) yang datanya bersih/representatif — gunakan data dummy realistis (nama alat asli Humas, daftar mahasiswa contoh).
- [ ] Siapkan akun uji untuk masing-masing role:
  - 1–2 akun Mahasiswa
  - 1 akun Staff Admin Humas
  - 1 akun Kepala Humas
- [ ] Siapkan dokumen **skenario pengujian** (lihat 3.2–3.4) dan **form penilaian/checklist** (lihat 3.5) — cetak atau dalam bentuk Google Form/spreadsheet.
- [ ] Jadwalkan sesi pengujian dengan masing-masing pengguna (bisa terpisah per role atau end-to-end bersama-sama mensimulasikan alur penuh).

### 3.2 Skenario UAT — Role Mahasiswa (9 Use Case)

| Kode | Skenario | Langkah Pengujian | Kriteria Diterima |
|------|----------|--------------------|--------------------|
| AT-M01 | Login mahasiswa | Login menggunakan NIM & password | Berhasil masuk ke dashboard |
| AT-M02 | Melihat dashboard | Buka halaman utama setelah login | Informasi panduan/ringkasan tampil jelas |
| AT-M03 | Melihat daftar & detail alat | Buka menu daftar alat, klik salah satu alat | Stok, kondisi, deskripsi alat tampil akurat |
| AT-M04 | Mengajukan peminjaman alat | Isi formulir pengajuan, upload surat pengantar ormawa & KTM | Pengajuan berhasil terkirim, muncul di daftar pengajuan dengan status "menunggu" |
| AT-M05 | Validasi pengajuan tidak lengkap | Coba submit tanpa salah satu dokumen wajib | Sistem menolak & menampilkan pesan jelas |
| AT-M06 | Memantau status pengajuan | Refresh halaman daftar pengajuan setelah staff/kepala humas memproses | Status berubah real-time sesuai aksi admin |
| AT-M07 | Menerima notifikasi keputusan | Tunggu setelah Kepala Humas menyetujui/menolak | Notifikasi email & WhatsApp diterima mahasiswa |
| AT-M08 | Melihat riwayat peminjaman | Buka menu history | Seluruh riwayat (disetujui/ditolak/selesai) tampil lengkap |
| AT-M09 | Mengelola akun pribadi | Ubah profil/password | Perubahan tersimpan, bisa login ulang dengan password baru |

### 3.3 Skenario UAT — Role Staff Admin Humas (14 Use Case)

| Kode | Skenario | Langkah Pengujian | Kriteria Diterima |
|------|----------|--------------------|--------------------|
| AT-A01 | Login staff admin | Login dengan akun staff | Masuk ke dashboard admin |
| AT-A02 | Melihat dashboard ringkasan | Buka dashboard | Ringkasan pengajuan, jumlah mahasiswa, jumlah alat tampil benar |
| AT-A03 | Kelola data alat (CRUD) | Tambah, edit, hapus data alat | Perubahan tersimpan & tampil di listing |
| AT-A04 | Kelola data mahasiswa | Tambah/edit/hapus data mahasiswa | Data konsisten |
| AT-A05 | Kelola data jurusan | CRUD jurusan | Data konsisten |
| AT-A06 | Kelola data prodi | CRUD prodi | Data konsisten, terkait jurusan yang benar |
| AT-A07 | Kelola data ormawa | CRUD ormawa | Data konsisten |
| AT-A08 | Kelola data user & role | Tambah user baru, atur role | User baru bisa login sesuai role yang diberikan |
| AT-A09 | Verifikasi pengajuan masuk | Buka daftar pengajuan baru, cek dokumen (surat pengantar, KTM) | Bisa menyetujui/menolak verifikasi dengan alasan |
| AT-A10 | Meneruskan pengajuan terverifikasi ke Kepala Humas | Setelah verifikasi disetujui | Pengajuan otomatis muncul di antrian Kepala Humas |
| AT-A11 | Kelola pengembalian alat | Catat pengembalian, cek kondisi alat | Status alat & pengajuan terupdate sesuai kondisi |
| AT-A12 | Melihat histori peminjaman | Buka menu histori | Seluruh transaksi historis tampil & bisa difilter |
| AT-A13 | Mencetak/unduh laporan peminjaman | Generate laporan (periode tertentu) | File laporan terunduh dengan data akurat |
| AT-A14 | Kelola akun pribadi | Ubah profil/password staff admin | Perubahan tersimpan |

### 3.4 Skenario UAT — Role Kepala Humas (7 Use Case)

| Kode | Skenario | Langkah Pengujian | Kriteria Diterima |
|------|----------|--------------------|--------------------|
| AT-K01 | Login kepala humas | Login dengan akun kepala humas | Masuk ke dashboard |
| AT-K02 | Melihat dashboard ringkasan pengajuan | Buka dashboard | Daftar pengajuan masuk & stok alat tampil |
| AT-K03 | Melihat daftar pengajuan terverifikasi | Buka menu daftar pengajuan | Hanya pengajuan yang sudah diverifikasi staff yang muncul untuk diputuskan |
| AT-K04 | Menyetujui pengajuan | Klik setujui pada salah satu pengajuan | Status berubah "disetujui", notifikasi terkirim ke mahasiswa |
| AT-K05 | Menolak pengajuan | Klik tolak, isi alasan | Status berubah "ditolak", notifikasi terkirim ke mahasiswa |
| AT-K06 | Melihat rekapitulasi peminjaman bulanan | Buka menu rekap/laporan bulanan | Data agregat (jumlah pengajuan, alat dipinjam, dst.) sesuai data riil |
| AT-K07 | Kelola akun pribadi | Ubah profil/password | Perubahan tersimpan |

### 3.5 Alur Pengujian End-to-End (Gabungan 3 Role)

Selain pengujian per-role di atas, lakukan **1 skenario alur penuh** yang melibatkan ketiga aktor secara berurutan untuk memvalidasi integrasi antar role (sesuai activity diagram BAB 3.4.1):

1. Mahasiswa mengajukan peminjaman alat lengkap dengan dokumen.
2. Staff Admin memverifikasi kelengkapan dokumen → meneruskan ke Kepala Humas.
3. Kepala Humas menyetujui pengajuan.
4. Mahasiswa menerima notifikasi & melihat status "disetujui".
5. Mahasiswa mengambil alat (dicatat manual/di sistem jika ada fitur ini).
6. Mahasiswa mengembalikan alat sesuai jadwal.
7. Staff Admin mencatat pengembalian & mengecek kondisi alat.
8. Status alat kembali "tersedia", riwayat tercatat di ketiga dashboard.

- [ ] Catat waktu tempuh tiap tahap (untuk mengukur efisiensi dibanding proses manual — relevan untuk pembahasan Tabel 3.3 Analisis Perbandingan di proposal).
- [ ] Catat kendala/bug yang ditemukan di log terpisah.

### 3.6 Form Penilaian UAT

Gunakan format berikut untuk setiap baris skenario (AT-M01, AT-A01, dst.):

| Kode Skenario | Deskripsi | Hasil (Sesuai / Tidak Sesuai) | Catatan/Bug | Tanggal | Penguji (Nama & Role) |
|---------------|-----------|-------------------------------|-------------|---------|------------------------|

- [ ] Setiap penguji (minimal 1 mahasiswa, 1 staff admin, 1 kepala humas — bisa dosen pembimbing/pihak Humas asli sesuai narasi wawancara di proposal) mengisi & menandatangani form.
- [ ] Hitung persentase kelulusan: `(jumlah skenario "Sesuai" / total skenario) x 100%`.
- [ ] Lampirkan hasil ini sebagai bukti di BAB 4 (Hasil dan Pembahasan) laporan TA.

### 3.7 Penanganan Temuan (Bug/Ketidaksesuaian)

Mengikuti narasi BAB 3.7 proposal: *"Apabila pada proses pengujian ditemukan fitur yang tidak sesuai... maka dilakukan perbaikan... selanjutnya sistem akan diuji kembali."*

- [ ] Catat setiap temuan di log bug (bisa pakai tabel sederhana: ID, deskripsi, severity, status).
- [ ] Perbaiki kode → tambahkan/perbarui unit test terkait agar regresi tidak terjadi.
- [ ] Re-run unit test penuh (`php artisan test`).
- [ ] Ulangi skenario UAT yang sebelumnya gagal sampai dinyatakan "Sesuai".

---

## 4. Timeline Eksekusi (Disarankan)

| Minggu | Aktivitas |
|--------|-----------|
| 1 | Setup environment testing, buat factory, tulis & jalankan unit test modul Autentikasi + Pengajuan |
| 2 | Unit test modul Verifikasi, Persetujuan, Pengembalian, Notifikasi + cek coverage |
| 3 | Unit test modul Data Master, perbaikan bug dari hasil testing, finalisasi coverage report |
| 4 | Siapkan environment staging & dokumen skenario UAT, jadwalkan sesi dengan pengguna nyata |
| 5 | Eksekusi UAT per role + alur end-to-end, kumpulkan form penilaian |
| 6 | Perbaikan berdasarkan temuan UAT, re-testing, dokumentasi hasil untuk BAB 4 |

---

## 5. Checklist Akhir Sebelum Dianggap "Selesai"

- [ ] Semua unit test (`php artisan test --testsuite=Unit`) lulus 100%.
- [ ] Coverage report tersimpan (screenshot/HTML report) sebagai lampiran.
- [ ] Semua skenario UAT (30 skenario: 9 Mahasiswa + 14 Staff Admin + 7 Kepala Humas) terisi form penilaiannya.
- [ ] Skenario end-to-end gabungan 3 role berhasil tanpa bug blocking.
- [ ] Seluruh bug yang ditemukan sudah diperbaiki & diverifikasi ulang.
- [ ] Dokumen hasil pengujian (unit test + UAT) siap dilampirkan ke BAB 4 laporan TA.
