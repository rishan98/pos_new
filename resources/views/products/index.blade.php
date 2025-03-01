@extends('layouts.admin')

@section('title', __('product.Product_List'))
@section('content-header', __('product.Product_List'))
@section('content-actions')
<a href="{{route('products.create')}}" class="btn btn-primary">{{ __('product.Create_Product') }}</a>
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
@endsection
@section('content')
<div class="row">
    <form action="{{ route('products.index')}}" method="get">
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
                    <th>{{ __('product.ID') }}</th>
                    <th>{{ __('product.Image') }}</th>
                    <th>{{ __('product.Name') }}</th>
                    <th>Product Code</th>
                    <th>{{ __('product.Price') }}</th>
                    <th>Discounted Price</th>
                    <th>{{ __('product.Status') }}</th>
                    <th>{{ __('product.Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $index => $product)
                <tr>
                    <td>{{ $index + 1}}</td>
                    <td><img class="product-img" src="{{ asset($product->image) }}" alt=""></td>
                    <td>{{$product->name}}</td>
                    <td>{{$product->barcode}}</td>
                    <td>{{ config('settings.currency_symbol') }} {{$product->price}}</td>
                    <td>{{ config('settings.currency_symbol') }} {{ number_format($product->price - $product->discount_value, 2) }}</td>
                    <td>
                        <span class="right badge badge-{{ $product->status ? 'success' : 'danger' }}">{{$product->status ? __('common.Active') : __('common.Inactive') }}</span>
                    </td>
                    <td>
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-danger btn-delete" data-url="{{route('products.destroy', $product)}}"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $products->render() }}
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script type="module">
    $(document).ready(function() {
        $(document).on('click', '.btn-delete', function() {
            var $this = $(this);
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: '{{ __('
                product.sure ') }}',
                text: '{{ __('
                product.really_delete ') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '{{ __('
                product.yes_delete ') }}',
                cancelButtonText: '{{ __('
                product.No ') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post($this.data('url'), {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    }, function(res) {
                        $this.closest('tr').fadeOut(500, function() {
                            $(this).remove();
                        });
                    });
                }
            });
        });
    });
</script>

@endsection