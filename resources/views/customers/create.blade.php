@extends('layouts.velzon')

@section('title', 'Add Customer')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Tambah Pelanggan</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Pelanggan</a></li>
                    <li class="breadcrumb-item active">Tambah Baru</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi Pelanggan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('customers.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" name="name" id="name" class="form-control" required placeholder="Masukkan nama pelanggan">
                    </div>
                    <div class="mb-3">
                        <label for="contact" class="form-label">Info Kontak</label>
                        <input type="text" name="contact" id="contact" class="form-control" placeholder="Masukkan nomor telepon atau email">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea name="address" id="address" class="form-control" rows="3" placeholder="Masukkan alamat"></textarea>
                    </div>
                    <div class="d-flex align-items-center justify-content-end mt-4">
                        <a href="{{ route('customers.index') }}" class="btn btn-light me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Pelanggan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
