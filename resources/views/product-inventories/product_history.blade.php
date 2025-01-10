<!-- Button trigger modal -->
<button type="button" class="btn btn-dark btn-md text-white" data-bs-toggle="modal" data-bs-target="{{'#inventory-history-modal-'.$inventory->id}}">
    <i class="fas fa-clock"></i>
</button>

<!-- Modal -->
<div class="modal fade" id="{{'inventory-history-modal-'.$inventory->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Product Inventory - {{ $inventory->product->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Updated At</th>
                            <th>Quantity</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inventory->history as $history)
                        <tr>
                            <td>{{$history->updated_at}}</td>
                            <td>{{$history->quantity}}</td>
                            @if($history->operation == 0)
                            <td>Initial</td>
                            @elseif($history->operation == 1)
                            <td>Added</td>
                            @elseif($history->operation == 2)
                            <td>Removed</td>
                            @endif

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
           
        </div>
    </div>
</div>