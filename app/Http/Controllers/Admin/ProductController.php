<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\ProductInventoryHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {

            $searchKey = request('searchKey');
            $products = Product::query();

            $products->when($request->searchKey, function ($query, $search) {
                $query->where('name', 'LIKE', "%{$search}%");
            });

            $products = $products->latest()->paginate(10);

            if (request()->wantsJson()) {
                return ProductResource::collection($products);
            }
            return view('products.index', compact('products', 'searchKey'));
        } catch (\Exception $e) {

            $error = $e->getMessage();
            return view('errors.error_500', compact('error'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            return view('products.create');
        } catch (\Exception $e) {

            $error = $e->getMessage();
            return view('errors.error_500', compact('error'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStoreRequest $request)
    {
        try {
            $imageUrl = '';

            if ($request->hasFile('image')) {

                $imageName = time() . '.' . $request->file('image')->extension();
                $request->file('image')->move(public_path('images/products/'), $imageName);
                $imageUrl = 'images/products/' . $imageName;
            }

            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'image' => $imageUrl,
                'barcode' => $request->barcode,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'status' => $request->status
            ]);

            $inventory = new ProductInventory();
            $inventory->product_id = $product->id;
            $inventory->save();

            $inventory_history = new ProductInventoryHistory();
            $inventory_history->product_id = $product->id;
            $inventory_history->product_inventory_id = $inventory->id;
            $inventory_history->order_id = null;
            $inventory_history->operation = 0;
            $inventory_history->save();

            if (!$product) {
                return redirect()->back()->with('error', __('product.error_creating'));
            }
            return redirect()->route('products.index')->with('success', __('product.success_creating'));
        } catch (\Exception $e) {

            $error = $e->getMessage();
            return view('errors.error_500', compact('error'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        try {
            return view('products.edit')->with('product', $product);
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return view('errors.error_500', compact('error'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        try {
            $product->name = $request->name;
            $product->description = $request->description;
            $product->barcode = $request->barcode;
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $product->status = $request->status;

            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->file('image')->extension();
                $request->file('image')->move(public_path('images/products/'), $imageName);
                $imageUrl = 'images/products/' . $imageName;
                $product->image = $imageUrl;
            }

            if (!$product->save()) {
                return redirect()->back()->with('error', __('product.error_updating'));
            }
            return redirect()->route('products.index')->with('success', __('product.success_updating'));
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return view('errors.error_500', compact('error'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try {
            if ($product->image) {
                Storage::delete($product->image);
            }
            $product->delete();

            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {

            $error = $e->getMessage();
            return view('errors.error_500', compact('error'));
        }
    }
}
