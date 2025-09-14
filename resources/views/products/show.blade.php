@extends('layouts.app')

@section('content')
<div class="py-8">
	<div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
		<div class="flex flex-col md:flex-row md:space-x-6">
			<div class="flex-shrink-0 mb-4 md:mb-0">
				@if($product->product_image)
					<img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->product_name }}" class="w-64 h-64 object-cover rounded border" style="max-height:300px;">
				@else
					<div class="w-64 h-64 bg-gray-200 flex items-center justify-center text-gray-400 rounded border">No Image</div>
				@endif
			</div>
			<div class="flex-1">
				<h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $product->product_name }}</h1>
				<p class="text-red-600 font-bold text-xl mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
				<div class="mb-4">
					<h2 class="font-semibold text-gray-700 mb-1">Deskripsi Produk</h2>
					<p class="text-gray-700">{{ $product->description }}</p>
				</div>
			</div>
		</div>
        <!-- Tombol tambah ke keranjang -->
		<div class="mt-8 flex justify-center">
			<form action="{{ route('cart.store') }}" method="POST" class="flex items-center space-x-2">
				@csrf
				<input type="hidden" name="product_id" value="{{ $product->id }}">
				<input type="number" name="amount" value="1" min="1" max="{{ $product->stock }}" class="w-20 border rounded px-2 py-1 me-2" required>
				<button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Tambah ke Keranjang</button>
			</form>
		</div>
	</div>
</div>
@endsection