@extends('layouts.admin')

@section('title', __('supplier.Supplier_List'))
@section('content-header', __('supplier.Supplier_List'))
@section('content-actions')
<a href="{{route('suppliers.new')}}" class="btn btn-primary">{{ __('supplier.Add_Supplier') }}</a>
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
@endsection
@section('content')
<div class="row">
    <form action="{{ route('suppliers.index')}}" method="get">
        <div class="col-4 offset-8">
            <div class="form-group">
                <input type="text" name="searchKey" class="form-control" placeholder="Search by Supplier name" value="{{$searchKey}}">
            </div>
        </div>
    </form>
</div>
<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('supplier.ID') }}</th>
                    <th>{{ __('supplier.Name') }}</th>
                    <th>{{ __('supplier.Email') }}</th>
                    <th>{{ __('supplier.Phone') }}</th>
                    <th>{{ __('supplier.Address') }}</th>
                    <th>{{ __('common.Created_At') }}</th>
                    <th>{{ __('supplier.Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suppliers as $supplier)
                <tr>
                    <td>{{$supplier->id}}</td>
                    <td>{{$supplier->name}}</td>
                    <td>{{$supplier->email}}</td>
                    <td>{{$supplier->phone}}</td>
                    <td>{{$supplier->address}}</td>
                    <td>{{$supplier->created_at}}</td>
                    <td>
                        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-danger btn-delete" data-url="{{route('suppliers.destroy', $supplier->id)}}"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $suppliers->render() }}
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
            })

            swalWithBootstrapButtons.fire({
                title: "{{ __('supplier.sure') }}",
                text: "{{ __('supplier.really_delete') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "{{ __('supplier.yes_delete') }}",
                cancelButtonText: "{{ __('supplier.No') }}",
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.post($this.data('url'), {
                        _method: 'DELETE',
                        _token: '{{csrf_token()}}'
                    }, function(res) {
                        $this.closest('tr').fadeOut(500, function() {
                            $(this).remove();
                        })
                    })
                }
            })
        })
    })
</script>
@endsection
