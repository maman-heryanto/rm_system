<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date') ? \Carbon\Carbon::parse($request->input('date')) : today();

        // 1. Chart Data: Stock vs Product Name (Top 15 Lowest Stock)
        $products = \App\Models\Product::orderBy('stock', 'asc')
            ->take(15)
            ->get();
            
        $chartData = [
            'labels' => $products->pluck('name'),
            'stock' => $products->pluck('stock'),
        ];

        // 2. Metric: Purchases Today
        $todayPurchases = \App\Models\Transaction::where('type', 'purchase')
            ->whereDate('transaction_date', $date)
            ->sum('total_amount');

        // 3. Metric: Net Revenue (Uang Masuk Bersih Today)
        // Gross Payments - Change/Excess for debts paid today
        $grossPayments = \App\Models\Payment::whereDate('payment_date', $date)->sum('amount');

        // Calculate total excess (change) for debts that had payments today
        $debtsWithPaymentsToday = \App\Models\Debt::whereHas('payments', function ($q) use ($date) {
            $q->whereDate('payment_date', $date);
        })->get();

        $totalChange = 0;
        foreach ($debtsWithPaymentsToday as $debt) {
            // We need to check if the debt payment was completed *on this date* and if there was change.
            // Simplified logic as per original code: if amount_paid > amount_total, it's change.
            // However, we strictly only care about payments made ON $date.
             if ($debt->amount_paid > $debt->amount_total) {
                $totalChange += ($debt->amount_paid - $debt->amount_total);
            }
        }

        $todayPaid = $grossPayments - $totalChange;

        // 4. Daily Status Breakdown (Paid, Unpaid, Partial)
        // Note: 'created_at' filter implies we are looking at Debts CREATED on that date,
        // which might differ from payments made on that date for older debts.
        // Keeping logic consistent with original "Today" intent -> "Created on Date".
        $todayDebts = \App\Models\Debt::whereDate('created_at', $date)->get();

        $statusMetrics = [
            'paid' => [
                'count' => $todayDebts->where('status', 'paid')->count(),
                'cash_in' => $todayDebts->where('status', 'paid')->sum('amount_total'), // Use amount_total to exclude change
                'outstanding' => 0 // Fully paid
            ],
            'unpaid' => [
                'count' => $todayDebts->where('status', 'unpaid')->count(),
                'cash_in' => $todayDebts->where('status', 'unpaid')->sum('amount_paid'),
                'outstanding' => $todayDebts->where('status', 'unpaid')->sum(fn($d) => $d->amount_total - $d->amount_paid)
            ],
            'partial' => [
                'count' => $todayDebts->where('status', 'partial')->count(),
                'cash_in' => $todayDebts->where('status', 'partial')->sum('amount_paid'),
                'outstanding' => $todayDebts->where('status', 'partial')->sum(fn($d) => $d->amount_total - $d->amount_paid)
            ]
        ];

        return view('dashboard', compact('todayPurchases', 'todayPaid', 'statusMetrics', 'chartData', 'date'));
    }
}
