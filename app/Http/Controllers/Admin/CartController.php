<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        try {

            $searchKey = request('searchKey');

            $products = Product::with('inventory')
                ->whereHas('inventory', function ($query) {
                    $query->where('master_quantity', '>', 0);
                })
                ->when($searchKey, function ($query, $searchKey) {
                    $query->where('name', 'like', "%$searchKey%");
                })
                ->get();

            if ($request->ajax()) {

                $productListHtml = view('cart.product_list', compact('products'))->render();

                return response()->json(['status' => true, 'productListHtml' => $productListHtml]);
            }

            return view('cart.index', compact('products'));
        } catch (\Exception $ex) {

            $error = $ex->getMessage();
            return view('errors.error_500', compact('error'));
        }
    }

    public function searchCustomer(Request $request)
    {
        try {

            $query = $request->input('query');

            $customers = Customer::where('first_name', 'LIKE', "%$query%")
                ->orWhere('last_name', 'LIKE', "%$query%")
                ->take(10)
                ->get(['id', 'first_name', 'last_name']);

            return response()->json(['status' => true, 'customers' => $customers]);
        } catch (\Exception $ex) {

            $error = $ex->getMessage();
            return response()->json(['status' => false]);
        }
    }

    public function verifyCustomer(Request $request)
    {
        try {

            $fullName = $request->input('customerName');
            $firstName = explode(' ', $fullName)[0];
            $lastName = explode(' ', $fullName)[1];

            $customer = Customer::where('first_name', $firstName)
                ->where('last_name', $lastName)
                ->first();

            if ($customer) {

                return response()->json(['status' => true, 'customer' => $customer]);
            } else {

                return response()->json(['status' => false]);
            }
        } catch (\Exception $ex) {

            $error = $ex->getMessage();
            return response()->json(['status' => false]);
        }
    }

    public function addProductToCart(Request $request)
    {

        try {

            $productId = $request->input('productId');

            $product = Product::with('inventory')->find($productId);

            if ($product) {

                if ($product->inventory->master_quantity > 0) {
                    return response()->json(['status' => true, 'product' => $product]);
                } else {
                    return response()->json(['status' => false, 'error' => 'Product out of stock']);
                }
            } else {
                return response()->json(['status' => false, 'error' => 'Product not found']);
            }
        } catch (\Exception $ex) {

            $error = $ex->getMessage();
            return response()->json(['status' => false, 'error' => $error]);
        }
    }

    public function searchBarcode(Request $request)
    {

        try {

            $query = $request->input('searchKey');

            $product = Product::with('inventory')
                ->where('barcode', $query)
                ->whereHas('inventory', function ($query) {
                    $query->where('master_quantity', '>', 0);
                })
                ->first();

            if($product) {
                return response()->json(['status' => true, 'product' => $product]);
            } else {
                return response()->json(['status' => false, 'error' => 'Product not found']);
            }

        } catch (\Exception $ex) {

            $error = $ex->getMessage();
            return response()->json(['status' => false, 'error' => $error]);
        }
    }
}
