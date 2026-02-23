<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryApiController extends Controller
{
    public function getItemInfo(Request $request)
    {
        $itemName = $request->query('item_name');
        $branchId = $request->query('branch_id'); // Optional, passed from frontend if superadmin
        $user = auth()->user();

        // If not superadmin and logged in, enforce their branch
        if ($user && $user->isAdmin()) {
            $branchId = $user->branch_id;
        }
        
        if (!$itemName) {
            return response()->json(['error' => 'Item name is required'], 400);
        }

        // Calculate total stock remaining
        $inQuery = \App\Models\InventoryLedger::where('item_name', $itemName)
            ->whereIn('type', ['initial', 'purchase']);
        
        $outQuery = \App\Models\InventoryLedger::where('item_name', $itemName)
            ->where('type', 'sale');

        $priceQuery = \App\Models\InventoryLedger::where('item_name', $itemName)
            ->where('type', 'purchase');

        if ($branchId) {
            $inQuery->where('branch_id', $branchId);
            $outQuery->where('branch_id', $branchId);
            $priceQuery->where('branch_id', $branchId);
        }

        $totalIn = $inQuery->sum('quantity');
        $totalOut = $outQuery->sum('quantity');
            
        $stock = $totalIn - $totalOut;

        // Get minimum purchase price ever for this item
        $basePrice = $priceQuery->max('unit_price') ?? 0;

        return response()->json([
            'stock' => max(0, $stock),
            'base_price' => $basePrice
        ]);
    }
}
