<?php

namespace App\Services\Trait;

use App\Models\Image;
use App\Strategies\GstImages\ResponseImage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait ImageTrait
{
    public function uploadImagesFile(string $column, Request $request, Model $model): ?Collection
    {
        if (!$request->hasFile($column)) {
            return null;
        }
        $urlimages = [];
        $images = is_array($request->file($column)) ? $request->file($column) : [$request->file($column)];

        foreach ($images as $image) {
            $tmp = $image->getPathName();
            $responseImage = $this->contextImage->upload(['image' => $tmp, 'type' => 'file']);

            $urlimages[] = [
                'url' => $responseImage->link,
                'data' => $responseImage->data,
            ];
        }
        return $model->images()->createMany($urlimages);
    }

    public function removeImage(Image $image): bool
    {
        if ($this->contextImage->remove(new ResponseImage($image->id, $image->url, $image->data))) {
            return $image->delete();
        }
        return true;
    }
}
