@extends('layouts.velzon')

@section('title', 'Edit Customer')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Ubah Pelanggan</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Pelanggan</a></li>
                    <li class="breadcrumb-item active">Ubah</li>
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
                <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $customer->name }}" required placeholder="Masukkan nama pelanggan">
                    </div>
                    <div class="mb-3">
                        <label for="contact" class="form-label">Info Kontak</label>
                        <input type="text" name="contact" id="contact" class="form-control" value="{{ $customer->contact }}" placeholder="Masukkan nomor telepon atau email">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea name="address" id="address" class="form-control" rows="3" placeholder="Masukkan alamat">{{ $customer->address }}</textarea>
                    </div>
                    <!-- Hidden Type field to prevent validation error if not present in form but required in controller -->
                    <input type="hidden" name="type" value="{{ $customer->type ?? 'customer' }}">

                    <div class="d-flex align-items-center justify-content-end mt-4">
                        <a href="{{ route('customers.index') }}" class="btn btn-light me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Perbarui Pelanggan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
