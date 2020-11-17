<?php


namespace App\Http\Controllers\Api;


use App\Models\Api\Product;
use App\Services\Api\ProductService;
use Illuminate\Http\Request;

class ProductController extends BaseController
{

    /**
     * ProductController constructor.
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->baseApiService = $productService;
    }

    /**
     * @param $category_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function productsByCategoryId($category_id)
    {
        $products = $this->baseApiService->productsByCategoryId($category_id);
        return response()->json(['data' => $products], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'category_ids' => 'required|array|min:1'
        ]);
        $product = $this->baseApiService->store($request->only(['title', 'category_ids']));
        if (!$product) {
            return response()->json(['data' => [], 'message' => 'dont create product'], 500);
        }
        return response()->json(['data' => $product], 201);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'category_ids' => 'required|array|min:1'
        ]);
        $product = $this->baseApiService->update($request->only(['title', 'category_ids']), $id);
        if (!$product) {
            return response()->json(['data' => [], 'message' => 'product not found this id'], 404);
        }

        return response()->json(['data' => $product], 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $deleted = $this->baseApiService->delete($id);
        if (!$deleted){
            return response()->json(['message' => 'product not delete'], 500);
        }

        return response()->json(['message' => 'product delete'], 200);
    }
}
