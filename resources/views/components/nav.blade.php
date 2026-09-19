<div>
    <nav class="bg-slate-900 text-white px-4 py-3 flex items-center gap-6">
        <span class="font-semibold text-lg">Simple POS</span>
    
        <a href="{{ route('pos.create') }}" 
            class="hover:text-blue-300 {{ request()->routeIs('pos.create') ? 'text-blue-400 font-bold' : '' }}">
            Kasir
        </a>
    
        <a href="{{ route('transactions.index') }}" 
            class="hover:text-blue-300 {{ request()->routeIs('transactions.index') ? 'text-blue-400 font-bold' : '' }}">
            Transaksi
        </a>
    </nav>
</div>