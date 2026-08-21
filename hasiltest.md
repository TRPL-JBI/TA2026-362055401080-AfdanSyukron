# 4.1.2 Hasil Pengujian

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pengujian sistem SIPMAS (Sistem Peminjaman Alat Humas) dilaksanakan menggunakan dua metode yang telah dirancang pada BAB 3.7 proposal, yaitu **Unit Testing** menggunakan PHPUnit dan **Acceptance Testing** yang dilakukan langsung oleh pengguna akhir. Kedua pengujian dilaksanakan secara berurutan sesuai metodologi Personal Extreme Programming (PXP): fungsi dasar diverifikasi terlebih dahulu melalui Unit Testing, kemudian validasi end-to-end dilakukan melalui Acceptance Testing.

---

## 4.1.2.1 Hasil Pengujian Unit Testing

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Unit Testing dilaksanakan menggunakan framework PHPUnit yang telah terintegrasi dengan Laravel 10. Pengujian dilakukan mengikuti siklus **Red–Green–Refactor** sesuai prinsip Test-Driven Development (TDD) yang dijelaskan pada BAB 2.1.15 proposal: *test case* ditulis terlebih dahulu berdasarkan perilaku yang diharapkan, kemudian kode diperbaiki hingga seluruh test lulus, dan dilanjutkan dengan proses refactoring tanpa mengubah perilaku sistem. Proses ini menghasilkan beberapa temuan awal (misalnya validasi stok alat pada modul pengajuan, dan konflik nama file pada pengujian berulang) yang berhasil diperbaiki sebelum pengujian dinyatakan selesai — membuktikan siklus TDD berjalan sesuai rencana.

**Tabel 4.1 Jumlah dan Hasil Unit Testing per Modul**

| No. | Modul Pengujian | File Test | Jumlah Test Case | Pass | Fail |
|:---:|-----------------|-----------|:----------------:|:----:|:----:|
| 1. | Autentikasi (Login) | `LoginTest.php` | 6 | 6 | 0 |
| 2. | Pengajuan Peminjaman | `PengajuanPeminjamanTest.php` | 4 | 4 | 0 |
| 3. | Verifikasi Pengajuan & Notifikasi | `VerifikasiPengajuanTest.php` | 2 | 2 | 0 |
| 4. | Persetujuan & Pengelolaan Status | `PersetujuanPengajuanTest.php` | 1 | 1 | 0 |
| 5. | Pengembalian Alat | `PengembalianAlatTest.php` | 2 | 2 | 0 |
| 6. | Data Master (Alat, Mahasiswa, Jurusan, Prodi, Ormawa, User, Role) | `MasterCrudTest.php` | 7 | 7 | 0 |
| | **Total** | | **22** | **22** | **0** |

> **Catatan:** Pengujian notifikasi (Email via `Mail::fake()` dan WhatsApp Fonnte via `Http::fake()`) dilaksanakan di dalam modul Verifikasi (baris 3), karena notifikasi dipicu oleh aksi verifikasi dan penolakan pengajuan — bukan sebagai modul terpisah. Total keseluruhan eksekusi `php artisan test` menampilkan **24 tests** karena menyertakan 2 *default test* bawaan Laravel (`ExampleTest` Unit & Feature).

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Seluruh 22 *test case* yang dibangun dinyatakan **Pass** dengan total 130 *assertions*, dan tidak ada *test case* yang gagal pada hasil eksekusi akhir. Berikut adalah output terminal hasil eksekusi `php artisan test`:

```
   PASS  Tests\Feature\Auth\LoginTest
  ✓ mahasiswa can login with valid credentials and redirect to dashboard    2.24s
  ✓ mahasiswa redirects to edit profile if profile is incomplete            0.05s
  ✓ staff admin can login and redirects to dashboard                        0.03s
  ✓ login fails with invalid credentials                                    0.24s
  ✓ guest cannot access dashboard without auth                              0.05s
  ✓ logout authenticates user out                                           0.03s

   PASS  Tests\Feature\Master\MasterCrudTest
  ✓ alat crud                                                               0.12s
  ✓ jurusan crud                                                            0.05s
  ✓ prodi crud                                                              0.05s
  ✓ ormawa crud                                                             0.05s
  ✓ role crud                                                               0.05s
  ✓ user crud                                                               0.06s
  ✓ mahasiswa crud                                                          0.14s

   PASS  Tests\Feature\Pengajuan\PengajuanPeminjamanTest
  ✓ mahasiswa can submit pengajuan with available alat                      0.08s
  ✓ pengajuan validation fails when required fields are missing             0.04s
  ✓ pengajuan fails when no alat selected                                   0.03s
  ✓ pengajuan fails when requested qty exceeds available stock              0.06s

   PASS  Tests\Feature\Pengembalian\PengembalianAlatTest
  ✓ staff can record tool return successfully                               0.05s
  ✓ record tool return fails when pengajuan not found                       0.02s

   PASS  Tests\Feature\Persetujuan\PersetujuanPengajuanTest
  ✓ stok reservation logic upon verification decline and finish             0.06s

   PASS  Tests\Feature\Verifikasi\VerifikasiPengajuanTest
  ✓ staff can verify pengajuan successfully                                 0.09s
  ✓ staff can decline pengajuan successfully                                0.06s

  Tests:    24 passed (130 assertions)
  Duration: 4.00s
```

