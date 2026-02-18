@extends('layouts.velzon')

@section('title', 'Transaction Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Detail Transaksi</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('transactions.index') }}">Transaksi</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Transaksi #{{ $transaction->id }}</h5>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('transactions.index') }}" class="btn btn-soft-secondary btn-sm">
                        <i class="ri-arrow-left-line align-bottom me-1"></i> Kembali ke Daftar
                    </a>
                    <a href="{{ route('transactions.print', $transaction->id) }}" target="_blank" class="btn btn-soft-primary btn-sm">
                        <i class="ri-printer-line align-bottom me-1"></i> Cetak Struk
                    </a>
                    @if($transaction->debt)
                    <a href="{{ route('debts.show', $transaction->debt->id) }}" class="btn btn-soft-info btn-sm">
                        <i class="ri-history-line align-bottom me-1"></i> Lihat Riwayat Hutang
                    </a>
                    @endif
                     <span class="badge {{ $transaction->type === 'sale' ? 'bg-success-subtle text-success' : 'bg-primary-subtle text-primary' }} fs-12">
                        {{ ucfirst($transaction->type) }}
                    </span>
                    <span class="badge bg-secondary-subtle text-secondary fs-12">
                        {{ $transaction->transaction_date->format('d M Y') }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h6 class="text-muted text-uppercase fw-semibold mb-3">Pelanggan / Pemasok</h6>
                        <p class="fw-medium mb-2">{{ $transaction->customer->name ?? 'Umum' }}</p>
                    </div>
                    <div class="col-sm-6">
                         <h6 class="text-muted text-uppercase fw-semibold mb-3">Status Pembayaran</h6>
                        @if($transaction->debt)
                            <span class="badge {{ $transaction->debt->status === 'paid' ? 'bg-success' : ($transaction->debt->status === 'partial' ? 'bg-warning' : 'bg-danger') }} fs-12">
                                {{ ucfirst($transaction->debt->status) }}
                            </span>
                             <div class="mt-2 text-muted">
                                @if($transaction->debt->status !== 'paid')
                                     <a href="{{ route('debts.show', $transaction->debt->id) }}" class="link-primary text-decoration-underline">Lihat Detail Hutang</a>
                                @endif
                             </div>
                        @else
                            <span class="badge bg-success fs-12">Lunas</span>
                        @endif
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless table-nowrap align-middle mb-0">
                        <thead class="table-light text-muted">
                            <tr>
                                <th scope="col">Produk</th>
                                <th scope="col" class="text-end">Jumlah</th>
                                <th scope="col" class="text-end">Harga</th>
                                <th scope="col" class="text-end">Total Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->items as $item)
                            <tr>
                                <td class="fw-medium">{{ $item->product->name }}</td>
                                <td class="text-end">{{ $item->quantity }}</td>
                                <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr class="border-top border-top-dashed">
                                <td colspan="3" class="text-end fw-medium">Sub Total</td>
                                <td class="text-end text-muted">Rp {{ number_format($transaction->total_amount + $transaction->discount, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end fw-medium text-danger">Diskon</td>
                                <td class="text-end text-danger">- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="border-top border-top-dashed">
                                <td colspan="3" class="text-end fw-bold">Total Jumlah</td>
                                <td class="text-end fw-bold">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                            </tr>
                            @if($transaction->debt)
                            <tr>
                                <td colspan="3" class="text-end fw-medium">Jumlah Dibayar</td>
                                <td class="text-end fw-medium">Rp {{ number_format($transaction->debt->amount_paid, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end fw-medium text-success">Kembalian</td>
                                <td class="text-end fw-bold text-success">Rp {{ number_format(max(0, $transaction->debt->amount_paid - $transaction->debt->amount_total), 0, ',', '.') }}</td>
                            </tr>
                            @if($transaction->debt->status !== 'paid')
                            <tr>
                                <td colspan="3" class="text-end fw-medium text-danger">Sisa</td>
                                <td class="text-end fw-bold text-danger">Rp {{ number_format($transaction->debt->amount_total - $transaction->debt->amount_paid, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-end">
                    <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin? Ini akan menghapus transaksi, mengembalikan perubahan stok, dan menghapus catatan hutang terkait.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="ri-delete-bin-fill align-bottom me-1"></i> Hapus Transaksi (Batalkan)
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
