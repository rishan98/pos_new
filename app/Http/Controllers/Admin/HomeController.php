<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        try {

            $orderStats = Order::whereIn('return_status', [0, 1])
                ->selectRaw('COUNT(*) as count, SUM(paid_amount) as total_amount')
                ->first();

            $orderCount = $orderStats->count;
            $totalAmount = $orderStats->total_amount;

            $customers_count = Customer::count();

            $products_count = Product::count();

            return view('home', compact('orderCount', 'totalAmount', 'customers_count', 'products_count'));

        } catch (\Exception $e) {
            
            $error = $e->getMessage();
            return view('errors.error_500', compact('error'));
        }
        
    }
}
