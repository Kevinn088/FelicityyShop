@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h4>Edit Alamat Pengiriman</h4>

    <form action="{{ route('customer.alamat_update', $alamat->id_user_alamat) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="id_keranjang" value="{{ $id_keranjang ?? '' }}">

        <div class="form-group mt-3">
            <label for="nama">Nama Penerima</label>
            <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $alamat->nama_penerima) }}" required>
        </div>

        <div class="form-group mt-3">
            <label for="telp">No Telepon</label>
            <input type="text" class="form-control" id="telp" name="telp" value="{{ old('telp', $alamat->no_telp) }}" required>
        </div>

        <div class="form-group mt-3">
            <label for="provinsi">Provinsi</label>
            <select name="provinsi" id="provinsi" class="form-control" required>
                <option value="">-- Pilih Provinsi --</option>
                @foreach ($provinsi as $prov)
                    <option value="{{ $prov['id'] }}|{{ $prov['nama'] }}"
                        {{ $alamat->id_provinsi == $prov['id'] ? 'selected' : '' }}>
                        {{ $prov['nama'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mt-3">
            <label for="kota">Kota/Kabupaten</label>
            <select name="kota" id="kota" class="form-control" required>
                <option value="">-- Pilih Kota --</option>
                @foreach ($kota as $k)
                    <option value="{{ $k['id'] }}|{{ $k['nama'] }}"
                        {{ $alamat->id_kota == $k['id'] ? 'selected' : '' }}>
                        {{ $k['nama'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mt-3">
            <label for="pos">Kode Pos</label>
            <input type="text" class="form-control" id="pos" name="pos" value="{{ old('pos', $alamat->kode_pos) }}" required>
        </div>

        <div class="form-group mt-3">
            <label for="alamat">Alamat Lengkap</label>
            <textarea name="alamat" id="alamat" class="form-control" rows="3" required>{{ old('alamat', $alamat->alamat) }}</textarea>
        </div>

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
