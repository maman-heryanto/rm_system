@extends('layouts.velzon')

@section('title', 'Customers')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Pelanggan</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dasbor</a></li>
                    <li class="breadcrumb-item active">Pelanggan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h5 class="card-title mb-0 flex-grow-1">Daftar Pelanggan</h5>
                <div>
                     <a href="{{ route('customers.create') }}" class="btn btn-primary add-btn">
                        <i class="ri-add-line align-bottom me-1"></i> Tambah Pelanggan
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-nowrap align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Kontak</th>
                                <th>Alamat</th>
                                <th>Total Penjualan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->contact }}</td>
                                <td>{{ Str::limit($customer->address, 50) }}</td>
                                <td>
                                    <span class="badge bg-success-subtle text-success">
                                        {{ $customer->transactions->where('type', 'sale')->count() }} Penjualan
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a href="{{ route('customers.edit', $customer->id) }}" class="dropdown-item"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Ubah</a></li>
                                            <li>
                                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item remove-item-btn">
                                                        <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Hapus
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada pelanggan ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
