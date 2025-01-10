<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierStoreRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        try {
            $searchKey = request('searchKey');

            $suppliers = Supplier::when($searchKey, function ($query, $searchKey) {
                $query->where('name', 'like', "%$searchKey%");
            })
                ->latest()->paginate(10);

            return view('suppliers.index', compact('suppliers', 'searchKey'));
        } catch (\Exception $e) {

            $error = $e->getMessage();
            return view('errors.error_500', compact('error'));
        }
    }

    public function createUI()
    {
        try {

            return view('suppliers.create');
        } catch (\Exception $e) {

            $error = $e->getMessage();
            return view('errors.error_500', compact('error'));
        }
    }

    public function create(SupplierStoreRequest $request)
    {
        try {

            $supplier = new Supplier();
            $supplier->name = $request->name;
            $supplier->email = $request->email;
            $supplier->phone = $request->phone;
            $supplier->address = $request->address;
            $supplier->status = $request->status;

            $supplier->save();

            return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully');
        } catch (\Exception $e) {

            $error = $e->getMessage();
            return back()->with('error', $error);
        }
    }

    public function editUI($id)
    {
        try {

            $supplier = Supplier::findOrFail($id);

            return view('suppliers.edit', compact('supplier'));
        } catch (\Exception $e) {

            $error = $e->getMessage();
            return view('errors.error_500', compact('error'));
        }
    }

    public function edit(SupplierStoreRequest $request, $id)
    {
        try {

            $supplier = Supplier::findOrFail($id);

            $supplier->name = $request->name;
            $supplier->email = $request->email;
            $supplier->phone = $request->phone;
            $supplier->address = $request->address;
            $supplier->status = $request->status;

            $supplier->save();

            return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully');
        } catch (\Exception $e) {

            $error = $e->getMessage();
            return back()->with('error', $error);
        }
    }

    public function destroy($id)
    {

        try {
            $supplier = Supplier::findOrFail($id);
            $supplier->delete();

            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false
            ]);
        }
    }
}
