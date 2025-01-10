<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductInventory;
use App\Models\ProductInventoryHistory;
use App\Models\Setting;
use Illuminate\Http\Request;

class ProductInventoryController extends Controller
{
    public function index()
    {
        try {

            $searchKey = request('searchKey');
            $inventories = ProductInventory::with('product', 'history')
                ->when($searchKey, function ($query, $searchKey) {
                    $query->whereHas('product', function ($query) use ($searchKey) {
                        $query->where('name', 'like', "%$searchKey%");
                    });
                })
                ->latest()->paginate(10);

            return view('product-inventories.index', compact('inventories', 'searchKey'));
        } catch (\Exception $e) {

            $error = $e->getMessage();
            return view('errors.error_500', compact('error'));
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $inventory = ProductInventory::findOrFail($id);

            if ($request->action == 1) {
                $inventory->master_quantity = $inventory->master_quantity + $request->quantity;
            } else {

                if ($inventory->master_quantity < $request->quantity) {
                    return redirect()->back()->with('error', 'Inventory quantity is not enough.');
                }

                $inventory->master_quantity = $inventory->master_quantity - $request->quantity;
                $inventory->removed_quantity = $inventory->removed_quantity + $request->quantity;
            }

            $inventory->save();

            $inventoryHistory = new ProductInventoryHistory();
            $inventoryHistory->product_inventory_id = $inventory->id;
            $inventoryHistory->product_id = $inventory->product_id;
            $inventoryHistory->running_quantity = $inventory->master_quantity;

            if ($request->action == 1) {
                $inventoryHistory->operation = 1;
                $inventoryHistory->quantity = $request->quantity;
            } else {
                $inventoryHistory->operation = 2;
                $inventoryHistory->quantity = -$request->quantity;
            }

            $inventoryHistory->save();

            return redirect()->back()->with('success', 'Inventory updated successfully.');
        } catch (\Exception $e) {

            $error = $e->getMessage();
            return back()->with('error', $error);
        }
    }
}
