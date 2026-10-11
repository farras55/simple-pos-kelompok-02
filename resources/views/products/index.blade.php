@extends('layouts.app')

@section('title', 'Produk')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-lg font-semibold mb-4">
            Daftar Produk
        </h1>

        @if (session('success'))
            <div class="bg-green-50 text-green-700 p-3 rounded-md mb-4">
                {{ session('success') }}
            </div>
        @endif

        <a
            href="{{ route('products.create') }}"
            class="inline-block mb-4 bg-blue-600 text-white px-4 py-2 rounded-md"
        >
            Tambah Produk
        </a>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b">
                        <th class="py-3 px-4">Nama</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Harga</th>
                        <th class="py-3 px-4">Stok</th>
                        <th class="py-3 px-4">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($products as $product)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">
                                {{ $product->name }}
                            </td>

                            <td class="py-3 px-4">
                                {{ $product->category->name ?? '-' }}
                            </td>

                            <td class="py-3 px-4">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>

                            <td class="py-3 px-4">
                                {{ $product->stock }}
                            </td>

                            <td class="py-3 px-4">
                                <a
                                    href="{{ route('products.edit', $product->id) }}"
                                    style="display: inline-block; background-color: #4f46e5; color: #ffffff; padding: 8px 14px; border-radius: 6px; text-decoration: none; font-weight: 600;"
                                >
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 px-4 text-center text-gray-500">
                                Belum ada data produk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
@endsection