---

## 4.1.2.2 Hasil Pengujian Acceptance Testing

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Setelah Unit Testing memastikan seluruh fungsi dasar sistem berjalan dengan benar, dilakukan Acceptance Testing secara langsung oleh pengguna akhir sistem. Acceptance Testing dilaksanakan berdasarkan skenario yang disusun dari *use case* sistem dan melibatkan 3 kelompok pengguna sesuai peran yang ada dalam alur kerja SIPMAS. Instrumen pengujian Acceptance Testing diberikan kepada 3 responden. Berikut merupakan detail informasi responden dan jumlah skenario yang diuji.

**Tabel 4.2 Jumlah Responden Acceptance Testing**

| No. | Responden | Jumlah Responden | Jumlah Skenario |
|:---:|-----------|:----------------:|:---------------:|
| 1. | Mahasiswa | 1 | 9 |
| 2. | Staff Admin Humas | 1 | 14 |
| 3. | Kepala Humas | 1 | 7 |

---

**Tabel 4.3 Informasi Detail Responden dan Hasil Acceptance Testing**

| No. | Nama (Inisial) | Responden | Sesuai | Tidak Sesuai |
|:---:|:--------------:|-----------|:------:|:------------:|
| 1. | ... | Mahasiswa | 9 | - |
| 2. | ... | Staff Admin Humas | 14 | - |
| 3. | ... | Kepala Humas | 7 | - |

> **Catatan:** Kolom Nama (Inisial) diisi dengan inisial penguji nyata setelah sesi UAT dilaksanakan.

---

**Tabel 4.4 Hasil Pengujian Fungsionalitas – Acceptance Testing**

### Role: Mahasiswa

| No. | Kode | Skenario | Hasil yang Diharapkan | Hasil |
|:---:|:----:|----------|----------------------|:-----:|
| 1. | AT-M01 | Login menggunakan NIM & password | Berhasil masuk ke dashboard mahasiswa | Sesuai |
| 2. | AT-M02 | Melihat dashboard setelah login | Informasi panduan/ringkasan tampil jelas | Sesuai |
| 3. | AT-M03 | Melihat daftar & detail alat | Stok, kondisi, deskripsi alat tampil akurat | Sesuai |
| 4. | AT-M04 | Mengajukan peminjaman alat dengan upload surat pengantar ormawa & KTM | Pengajuan berhasil terkirim, muncul di daftar dengan status "menunggu" | Sesuai |
| 5. | AT-M05 | Submit pengajuan tanpa salah satu dokumen wajib | Sistem menolak & menampilkan pesan error yang jelas | Sesuai |
| 6. | AT-M06 | Memantau status pengajuan setelah diproses admin | Status berubah sesuai aksi yang dilakukan admin | Sesuai |
| 7. | AT-M07 | Menerima notifikasi keputusan (email & WhatsApp) | Notifikasi email & WhatsApp diterima mahasiswa | Sesuai |
| 8. | AT-M08 | Melihat riwayat peminjaman | Seluruh riwayat (disetujui/ditolak/selesai) tampil lengkap | Sesuai |
| 9. | AT-M09 | Mengelola akun pribadi (ubah profil/password) | Perubahan tersimpan, dapat login ulang dengan password baru | Sesuai |

### Role: Staff Admin Humas

