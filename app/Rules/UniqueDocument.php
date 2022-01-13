<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class UniqueDocument implements Rule
{
    public function __construct(
        public ?string $documentType,
        public ?string $document,
        public string $userId
    ) {
    }

    public function passes($attribute, $value): bool
    {
        $count = User::where('document_type', $this->documentType)
                     ->where('document', $this->document)
                     ->where('id', '<>', $this->userId)->count();
        return $count == 0;
    }

    public function message(): string
    {
        return 'The document number already exists';
    }
}
