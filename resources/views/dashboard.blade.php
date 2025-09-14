@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-50 border border-green-200">
                <div class="flex items-center px-4 py-3">
                    <!-- Icon -->
                    <svg class="h-5 w-5 text-green-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0a9 9 0 0118 0z" />
                    </svg>
                    <p class="text-green-800 font-medium">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        @endif

        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
                
                <!-- Informasi Balance -->
                <div class="mb-6 p-4 bg-gradient-to-r from-green-500 to-emerald-600 text-gray-600 rounded-lg shadow flex items-center justify-between">
                    <h2 class="text-lg font-semibold">Saldo Akun</h2>
                    <span class="text-2xl font-bold">
                        Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}
                    </span>
                </div>

                <!-- Header + Tombol -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Produk Anda</h2>
                    <a href="{{ route('products.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        + Tambah Produk
                    </a>
                </div>

                <!-- List produk milik user -->
                @forelse(Auth::user()->products as $product)
                    <div class="mb-2 p-3 bg-gray-50 border border-gray-200 rounded-lg flex justify-between items-center hover:bg-gray-100 transition">
                        <div>
                            <p class="text-gray-800 font-medium">{{ $product->product_name }}</p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('products.edit', $product) }}" 
                               class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')" class="ml-4">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-gray-600 text-center border rounded-lg bg-gray-50">
                        Belum ada produk.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
