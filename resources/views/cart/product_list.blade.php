<style>
    .item {
        position: relative;
        display: inline-block;
    }

    .product-badge {
        position: absolute;
        top: 5px;
        right: 5px;
        background-color: red;
        color: white;
        font-size: 12px;
        font-weight: bold;
        padding: 3px 7px;
        border-radius: 50%;
    }

    .product-name {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100px;
        display: block;
    }
</style>

@foreach($products as $product)
<div class="item" onclick="addProductToCart({{ $product->id }})" style="cursor: pointer;">
    <!-- Badge for product count -->
    <span class="product-badge">{{ $product->inventory->master_quantity }}</span>

    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
    <h5 class="product-name">{{ $product->name }}</h5>
</div>
@endforeach