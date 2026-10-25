<div>
    <nav class="bg-slate-900 text-white px-4 py-3 flex items-center gap-6 flex-wrap">
        <span class="font-semibold text-lg">Simple POS</span>

        <a href="{{ route('pos.create') }}"
            class="hover:text-blue-300 {{ request()->routeIs('pos.create') ? 'text-blue-400 font-bold' : '' }}">
            Kasir
        </a>

        <a href="{{ route('transactions.index') }}"
            class="hover:text-blue-300 {{ request()->routeIs('transactions.*') ? 'text-blue-400 font-bold' : '' }}">
            Transaksi
        </a>

        @if (auth()->user()?->isAdmin())
            <a href="{{ route('products.index') }}"
                class="hover:text-blue-300 {{ request()->routeIs('products.*') ? 'text-blue-400 font-bold' : '' }}">
                Produk
            </a>

            <a href="{{ route('categories.index') }}"
                class="hover:text-blue-300 {{ request()->routeIs('categories.*') ? 'text-blue-400 font-bold' : '' }}">
                Kategori
            </a>
        @endif

        <div class="ml-auto flex items-center gap-4">
            <span class="text-sm">
                {{ auth()->user()?->name }}
                ({{ auth()->user()?->role }})
            </span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="hover:text-red-300">
                    Keluar
                </button>
            </form>
        </div>
    </nav>
</div>