<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Http\Requests\OrderStoreRequest;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request) {

        try {
            $orders = new Order();
            if($request->start_date) {
                $orders = $orders->where('created_at', '>=', $request->start_date);
            }
            if($request->end_date) {
                $orders = $orders->where('created_at', '<=', $request->end_date . ' 23:59:59');
            }
            $orders = $orders->with(['items', 'payments', 'customer'])->latest()->paginate(10);
    
            $total = $orders->map(function($i) {
                return $i->total();
            })->sum();
            $receivedAmount = $orders->map(function($i) {
                return $i->receivedAmount();
            })->sum();
    
            return view('orders.index', compact('orders', 'total', 'receivedAmount'));
        } catch (\Exception $e) {
           
            $error = $e->getMessage();
            return view('errors.error_500', compact('error'));
        }
        
    }

    public function store(OrderStoreRequest $request)
    {
        try {
           dd($request->all());
        } catch (\Exception $e) {
            
            $error = $e->getMessage();
            return view('errors.error_500', compact('error'));
        }
        
    }
}
