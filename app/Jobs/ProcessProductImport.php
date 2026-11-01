<?php

namespace App\Jobs;

use App\Imports\ProductsImport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProcessProductImport implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public array $backoff = [10, 60];
    public int $timeout = 60;

    public function __construct(private string $csvPath) {}

    public function handle(): void
    {
        // Semua logika pembacaan file yang panjang diganti dengan HANYA SATU BARIS ini!
        Excel::import(new ProductsImport, $this->csvPath);

        // Hapus file setelah selesai diimpor
        Storage::delete($this->csvPath);
    }
}