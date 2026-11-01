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

    public function __construct(
        private string $csvPath,
        private string $batchId,
        private string $fileName
    ) {}

    public function handle(): void
    {
        Excel::import(
            new ProductsImport($this->batchId, $this->fileName),
            $this->csvPath
        );

        // Hapus file hanya jika proses import berhasil.
        Storage::delete($this->csvPath);
    }
}
