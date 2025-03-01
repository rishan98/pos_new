<!-- Button trigger modal -->
<button type="button" class="btn btn-success btn-md text-white" data-bs-toggle="modal" data-bs-target="{{'#view-product-modal-'.$inventory->id}}">
    <i class="fas fa-eye"></i>
</button>

<!-- Modal -->
<div class="modal fade" id="{{'view-product-modal-'.$inventory->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Product Inventory - {{ $inventory->product->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Product Code</th>
                            <th>Price</th>
                            <th>Discounted Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                           <td>{{$inventory->product->name}}</td>
                           <td>{{$inventory->product->barcode}}</td>
                           <td>{{ config('settings.currency_symbol') }} {{$inventory->product->price}}</td>
                           <td>{{ config('settings.currency_symbol') }} {{ number_format($inventory->product->price - $inventory->product->discount_value, 2) }}</td>
                           <td>
                               <span class="right badge badge-{{ $inventory->product->status ? 'success' : 'danger' }}">{{$inventory->product->status ? __('common.Active') : __('common.Inactive') }}</span>
                           </td>
                        </tr>
                    </tbody>
                </table>
            </div>
           
        </div>
    </div>
</div>