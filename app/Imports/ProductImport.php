<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Notifications\ImportHasFailedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\ImportFailed;

class ProductImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading, ShouldQueue, WithEvents
{
    use Importable;

    public User $user;
    public string $title;

    public function __construct(User $user, string $title)
    {
        $this->user = $user;
        $this->title = $title;
    }

    public function model(array $row): void
    {
        $data = [
            'name' => $row['name'],
            'description' => $row['description'],
            'category_id' => Category::getFromCache()->get($row['category'])->id,
            'quantity' => $row['quantity'],
            'price' => $row['price'],
            'deleted_at' => $row['disabled'],
        ];
        $product = Product::withTrashed()->findOrNew($row['id']);
        $product->fill($data)->save();
    }

    public function rules(): array
    {
        return [
            'id' => ['present'],
            'name' => ['required', 'max:120'],
            'category' => ['required', fn ($attribute, $value, $fail) => (
                !Category::getFromCache()->get($value) ? $fail('The ' . $attribute . ' "' . $value . '" no exist.') : true
            )],
            'quantity' => ['required', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:1'],
            'description' => ['required'],
            'disabled' => ['nullable', 'date_format:Y-m-d H:i:s'],
        ];
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function registerEvents(): array
    {
        return [
            ImportFailed::class => fn (ImportFailed $event) => (
                $this->user->notify(new ImportHasFailedNotification($this->user, $this->title, $event))
            ),
        ];
    }
}
