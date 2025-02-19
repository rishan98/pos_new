@foreach($products as $product)
<div class="item" onclick="addProductToCart({{ $product->id }})" style="cursor: pointer;">
    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
    <h5>{{ $product->name }} ({{ $product->inventory->master_quantity }})</h5>
</div>
@endforeach