<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;

    protected $table = 'keranjang';
    protected $primaryKey = 'id_keranjang';

    // Tambahkan 'ukuran' ke sini
    protected $fillable = ['id_user', 'id_produk', 'total', 'ukuran', 'warna'];
}
