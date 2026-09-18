@extends('layouts.app')

@section('title', 'Kasir')

@section('content')
<h1 class="text-lg font-semibold mb-4">Transaksi Kasir</h1>
<div x-data="{
    cart: [],
    addToCart(id, name, price) {
        let existingItem = this.cart.find(item => item.id === id);
        
        if (existingItem) {
            existingItem.qty++;
        } else {
            this.cart.push({ id: id, name: name, price: price, qty: 1 });
        }
    },
    removeFromCart(id) {
        this.cart = this.cart.filter(item => item.id !== id);
    },
    subtotal() {
        return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    }
}">
    <div class="grid grid-cols-3 gap-4">
        @foreach ($products as $product)
        <div class="border rounded-md p-3 cursor-pointer"
             @click="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})">
            <p class="font-medium">{{ $product->name }}</p>
            <p class="text-sm text-slate-500">Rp {{ number_format($product->price) }}</p>
        </div>
        @endforeach
    </div>
    
    <div class="mt-4 border-t pt-3">
        <template x-for="item in cart" :key="item.id">
            <div class="flex items-center gap-3 mb-2">
                <p x-text="item.name + ' x' + item.qty + ' - Rp ' + (item.price * item.qty)"></p>
                
                <button @click="removeFromCart(item.id)" class="text-red-600 hover:bg-red-100 bg-red-50 px-2 py-0.5 rounded text-xs font-semibold border border-red-200">
                    Hapus
                </button>
            </div>
        </template>
        
        <p class="font-semibold mt-2">Subtotal: Rp <span x-text="subtotal()"></span></p>
    </div>
</div>
@endsection