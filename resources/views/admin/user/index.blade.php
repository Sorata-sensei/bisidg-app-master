@extends('admin.template.index')
@push('css')
    <style>
        .container {
            width: 100%;
            padding: 0;
            margin-right: auto;
            margin-left: auto;
        }
    </style>
@endpush

@section('content')
    <div class="container pt-5">
        <form action="{{ route('user.admin.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3 row">
                <label for="inputNama" class="col-sm-2 col-form-label">Nama</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputNama" name="name"
                        value="{{ old('name', $user->name) }}" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="inputEmail" name="email"
                        value="{{ old('email', $user->email) }}" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="inputPassword" class="col-sm-2 col-form-label">Password (opsional)</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="inputPassword" name="password"
                        placeholder="Kosongkan jika tidak ingin mengganti">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="inputttd" class="col-sm-2 col-form-label">TTD</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control" id="inputttd" name="ttd" accept="image/*">
                    @if ($user->ttd)
                        <small class="d-block mt-2">ttd saat ini:</small>
                        <img src="{{ asset('storage/' . $user->ttd) }}" alt="ttd {{ $user->name }}" class="img-thumbnail"
                            style="max-height: 120px;">
                    @endif
                    <p class="text-white btn-danger">Mohon pastikan TTD tidak ada background nya atau bisa gunakan ini untuk
                        menghilangkan background sebelum menggunggah <a
                            href="https://www.photoroom.com/tools/background-remover">Background Remover</a></p>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="inputphoto" class="col-sm-2 col-form-label">Photo Profil</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control" id="inputphoto" name="photo" accept="image/*">
                    @if ($user->photo)
                        <small class="d-block mt-2">photo saat ini:</small>
                        <img src="{{ asset('storage/' . $user->photo) }}" alt="photo {{ $user->name }}"
                            class="img-thumbnail" style="max-height: 120px;">
                    @endif
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Ubah Data</button>
        </form>
    </div>
@endsection

@push('scripts')
@endpush
