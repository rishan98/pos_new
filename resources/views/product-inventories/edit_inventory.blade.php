<!-- Button trigger modal -->
<button type="button" class="btn btn-primary btn-md text-white" data-bs-toggle="modal" data-bs-target="{{'#edit-inventory-modal-'.$inventory->id}}">
    <i class="fas fa-edit"></i>
</button>

<!-- Modal -->
<div class="modal fade" id="{{'edit-inventory-modal-'.$inventory->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Product Inventory - {{ $inventory->product->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('inventory.edit', $inventory->id)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input type="number" name="quantity" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="action">Action</label>
                                <select name="action" class="form-control" required>
                                    <option value="" selected disabled>-- Select --</option>
                                    <option value="1">Increase</option>
                                    <option value="0">Decrease</option>
                                </select>
                            </div>
                        </div>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>