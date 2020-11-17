<?php


namespace App\Http\Controllers\Api;


use App\Models\Api\Category;
use App\Models\Api\Product;
use App\Services\Api\BaseService;
use App\Services\Api\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{

    /**
     * CategoryController constructor.
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->baseApiService = $categoryService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllCategories()
    {
        $categories = $this->baseApiService->getAllCategories();
        return response()->json(['data' => $categories], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        $product = $this->baseApiService->store($request->only('name'));

        return response()->json(['data'=> $product], 201);
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
            'name' => 'required',
        ]);
        $category = $this->baseApiService->update($request->only('name'), $id);

        if (!$category){
            return response()->json(['data' => [],'message' => 'category not found this id'], 404);
        }

        return response()->json(['data'=> $category], 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $deleted = $this->baseApiService->delete($id);
        if (!$deleted){
            return response()->json(['message' => 'category not delete'], 500);
        }

        return response()->json(['message' => 'category delete'], 200);
    }
}
