@extends('layouts.app')

@section('content')
<div class="py-4">
    <div class="container">
        <h1 class="mb-4 fw-bold text-dark">Daftar Produk</h1>

        <div class="row g-4">
    @foreach($products as $product)
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden hover-shadow">
                
                {{-- Gambar produk --}}
                @if($product->product_image)
                    <img src="{{ asset('storage/' . $product->product_image) }}" 
                        alt="{{ $product->product_name }}" 
                        class="card-img-top img-fluid" 
                        style="height: 180px; object-fit: cover;">
                @else
                    <div class="d-flex align-items-center justify-content-center bg-light" 
                        style="height: 180px;">
                        <span class="text-muted">No Image</span>
                    </div>
                @endif

                {{-- Body card --}}
                <div class="card-body">
                    <h5 class="card-title text-truncate mb-2">
                        {{ $product->product_name }}
                    </h5>
                    <span class="badge bg-danger fs-6">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                </div>

                {{-- Footer --}}
                <div class="card-footer bg-white border-0 text-center">
                    <a href="{{ route('products.show', $product) }}" 
                    class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>

</div>

{{-- Sedikit style tambahan hover --}}
<style>
    .hover-shadow:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.12);
        transition: 0.3s;
    }
</style>
@endsection
