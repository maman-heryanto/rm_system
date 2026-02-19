@extends('layouts.velzon')

@section('title', 'Edit Pengeluaran')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Edit Pengeluaran</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('expenses.index') }}">Pengeluaran</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Form Edit Pengeluaran</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('expenses.update', $expense->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="expense_date" class="form-label">Tanggal Pengeluaran</label>
                        <input type="date" class="form-control" id="expense_date" name="expense_date" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label">Kategori</label>
                        <input type="text" class="form-control" id="category" name="category" value="{{ old('category', $expense->category) }}" placeholder="Contoh: Operasional, Bahan Baku, Gaji">
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">Jumlah (Rp)</label>
                        <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount', $expense->amount) }}" placeholder="0" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description', $expense->description) }}</textarea>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('expenses.index') }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
