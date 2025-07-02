<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Warna;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::join('kategori', 'produk.kategori', '=', 'kategori.id_kategori')
            ->select('produk.*', 'kategori.jenis_kategori')
            ->get();
        return view('admin.produk.produk', compact('produk'));
    }

    public function create()
    {
        $kategori = Kategori::orderBy('id_kategori', 'desc')->get();
        return view('admin.produk.produk_create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|unique:produk,nama_produk',
            'pilih_kategori' => 'required',
            'deskripsi_produk' => 'required',
        ]);

        $harga1 = substr(preg_replace('/[Rp.,]/', '', $request->harga_produk1), 0, -2);
        $harga2 = substr(preg_replace('/[Rp.,]/', '', $request->harga_produk2), 0, -2);
        $harga3 = substr(preg_replace('/[Rp.,]/', '', $request->harga_produk3), 0, -2);
        $harga4 = substr(preg_replace('/[Rp.,]/', '', $request->harga_produk4), 0, -2);
        $harga5 = substr(preg_replace('/[Rp.,]/', '', $request->harga_produk5), 0, -2);

        $foto1 = $request->hasFile('img1') ? $request->file('img1')->getClientOriginalName() : null;
        $foto2 = $request->hasFile('img2') ? $request->file('img2')->getClientOriginalName() : null;
        $foto3 = $request->hasFile('img3') ? $request->file('img3')->getClientOriginalName() : null;
        $foto4 = $request->hasFile('img4') ? $request->file('img4')->getClientOriginalName() : null;

        if ($foto1) $request->img1->move(public_path('produk'), $foto1);
        if ($foto2) $request->img2->move(public_path('produk'), $foto2);
        if ($foto3) $request->img3->move(public_path('produk'), $foto3);
        if ($foto4) $request->img4->move(public_path('produk'), $foto4);

        $produk = Produk::create([
            'nama_produk' => $request->nama_produk,
            'kategori' => $request->pilih_kategori,
            'deskripsi' => $request->deskripsi_produk,
            'harga_produk1' => $harga1,
            'harga_produk2' => $harga2,
            'harga_produk3' => $harga3,
            'harga_produk4' => $harga4,
            'harga_produk5' => $harga5,
            'foto_produk1' => $foto1,
            'foto_produk2' => $foto2,
            'foto_produk3' => $foto3,
            'foto_produk4' => $foto4,
        ]);

        if ($request->has('warna')) {
            foreach ($request->warna as $warna) {
                if (!empty($warna)) {
                    Warna::create([
                        'id_produk' => $produk->id_produk,
                        'nama_warna' => $warna,
                    ]);
                }
            }
        }

        return to_route('produk.index')->with('success', 'Berhasil Menambahkan Produk Baru');
    }

    public function show($id)
    {
        $produk = Produk::with('warna')->findOrFail($id);
        return view('produk.detail', compact('produk'));
    }

    public function edit($id)
    {
        $produk = Produk::with('warna')->findOrFail($id);
        $kategori = Kategori::orderBy('id_kategori', 'desc')->get();
        return view('admin.produk.produk_edit', compact('produk', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_produk' => 'required',
            'pilih_kategori' => 'required',
            'deskripsi_produk' => 'required',
        ]);

        $harga1 = substr(preg_replace('/[Rp.,]/', '', $request->harga_produk1), 0, -2);
        $harga2 = substr(preg_replace('/[Rp.,]/', '', $request->harga_produk2), 0, -2);
        $harga3 = substr(preg_replace('/[Rp.,]/', '', $request->harga_produk3), 0, -2);
        $harga4 = substr(preg_replace('/[Rp.,]/', '', $request->harga_produk4), 0, -2);
        $harga5 = substr(preg_replace('/[Rp.,]/', '', $request->harga_produk5), 0, -2);

        $foto1 = $request->hasFile('img1') ? $request->file('img1')->getClientOriginalName() : $request->fileimg1;
        $foto2 = $request->hasFile('img2') ? $request->file('img2')->getClientOriginalName() : $request->fileimg2;
        $foto3 = $request->hasFile('img3') ? $request->file('img3')->getClientOriginalName() : $request->fileimg3;
        $foto4 = $request->hasFile('img4') ? $request->file('img4')->getClientOriginalName() : $request->fileimg4;

        if ($request->hasFile('img1')) $request->img1->move(public_path('produk'), $foto1);
        if ($request->hasFile('img2')) $request->img2->move(public_path('produk'), $foto2);
        if ($request->hasFile('img3')) $request->img3->move(public_path('produk'), $foto3);
        if ($request->hasFile('img4')) $request->img4->move(public_path('produk'), $foto4);

        Produk::where('id_produk', $id)->update([
            'nama_produk' => $request->nama_produk,
            'kategori' => $request->pilih_kategori,
            'deskripsi' => $request->deskripsi_produk,
            'harga_produk1' => $harga1,
            'harga_produk2' => $harga2,
            'harga_produk3' => $harga3,
            'harga_produk4' => $harga4,
            'harga_produk5' => $harga5,
            'foto_produk1' => $foto1,
            'foto_produk2' => $foto2,
            'foto_produk3' => $foto3,
            'foto_produk4' => $foto4,
        ]);

        // Update warna produk
        Warna::where('id_produk', $id)->delete();
        if ($request->has('warna')) {
            foreach ($request->warna as $warna) {
                if (!empty($warna)) {
                    Warna::create([
                        'id_produk' => $id,
                        'nama_warna' => $warna,
                    ]);
                }
            }
        }

        return to_route('produk.index')->with('success', 'Berhasil Memperbaharui Produk');
    }

    public function destroy($id)
    {
        Produk::where('id_produk', $id)->delete();
        Warna::where('id_produk', $id)->delete();

        return back()->with('delete', 'Berhasil Menghapus Produk');
    }
}
