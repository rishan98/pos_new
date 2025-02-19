@extends('layouts.admin')

@section('title', __('order.title'))

@section('content')
<div class="row">
    <!-- Left Column -->
    <div class="col-md-6 col-lg-4">
        <!-- Scan Barcode and Select Customer -->
        <div class="row mb-2">
            <div class="col">
                <div class="input-group position-relative">
                    <input type="text" class="form-control" placeholder="Enter Product Barcode" id="search-barcode" autocomplete="off">
                    <button class="btn btn-dark text-white" type="button" id="search-barcode-button" onClick="searchBarcode()">Add</button>
                </div>
            </div>
            <div class="col">
                <div class="input-group position-relative">
                    <input type="text" class="form-control" id="search-customer" placeholder="Search Customer..." autocomplete="off">
                    <button class="btn btn-dark text-white" type="button" id="search-customer-button" onClick="verifyCustomer()">Add</button>
                    <div class="dropdown-menu w-100" id="customer-suggestions"></div>
                </div>

            </div>
        </div>

        <!-- User Cart -->
        <form action="{{ route('orders.store')}}" method="post" id="order-form">
            @csrf
            <div class="user-cart">
                <div class="card h-50">
                    <h5 class="card-header"><b>Customer Name</b></h5>
                    <h6 class="card-title ml-3 mt-2" id="customer-name-text">No Selected Customer</h6>
                    <input type="hidden" name="verified-customer-id" id="verified-customer-id">
                </div>
                <div class="card">

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th><span style="margin-left: 60px;">Quantity</span></th>
                                <th class="text-end">Price</th>
                            </tr>
                        </thead>
                        <tbody id="product-table-body">

                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Total and Buttons -->
            <div class="row mt-3">
                <div class="col">Total:</div>
                <div class="col text-end">Rs 450.00</div>
            </div>
            <div class="row mt-2">
                <div class="col">
                    <button class="btn btn-danger btn-block" disabled>Cancel</button>
                </div>
                <div class="col">
                    <button class="btn btn-primary btn-block" type="submit">Checkout</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Right Column -->
    <div class="col-md-6 col-lg-8">
        <!-- Search Product -->
        <div class="mb-2">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search Product..." id="search-product-input">
            </div>
        </div>

        <!-- Product List -->
        @if(count($products) > 0)
        <div class="order-product" id="product-list-content">
            @include('cart.product_list')
        </div>
        @else
        <p>No products found.</p>
        @endif
    </div>
</div>
@endsection