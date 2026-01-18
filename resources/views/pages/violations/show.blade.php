@extends('layouts.main')
@section('content')

<div class="container my-4">
    <h1>Detail Pelanggaran</h1>
    <div class="mb-3">
        <a href="{{ route('violations.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $violation->nama_siswa }} ({{ $violation->nis }})</h5>
            <hr>
            <p class="card-text"><strong>Tanggal Pelanggaran:</strong> {{ date('d F Y', strtotime($violation->tgl_pelanggaran)) }}</p>
            <p class="card-text"><strong>Kategori:</strong> {{ $violation->kategori_pelanggaran }}</p>
            <p class="card-text"><strong>Point Pelanggaran:</strong> <span class="badge text-bg-danger">{{ $violation->point_pelanggaran }}</span></p>
            <p class="card-text"><strong>Total Point Siswa:</strong> {{ $violation->total_point }}</p>
            <p class="card-text"><strong>Deskripsi:</strong> <br> {{ $violation->deskripsi_pelanggaran }}</p>
        </div>
    </div>
</div>

@endsection