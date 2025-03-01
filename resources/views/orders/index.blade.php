@extends('layouts.admin')

@section('title', __('order.Orders_List'))
@section('content-header', __('order.Orders_List'))
@section('content-actions')
    <a href="{{route('cart.index')}}" class="btn btn-primary">{{ __('cart.title') }}</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-7"></div>
            <div class="col-md-5">
                <form action="{{route('orders.index')}}">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="date" name="start_date" class="form-control" value="{{request('start_date')}}" />
                        </div>
                        <div class="col-md-5">
                            <input type="date" name="end_date" class="form-control" value="{{request('end_date')}}" />
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-primary" type="submit">{{ __('order.submit') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Order Number</th>
                    <th>{{ __('order.Customer_Name') }}</th>
                    <th>{{ __('order.Total') }}</th>
                    <th>{{ __('order.Received_Amount') }}</th>
                    <th>{{ __('order.Status') }}</th>
                    <th>{{ __('order.To_Pay') }}</th>
                    <th>{{ __('order.Created_At') }}</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $key => $order)
                <tr>
                    <td>{{ $key + 1}}</td>
                    <td>{{ $order->order_number }}</td>
                    <td>{{$order->getCustomerName()}}</td>
                    <td>{{ config('settings.currency_symbol') }} {{$order->total_amount}}</td>
                    <td>{{ config('settings.currency_symbol') }} {{$order->paid_amount}}</td>
                    <td>
                        @if($order->payment_status == 0)
                            <span class="badge badge-danger">{{ __('order.Not_Paid') }}</span>
                        @elseif($order->payment_status == 1)
                            <span class="badge badge-warning">{{ __('order.Partial') }}</span>
                        @else
                            <span class="badge badge-success">{{ __('order.Paid') }}</span>
                        @endif
                    </td>
                    <td>{{config('settings.currency_symbol')}} {{number_format($order->total_amount - $order->paid_amount, 2)}}</td>
                    <td>{{$order->created_at}}</td>
                    <td>
                        <a href="{{ route('orders.orderDetails', $order->id) }}" class="btn btn-primary"><i class="fas fa-eye"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
           
        </table>
        {{ $orders->links() }}
    </div>
</div>
@endsection

