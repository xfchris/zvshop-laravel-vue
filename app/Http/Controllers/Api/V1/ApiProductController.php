<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use App\Models\Image;
use App\Services\Product\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use LaravelJsonApi\Core\Responses\DataResponse;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;
use LaravelJsonApi\Laravel\Http\Requests\AnonymousCollectionQuery;

class ApiProductController extends Controller
{
    use Actions\FetchMany;
    use Actions\FetchOne;
    use Actions\Store;
    use Actions\Update;
    use Actions\Destroy;
    use Actions\FetchRelated;
    use Actions\FetchRelationship;
    use Actions\UpdateRelationship;
    use Actions\AttachRelationship;
    use Actions\DetachRelationship;

    public function __construct(
        public ProductService $productService
    ) {
    }

    public function search(AnonymousCollectionQuery $request): DataResponse
    {
        $this->authorize('can', 'users_show_products');

        $category_id = $request->filter ? $request->filter['category_id'] : null;
        $response = $this->productService->searchProductsPerPage($category_id, $request->search);

        return new DataResponse($response->get());
    }

    public function removeImage(Image $image): JsonResponse
    {
        $this->authorize('can', 'users_update_products');

        $removed = $this->productService->removeImage($image);
        $response = $removed ? ['status' => 200, 'message' => trans('app.image_management.image_removed')]
                             : ['status' => 400, 'message' => trans('app.image_management.image_no_removed')];

        return response()->json($response, $response['status']);
    }

    public function export(): JsonResponse
    {
        $this->authorize('can', 'users_update_products');
        $this->productService->export();
        $response = ['status' => 200, 'message' => trans('app.reports.notify_export_products') . Auth::user()->email];
        return response()->json($response);
    }

    public function import(ImportRequest $request): JsonResponse
    {
        $this->authorize('can', 'users_update_products');
        $this->productService->import($request);
        $response = ['status' => 200, 'message' => trans('app.reports.notify_import_products') . Auth::user()->email];
        return response()->json($response);
    }
}
