<?php

namespace App\Http\Controllers;

use App\DataTables\violationsDataTable;
use Illuminate\Http\Request;
use App\Models\Violation;
use App\Models\Student;
use Illuminate\Support\Str;

class ViolationController extends Controller
{
    /**
     * Constructor to handle authorization.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = $request->user();
            // Cek apakah user memiliki role 'admin' atau 'guru'
            // Asumsi: User memiliki relasi 'role' ke model Role yang punya kolom 'role_name'
            if (!$user || !in_array(optional($user->role)->role_name, ['admin', 'guru'])) {
                abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk melakukan tindakan ini.');
            }

            return $next($request);
        })->except(['index', 'show', 'searchStudent']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(violationsDataTable $dataTable)
    {
        return $dataTable->render('pages.violations.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('pages.violations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
       $request->validate([
           'nis' => 'required',
           'nama_siswa' => 'required',
           'kategori_pelanggaran' => 'required|in:Ringan,Sedang,Berat',
           'tgl_pelanggaran' => 'required|date',
           'point_pelanggaran' => 'required|numeric',

       ]);
       
       $studentExists = Student::where('nis', $request->nis)->where('nama_siswa', $request->nama_siswa)->exists();
       if (!$studentExists) {
           return redirect()->back()->withInput()->withErrors(['nis' => 'Data siswa dengan NIS dan Nama tersebut tidak ditemukan di database siswa.']);
       }

       $existingStudent = Violation::where('nis', $request->nis)->first();
       if ($existingStudent && $existingStudent->nama_siswa !== $request->nama_siswa) {
           return redirect()->back()->withInput()->withErrors(['nama_siswa' => 'NIS ' . $request->nis . ' sudah terdaftar dengan nama ' . $existingStudent->nama_siswa . '. Harap gunakan nama yang sesuai.']);
       }

       $totalPointSebelumnya = Violation::where('nis', $request->nis)->sum('point_pelanggaran');
       $totalPontBaru = $totalPointSebelumnya + $request->point_pelanggaran;


       $data = [
           'nis' => $request->nis,
           'nama_siswa' => $request->nama_siswa,
           'kategori_pelanggaran' => $request->kategori_pelanggaran,
           'tgl_pelanggaran' => $request->tgl_pelanggaran,
           'point_pelanggaran' => $request->point_pelanggaran,
           'total_point' => $totalPontBaru,
           'deskripsi_pelanggaran' => $request->deskripsi_pelanggaran,
           'slug' => Str::slug($request->nama_siswa),
       ];   


        Violation::create($data);

        return redirect()->route('violations.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $violation = Violation::findOrFail($id);
        return view('pages.violations.show', compact('violation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $violation = Violation::findOrFail($id);
        return view('pages.violations.edit', compact('violation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nis' => 'required',
            'nama_siswa' => 'required',
            'kategori_pelanggaran' => 'required',
            'tgl_pelanggaran' => 'required|date',
            'point_pelanggaran' => 'required|numeric',
            'deskripsi_pelanggaran' => 'required',
        ]);

        $existingStudent = Violation::where('nis', $request->nis)->first();
        if ($existingStudent && $existingStudent->nama_siswa !== $request->nama_siswa) {
            return redirect()->back()->withInput()->withErrors(['nama_siswa' => 'NIS ' . $request->nis . ' sudah terdaftar dengan nama ' . $existingStudent->nama_siswa . '. Harap gunakan nama yang sesuai.']);
        }

        $violation = Violation::findOrFail($id);
        $violation->nis=$request->nis;
        $violation->nama_siswa=$request->nama_siswa;
        $violation->kategori_pelanggaran=$request->kategori_pelanggaran;
        $violation->tgl_pelanggaran=$request->tgl_pelanggaran;
        $violation->point_pelanggaran=$request->point_pelanggaran;
        
        $existingPoints = Violation::where('nis', $request->nis)->where('id', '!=', $id)->sum('point_pelanggaran');
        $violation->total_point = $existingPoints + $request->point_pelanggaran;

        $violation->deskripsi_pelanggaran=$request->deskripsi_pelanggaran;
        $violation->slug=Str::slug($request->nama_siswa);
        $violation->save();
        

        return redirect()->route('violations.index');   
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $violation = Violation::findOrFail($id);
        $violation->delete();
        

        return redirect()->route('violations.index');
    }

    public function searchStudent(Request $request)
    {
        $query = $request->get('query');
        $students = Student::select('nis', 'nama_siswa', 'kelas')
                    ->where('nis', 'LIKE', "%{$query}%")
                    ->orWhere('nama_siswa', 'LIKE', "%{$query}%")
                    ->limit(10)
                    ->get();
        return response()->json($students);
    }
}
