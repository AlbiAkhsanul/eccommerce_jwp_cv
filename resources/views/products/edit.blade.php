@extends('layouts.app')

@section('content')
<div class="py-8">
	<div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
		@if ($errors->any())
			<div class="mb-6 rounded-lg bg-red-50 border border-red-200">
				<div class="flex items-center px-4 py-3">
					<svg class="h-5 w-5 text-red-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 5c.818 0 1.545.393 2.015 1.023a2.5 2.5 0 01.502 1.595c0 .398-.078.784-.226 1.144l-4.95 11.537a2.5 2.5 0 01-4.65-1.144c0-.398.078-.784.226-1.144l4.95-11.537A2.5 2.5 0 0112 5z" />
					</svg>
					<h3 class="text-red-800 font-semibold">Terjadi Kesalahan</h3>
				</div>
				<div class="px-4 pb-4">
					<ul class="list-disc list-inside text-sm text-red-700 space-y-1">
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			</div>
		@endif

		<div class="bg-white shadow-sm sm:rounded-lg">
			<div class="p-6">
				<h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Produk</h1>
				<form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
					@csrf
					@method('PUT')

					<!-- Nama Produk -->
					<div>
						<label for="product_name" class="block font-medium text-gray-700 mb-1">Nama Produk</label>
						<input type="text" name="product_name" id="product_name"
							   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 px-3 py-2"
							   value="{{ old('product_name', $product->product_name) }}" required>
					</div>

					<!-- Gambar Produk -->
					<div>
						<label for="product_image" class="block font-medium text-gray-700 mb-1">Gambar Produk</label>
						<input type="file" name="product_image" id="product_image"
							   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 px-3 py-2"
							   accept="image/*">
						<div class="mt-2">
							<span class="text-sm text-gray-600">Gambar saat ini:</span><br>
							<img id="preview-image" src="{{ $product->product_image ? asset('storage/' . $product->product_image) : '' }}" alt="Gambar Produk" class="rounded border mt-1" style="max-height:300px;{{ $product->product_image ? '' : 'display:none;' }}">
						</div>
                        @push('scripts')
                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const input = document.getElementById('product_image');
                            const preview = document.getElementById('preview-image');
                            if(input && preview) {
                                input.addEventListener('change', function(e) {
                                    const [file] = input.files;
                                    if (file) {
                                        const reader = new FileReader();
                                        reader.onload = function(e) {
                                            preview.src = e.target.result;
                                            preview.style.display = 'block';
                                        };
                                        reader.readAsDataURL(file);
                                    } else {
                                        preview.src = '';
                                        preview.style.display = 'none';
                                    }
                                });
                            }
                        });
                        </script>
                        @endpush
					</div>

					<!-- Deskripsi Produk -->
					<div>
						<label for="description" class="block font-medium text-gray-700 mb-1">Deskripsi Produk</label>
						<textarea name="description" id="description" rows="4"
								  class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 px-3 py-2"
								  required>{{ old('description', $product->description) }}</textarea>
					</div>

					<!-- Harga Produk -->
					<div>
						<label for="price" class="block font-medium text-gray-700 mb-1">Harga Produk</label>
						<input type="number" name="price" id="price" step="0.01" min="0"
							   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 px-3 py-2"
							   value="{{ old('price', $product->price) }}" required>
					</div>

					<!-- Stok Produk -->
					<div>
						<label for="stock" class="block font-medium text-gray-700 mb-1">Stok Produk</label>
						<input type="number" name="stock" id="stock" min="0"
							   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 px-3 py-2"
							   value="{{ old('stock', $product->stock) }}" required>
					</div>

					<!-- Tombol Simpan -->
					<div class="pt-4">
						<button type="submit"
								class="w-full sm:w-auto px-6 py-2 bg-red-600 text-white font-medium rounded-lg shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
							Update Produk
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection