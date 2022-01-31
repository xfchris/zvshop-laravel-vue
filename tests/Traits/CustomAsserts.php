<?php

namespace Tests\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait CustomAsserts
{
    protected function assertFileStorageExists($path, $deleteWhenFinished = true): ?string
    {
        $file = File::glob(Storage::path('') . $path)[0];
        $this->assertFileExists($file);
        if ($deleteWhenFinished) {
            File::delete($path);
        }
        return $file;
    }
}
