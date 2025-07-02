@extends('admin.layouts.master')

@section('content')
    <!-- Page Title -->
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <div class="page-title">
                        <h4>Edit Produk</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Content -->
    <div class="container-fluid">
        <div class="page-content-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Form Edit Produk</h4>
                            <p class="card-title-desc">Perhatikan penulisan setiap produk agar konsumen nyaman bertransaksi</p>

                            <form action="{{ route('produk.update', $produk->id_produk) }}" method="POST" enctype="multipart/form-data">
                                @method('PUT')
                                @csrf

                                <!-- Nama Produk -->
                                <div class="mb-3">
                                    <label class="form-label">Nama Produk</label>
                                    <input type="text" name="nama_produk" class="form-control @error('nama_produk') is-invalid @enderror" value="{{ $produk->nama_produk }}" placeholder="Cotton Carded 30S">
                                    @error('nama_produk')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Kategori Produk -->
                                <div class="mb-3">
                                    <label class="form-label">Kategori Produk</label>
                                    <select name="pilih_kategori" class="form-control select2 @error('pilih_kategori') is-invalid @enderror">
                                        <option disabled selected>--- Pilih Kategori Produk ---</option>
                                        @foreach ($kategori as $data)
                                            <option value="{{ $data->id_kategori }}" {{ $produk->kategori == $data->id_kategori ? 'selected' : '' }}>
                                                {{ strtoupper($data->jenis_kategori) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pilih_kategori')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Foto Produk -->
                                <div class="mb-3">
                                    <label class="form-label">Foto Produk</label>
                                    <div class="row">
                                        @for ($i = 1; $i <= 4; $i++)
                                            <div class="col-md-3">
                                                <input type="file" name="img{{ $i }}" class="form-control" accept="image/*" id="imgInp{{ $i }}">
                                                <input type="hidden" name="fileimg{{ $i }}" value="{{ $produk['foto_produk' . $i] }}">
                                            </div>
                                        @endfor
                                    </div>
                                </div>

                                <!-- Preview Foto -->
                                <div class="mb-3">
                                    <div class="row">
                                        @for ($i = 1; $i <= 4; $i++)
                                            <div class="col-md-3">
                                                <img id="output{{ $i }}" src="{{ $produk['foto_produk' . $i] ? asset('produk/' . $produk['foto_produk' . $i]) : asset('morvin/dist/assets/images/upload.png') }}" width="150px" height="110px" />
                                            </div>
                                        @endfor
                                    </div>
                                </div>

                                <!-- Harga Produk -->
                                <div class="mb-3">
                                    <label class="form-label">Daftar Harga Produk</label>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label class="form-label">Harga 1 pcs</label>
                                            <input type="text" name="harga_produk1" class="form-control input-mask" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'prefix': 'Rp. '" value="{{ $produk->harga_produk1 }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Warna Produk -->
                                <div class="mb-3">
                                    <label class="form-label">Warna Produk</label>
                                    <div id="warna-wrapper">
                                        @if ($produk->warna && $produk->warna->count())
                                            @foreach ($produk->warna as $warna)
                                                <div class="input-group mb-2">
                                                    <input type="text" name="warna[]" class="form-control" value="{{ $warna->nama_warna }}">
                                                    <button type="button" class="btn btn-outline-danger hapus-warna">-</button>
                                                </div>
                                            @endforeach
                                        @endif
                                        <div class="input-group mb-2">
                                            <input type="text" name="warna[]" class="form-control" placeholder="Contoh: Merah">
                                            <button type="button" class="btn btn-outline-success tambah-warna">+</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Deskripsi Produk -->
                                <div class="mb-3">
                                    <label class="form-label">Deskripsi Produk</label>
                                    <textarea name="deskripsi_produk" class="form-control @error('deskripsi_produk') is-invalid @enderror" id="elm1">{{ $produk->deskripsi }}</textarea>
                                    @error('deskripsi_produk')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Submit -->
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-success w-100"><i class="mdi mdi-content-save"></i> Perbarui Produk</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="/morvin/dist/assets/libs/select2/css/select2.min.css" rel="stylesheet" />
    <link href="/morvin/dist/assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" />
    <link href="/morvin/dist/assets/libs/spectrum-colorpicker2/spectrum.min.css" rel="stylesheet" />
    <link href="/morvin/dist/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    <link href="/morvin/dist/assets/libs/summernote/summernote-bs4.min.css" rel="stylesheet" />
@endsection

@section('js')
    <!-- Preview Gambar -->
    @for ($i = 1; $i <= 4; $i++)
        <script>
            document.getElementById('imgInp{{ $i }}').addEventListener('change', function (evt) {
                const [file] = this.files;
                if (file) {
                    document.getElementById('output{{ $i }}').src = URL.createObjectURL(file);
                }
            });
        </script>
    @endfor

    <!-- Dinamis Warna -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const wrapper = document.querySelector('#warna-wrapper');

            wrapper.addEventListener('click', function (e) {
                if (e.target.classList.contains('tambah-warna')) {
                    const group = document.createElement('div');
                    group.classList.add('input-group', 'mb-2');
                    group.innerHTML = `
                        <input type="text" name="warna[]" class="form-control" placeholder="Contoh: Merah">
                        <button type="button" class="btn btn-outline-danger hapus-warna">-</button>
                    `;
                    wrapper.appendChild(group);
                }

                if (e.target.classList.contains('hapus-warna')) {
                    e.target.closest('.input-group').remove();
                }
            });
        });
    </script>

    <!-- Plugin JS -->
    <script src="/morvin/dist/assets/libs/select2/js/select2.min.js"></script>
    <script src="/morvin/dist/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="/morvin/dist/assets/libs/spectrum-colorpicker2/spectrum.min.js"></script>
    <script src="/morvin/dist/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
    <script src="/morvin/dist/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
    <script src="/morvin/dist/assets/libs/inputmask/jquery.inputmask.min.js"></script>
    <script src="/morvin/dist/assets/libs/tinymce/tinymce.min.js"></script>
    <script src="/morvin/dist/assets/libs/summernote/summernote-bs4.min.js"></script>

    <!-- Init JS -->
    <script src="/morvin/dist/assets/js/pages/form-editor.init.js"></script>
    <script src="/morvin/dist/assets/js/pages/form-mask.init.js"></script>
    <script src="/morvin/dist/assets/js/pages/form-advanced.init.js"></script>
@endsection
