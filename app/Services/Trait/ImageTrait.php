<?php

namespace App\Services\Trait;

use App\Models\Image;
use App\Strategies\GstImages\ResponseImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait ImageTrait
{
    public function uploadImagesFile(string $column, Request $request, Model $model): array
    {
        $urlimages = [];
        try {
            $images = is_array($request->file($column)) ? $request->file($column) : [$request->file($column)];

            foreach ($images as $image) {
                $responseImage = $this->contextImage->upload(['image' => $image->getPathName(), 'type' => 'file']);

                $urlimages[] = [
                    'url' => $responseImage->link,
                    'data' => $responseImage->data,
                ];
            }
        } catch (\Throwable $th) {
            Log::error('Error uploading images: ' . $th->getMessage());
        }
        return $urlimages;
    }

    public function removeImage(Image $image): bool
    {
        if ($this->contextImage->remove(new ResponseImage($image->id, $image->url, $image->data))) {
            return $image->delete();
        }
        return false;
    }
}
