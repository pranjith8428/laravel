@extends('layouts.app')
  
@section('title', 'Home Products')
  
@section('contents')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="mb-0">List Products</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
    </div>
    <hr />
    @if(Session::has('success'))
        <div id="successAlert" class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <table class="table table-hover">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th class="sortable" data-sort="title">Title<i class="fas fa-sort"></i></th>
                <th class="sortable" data-sort="price">Price<i class="fas fa-sort"></i></th>
                <th class="sortable" data-sort="product_code">Product Code<i class="fas fa-sort"></i></th>
                <th class="sortable" data-sort="description">Description<i class="fas fa-sort"></i></th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @php
            $index = ($products->currentPage() - 1) * $products->perPage() + 1;
        @endphp
            @if($products->count() > 0)
                @foreach($products as $rs)
                    <tr>
                        <td class="align-middle">{{ $index++ }}</td>
                        <td class="align-middle">{{ $rs->title }}</td>
                        <td class="align-middle">{{ $rs->price }}</td>
                        <td class="align-middle">{{ $rs->product_code }}</td>
                        <td class="align-middle">{{ $rs->description }}</td>  
                        <td class="align-middle">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="{{ route('products.show', $rs->id) }}"><i class="fa fa-eye" aria-hidden="true" style="font-size: 18px; color: blue; cursor:pointer; margin-right:10px"></i></a>
                                <a href="{{ route('products.edit', $rs->id)}}" ><i class="fas fa-edit" aria-hidden="true" style="font-size: 18px; color: blue; cursor:pointer; margin-right:2px"></i></a>
                                <form action="{{ route('products.destroy', $rs->id) }}" method="POST" onsubmit="return confirm('Delete?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="border: none; background: none;">
                                    <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px; color: red; cursor:pointer;"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td id="productNotFoundMessage" class="text-center" colspan="5">Product not found</td>
                </tr>
            @endif
           
        </tbody>
    </table>
    <div class="row">
        <div class="col-md-12">
            <div class="float-right">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection
<script src="{{ asset('admin_assets/vendor/jquery/jquery.min.js') }}"></script>
<script>
   
    setTimeout(function() {
        $('#successAlert').fadeOut('slow');
    }, 3000);

    $(document).ready(function() {
        $('.sortable').click(function() {
            var $th = $(this);
            var isAsc = $th.find('i').hasClass('fa-sort-asc');

            // Reset sorting icons
            $('.sortable i').removeClass('fa-sort-asc fa-sort-desc').addClass('fa-sort');

            if (isAsc) {
                $th.find('i').removeClass('fa-sort-asc').addClass('fa-sort-desc');
            } else {
                $th.find('i').removeClass('fa-sort-desc').addClass('fa-sort-asc');
            }

            var column = $th.data('sort');
            var rows = $('tbody tr').get();

            rows.sort(function(a, b) {
                var keyA = $(a).find('td:eq(' + $th.index() + ')').text().toUpperCase();
                var keyB = $(b).find('td:eq(' + $th.index() + ')').text().toUpperCase();

                if (keyA < keyB) return -1;
                if (keyA > keyB) return 1;
                return 0;
            });

            if (!isAsc) {
                rows.reverse();
            }

            $.each(rows, function(index, row) {
                $('tbody').append(row);
            });
        });

        $('.navbar-search input').on('input', function() {
            var searchText = $(this).val().toLowerCase();
            var found = false; 
            $('tbody tr').each(function() {
                var rowText = $(this).text().toLowerCase();
                if (rowText.includes(searchText)) {
                    found = true;
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });

            if (!found) {
                $('tbody tr.product-not-found').remove(); // Remove any existing "Product not found" message
                $('tbody').append('<tr class="product-not-found"><td class="text-center" colspan="5">Product not found</td></tr>');
            } else {
                 $('tbody tr.product-not-found').remove();
            }
        });
    });
</script>