<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\OrderStoreRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductInventory;
use App\Models\ProductInventoryHistory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {

        try {

            $searchKey = request('searchKey');
            $orders = Order::with('customer', 'items')->when($searchKey, function ($query, $searchKey) {
                return $query->where('order_number', 'like', '%' . $searchKey . '%');
            })->orderBy('id', 'desc')->paginate(10);

            //    dd($orders);

            return view('orders.index', compact('orders', 'searchKey'));
        } catch (\Exception $e) {

            $error = $e->getMessage();
            return view('errors.error_500', compact('error'));
        }
    }

    public function store(OrderStoreRequest $request)
    {
        try {

            DB::beginTransaction();

            $number = 0000;
            $lastOrderId = Order::latest()->first();
            $lastOrderId->id = $lastOrderId->id + 1 ?? 1;
            $num = $number + $lastOrderId->id;

            // Format with leading zeros
            $formattedNumber = str_pad($num, 5, "0", STR_PAD_LEFT);

            $order = new Order();

            $order->customer_id = $request->verified_customer_id;
            $order->order_number = 'ORD' . date('Ymd') . '' . $formattedNumber;
            $order->user_id = Auth::user()->id;
            $order->total_amount = $request->total_amount;
            $order->total_discount = $request->total_discount;
            $order->payment_status = $request->payment_status;

            if ($request->payment_status == 2) {
                $order->paid_amount = $request->total_amount;
            } else {
                $order->paid_amount = $request->paid_amount;
            }
            $order->return_status = 0;
            $order->save();

            foreach ($request->products as $key => $product) {

                $order_item = new OrderItem();

                $order_item->order_id = $order->id;
                $order_item->product_id = $product;
                $order_item->quantity = $request->quantities[$key];
                $order_item->price = $request->price[$key];
                $order_item->discount = $request->discount[$key];
                $order_item->is_return = 0;
                $order_item->save();

                // Update product quantity
                $productInventory = ProductInventory::where('product_id', $product)->first();
                $productInventory->master_quantity = $productInventory->master_quantity - $request->quantities[$key];
                $productInventory->reserved_quantity = $productInventory->reserved_quantity + $request->quantities[$key];
                $productInventory->save();

                $inventoryHistory = new ProductInventoryHistory();
                $inventoryHistory->product_inventory_id = $productInventory->id;
                $inventoryHistory->product_id = $productInventory->product_id;
                $inventoryHistory->running_quantity = $productInventory->master_quantity;
                $inventoryHistory->operation = 2;
                $inventoryHistory->quantity = -$request->quantities[$key];
                $inventoryHistory->save();
            }

            DB::commit();

            return back()->with('success', 'Order created successfully');
        } catch (\Exception $e) {

            $error = $e->getMessage();
            return view('errors.error_500', compact('error'));
        }
    }

    public function orderDetails($id)
    {

        try {

            $order = Order::with('customer', 'items')->findOrFail($id);

            return view('orders.order_details', compact('order'));
        } catch (\Exception $e) {

            $error = $e->getMessage();
            return view('errors.error_500', compact('error'));
        }
    }
}
