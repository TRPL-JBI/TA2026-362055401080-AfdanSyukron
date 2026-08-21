<!-- resources/views/pengajuans/create.blade.php -->

@extends('layout.main')

@section('content')

<link href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css" rel="stylesheet" />

<div class="container">
    <h1>Tambah Pengajuan</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-3">
            <label for="nama_kegiatan" class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" required>
        </div>
        <div class="row align-items-center">
            <div class="mb-3 col">
                <label for="tanggal_peminjaman" class="form-label text-xs">Tanggal Peminjaman <span class="text-danger">*</span></label>
                <input type="text" id="peminjaman" class="form-control" name="tanggal_peminjaman" value="{{ old('tanggal_peminjaman') }}" style="height: 40px;" required>
            </div>
            <div class="mb-3 col-auto text-center mt-3">
                <span>s/d</span>
            </div>
            <div class="mb-3 col">
                <label for="tanggal_pengembalian" class="form-label text-xs">Tanggal Pengembalian <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="pengembalian" name="tanggal_pengembalian" value="{{ old('tanggal_pengembalian') }}" style="height: 40px;" required>
            </div>
            <div class="mb-3 col">
                <label for="file" class="form-label text-xs">File Pengajuan (PDF/Doc) <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="file" name="file" accept=".pdf,.doc,.docx" style="height: 40px;" required>
            </div>
            <div class="mb-3 col">
                <label for="ktm" class="form-label text-xs">Kartu Tanda Mahasiswa (JPG/PNG/PDF) <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="ktm" name="ktm" accept=".jpg,.jpeg,.png,.pdf" style="height: 40px;" required>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-4">
                <label class="form-label font-weight-bolder">Daftar Alat <span class="text-danger">*</span></label>
                <div class="table-responsive border rounded" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-sm align-items-center mb-0">
                        <thead class="bg-light sticky-top">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 5%">Pilih</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Serial</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Alat</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Deskripsi</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Tersedia</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 10%">Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alat as $j)
                            <tr class="{{ $j->stok_tersedia <= 0 ? 'bg-light text-muted' : '' }}">
                                <td class="align-middle text-center">
                                    <div class="d-flex justify-content-center">
                                        <input class="form-check-input alat-checkbox m-0" type="checkbox" name="alat[]" value="{{ $j->id }}" id="alat_{{ $j->id }}" {{ $j->stok_tersedia <= 0 ? 'disabled' : '' }} style="border: 1px solid #adb5bd;">
                                    </div>
                                </td>
                                <td class="align-middle text-center"><span class="text-xs font-weight-bold">{{ $loop->iteration }}</span></td>
                                <td class="align-middle"><span class="text-xs font-weight-bold">{{ $j->serial_number }}</span></td>
                                <td class="align-middle"><span class="text-xs font-weight-bold">{{ $j->nama }}</span></td>
                                <td class="align-middle"><p class="text-xs font-weight-bold mb-0" style="white-space: normal; max-width: 250px;">{{ $j->deskripsi }}</p></td>
                                <td class="align-middle text-center">
                                    <span class="badge badge-sm bg-{{ $j->stok_tersedia > 0 ? 'info' : 'secondary border border-danger' }}">
                                        {{ $j->stok_tersedia }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <input type="number" 
                                        name="qty[{{ $j->id }}]" 
                                        min="1" 
                                        max="{{ $j->stok_tersedia }}" 
                                        value="{{ $j->stok_tersedia > 0 ? 1 : 0 }}" 
                                        class="form-control form-control-sm" 
                                        style="width: 70px;"
                                        {{ $j->stok_tersedia <= 0 ? 'disabled' : '' }}
                                    >
                                    @if($j->is_maintenance)
                                        <div class="text-xxs text-warning font-weight-bold mt-1">Perbaikan</div>
                                    @elseif($j->stok_tersedia <= 0)
                                        <div class="text-xxs text-danger font-weight-bold mt-1">Stok Habis</div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mb-3 mt-5">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
    
    
</div>

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/l10n/id.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>



<script>
    $(document).ready(function(){
        const initialPeminjamanVal = $("#peminjaman").val();

        const peminjamanPicker = $("#peminjaman").flatpickr({
            altInput: true,
            altFormat: "j F, Y",
            dateFormat: "Y-m-d",
            locale: "id",
            minDate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                if (dateStr) {
                    pengembalianPicker.set('minDate', dateStr);
                } else {
                    pengembalianPicker.set('minDate', 'today');
                }
            }
        });
    
        const pengembalianPicker = $("#pengembalian").flatpickr({
            altInput: true,
            altFormat: "j F, Y",
            dateFormat: "Y-m-d",
            locale: "id",
            minDate: initialPeminjamanVal ? initialPeminjamanVal : "today"
        });
    });
</script>