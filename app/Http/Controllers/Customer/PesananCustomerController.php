<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\Rekening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananCustomerController extends Controller
{
    public function index()
    {
        $pesanan_paid = Pesanan::join('produk', 'produk.id_produk', '=', 'pesanan.id_produk')
            ->join('user_alamat', 'user_alamat.id_user_alamat', '=', 'pesanan.id_alamat')
            ->select('pesanan.*', 'produk.*', 'user_alamat.nama_prov', 'user_alamat.nama_kota')
            ->where('pesanan.id_user', Auth::user()->id)
            ->where(function ($query) {
                $query->where('pesanan.status', 'menunggu pembayaran')
                    ->orWhere('pesanan.status', 'Bukti Pembayaraan Sedang Di Tinjau')
                    ->orWhere('pesanan.status', 'Pesanan Di Tolak');
            })
            ->orderBy('pesanan.updated_at', 'desc')
            ->get();

        $ongoing = Pesanan::join('produk', 'produk.id_produk', '=', 'pesanan.id_produk')
            ->join('user_alamat', 'user_alamat.id_user_alamat', '=', 'pesanan.id_alamat')
            ->select('pesanan.*', 'produk.*', 'user_alamat.nama_prov', 'user_alamat.nama_kota')
            ->where('pesanan.id_user', Auth::user()->id)
            ->where('pesanan.status', 'Pesanan Di Terima')
            ->orderBy('pesanan.updated_at', 'desc')
            ->get();

        $kirim = Pesanan::join('produk', 'produk.id_produk', '=', 'pesanan.id_produk')
            ->join('user_alamat', 'user_alamat.id_user_alamat', '=', 'pesanan.id_alamat')
            ->select('pesanan.*', 'produk.nama_produk', 'user_alamat.nama_prov', 'user_alamat.nama_kota')
            ->where('pesanan.id_user', Auth::user()->id)
            ->where('pesanan.status', 'Barang Dalam Pengiriman')
            ->orderBy('pesanan.updated_at', 'desc')
            ->get();

        $tagihan = Pesanan::join('produk', 'produk.id_produk', '=', 'pesanan.id_produk')
            ->join('user_alamat', 'user_alamat.id_user_alamat', '=', 'pesanan.id_alamat')
            ->select('pesanan.*', 'produk.nama_produk', 'user_alamat.nama_prov', 'user_alamat.nama_kota')
            ->where('pesanan.id_user', Auth::user()->id)
            ->where('pesanan.dp_status', 'tagihan deliver')
            ->orderBy('pesanan.updated_at', 'desc')
            ->get();

        return view('customer.pesanan.pesanan', compact(['pesanan_paid', 'ongoing', 'kirim', 'tagihan']));
    }

    public function store(Request $request)
    {
        if ($request->alamat_kirim == NULL) {
            return back()->with('error', 'Proses Gagal Wajib Memilih Salah Satu Alamat Pengiriman');
        }

        $id_keranjang = $request->id_keranjang;
        $harga_variasi = $request->variasi_harga ?? "0";
        $harga_sablon = $request->sablon_harga ?? "0";

        $total_variasi = array_sum(explode(',', $harga_variasi));
        $total_sablon = array_sum(explode(',', $harga_sablon));

        [$id_kota, $id_alamat] = explode('|', $request->alamat_kirim);

        $keranjang = Keranjang::join('produk', 'keranjang.id_produk', '=', 'produk.id_produk')
            ->select('keranjang.*', 'produk.*')
            ->find($id_keranjang);

        $total = $keranjang->total;

        if ($total <= 11) {
            $harga = $keranjang->harga_produk1;
        } elseif ($total <= 23) {
            $harga = $keranjang->harga_produk2;
        } elseif ($total <= 50) {
            $harga = $keranjang->harga_produk3;
        } elseif ($total <= 100) {
            $harga = $keranjang->harga_produk4;
        } else {
            $harga = $keranjang->harga_produk5;
        }

        $jumlah = $harga * $total;
        $harga_ongkir = 15000; // Ongkir tetap
        $total_bayar = $jumlah + $harga_ongkir + $total_variasi + $total_sablon;

        Pesanan::create([
            'id_user' => Auth::user()->id,
            'id_produk' => $keranjang->id_produk,
            'quantity' => $total,
            'id_alamat' => $id_alamat,
            'id_kota'   => $id_kota,
            'variasi'   => $request->variasi,
            'variasi_harga' => $harga_variasi,
            'variasi_total' => $total_variasi,
            'sablon'   => $request->sablon,
            'sablon_harga' => $harga_sablon,
            'sablon_total' => $total_sablon,
            'note_sablon_variasi' => $request->note,
            'bayar' => $jumlah,
            'ongkir' => $harga_ongkir,
            'total_bayar' => $total_bayar,
            'status' => "menunggu pembayaran",
        ]);

        Keranjang::find($id_keranjang)->delete();

        return to_route('pesanan.index');
    }

    public function show($id)
    {
        $pesanan = Pesanan::join('produk', 'produk.id_produk', '=', 'pesanan.id_produk')
            ->join('user_alamat', 'user_alamat.id_user_alamat', '=', 'pesanan.id_alamat')
            ->join('users', 'users.id', '=', 'pesanan.id_user')
            ->select('pesanan.*', 'produk.*', 'user_alamat.no_telp', 'user_alamat.alamat', 'user_alamat.nama_penerima', 'user_alamat.nama_prov', 'user_alamat.nama_kota', 'users.*')
            ->find($id);

        return view('customer.pesanan.pesanan_cetak', compact(['pesanan']));
    }

    public function edit($id)
    {
        $pesanan  = Pesanan::join('produk', 'produk.id_produk', '=', 'pesanan.id_produk')
            ->join('user_alamat', 'user_alamat.id_user_alamat', '=', 'pesanan.id_alamat')
            ->select(
                'pesanan.*',
                'produk.*',
                'user_alamat.nama_prov',
                'user_alamat.nama_kota',
                'user_alamat.alamat',
                'user_alamat.kode_pos',
                'user_alamat.nama_penerima',
                'user_alamat.no_telp'
            )
            ->find($id);

        $rekening = Rekening::get();
        $ongkir = 15000;

        return view('customer.pesanan.pesanan_edit', compact(['pesanan', 'ongkir', 'rekening']));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bukti_bayar' => 'required',
            'metode' => 'required'
        ]);

        $data_desain = Pesanan::find($id);

        if ($request->hasFile('bukti_bayar')) {
            $bukit_pembayaran = $request->file('bukti_bayar')->getClientOriginalName();
            $request->bukti_bayar->move(public_path('bukti_bayar'), $bukit_pembayaran);
        }

        if ($request->hasFile('desain')) {
            $desain = $request->file('desain')->getClientOriginalName();
            $request->desain->move(public_path('desain'), $desain);
        } else {
            $desain = $data_desain->desain;
        }

        if ($request->metode == 'dp') {
            $data_desain->update([
                'bukti_bayar_dp' => $bukit_pembayaran,
                'desain' => $desain,
                'request_user' => $request->request_desain,
                'status' => 'Bukti Pembayaraan Sedang Di Tinjau',
                'tipe_pembayaran' => 'dp',
            ]);
        } else {
            $data_desain->update([
                'bukti_bayar' => $bukit_pembayaran,
                'desain' => $desain,
                'request_user' => $request->request_desain,
                'status' => 'Bukti Pembayaraan Sedang Di Tinjau',
                'tipe_pembayaran' => 'lunas',
            ]);
        }

        return to_route('pesanan.index');
    }

    public function destroy($id)
    {
        //
    }
}
