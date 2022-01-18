<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductExport implements FromCollection, WithMapping, WithHeadings, WithStyles, ShouldAutoSize
{
    use Exportable;

    public function collection()
    {
        return Product::withTrashed()->get();
    }

    public function map(mixed $product): array
    {
        return [
            $product->id,
            $product->name,
            $product->description,
            $product->category->name,
            $product->quantity,
            $product->price,
            $product->deleted_at,
            $product->updated_at,
            $product->created_at,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Description',
            'Category',
            'Quantity',
            'Price',
            'Status',
            'Updated',
            'Created',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