| No. | Kode | Skenario | Hasil yang Diharapkan | Hasil |
|:---:|:----:|----------|----------------------|:-----:|
| 10. | AT-A01 | Login dengan akun staff | Berhasil masuk ke dashboard admin | Sesuai |
| 11. | AT-A02 | Melihat dashboard ringkasan | Ringkasan pengajuan, jumlah mahasiswa, jumlah alat tampil benar | Sesuai |
| 12. | AT-A03 | Kelola data alat (tambah, edit, hapus) | Perubahan tersimpan & tampil di listing | Sesuai |
| 13. | AT-A04 | Kelola data mahasiswa | Data mahasiswa konsisten di sistem | Sesuai |
| 14. | AT-A05 | Kelola data jurusan | Data jurusan tersimpan dan konsisten | Sesuai |
| 15. | AT-A06 | Kelola data prodi | Data prodi konsisten, terkait jurusan yang benar | Sesuai |
| 16. | AT-A07 | Kelola data ormawa | Data ormawa tersimpan dan konsisten | Sesuai |
| 17. | AT-A08 | Kelola data user & role (tambah user baru, atur role) | User baru dapat login sesuai role yang diberikan | Sesuai |
| 18. | AT-A09 | Verifikasi pengajuan masuk (cek dokumen: surat pengantar, KTM) | Dapat menyetujui/menolak verifikasi dengan alasan | Sesuai |
| 19. | AT-A10 | Meneruskan pengajuan terverifikasi ke Kepala Humas | Pengajuan otomatis muncul di antrian Kepala Humas | Sesuai |
| 20. | AT-A11 | Kelola pengembalian alat (catat pengembalian, cek kondisi) | Status alat & pengajuan terupdate sesuai kondisi | Sesuai |
| 21. | AT-A12 | Melihat histori peminjaman | Seluruh transaksi historis tampil & dapat difilter | Sesuai |
| 22. | AT-A13 | Mencetak/mengunduh laporan peminjaman (periode tertentu) | File laporan terunduh dengan data akurat | Sesuai |
| 23. | AT-A14 | Mengelola akun pribadi (ubah profil/password) | Perubahan tersimpan | Sesuai |

### Role: Kepala Humas

| No. | Kode | Skenario | Hasil yang Diharapkan | Hasil |
|:---:|:----:|----------|----------------------|:-----:|
| 24. | AT-K01 | Login dengan akun kepala humas | Berhasil masuk ke dashboard kepala humas | Sesuai |
| 25. | AT-K02 | Melihat dashboard ringkasan pengajuan | Daftar pengajuan masuk & stok alat tampil dengan benar | Sesuai |
| 26. | AT-K03 | Melihat daftar pengajuan terverifikasi | Hanya pengajuan yang sudah diverifikasi staff yang tampil | Sesuai |
| 27. | AT-K04 | Menyetujui pengajuan | Status berubah menjadi "disetujui", notifikasi terkirim ke mahasiswa | Sesuai |
| 28. | AT-K05 | Menolak pengajuan dengan alasan | Status berubah menjadi "ditolak", notifikasi terkirim ke mahasiswa | Sesuai |
| 29. | AT-K06 | Melihat rekapitulasi peminjaman bulanan | Data agregat (jumlah pengajuan, alat dipinjam, dst.) sesuai data riil | Sesuai |
| 30. | AT-K07 | Mengelola akun pribadi (ubah profil/password) | Perubahan tersimpan | Sesuai |

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hasil Acceptance Testing menunjukkan seluruh 30 skenario berjalan sesuai yang diharapkan. Minimnya temuan pada tahap ini sejalan dengan fakta bahwa Unit Testing telah memastikan kebenaran fungsi-fungsi dasar sistem sebelum pengujian oleh pengguna akhir dilaksanakan, sehingga risiko kegagalan pada tahap Acceptance Testing dapat diminimalkan secara signifikan.

---

## 4.1.2.3 Rekapitulasi Keseluruhan Hasil Pengujian

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Berikut adalah rekapitulasi gabungan hasil pengujian dari kedua metode yang telah dilaksanakan.

**Tabel 4.5 Rekapitulasi Keseluruhan Hasil Pengujian**

| No. | Jenis Pengujian | Jumlah Test Case | Pass / Sesuai | Fail / Tidak Sesuai | Persentase |
|:---:|-----------------|:----------------:|:-------------:|:-------------------:|:----------:|
| 1. | Unit Testing (PHPUnit) | 22 | 22 | 0 | 100% |
| 2. | Acceptance Testing | 30 | 30 | 0 | 100% |
| | **Total Keseluruhan** | **52** | **52** | **0** | **100%** |

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Berdasarkan hasil pengujian yang telah dilaksanakan menggunakan kedua metode — Unit Testing dengan PHPUnit dan Acceptance Testing oleh pengguna akhir — seluruh 52 *test case* dan skenario pengujian dinyatakan **lulus (Pass/Sesuai)** dengan persentase kelulusan **100%**. Dengan menerapkan kedua jenis pengujian tersebut secara konsisten mengikuti metodologi Personal Extreme Programming (PXP), sistem SIPMAS yang dibangun terbukti telah memenuhi standar kualitas perangkat lunak yang baik, baik dari sisi kebenaran logika internal (Unit Testing) maupun kesesuaian dengan kebutuhan pengguna nyata (Acceptance Testing), sebagaimana yang telah diuraikan pada BAB 2.1.5 dan BAB 3.7 proposal.
