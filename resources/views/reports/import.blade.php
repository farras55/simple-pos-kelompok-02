@extends('layouts.app')

@section('title', 'Import Produk')

@section('content')
<div class="max-w-5xl">
    <h1 class="text-lg font-semibold mb-4">Import Produk dari CSV</h1>

    @if (session('success'))
        <div class="bg-green-50 text-green-700 p-3 rounded-md mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('import.store') }}"
          enctype="multipart/form-data"
          class="max-w-md mb-8">
        @csrf

        <label class="block mb-3">
            <span class="text-sm font-medium">File CSV</span>
            <input type="file" name="csv" accept=".csv"
                   class="mt-1 block w-full text-sm">

            @error('csv')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </label>

        <p class="text-xs text-slate-500 mb-3">
            Kolom yang dibutuhkan:
            <code>name</code>,
            <code>category_id</code>,
            <code>price</code>.
        </p>

        <button type="submit"
                class="bg-slate-900 text-white px-4 py-2 rounded-md text-sm">
            Import
        </button>
    </form>

    @if (!empty($batchId))
        <div class="border rounded-md p-4">
            <h2 class="font-semibold mb-3">Hasil Import</h2>

            @if ($importErrors->isNotEmpty())
                <p class="text-sm text-red-600 mb-3">
                    Ditemukan {{ $importErrors->count() }} catatan baris gagal.
                </p>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-collapse">
                        <thead>
                            <tr class="bg-slate-100">
                                <th class="border p-2 text-left">Nama File</th>
                                <th class="border p-2 text-left">Nomor Baris</th>
                                <th class="border p-2 text-left">Pesan Error</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($importErrors as $error)
                                <tr>
                                    <td class="border p-2">
                                        {{ $error->file_name }}
                                    </td>
                                    <td class="border p-2">
                                        {{ $error->row_number }}
                                    </td>
                                    <td class="border p-2">
                                        {{ $error->error_message }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-slate-600">
                    Belum ada baris gagal yang tercatat. Jika import masih
                    diproses di background, muat ulang halaman beberapa saat lagi.
                </p>
            @endif
        </div>
    @endif
</div>
@endsection