# Plan Revisi — Laporan Hasil Pengujian (BAB 4.1.2)
**Tujuan:** menyelaraskan `hasiltest.md` dengan metodologi yang dijanjikan di proposal (Unit Testing + Acceptance Testing, bukan "Black Box Testing"), dan melengkapi bagian yang masih kosong.

---

## 1. Masalah yang Harus Diperbaiki (hasil review sebelumnya)

| No | Masalah | Risiko jika tidak diperbaiki |
|----|---------|-------------------------------|
| 1 | Judul section pakai "Black Box Testing", proposal pakai "Acceptance Testing" | Dosen penguji menganggap metodologi BAB 3 & BAB 4 tidak konsisten |
| 2 | Tidak ada hasil Unit Testing sama sekali di BAB 4 | Salah satu dari 2 metode pengujian yang dijanjikan proposal tidak terbukti dilaksanakan |
| 3 | Kolom "Nama (Inisial)" responden masih `...` (kosong) | Tidak ada bukti pengujian dilakukan oleh end-user nyata |
| 4 | Hasil 100% "Sesuai" tanpa jejak revisi sama sekali | Tidak sejalan dengan narasi proposal yang menyebutkan ada siklus "ditemukan → diperbaiki → diuji ulang" |

---

## 2. Struktur BAB 4.1.2 Setelah Direvisi

Urutan baru yang disarankan (mengikuti urutan proposal: Unit Testing dulu, baru Acceptance Testing):

```
4.1.2 Hasil Pengujian
├── 4.1.2.1 Hasil Pengujian Unit Testing        ← BAGIAN BARU, perlu ditambahkan
├── 4.1.2.2 Hasil Pengujian Acceptance Testing  ← REVISI dari "Black Box Testing"
└── 4.1.2.3 Rekapitulasi Hasil Pengujian        ← gabungan ringkasan unit + acceptance
```

---

## 3. Rencana Kerja: Bagian Unit Testing (4.1.2.1)

### 3.1 Data yang perlu dikumpulkan dari hasil eksekusi PHPUnit

Berdasarkan modul yang sudah dirancang di `implementationPlan.md` (Bagian 2.1–2.7), jalankan dan catat:

```bash
php artisan test --testsuite=Unit
```

- [ ] Catat jumlah total test method per modul (Autentikasi, Pengajuan, Verifikasi, Persetujuan, Pengembalian, Data Master, Notifikasi).
- [ ] Catat status pass/fail per modul.
- [ ] Jika pakai coverage:
  ```bash
  php artisan test --coverage
  ```
  catat persentase coverage per folder (`app/Models`, `app/Http/Controllers`).

### 3.2 Format Tabel yang Disarankan

**Tabel 4.x Jumlah dan Hasil Unit Testing per Modul**

| No | Modul Pengujian | Jumlah Test Case | Pass | Fail |
|----|------------------|:-----------------:|:----:|:----:|
| 1 | Autentikasi (Login) | ... | ... | ... |
| 2 | Pengajuan Peminjaman | ... | ... | ... |
| 3 | Verifikasi Pengajuan | ... | ... | ... |
| 4 | Persetujuan Pengajuan | ... | ... | ... |
| 5 | Pengembalian & Pengecekan Kondisi Alat | ... | ... | ... |
| 6 | Data Master (Alat, Mahasiswa, Jurusan, Prodi, Ormawa, User/Role) | ... | ... | ... |
| 7 | Notifikasi (Email & WhatsApp) | ... | ... | ... |
| | **Total** | **...** | **...** | **...** |

- [ ] Tambahkan narasi 1 paragraf: jelaskan bahwa Unit Testing dilakukan menggunakan PHPUnit mengikuti siklus Red-Green-Refactor, sesuai BAB 2.1.15.
- [ ] Jika ada test yang awalnya fail lalu diperbaiki, sebutkan secara singkat (1-2 kalimat) sebagai bukti siklus TDD berjalan — ini juga menjawab masalah no. 4 di atas.
- [ ] Lampirkan screenshot ringkas hasil `php artisan test` di bagian lampiran (bukan di badan teks).

---

## 4. Rencana Kerja: Bagian Acceptance Testing (4.1.2.2)

### 4.1 Perubahan Penamaan (Find & Replace)

- [ ] Ganti semua kemunculan istilah:
  - "Hasil Pengujian Black Box" → "Hasil Pengujian Acceptance Testing"
  - "Pengujian *black box*" → "Acceptance Testing" / "*Acceptance Testing*"
  - "kuesioner untuk *black box*" → "instrumen pengujian Acceptance Testing"
