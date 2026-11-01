<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ProductsImport implements ToModel, WithHeadingRow, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Skip jika data kosong / tidak valid
        if (empty($row['name']) || empty($row['category_id']) || empty($row['price'])) {
            return null;
        }

        // Tetap gunakan updateOrCreate agar bersifat idempotent (aman diulang)
        Product::updateOrCreate(
            ['name' => $row['name'], 'category_id' => $row['category_id']],
            ['price' => $row['price']]
        );

        // Kita return null karena penyimpanan sudah ditangani secara manual oleh updateOrCreate di atas
        return null;
    }

    public function chunkSize(): int
    {
        return 100; // Membaca 100 baris per proses agar memori server tetap aman
    }
}