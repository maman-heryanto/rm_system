@extends('layouts.velzon')

@section('title', 'Daftar Pengeluaran')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Daftar Pengeluaran</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Pengeluaran</li>
                </ol>
            </div>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Data Pengeluaran</h4>
                <a href="{{ route('expenses.create') }}" class="btn btn-primary btn-sm">
                    <i class="ri-add-line align-bottom me-1"></i> Tambah Pengeluaran
                </a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-nowrap align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Deskripsi</th>
                                <th scope="col">Jumlah</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $expense)
                            <tr>
                                <td>{{ $loop->iteration + ($expenses->currentPage() - 1) * $expenses->perPage() }}</td>
                                <td>{{ $expense->expense_date->format('d M Y') }}</td>
                                <td>{{ $expense->category ?? '-' }}</td>
                                <td>{{ Str::limit($expense->description, 50) }}</td>
                                <td>Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-sm btn-info">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengeluaran ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data pengeluaran.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    {{ $expenses->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