- [ ] Sesuaikan penomoran tabel & section (4.1, 4.2, 4.3, 4.4 tetap bisa dipakai, hanya judulnya diganti).

### 4.2 Melengkapi Data Responden (Tabel 4.2)

- [ ] Isi kolom "Nama (Inisial)" dengan inisial nyata 3 penguji:
  - Mahasiswa: inisial nama mahasiswa yang menguji (boleh teman satu jurusan/pengguna alat Humas).
  - Staff Admin Humas: inisial nama staff Humas asli (sesuai narasi wawancara di proposal BAB 3.3.2).
  - Kepala Humas: inisial nama Kepala Humas asli.
- [ ] Jika belum ada sesi pengujian nyata dengan pihak Humas, jadwalkan dulu (lihat Bagian 6 — Timeline) sebelum tabel ini diisi, supaya datanya bukan rekaan.

### 4.3 Pertahankan Struktur Tabel 4.3 (Detail per Role)

Struktur tabel skenario per role (AT-M01–09, AT-A01–14, AT-K01–07) **sudah sesuai** dan tidak perlu diubah secara substansi — hanya judul section induknya yang diganti dari "Black Box" ke "Acceptance Testing".

- [ ] Cek ulang apakah ada skenario yang **benar-benar** ditemukan tidak sesuai saat pengujian riil (bukan asumsi semua lolos). Jika ada, isi kolom "Hasil" dengan "Tidak Sesuai" + tambahkan kolom/catatan singkat tindak lanjut perbaikan.
- [ ] Jika memang seluruhnya sesuai di percobaan pertama, tambahkan 1 kalimat narasi yang menjelaskan ini adalah hasil pengujian **setelah** Unit Testing memastikan fungsi dasar sudah benar — sehingga masuk akal kalau Acceptance Testing minim temuan.

---

## 5. Rencana Kerja: Rekapitulasi Gabungan (4.1.2.3)

- [ ] Gabungkan ringkasan dua tahap pengujian dalam satu tabel akhir:

**Tabel 4.x Rekapitulasi Keseluruhan Hasil Pengujian**

| No | Jenis Pengujian | Jumlah Test Case | Pass / Sesuai | Fail / Tidak Sesuai | Persentase |
|----|-------------------|:------------------:|:---------------:|:----------------------:|:------------:|
| 1 | Unit Testing | ... | ... | ... | ...% |
| 2 | Acceptance Testing | 30 | 30 | 0 | 100% |

- [ ] Tutup dengan paragraf simpulan yang menyebutkan **kedua** metode (bukan hanya acceptance), konsisten dengan klaim metodologi PXP di proposal: *"Dengan menerapkan kedua jenis pengujian tersebut secara konsisten... sistem SIPMAS yang dibangun dapat memenuhi standar kualitas perangkat lunak yang baik..."* (parafrase dari BAB 2.1.5 proposal).

---

## 6. Timeline Penyelesaian Revisi

| Tahap | Aktivitas | Estimasi |
|-------|-----------|----------|
| 1 | Jalankan/lengkapi unit test PHPUnit, kumpulkan angka pass/fail + coverage | 2-3 hari |
| 2 | Susun ulang `hasiltest.md`: tambah section 4.1.2.1 Unit Testing | 1 hari |
| 3 | Ganti istilah "Black Box" → "Acceptance Testing" di seluruh dokumen | 30 menit |
| 4 | Jadwalkan/konfirmasi ulang sesi pengujian dengan Staff Humas & Kepala Humas asli (jika belum), isi nama inisial responden | 1-3 hari (tergantung jadwal pihak Humas) |
| 5 | Review ulang tabel rekapitulasi gabungan & paragraf simpulan | 1 hari |
| 6 | Cek konsistensi penomoran tabel/gambar dengan BAB 4 keseluruhan | 30 menit |

---

## 7. Checklist Akhir Sebelum Dianggap Selesai

- [ ] Tidak ada lagi istilah "Black Box" di BAB 4 — seluruhnya "Unit Testing" / "Acceptance Testing".
- [ ] Section Unit Testing (4.1.2.1) berisi data riil dari `php artisan test`, bukan placeholder.
- [ ] Kolom nama responden Acceptance Testing terisi inisial nyata.
- [ ] Tabel rekapitulasi akhir menggabungkan kedua jenis pengujian.
- [ ] Narasi simpulan BAB 4.1.2 menyebut kedua metode pengujian secara eksplisit, sejalan dengan BAB 2 & BAB 3 proposal.
