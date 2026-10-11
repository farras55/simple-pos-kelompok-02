@extends('layouts.app')

@section('title', 'Kasir')

@section('content')
    <h1 class="text-lg font-semibold mb-4">Transaksi Kasir</h1>

    @if (session('success'))
        <div class="bg-green-50 text-green-700 p-3 rounded-md mb-4">
            {{ session('success') }}
        </div>
    @endif

    @error('items')
        <div class="bg-red-50 text-red-700 p-3 rounded-md mb-4">
            {{ $message }}
        </div>
    @enderror

    <form method="POST"
          action="{{ route('transactions.store') }}"
          x-data="{
              cart: [],

              addToCart(id, name, price) {
                  // Cek apakah item sudah ada di keranjang
                  let existingItem = this.cart.find(item => item.id === id);
                  
                  if (existingItem) {
                      // Jika ada, tambah qty
                      existingItem.qty++;
                  } else {
                      // Jika belum ada, masukkan sebagai item baru dengan qty 1
                      this.cart.push({ id: id, name: name, price: price, qty: 1 });
                  }
              },

              removeFromCart(id) {
                  // Filter keranjang untuk membuang item dengan id yang diklik
                  this.cart = this.cart.filter(item => item.id !== id);
              },

              subtotal() {
                  // Kalikan harga dengan qty untuk setiap item
                  return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
              }
          }">

        @csrf

        <div class="grid grid-cols-3 gap-4">
            @foreach ($products as $product)
                <div class="border rounded-md p-3 cursor-pointer"
                     @click="addToCart(
                         {{ $product->id }},
                         '{{ $product->name }}',
                         {{ $product->price }}
                     )">

                    <p class="font-medium">{{ $product->name }}</p>

                    <p class="text-sm text-slate-500">
                        Rp {{ number_format($product->price) }}
                    </p>
                </div>
            @endforeach
        </div>

        <div class="mt-4 border-t pt-3">
            <template x-for="(item, index) in cart" :key="item.id">
                <div class="flex flex-col mb-2">
                    
                    <input type="hidden" :name="'items[' + index + '][product_id]'" :value="item.id">
                    <input type="hidden" :name="'items[' + index + '][qty]'" :value="item.qty">

                    <div class="flex items-center gap-3">
                        <p x-text="item.name + ' x' + item.qty + ' - Rp ' + (item.price * item.qty)"></p>
                        
                        <button type="button" @click="removeFromCart(item.id)" class="text-red-600 hover:bg-red-100 bg-red-50 px-2 py-0.5 rounded text-xs font-semibold border border-red-200">
                            Hapus
                        </button>
                    </div>
                </div>
            </template>
            
            <p class="font-semibold mt-2">Subtotal: Rp <span x-text="subtotal()"></span></p>

            <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded font-medium hover:bg-blue-700 w-full" x-show="cart.length > 0">
                Simpan Transaksi
            </button>
        </div>
    </form>
@endsection