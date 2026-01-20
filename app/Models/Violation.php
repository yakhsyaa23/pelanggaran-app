<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    protected $fillable = ['nis', 'nama_siswa', 'kategori_pelanggaran', 'slug', 'tgl_pelanggaran', 'point_pelanggaran', 'total_point', 'deskripsi_pelanggaran'];
}
