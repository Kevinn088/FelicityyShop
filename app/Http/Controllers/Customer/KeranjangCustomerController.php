<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\Alamat;
use App\Models\Keranjang;
use App\Models\Sablon;
use App\Models\Variasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $keranjang = Keranjang::join('produk', 'keranjang.id_produk', '=', 'produk.id_produk')
            ->select('keranjang.*', 'produk.*')
            ->where('keranjang.id_user', Auth::user()->id)
            ->orderBy('keranjang.id_keranjang', 'desc')
            ->paginate(10);

        return view('customer.keranjang.keranjang', compact(['keranjang']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->demo0 <= 0) {
            return back()->with('error', 'Maaf Pembelian Produk hanya Berlaku Minimal 1 Pcs');
        }

        // ✅ 1. Validasi biasa
        $request->validate([
            'demo0' => 'required|integer|min:1',
            'ukuran' => 'required|in:XL,XXL',
            'produk' => 'required|integer|exists:produk,id_produk',
            'warna' => 'required|string',
        ]);

        // ✅ 2. Cek apakah warna tersedia untuk produk terkait (diletakkan di sini)
        $warnaTersedia = \App\Models\Warna::where('id_produk', $request->produk)
            ->where('nama_warna', $request->warna)
            ->exists();

        if (!$warnaTersedia) {
            return back()->with('error', 'Warna yang dipilih tidak tersedia untuk produk ini.');
        }

        // ✅ 3. Simpan ke keranjang
        Keranjang::create([
            'id_user' => Auth::user()->id,
            'id_produk' => $request->produk,
            'total' => $request->demo0,
            'ukuran' => $request->ukuran,
            'warna' => $request->warna,
        ]);

        return to_route('keranjang.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = $id;

        $alamat = Alamat::where('id_user', Auth::user()->id)
        ->orderBy('id_user_alamat', 'DESC')
        ->get();

        $keranjang = Keranjang::join('produk', 'keranjang.id_produk', '=', 'produk.id_produk')
        ->select('keranjang.*', 'produk.*')
        ->where('keranjang.id_keranjang', $id)
        ->get();

        $variasi = Variasi::get();

        $sablon = Sablon::get();

        return view('customer.checkout.checkout', compact(['alamat','id','keranjang','variasi','sablon']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->pembelian < 0) {
            return back()->with('error', 'Maaf Pembelian Produk hanya Berlaku Minimal 6 Pcs');
        }

        Keranjang::where('id_keranjang', $id)->update([
            'total' => $request->pembelian
        ]);

        return back()->with('success', 'Berhasil Memperbaharui Banyak Pembelian');

        $request->validate([
        'pembelian' => 'required|integer|min:6',
        'ukuran' => 'nullable|in:XL,XXL',
        ]);

        Keranjang::where('id_keranjang', $id)->update([
        'total' => $request->pembelian,
        'ukuran' => $request->ukuran,
        ]);

        return back()->with('success', 'Berhasil Memperbaharui Banyak Pembelian');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Keranjang::where('id_keranjang', $id)->delete();

        return to_route('keranjang.index')->with('success', 'Berhasil Menghapus Keranjang');
    }
}
