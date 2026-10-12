@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="container mx-auto px-4 py-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Edit Produk
        </h1>
        <p class="mt-1 text-sm text-gray-500">
            Perbarui informasi produk berikut.
        </p>
    </div>

    {{-- Pesan validasi --}}
    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-100 p-4 text-red-700">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('products.update', $product->id) }}"
        method="POST"
        class="max-w-2xl space-y-5 rounded-xl bg-white p-6 shadow"
    >
        @csrf
        @method('PUT')

        {{-- Nama Produk --}}
        <div>
            <label for="name" class="mb-1 block font-medium text-gray-700">
                Nama Produk
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $product->name) }}"
                required
                maxlength="255"
                class="w-full rounded-lg border border-gray-300 px-3 py-2"
            >

            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Kategori --}}
        <div>
            <label for="category_id" class="mb-1 block font-medium text-gray-700">
                Kategori
            </label>

            <select
                id="category_id"
                name="category_id"
                required
                class="w-full rounded-lg border border-gray-300 px-3 py-2"
            >
                <option value="">Pilih kategori</option>

                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        @selected(old('category_id', $product->category_id) == $category->id)
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            @error('category_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Harga --}}
        <div>
            <label for="price" class="mb-1 block font-medium text-gray-700">
                Harga
            </label>

            <input
                type="number"
                id="price"
                name="price"
                value="{{ old('price', $product->price) }}"
                min="0"
                step="1"
                required
                class="w-full rounded-lg border border-gray-300 px-3 py-2"
            >

            @error('price')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Stok --}}
        <div>
            <label for="stock" class="mb-1 block font-medium text-gray-700">
                Stok
            </label>

            <input
                type="number"
                id="stock"
                name="stock"
                value="{{ old('stock', $product->stock) }}"
                min="0"
                step="1"
                required
                class="w-full rounded-lg border border-gray-300 px-3 py-2"
            >

            @error('stock')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex flex-wrap items-center gap-3 pt-2">

            <button
                type="submit"
                style="background-color: #4f46e5; color: white; padding: 10px 18px; border-radius: 6px; font-weight: 600; border: none; cursor: pointer;"
            >
                Simpan Perubahan
            </button>

            <a
                href="{{ route('products.index') }}"
                style="display: inline-block; border: 1px solid #d1d5db; padding: 10px 18px; border-radius: 6px; color: #374151; text-decoration: none;"
            >
                Batal
            </a>

        </div>
    </form>
</div>
@endsection