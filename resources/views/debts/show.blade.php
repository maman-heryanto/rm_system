@extends('layouts.velzon')

@section('title', 'Debt Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Detail Hutang</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('debts.index') }}">Hutang</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi Transaksi</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="ps-0" scope="row">Pelanggan</th>
                                <td class="text-muted">{{ $debt->transaction->customer->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0" scope="row">Tanggal Transaksi</th>
                                <td class="text-muted">{{ $debt->transaction->transaction_date->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0" scope="row">Total Pembelian</th>
                                <td class="text-success fw-bold">Rp {{ number_format($debt->amount_total, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <h6 class="fs-14 mb-3">Barang yang Dibeli</h6>
                    <div class="table-responsive">
                         <table class="table table-bordered table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($debt->transaction->items as $item)
                                <tr>
                                    <td>{{ $item->product->name }} <span class="text-muted">(x{{ $item->quantity }})</span></td>
                                    <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Riwayat Pembayaran</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                     <table class="table table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($debt->payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_date->format('d M Y') }}</td>
                                <td class="text-success fw-semibold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td>{{ $payment->notes ?? '-' }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-sm btn-soft-info">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-soft-danger">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Belum ada pembayaran yang tercatat.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-success-subtle">
                <h5 class="card-title mb-0 text-success">Catat Pembayaran</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <p class="text-muted mb-1 text-uppercase fw-semibold fs-12">Sisa Hutang</p>
                    <h3 class="fw-bold text-danger">Rp {{ number_format($debt->amount_total - $debt->amount_paid, 0, ',', '.') }}</h3>
                </div>

                @if($debt->status !== 'paid')
                <form action="{{ route('debts.payment.store', $debt->id) }}" method="POST" id="record_payment_form">
                    @csrf
                    <div class="mb-3">
                        <label for="amount" class="form-label">Jumlah (Rp)</label>
                        <input type="text" id="amount_display" class="form-control" oninput="formatNumber(this)" data-max="{{ $maxPayment }}" required>
                        <input type="hidden" name="amount" id="amount">

                        <div class="form-text">Maksimal: Rp {{ number_format($maxPayment, 0, ',', '.') }}</div>
                    </div>
                    <div class="mb-3">
                        <label for="payment_date" class="form-label">Tanggal Pembayaran</label>
                        <input type="date" name="payment_date" id="payment_date" value="{{ date('Y-m-d') }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="ri-money-dollar-circle-line align-bottom me-1"></i> Kirim Pembayaran
                    </button>
                </form>
                @else
                <div class="alert alert-success mb-0" role="alert">
                    <i class="ri-check-double-line me-1 align-bottom"></i> Hutang ini sudah lunas!
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function formatNumber(input) {
        // Remove non-numeric characters
        let value = input.value.replace(/[^0-9]/g, '');
        
        // Convert to integer for formatting (if not empty)
        if (value) {
            value = parseInt(value, 10);
            // Format back to 1.000 structure
            input.value = new Intl.NumberFormat('id-ID').format(value);
        } else {
            input.value = '';
        }
    }

    // On form submit, clean the value and validate
    document.getElementById('record_payment_form').addEventListener('submit', function(e) {
        let amountInput = document.getElementById('amount_display');
        if (amountInput) {
            let cleanValue = parseInt(amountInput.value.replace(/\./g, '')) || 0;
            let maxAmount = parseInt(amountInput.getAttribute('data-max')) || 0;

            if (cleanValue > maxAmount) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Jumlah Tidak Valid',
                    text: 'Jumlah tidak boleh lebih besar dari Rp ' + new Intl.NumberFormat('id-ID').format(maxAmount)
                });
                return;
            }

            document.getElementById('amount').value = cleanValue;
        }
    });
</script>
@endsection
