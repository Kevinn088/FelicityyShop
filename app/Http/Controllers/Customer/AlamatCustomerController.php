<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\Alamat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlamatCustomerController extends Controller
{
    public function create_checkout($id)
    {
        $provinsi = [
            ['id' => '11', 'nama' => 'Aceh'],
            ['id' => '12', 'nama' => 'Sumatera Utara'],
            ['id' => '13', 'nama' => 'Sumatera Barat'],
            ['id' => '31', 'nama' => 'DKI Jakarta'],
            ['id' => '32', 'nama' => 'Jawa Barat'],
            ['id' => '33', 'nama' => 'Jawa Tengah'],
            ['id' => '34', 'nama' => 'DI Yogyakarta'],
            ['id' => '35', 'nama' => 'Jawa Timur'],
            ['id' => '61', 'nama' => 'Kalimantan Barat'],
            ['id' => '62', 'nama' => 'Kalimantan Selatan'],
            ['id' => '63', 'nama' => 'Kalimantan Tengah'],
            ['id' => '64', 'nama' => 'Kalimantan Timur'],
            ['id' => '65', 'nama' => 'Kalimantan Utara'],
        ];

        $kota = [
            ['id' => '501', 'nama' => 'Kota Bandung'],
            ['id' => '502', 'nama' => 'Kota Bekasi'],
            ['id' => '503', 'nama' => 'Kota Depok'],
            ['id' => '504', 'nama' => 'Kota Pontianak'],
            ['id' => '505', 'nama' => 'Kota Banjarmasin'],
            ['id' => '601', 'nama' => 'Kota Surabaya'],
            ['id' => '701', 'nama' => 'Kota Medan'],
        ];

        return view('customer.alamat.alamat_checkout', compact(['id', 'provinsi', 'kota']));
    }

    public function store_alamat_checkout(Request $request)
    {
        $id_keranjang = $request->id_keranjang;
        $alamat = Alamat::where('id_user', Auth::user()->id)->get();

        if ($alamat->count() >= 3) {
            return to_route('keranjang.show', $id_keranjang)->with('error', 'Kapasitas Pengisian Alamat Maksimal Hanya 3');
        }

        $request->validate([
            'nama' => 'required',
            'telp' => 'required',
            'pos' => 'required',
            'provinsi' => 'required',
            'kota' => 'required',
            'alamat' => 'required'
        ]);

        $provinsi_result = explode('|', $request->provinsi);
        $kota_result = explode('|', $request->kota);

        Alamat::create([
            'id_user' => Auth::user()->id,
            'no_telp' => $request->telp,
            'nama_penerima' => $request->nama,
            'id_provinsi' => $provinsi_result[0],
            'nama_prov' => $provinsi_result[1],
            'id_kota' => $kota_result[0],
            'nama_kota' => $kota_result[1],
            'kode_pos' => $request->pos,
            'alamat' => $request->alamat,
        ]);

        return to_route('keranjang.show', $id_keranjang)->with('success', 'Berhasil Menambahkan Alamat Pengiriman');
    }

    public function edit_checkout($id)
    {
        $alamat = Alamat::findOrFail($id);

        $provinsi = [
            ['id' => '11', 'nama' => 'Aceh'],
            ['id' => '12', 'nama' => 'Sumatera Utara'],
            ['id' => '13', 'nama' => 'Sumatera Barat'],
            ['id' => '31', 'nama' => 'DKI Jakarta'],
            ['id' => '32', 'nama' => 'Jawa Barat'],
            ['id' => '33', 'nama' => 'Jawa Tengah'],
            ['id' => '34', 'nama' => 'DI Yogyakarta'],
            ['id' => '35', 'nama' => 'Jawa Timur'],
            ['id' => '61', 'nama' => 'Kalimantan Barat'],
            ['id' => '62', 'nama' => 'Kalimantan Selatan'],
            ['id' => '63', 'nama' => 'Kalimantan Tengah'],
            ['id' => '64', 'nama' => 'Kalimantan Timur'],
            ['id' => '65', 'nama' => 'Kalimantan Utara'],
        ];

        $kota = [
            ['id' => '501', 'nama' => 'Kota Bandung'],
            ['id' => '502', 'nama' => 'Kota Bekasi'],
            ['id' => '503', 'nama' => 'Kota Depok'],
            ['id' => '504', 'nama' => 'Kota Pontianak'],
            ['id' => '505', 'nama' => 'Kota Banjarmasin'],
            ['id' => '601', 'nama' => 'Kota Surabaya'],
            ['id' => '701', 'nama' => 'Kota Medan'],
        ];

        return view('customer.alamat.alamat_edit_checkout', compact(['alamat', 'provinsi', 'kota']));
    }

    public function update_checkout(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'telp' => 'required',
            'pos' => 'required',
            'provinsi' => 'required',
            'kota' => 'required',
            'alamat' => 'required'
        ]);

        $provinsi_result = explode('|', $request->provinsi);
        $kota_result = explode('|', $request->kota);

        $alamat = Alamat::findOrFail($id);
        $alamat->update([
            'no_telp' => $request->telp,
            'nama_penerima' => $request->nama,
            'id_provinsi' => $provinsi_result[0],
            'nama_prov' => $provinsi_result[1],
            'id_kota' => $kota_result[0],
            'nama_kota' => $kota_result[1],
            'kode_pos' => $request->pos,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('pesanan.index')->with('success', 'Alamat berhasil diperbarui');
    }
}
