<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_produk';
    protected $table = 'produk';
    protected $fillable = ['nama_produk','kategori','deskripsi','harga_produk1',
    'harga_produk2','harga_produk3','harga_produk4','harga_produk5','foto_produk1',
    'foto_produk2','foto_produk3','foto_produk4'];
    // Relasi ke warna (many to one produk → warna)
    public function warnas()
    {
    return $this->hasMany(Warna::class, 'id_produk', 'id_produk');
    }
    public function warna()
    {
    return $this->hasMany(Warna::class, 'id_produk');
    }

// Relasi ke kategori (produk → belongsTo kategori)
    public function kategori()
    {
    return $this->belongsTo(Kategori::class, 'kategori', 'id_kategori');
    }

}
