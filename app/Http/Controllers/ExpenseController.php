<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Branch;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Expense::with('branch')->orderBy('expense_date', 'desc');

        // Set default date range to current month if not provided
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Apply date filters
        if ($startDate) {
            $query->whereDate('expense_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('expense_date', '<=', $endDate);
        }
        
        // Filter by branch
        if (!auth()->user()->isSuperAdmin()) {
            // Regular admins can only see their own branch
            $query->where('branch_id', auth()->user()->branch_id);
        } else {
            // Super admins can filter by any branch
            if ($request->filled('branch_id')) {
                $query->where('branch_id', $request->input('branch_id'));
            }
        }

        // Calculate total amount for the filtered query (before pagination)
        $totalAmount = $query->sum('amount');

        // Get paginated results
        $expenses = $query->paginate(10)->withQueryString();
        
        // Fetch branches for filter dropdown if user is super admin
        $branches = auth()->user()->isSuperAdmin() ? Branch::all() : collect();

        // Pass filter values back to view
        $filters = [
            'start_date' => $startDate,
            'end_date'   => $endDate,
            'branch_id'  => $request->input('branch_id'),
        ];

        return view('expenses.index', compact('expenses', 'branches', 'filters', 'totalAmount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branches = Branch::all();
        return view('expenses.create', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'expense_date' => 'required|date',
            'category' => 'nullable|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        // Default to user's branch if not superadmin
        if (!auth()->user()->isSuperAdmin()) {
            $validatedData['branch_id'] = auth()->user()->branch_id;
        }

        Expense::create($validatedData);

        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        // Check authorization
        if (!auth()->user()->isSuperAdmin() && $expense->branch_id !== auth()->user()->branch_id) {
            abort(403, 'Unauthorized action.');
        }

        $branches = Branch::all();
        return view('expenses.edit', compact('expense', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        // Check authorization
        if (!auth()->user()->isSuperAdmin() && $expense->branch_id !== auth()->user()->branch_id) {
            abort(403, 'Unauthorized action.');
        }

        $validatedData = $request->validate([
            'expense_date' => 'required|date',
            'category' => 'nullable|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        // Enforce user's branch if not superadmin
        if (!auth()->user()->isSuperAdmin()) {
            $validatedData['branch_id'] = auth()->user()->branch_id;
        }

        $expense->update($validatedData);

        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        // Check authorization
        if (!auth()->user()->isSuperAdmin() && $expense->branch_id !== auth()->user()->branch_id) {
            abort(403, 'Unauthorized action.');
        }

        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
