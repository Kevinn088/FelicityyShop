@extends('customer.layouts.master')

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <div class="page-title">
                        <h4>Alamat Penerima</h4>
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item active">Alamat Penerima</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="page-content-wrapper">
            <div class="card">
                <div class="card-body">
                    <h5 class="header-title">Form Alamat Penerima Barang</h5>
                    <p class="card-title-desc">Isi Dengan Sesuai Agar Pengiriman Tidak Terkendala</p>
                    <form action="{{ route('customer.alamat_checkout_store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-md-4">
                                <input type="hidden" value="{{ $id }}" name="id_keranjang">
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap Penerima</label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" placeholder="Nama Lengkap">
                                    @error('nama') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">No. Telp</label>
                                    <input type="number" class="form-control @error('telp') is-invalid @enderror" name="telp" placeholder="No. Telp">
                                    @error('telp') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Kode Pos</label>
                                    <input type="number" class="form-control @error('pos') is-invalid @enderror" name="pos" placeholder="Kode POS / NO. POS">
                                    @error('pos') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Pilih Provinsi</label>
                                    <select class="form-control select2 @error('provinsi') is-invalid @enderror" name="provinsi" id="provinsi">
                                        <option disabled selected>Pilih Provinsi</option>
                                        <option value="1|Jawa Barat">Jawa Barat</option>
                                        <option value="2|Jawa Tengah">Jawa Tengah</option>
                                        <option value="3|DKI Jakarta">DKI Jakarta</option>
                                        <option value="4|Jawa Timur">Jawa Timur</option>
                                        <option value="5|Yogyakarta">Yogyakarta</option>
                                        <option value="6|Kalimantan Barat">Kalimantan Barat</option>
                                        <option value="7|Kalimantan Tengah">Kalimantan Tengah</option>
                                        <option value="8|Kalimantan Selatan">Kalimantan Selatan</option>
                                        <option value="9|Kalimantan Timur">Kalimantan Timur</option>
                                        <option value="10|Kalimantan Utara">Kalimantan Utara</option>
                                    </select>
                                    @error('provinsi') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Pilih Kota / Kabupaten</label>
                                    <select class="form-control select2 @error('kota') is-invalid @enderror" name="kota" id="kota">
                                        <option disabled selected>Pilih Kota</option>
                                    </select>
                                    @error('kota') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Tulis Lokasi Penerimaan Barang</label>
                                <div class="mb-3">
                                    <textarea name="alamat" placeholder="Jl. Jend Sudirman 2010 A / Komplek Permata 1 RT 34 RW 20"
                                        class="form-control @error('alamat') is-invalid @enderror" cols="20" rows="5"></textarea>
                                    @error('alamat') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary w-100">Simpan Alamat</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="/morvin/dist/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('js')
    <script src="/morvin/dist/assets/libs/select2/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2();

            const kotaOptions = {
                "1|Jawa Barat": [
                    { id: "101|Bandung", text: "Bandung" },
                    { id: "102|Bekasi", text: "Bekasi" },
                    { id: "103|Bogor", text: "Bogor" }
                ],
                "2|Jawa Tengah": [
                    { id: "201|Semarang", text: "Semarang" },
                    { id: "202|Solo", text: "Solo" },
                    { id: "203|Magelang", text: "Magelang" }
                ],
                "3|DKI Jakarta": [
                    { id: "301|Jakarta Pusat", text: "Jakarta Pusat" },
                    { id: "302|Jakarta Timur", text: "Jakarta Timur" },
                    { id: "303|Jakarta Selatan", text: "Jakarta Selatan" }
                ],
                "4|Jawa Timur": [
                    { id: "401|Surabaya", text: "Surabaya" },
                    { id: "402|Malang", text: "Malang" },
                    { id: "403|Kediri", text: "Kediri" }
                ],
                "5|Yogyakarta": [
                    { id: "501|Yogyakarta", text: "Yogyakarta" },
                    { id: "502|Sleman", text: "Sleman" },
                    { id: "503|Bantul", text: "Bantul" }
                ],
                "6|Kalimantan Barat": [
                    { id: "601|Pontianak", text: "Pontianak" },
                    { id: "602|Singkawang", text: "Singkawang" },
                    { id: "603|Ketapang", text: "Ketapang" }
                ],
                "7|Kalimantan Tengah": [
                    { id: "701|Palangka Raya", text: "Palangka Raya" },
                    { id: "702|Sampit", text: "Sampit" },
                    { id: "703|Pangkalan Bun", text: "Pangkalan Bun" }
                ],
                "8|Kalimantan Selatan": [
                    { id: "801|Banjarmasin", text: "Banjarmasin" },
                    { id: "802|Banjarbaru", text: "Banjarbaru" },
                    { id: "803|Martapura", text: "Martapura" }
                ],
                "9|Kalimantan Timur": [
                    { id: "901|Samarinda", text: "Samarinda" },
                    { id: "902|Balikpapan", text: "Balikpapan" },
                    { id: "903|Bontang", text: "Bontang" }
                ],
                "10|Kalimantan Utara": [
                    { id: "1001|Tarakan", text: "Tarakan" },
                    { id: "1002|Tanjung Selor", text: "Tanjung Selor" }
                ]
            };

            $('#provinsi').on('change', function () {
                const provinsiValue = $(this).val();
                const kotaSelect = $('#kota');
                kotaSelect.empty().append('<option disabled selected>Pilih Kota</option>');

                if (kotaOptions[provinsiValue]) {
                    kotaOptions[provinsiValue].forEach(function (kota) {
                        kotaSelect.append(new Option(kota.text, kota.id));
                    });
                }

                kotaSelect.trigger('change.select2');
            });
        });
    </script>
@endsection
