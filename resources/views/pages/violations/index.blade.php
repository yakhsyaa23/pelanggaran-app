@extends('layouts.main')
@section('content')
<div class="container my-4">
    <div class="card">
        <div class="card-header">
            <div class="col">
                <h1>Daftar Pelanggaran Siswa</h1>
            </div>
            <div class="col text-end">
                <a href="{{ route('violations.create') }}" class="btn btn-primary">Tambah Pelanggaran</a>
            </div>
        </div>
        <div class="card-body">
   {{ $dataTable->table() }}
</div>
@endsection


@push('js')
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush