<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use App\Models\Mahasiswa;
use App\Models\PembimbingPendamping;
use App\Models\PembimbingUtama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BimbinganController extends Controller
{
    public function set_pembimbing(Request $request)
    {
        $request->validate([
            'dosen_pembimbing_utama' => ['required','numeric'],
            'dosen_pembimbing_pendamping' => ['required','numeric'],
            'judul_skripsi' => ['required', 'string', 'max:255'],
        ]);

        $mahasiswa = Mahasiswa::where('user_id', Auth::user()->id)->first();
        $item = PembimbingUtama::where('mahasiswa_id', Auth::user()->id)->first();
        $item2 = PembimbingPendamping::where('mahasiswa_id', Auth::user()->id)->first();

        if ($item == NULL && $item2 == NULL) {
            PembimbingUtama::create([
                'dosen_id' => $request->dosen_pembimbing_utama,
                'mahasiswa_id' => Auth::user()->id
            ]);

            PembimbingPendamping::create([
                'dosen_id' => $request->dosen_pembimbing_pendamping,
                'mahasiswa_id' => Auth::user()->id
            ]);

            $mahasiswa->update([
                'judul_skripsi' => $request->judul_skripsi
            ]);
        }

        return redirect()->route('dashboard')->with(['success' => 'Berhasil Mengset Dosen Pembimbing Utama dan Dosen Pembimbing Pendamping']);
    }

    public function show_konfirmasi_persetujuan()
    {
        $items = PembimbingUtama::where('dosen_id', Auth::user()->id)->get();

        $items2 = PembimbingPendamping::where('dosen_id', Auth::user()->id)->get();

        if ($items->count() > 0 || $items2->count() > 0) {
            return view('pages.dosen.konfirmasi-persetujuan.index', [
                'items' => $items, 'items2' => $items2
            ]);
        }else {
            return view('pages.dosen.konfirmasi-persetujuan.index', [
                'items' => NULL, 'items2' => NULL
            ]);
        }
    }

    public function konfirmasi_persetujuan($id, $tipe)
    {
        if ($tipe === 'Pembimbing-Utama') {
            $item = PembimbingUtama::findOrFail($id);

            $item->status_persetujuan = '1';
            $item->save();
        }elseif ($tipe === 'Pembimbing-Pendamping') {
            $item = PembimbingPendamping::findOrFail($id);

            $item->status_persetujuan = '1';
            $item->save();
        }

        return redirect()->route('bimbingan.show_konfirmasi_persetujuan');
    }

    public function show_pembimbing_1()
    {
        $check = PembimbingUtama::where('mahasiswa_id', Auth::user()->id)->first();

        $check2 = Bimbingan::where('mahasiswa_id', Auth::user()->id)->where('dosen_id', $check->dosen_id)->where('status', 'Dibaca')->orWhere('status', '=', 'Terkirim')->latest()->first();

        $check3 = Bimbingan::where('mahasiswa_id', Auth::user()->id)->latest()->where('dosen_id', $check->dosen_id)->where('status', '=', 'Terkirim')->first();

        if ($check) {
            return view('pages.mahasiswa.pembimbing-1.index', [
                'check' => $check2, 'check2' => $check3
            ]);
        }else {
            return redirect()->route('dashboard');
        }
    }

    public function store_pembimbing_1(Request $request)
    {
        $request->validate([
            'bab_pembahasan' => ['required', 'string'],
            'uraian_konsultasi' => ['required', 'string'],
            'file_mahasiswa' => ['required', 'mimes:pdf', 'max:1048']
        ]);

        $item = PembimbingUtama::where('mahasiswa_id', Auth::user()->id)->first();

        $random = Str::random(6);

        if ($request->file('file_mahasiswa')) {
            $value = $request->file('file_mahasiswa');
            $extension = $value->extension();
            $fileNames = Auth::user()->nama . '-' . $item->dosen_id . '-' . $request->bab_pembahasan . $random . '.' . $extension;
            Storage::putFileAs('public/assets/file-mahasiswa', $value, $fileNames);
        }

        Bimbingan::create([
            'dosen_id' => $item->dosen_id,
            'mahasiswa_id' => Auth::user()->id,
            'bab_pembahasan' => $request->bab_pembahasan,
            'uraian_konsultasi' => $request->uraian_konsultasi,
            'file_mahasiswa' => $fileNames,
            'status' => 'Terkirim'
        ]);

        return redirect()->route('bimbingan.riwayat-bimbingan')->with(['success-bimbingan' => 'Berhasil Mengirim Data Bimbingan Untuk Dosen Pembimbing Utama']);
    }

    public function show_pembimbing_2()
    {
        $check = PembimbingPendamping::where('mahasiswa_id', Auth::user()->id)->first();

        $check2 = Bimbingan::where('mahasiswa_id', Auth::user()->id)->where('dosen_id', $check->dosen_id)->where('status', '=', 'Dibaca')->latest()->first();

        $check3 = Bimbingan::where('mahasiswa_id', Auth::user()->id)->where('dosen_id', $check->dosen_id)->where('status', '=', 'Terkirim')->latest()->first();

        if ($check) {
            return view('pages.mahasiswa.pembimbing-2.index', [
                'check' => $check2, 'check2' => $check3
            ]);
        }else {
            return redirect()->route('dashboard');
        }
    }

    public function store_pembimbing_2(Request $request)
    {
        $request->validate([
            'bab_pembahasan' => ['required', 'string'],
            'uraian_konsultasi' => ['required', 'string'],
            'file_mahasiswa' => ['required', 'mimes:pdf', 'max:1048']
        ]);

        $item = PembimbingPendamping::where('mahasiswa_id', Auth::user()->id)->first();

        $random = Str::random(6);

        if ($request->file('file_mahasiswa')) {
            $value = $request->file('file_mahasiswa');
            $extension = $value->extension();
            $fileNames = Auth::user()->nama . '-' . $item->dosen_id . '-' . $request->bab_pembahasan . $random . '.' . $extension;
            Storage::putFileAs('public/assets/file-mahasiswa', $value, $fileNames);
        }

        Bimbingan::create([
            'dosen_id' => $item->dosen_id,
            'mahasiswa_id' => Auth::user()->id,
            'bab_pembahasan' => $request->bab_pembahasan,
            'uraian_konsultasi' => $request->uraian_konsultasi,
            'file_mahasiswa' => $fileNames,
            'status' => 'Terkirim'
        ]);

        return redirect()->route('bimbingan.riwayat-bimbingan')->with(['success-bimbingan' => 'Berhasil Mengirim Data Bimbingan Untuk Dosen Pembimbing Pendamping']);
    }

    public function index_bimbingan()
    {
        $items = Bimbingan::where('dosen_id', Auth::user()->id)->orderByRaw("FIELD(status , 'Terkirim', 'Dibaca', 'Revisi', 'ACC') ASC")->latest()->get();

        return view('pages.dosen.bimbingan-mahasiswa.index', [
            'items' => $items
        ]);
    }

    public function detail_bimbingan($id)
    {
        $item = Bimbingan::where('dosen_id', Auth::user()->id)->where('id', $id)->first();

        if ($item->status === 'Terkirim') {
            $item->status = 'Dibaca';
            $item->save();
        }

        return view('pages.dosen.bimbingan-mahasiswa.show', [
            'item' => $item
        ]);
    }

    public function update_bimbingan(Request $request, $id)
    {
        $item = Bimbingan::where('dosen_id', Auth::user()->id)->where('id', $id)->latest()->first();

        $request->validate([
            'komentar_dosen' => ['required', 'string'],
            'status' => ['required', 'in:ACC,Revisi'],
            'file_dosen' => ['required', 'mimes:pdf', 'max:1048'],
            'tanda_tangan' => ['required']
        ]);

        if ($request->file('file_dosen')) {
            $value = $request->file('file_dosen');
            $extension = $value->extension();
            $fileNames = uniqid('pdf_', microtime()) . '.' . $extension;
            Storage::putFileAs('public/assets/file-dosen', $value, $fileNames);
        }

        $folderPath = public_path('tanda-tangan/');

        $image_parts = explode(";base64,", $request->tanda_tangan);

        $image_type_aux = explode("image/", $image_parts[0]);

        $image_type = $image_type_aux[1];

        $sign_name = uniqid() . '.'.$image_type;

        $image_base64 = base64_decode($image_parts[1]);

        $file = $folderPath . $sign_name;
        file_put_contents($file, $image_base64);

        $item->status = $request->status;
        $item->komentar_dosen = $request->komentar_dosen;
        $item->file_dosen = $fileNames;
        $item->tanda_tangan = $sign_name;
        $item->save();

        return redirect()->route('bimbingan.index_bimbingan')->with(['success-balas-bimbingan' => 'Berhasil Mengirim Balasan Bimbingan']);
    }

    public function riwayat_bimbingan()
    {
        $items = Bimbingan::where('mahasiswa_id', Auth::user()->id)->orderByRaw("FIELD(status , 'Terkirim', 'Dibaca', 'Revisi', 'ACC') ASC")->latest()->get();

        return view('pages.mahasiswa.riwayat-bimbingan.index', [
            'items' => $items
        ]);
    }

    public function detail_riwayat_bimbingan($id)
    {
        $item = Bimbingan::where('mahasiswa_id', Auth::user()->id)->where('id', $id)->first();

        return view('pages.mahasiswa.riwayat-bimbingan.show', [
            'item' => $item
        ]);
    }

    public function riwayat_bimbingan_dosen()
    {
        $items = Bimbingan::where('dosen_id', Auth::user()->id)->orderByRaw("FIELD(status , 'Terkirim', 'Dibaca', 'Revisi', 'ACC') ASC")->latest()->get();

        return view('pages.dosen.riwayat-bimbingan.index', [
            'items' => $items
        ]);
    }

    public function monitoring_bimbingan()
    {
        $items = Bimbingan::orderByRaw("FIELD(status , 'Terkirim', 'Dibaca', 'Revisi', 'ACC') ASC")->get()->groupBy('mahasiswa_id');

        return view('pages.admin.monitoring-bimbingan.index', [
            'items' => $items
        ]);
    }

    public function show_monitoring_bimbingan($id)
    {
        $items =  Bimbingan::where('mahasiswa_id', $id)->orderByRaw("FIELD(status , 'Terkirim', 'Dibaca', 'Revisi', 'ACC') ASC")->latest()->get();

        return view('pages.admin.monitoring-bimbingan.show', [
            'items' => $items
        ]);
    }

    public function detail_monitoring_bimbingan($id)
    {
        $item =  Bimbingan::findOrFail($id);

        return view('pages.admin.monitoring-bimbingan.detail', [
            'item' => $item
        ]);
    }

    public function kartu_bimbingan()
    {
        return view('pages.mahasiswa.kartu-bimbingan.index');
    }

    public function show_kartu_bimbingan($dosen)
    {
        if ($dosen == 'Utama') {
            $check = PembimbingUtama::where('mahasiswa_id', Auth::user()->id)->first();
        }elseif ($dosen == 'Pendamping') {
            $check = PembimbingPendamping::where('mahasiswa_id', Auth::user()->id)->first();
        }
        $items = Bimbingan::where('mahasiswa_id', Auth::user()->id)->where('dosen_id', $check->dosen_id)->oldest()->get();

        return view('pages.mahasiswa.kartu-bimbingan.show', [
            'items' => $items, 'dosen' => $dosen
        ]);
    }

    public function cetak_kartu($dosen)
    {
        if ($dosen == 'Utama') {
            $check = PembimbingUtama::where('mahasiswa_id', Auth::user()->id)->first();
        }elseif ($dosen == 'Pendamping') {
            $check = PembimbingPendamping::where('mahasiswa_id', Auth::user()->id)->first();
        }
        $items = Bimbingan::where('mahasiswa_id', Auth::user()->id)->where('dosen_id', $check->dosen_id)->oldest()->get();
        $count = Bimbingan::where('mahasiswa_id', Auth::user()->id)->where('dosen_id', $check->dosen_id)->count();

        if ($count >= 8) {
            return view('pages.mahasiswa.kartu-bimbingan.kartu', [
                'items' => $items, 'dosen' => $dosen
            ]);
        } else {
            return redirect()->back()->with(['error' => 'Harus Melakukan Bimbingan Minimal 8x Pertemuan']);
        }
    }

    public function selesaikan_bimbingan($id)
    {
        $item = Mahasiswa::where('user_id', $id)->first();

        $item->update([
            'status_bimbingan' => 'Selesai'
        ]);

        return redirect()->route('bimbingan.monitoring-bimbingan');
    }
}
