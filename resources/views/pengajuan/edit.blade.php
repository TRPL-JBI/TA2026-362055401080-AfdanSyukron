<!-- resources/views/pengajuans/create.blade.php -->

@extends('layout.main')

@section('content')

<link href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css" rel="stylesheet" />

<div class="container">
    <h1>Edit Pengajuan</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pengajuan.update', $pengajuan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-3">
            <label for="nama_kegiatan" class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="nama_kegiatan" value="{{ $pengajuan->nama_kegiatan ?? old('nama_kegiatan') }}" required>
        </div>
        <div class="row align-items-center">
            <div class="mb-3 col">
                <label for="tanggal_peminjaman" class="form-label text-xs">Tanggal Peminjaman <span class="text-danger">*</span></label>
                <input type="text" id="peminjaman" class="form-control" name="tanggal_peminjaman" value="{{ $pengajuan->tanggal_peminjaman ?? old('tanggal_peminjaman') }}" style="height: 40px;" required>
            </div>
            <div class="mb-3 col-auto text-center mt-3">
                <span>s/d</span>
            </div>
            <div class="mb-3 col">
                <label for="tanggal_pengembalian" class="form-label text-xs">Tanggal Pengembalian <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="pengembalian" name="tanggal_pengembalian" value="{{ $pengajuan->tanggal_pengembalian ?? old('tanggal_pengembalian') }}" style="height: 40px;" required>
            </div>
            <div class="mb-3 col">
                <label for="file" class="form-label text-xs">File Pengajuan (Baru)</label>
                <input type="file" class="form-control" id="file" name="file" accept=".pdf,.doc,.docx" style="height: 40px;">
                @if($pengajuan->file)
                    <small class="text-muted" style="font-size: 0.65rem;">File saat ini: <a href="{{ asset('uploads/pengajuan/' . $pengajuan->file) }}" target="_blank">Lihat</a></small>
                @endif
            </div>
            <div class="mb-3 col">
                <label for="ktm" class="form-label text-xs">KTM (Baru)</label>
                <input type="file" class="form-control" id="ktm" name="ktm" accept=".jpg,.jpeg,.png,.pdf" style="height: 40px;">
                @if($pengajuan->ktm)
                    <small class="text-muted" style="font-size: 0.65rem;">KTM saat ini: <a href="{{ asset('uploads/ktm/' . $pengajuan->ktm) }}" target="_blank">Lihat</a></small>
                @endif
            </div>
        </div>
        @php
            $selectedDetails = $detail_pengajuan->keyBy('alat_id');
        @endphp
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
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 10%">Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alat as $j)
                            @php $detail = $selectedDetails->get($j->id); @endphp
                            <tr>
                                <td class="align-middle text-center">
                                    <div class="d-flex justify-content-center">
                                        <input class="form-check-input alat-checkbox m-0" type="checkbox" name="alat[]" value="{{ $j->id }}" id="alat_{{ $j->id }}" {{ $detail ? 'checked' : '' }} style="border: 1px solid #adb5bd;">
                                    </div>
                                </td>
                                <td class="align-middle text-center"><span class="text-xs font-weight-bold">{{ $loop->iteration }}</span></td>
                                <td class="align-middle"><span class="text-xs font-weight-bold">{{ $j->serial_number }}</span></td>
                                <td class="align-middle"><span class="text-xs font-weight-bold">{{ $j->nama }}</span></td>
                                <td class="align-middle"><p class="text-xs font-weight-bold mb-0" style="white-space: normal; max-width: 250px;">{{ $j->deskripsi }}</p></td>
                                <td class="align-middle">
                                    <input type="number" name="qty[{{ $j->id }}]" min="1" value="{{ $detail ? $detail->qty : 1 }}" class="form-control form-control-sm" style="width: 70px;">
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

<input type="hidden" value="{{ $pengajuan->id }}" id="pengajuan_id">


@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/l10n/id.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>



<script>
    $(document).ready(function(){
        const initialPeminjamanVal = $("#peminjaman").val();
        const todayStr = new Date().toISOString().split('T')[0];

        // If the initial date is in the past, allow that date as the minimum date.
        // Otherwise, the minimum date is today.
        let minPeminjamanDate = "today";
        if (initialPeminjamanVal && initialPeminjamanVal < todayStr) {
            minPeminjamanDate = initialPeminjamanVal;
        }

        const peminjamanPicker = $("#peminjaman").flatpickr({
            altInput: true,
            altFormat: "j F, Y",
            dateFormat: "Y-m-d",
            locale: "id",
            minDate: minPeminjamanDate,
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