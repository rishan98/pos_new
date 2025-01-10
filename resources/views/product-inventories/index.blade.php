@extends('layouts.admin')

@section('title', __('inventory.title'))
@section('content-header', __('inventory.title'))
@section('css')
<link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
@endsection
@section('content')
<div class="row">
    <form action="{{ route('inventory.index')}}" method="get">
        <div class="col-4 offset-8">
            <div class="form-group">
                <input type="text" name="searchKey" class="form-control" placeholder="Search by product name" value="{{$searchKey}}">
            </div>
        </div>
    </form>
</div>
<div class="card product-list">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('inventory.product_name') }}</th>
                    <th>{{ __('inventory.master_quantity') }}</th>
                    <th>{{ __('inventory.reserved_quantity') }}</th>
                    <th>{{ __('inventory.removed_quantity') }}</th>
                    <th>{{ __('inventory.Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inventories as $inventory)
                <tr>
                    <td>{{$inventory->product->name}}</td>
                    <td>{{$inventory->master_quantity}}</td>
                    <td>{{$inventory->reserved_quantity}}</td>
                    <td>{{$inventory->removed_quantity}}</td>
                    <td>
                        @include('product-inventories.edit_inventory')
                        @include('product-inventories.product_history')
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $inventories->render() }}
    </div>
</div>
@endsection