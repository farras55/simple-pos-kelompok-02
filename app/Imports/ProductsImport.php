<?php

namespace App\Imports;

use App\Models\ImportError;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;

class ProductsImport implements
    ToModel,
    WithHeadingRow,
    WithChunkReading,
    WithValidation,
    SkipsOnFailure
{
    public function __construct(
        private string $batchId,
        private string $fileName
    ) {}

    public function model(array $row): ?Model
    {
        Product::updateOrCreate(
            [
                'name' => $row['name'],
                'category_id' => $row['category_id'],
            ],
            [
                'price' => $row['price'],
            ]
        );

        return null;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
        ];
    }

    public function onFailure(Failure ...$failures): void
    {
        foreach ($failures as $failure) {
            ImportError::updateOrCreate(
                [
                    'batch_id' => $this->batchId,
                    'row_number' => $failure->row(),
                ],
                [
                    'file_name' => $this->fileName,
                    'error_message' => implode(
                        '; ',
                        $failure->errors()
                    ),
                ]
            );
        }
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
