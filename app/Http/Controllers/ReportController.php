<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        // Mengambil tanggal awal dan akhir dari filter
        $from = $request->date('from')?->startOfDay()
            ?? now()->startOfMonth();

        $to = $request->date('to')?->endOfDay()
            ?? now()->endOfDay();

        // Mengambil transaksi sesuai rentang tanggal
        $query = Transaction::whereBetween('created_at', [
            $from,
            $to
        ]);

        // Menghitung total penjualan dan jumlah transaksi
        $totalPenjualan = (clone $query)->sum('total');
        $jumlahTransaksi = (clone $query)->count();

        return view('reports.index', [
            'totalPenjualan' => $totalPenjualan,
            'jumlahTransaksi' => $jumlahTransaksi,
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
        ]);
    }
}
