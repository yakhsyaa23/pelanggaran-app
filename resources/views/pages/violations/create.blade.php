@extends('layouts.main')
@section('content')





<div class="container my-4">
    <h1>Tambah Pelanggaran</h1>
    <div class="mb-3">
        <a href="{{ route('violations.index') }}" class="btn btn-secondary">Kembali</a>
    </div>  
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <form action="{{ route('violations.store') }}" method="POST">
                    @csrf
                    <div class="mb-3 position-relative">
                        <label for="nis" class="form-label">NIS</label>
                        <input type="text" class="form-control" id="nis" name="nis" value="{{ old('nis') }}" autocomplete="off" inputmode="numeric">
                        <div id="nis-list" class="list-group position-absolute w-100" style="z-index: 1050;"></div>
                        @error('nis')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="nama_siswa" class="form-label">Nama Siswa</label>
                        <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" value="{{ old('nama_siswa') }}" autocomplete="off">
                        <div id="nama-list" class="list-group position-absolute w-100" style="z-index: 1050;"></div>
                        @error('nama_siswa')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                    <label>Kategori Pelanggaran</label>
                    <select id="kategori_pelanggaran" name="kategori_pelanggaran" class="form-control">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Ringan">Ringan (10 Point)</option>
                        <option value="Sedang">Sedang (25 Point)</option>
                        <option value="Berat">Berat (50 Point)</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label>Point Pelanggaran</label>
                    <input type="number" id="point_pelanggaran" name="point_pelanggaran" class="form-control" readonly>
                </div>
                    <div class="mb-3">
                        <label for="tgl_pelanggaran" class="form-label">Tanggal Pelanggaran</label>
                        <input type="date" class="form-control" id="tgl_pelanggaran" name="tgl_pelanggaran" value="{{ old('tgl_pelanggaran') }}">
                        @error('tgl_pelanggaran')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi_pelanggaran" class="form-label">Deskripsi Pelanggaran</label>
                        <textarea class="form-control" id="deskripsi_pelanggaran" name="deskripsi_pelanggaran" rows="3">{{ old('deskripsi_pelanggaran') }}</textarea>
                    </div>
                    @error('deskripsi_pelanggaran')
                        <div class="text-danger">{{ $message }}</div>       
                    @enderror
                    
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div> 

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const nisInput = document.getElementById('nis');
            const nisList = document.getElementById('nis-list');
            const namaInput = document.getElementById('nama_siswa');
             const namaList = document.getElementById('nama-list');
            const kategoriInput = document.getElementById('kategori_pelanggaran');
            const pointInput = document.getElementById('point_pelanggaran');

             function fetchStudents(query, listElement) {
                if (query.length < 1) {
                    listElement.innerHTML = '';
                    return;
                }

                fetch(`{{ route('violations.searchStudent') }}?query=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        listElement.innerHTML = '';
                        data.forEach(student => {
                            const item = document.createElement('a');
                            item.classList.add('list-group-item', 'list-group-item-action');
                            item.href = '#';
                            item.textContent = `${student.nis} - ${student.nama_siswa}`;
                            item.addEventListener('click', function(e) {
                                e.preventDefault();
                                nisInput.value = student.nis;
                                namaInput.value = student.nama_siswa;
                                listElement.innerHTML = '';
                                namaList.innerHTML = '';
                            });
                            listElement.appendChild(item);
                        });
                    });
            }



function isiPoinOtomatis() {
    const kategori = kategoriInput.value;
    const inputPoint = pointInput;
    const inputTotalPoint = document.getElementById('total_point');
    let pointBaru = 0;
    if (kategori === "Ringan") {
        inputPoint.value = 10;
    } else if (kategori === "Sedang") {
        inputPoint.value = 25;
    } else if (kategori === "Berat") {
        inputPoint.value = 50;
    } else {
        inputPoint.value = "";
        
        let totalLama = {{ $totalPointSebelumnya ?? 0 }};

        inputTotalPoint.value = parseInt(totalLama) + parseInt(inputPoint.value);
    }
}




            nisInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                fetchStudents(this.value, nisList);
            });

            nisInput.addEventListener('blur', function() {
                const query = this.value;
                if (query.length > 0) {
                    fetch(`{{ route('violations.searchStudent') }}?query=${query}`)
                        .then(response => response.json())
                        .then(data => {
                            const exactMatch = data.find(student => student.nis == query);
                            if (exactMatch) {
                                namaInput.value = exactMatch.nama_siswa;
                            }
                        });
                }
            });

            namaInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
                fetchStudents(this.value, namaList);
            });

             kategoriInput.addEventListener('change', function() {
                isiPoinOtomatis();
            });

            document.addEventListener('click', function(e) {
                if (e.target !== nisInput && e.target !== namaInput) {
                    nisList.innerHTML = '';
                    namaList.innerHTML = '';
                }
            });
        });
    </script>
   


@endsection