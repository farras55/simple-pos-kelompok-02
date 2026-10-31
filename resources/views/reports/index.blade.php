@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-semibold mb-6">
            Laporan Penjualan
        </h1>

        <form method="GET"
              action="{{ route('reports.index') }}"
              class="flex flex-wrap gap-4 items-end mb-6">

            <div>
                <label for="from" class="block text-sm font-medium mb-1">
                    Dari Tanggal
                </label>

                <input
                    type="date"
                    id="from"
                    name="from"
                    value="{{ $from }}"
                    class="border rounded-md px-3 py-2">
            </div>

            <div>
                <label for="to" class="block text-sm font-medium mb-1">
                    Sampai Tanggal
                </label>

                <input
                    type="date"
                    id="to"
                    name="to"
                    value="{{ $to }}"
                    class="border rounded-md px-3 py-2">
            </div>

            <button type="submit"
                    class="bg-slate-900 text-white px-4 py-2 rounded-md">
                Filter
            </button>

            <a href="{{ route('reports.export.csv', ['from' => $from, 'to' => $to]) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-md">
                Export CSV
            </a>
        </form>

        <div class="bg-white border rounded-md p-6 max-w-md">
            <p class="text-sm text-gray-500">
                Total Penjualan
            </p>

            <p class="text-2xl font-bold mt-2">
                Rp {{ number_format($totalPenjualan, 0, ',', '.') }}
            </p>

            <p class="text-sm text-gray-500 mt-3">
                Jumlah Transaksi: {{ $jumlahTransaksi }}
            </p>
        </div>
    </div>
@endsection
