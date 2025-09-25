@extends('students.template.index')

@push('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <style>
        .form-control:focus {
            border-color: #4361ee;
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
        }

        .btn-primary {
            background-color: #4361ee;
            border: none;
            border-radius: 6px;
            padding: 10px 20px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #3a56d4;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        #map {
            width: 100%;
            height: 400px;
            border-radius: 12px;
            border: 1px solid #ccc;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i> Edit Data - {{ $student->nama_lengkap }}
                        </h5>
                    </div>
                    <div class="card-body">

                        {{-- === Form Foto === --}}
                        <form action="{{ route('student.personal.updateData') }}" method="POST"
                            enctype="multipart/form-data" class="mb-4">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="form_type" value="foto">
                            <label class="form-label">Foto Profil</label>
                            @if ($student->foto)
                                <img src="{{ asset('storage/' . $student->foto) }}" class="img-thumbnail d-block mb-2"
                                    style="max-width:150px;">
                            @endif
                            @if ($student->is_edited)
                                <input type="file" name="foto" class="form-control mb-2" accept="image/*">
                                <button type="submit" class="btn btn-primary">Simpan Foto</button>
                            @endif
                        </form>

                        {{-- === Form TTD === --}}
                        <form action="{{ route('student.personal.updateData') }}" method="POST"
                            enctype="multipart/form-data" class="mb-4">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="form_type" value="ttd">
                            <label class="form-label">Tanda Tangan</label>
                            @if ($student->ttd)
                                <img src="{{ asset('storage/' . $student->ttd) }}" class="img-thumbnail d-block mb-2"
                                    style="max-width:150px;">
                            @endif
                            @if ($student->is_edited)
                                <input type="file" name="ttd" class="form-control mb-2" accept="image/*">
                                <button type="submit" class="btn btn-primary">Simpan TTD</button>
                            @endif
                        </form>

                        {{-- === Form Data Teks === --}}
                        <form action="{{ route('student.personal.updateData') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="form_type" value="text">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" value="{{ $student->nama_lengkap }}"
                                        readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Orangtua</label>
                                    <input type="text" name="nama_orangtua" class="form-control"
                                        value="{{ old('nama_orangtua', $student->nama_orangtua) }}" @readonly(!$student->is_edited)>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">NIM</label>
                                    <input type="text" class="form-control" value="{{ $student->nim }}" readonly
                                        disabled>
                                </div>
                                <div class="col-md-6 mb-3">
                                    @if ($isDefaultPassword)
                                        <label class="form-label">Password Baru</label>
                                        <input type="text" name="password" class="form-control" @readonly(!$student->is_edited)
                                            minlength="8">
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-control" @disabled(!$student->is_edited)>
                                        <option value="L" @selected($student->jenis_kelamin == 'L')>Laki-laki</option>
                                        <option value="P" @selected($student->jenis_kelamin == 'P')>Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="form-control"
                                        value="{{ $student->tanggal_lahir }}" @readonly(!$student->is_edited)>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" rows="3" class="form-control" @readonly(!$student->is_edited)>{{ old('alamat', $student->alamat) }}</textarea>
                            </div>

                            {{-- Lokasi Maps --}}
                            @if ($student->is_edited)
                                <div id="map"></div>
                                <input type="hidden" name="alamat_lat" id="alamat_lat"
                                    value="{{ old('alamat_lat', $student->alamat_lat) }}">
                                <input type="hidden" name="alamat_lng" id="alamat_lng"
                                    value="{{ old('alamat_lng', $student->alamat_lng) }}">
                            @else
                                @if ($student->alamat_lat && $student->alamat_lng)
                                    <a href="https://www.google.com/maps?q={{ $student->alamat_lat }},{{ $student->alamat_lng }}"
                                        target="_blank" class="btn btn-outline-primary">
                                        Lihat Lokasi di Google Maps
                                    </a>
                                @endif
                            @endif

                            <div class="row mt-3">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">No. HP</label>
                                    <input type="text" name="no_telepon" class="form-control"
                                        value="{{ old('no_telepon', $student->no_telepon) }}" @readonly(!$student->is_edited)>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">No. HP Orang Tua</label>
                                    <input type="text" name="no_telepon_orangtua" class="form-control"
                                        value="{{ old('no_telepon_orangtua', $student->no_telepon_orangtua) }}"
                                        @readonly(!$student->is_edited)>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $student->email) }}" @readonly(!$student->is_edited)>
                            </div>

                            @if ($student->is_edited)
                                <button type="submit" class="btn btn-success mt-3"><i class="fas fa-save"></i> Simpan
                                    Data</button>
                            @else
                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-info-circle"></i> Data sudah terkunci.
                                    Jika ingin melakukan perubahan, silakan hubungi <strong>dosen pembimbing</strong>.
                                </div>
                            @endif
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @if ($student->is_edited)
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var lat = {{ old('alamat_lat', $student->alamat_lat ?? -6.2) }};
                var lng = {{ old('alamat_lng', $student->alamat_lng ?? 106.816666) }};
                var map = L.map('map').setView([lat, lng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

                var marker = L.marker([lat, lng], {
                    draggable: true
                }).addTo(map);
                marker.on('dragend', function(e) {
                    var pos = marker.getLatLng();
                    document.getElementById('alamat_lat').value = pos.lat;
                    document.getElementById('alamat_lng').value = pos.lng;
                });

                L.Control.geocoder({
                        defaultMarkGeocode: false
                    })
                    .on('markgeocode', function(e) {
                        var center = e.geocode.center;
                        map.setView(center, 16);
                        marker.setLatLng(center);
                        document.getElementById('alamat_lat').value = center.lat;
                        document.getElementById('alamat_lng').value = center.lng;
                    }).addTo(map);
            });
        </script>
    @endif
@endpush
