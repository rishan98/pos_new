@extends('layouts.admin')
@section('content')

<style>
    .invoice-container {
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ddd;
        background: #fff;
    }

    .invoice-header img {
        width: 100%;
        max-height: 150px;
        object-fit: contain;
    }

    .table td,
    .table th {
        text-align: center;
        vertical-align: middle;
    }

    .total {
        font-size: 20px;
        font-weight: bold;
        text-align: right;
        margin-top: 10px;
    }
</style>

<div class="invoice-container shadow">
    <div class="invoice-header text-center">
        <img src="{{ asset('images/pdf_header.jpg') }}" class="img-fluid" alt="Invoice Header">
    </div>

    <table class="table table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>Qty</th>
                <th>Description</th>
                <th>Rate</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($order))
            @if($order->items->count() > 0)
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->price }}</td>
                <td>{{ $item->quantity * $item->price }}</td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="4">No items found.</td>
            </tr>
            @endif
            @endif
        </tbody>
    </table>

    <div class="total">
        Total: <span id="totalAmount">Rs. {{ $order->total_amount }}</span>
    </div>
</div>
@endsection