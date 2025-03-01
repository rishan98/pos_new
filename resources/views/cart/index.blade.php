@extends('layouts.admin')

@section('title', __('order.title'))

@section('content')
<div class="row">
    <!-- Left Column -->
    <div class="col-md-6 col-lg-5">
        <!-- Scan Barcode and Select Customer -->
        <div class="row mb-2">
            <div class="col">
                <div class="input-group position-relative">
                    <input type="text" class="form-control" placeholder="Enter Product Code" id="search-barcode" autocomplete="off">
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

            <input type="hidden" name="total_amount" id="hidden-total-amount" value="0">
            <input type="hidden" name="total_discount" id="hidden-total-discount" value="0">
            <input type="hidden" name="payment_status" id="hidden-payment-status">
            <input type="hidden" name="paid_amount" id="hidden-paid-amount">

            <div class="user-cart">
                <div class="card h-50">
                    <h5 class="card-header"><b>Customer Name</b></h5>
                    <h6 class="card-title ml-3 mt-2" id="customer-name-text">No Selected Customer</h6>
                    <input type="hidden" name="verified_customer_id" id="verified_customer_id">
                </div>
                <div class="card">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th><span style="margin-left: 60px;">Quantity</span></th>
                                <th><span>Discount (Rs.)</span></th>
                                <th class="text-end">Price (Rs.)</th>
                            </tr>
                        </thead>
                    </table>
                    <div style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-striped">
                            <tbody id="product-table-body">
                                <!-- Rows will be added dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Total and Buttons -->
            <div class="row mt-3">
                <div class="col">Sub Total :</div>
                <div class="col text-end"><span id="sub-total">Rs. 0.00</span></div>
            </div>
            <div class="row mt-3">
                <div class="col">Total Discount :</div>
                <div class="col text-end"><span id="total-discount">Rs. 0.00</span></div>
            </div>
            <div class="row mt-3">
                <div class="col">Total Amount :</div>
                <div class="col text-end"><span id="total-amount">Rs. 0.00</span></div>
            </div>

        </form>
        <div class="row mt-2">
            <div class="col">
                <button class="btn btn-danger btn-block" onclick="clearCart()">Cancel</button>
            </div>
            <div class="col">
                <button id="checkout-btn" class="btn btn-primary btn-block" type="button" disabled>Checkout</button>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-md-6 col-lg-7">
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

<!-- Bootstrap Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">Confirm Checkout</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Total Amount: </strong><span id="modal-total-amount">Rs.0.00</span></p>

                <div class="form-group">
                    <label for="payment_status">Payment Status</label>
                    <select class="form-control" id="payment_status">
                        <option value="0">Not Paid</option>
                        <option value="1">Partially Paid</option>
                        <option value="2">Fully Paid</option>
                    </select>
                </div>

                <!-- Partially Paid Amount Input (Hidden by Default) -->
                <div class="form-group mt-3" id="partial-payment-container" style="display: none;">
                    <label for="partial_payment_amount">Enter Paid Amount</label>
                    <input type="number" class="form-control" id="partial_payment_amount" name="partial_payment_amount" min="0">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="confirm-checkout">Confirm</button>
            </div>
        </div>
    </div>
</div>
@endsection