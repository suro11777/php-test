<?php


namespace App\Services\Api;


use App\Models\Api\Product;
use Illuminate\Support\Facades\DB;

class ProductService extends BaseService
{

    /**
     * @param $category_id
     * @return mixed
     */
    public function productsByCategoryId($category_id)
    {
        $products = Product::whereHas('categories', function ($query) use ($category_id){
            return $query->where('category_id', $category_id);
        })->paginate(15,['id', 'title']);

        return $products;
    }

    /**
     * @param $data
     * @return bool
     */
    public function store($data)
    {
        $data['user_id']  = auth('api')->id();
        DB::beginTransaction();
        $product = Product::create($data);
        if (!$product){
            DB::rollBack();
            return false;
        }
        $productCategory = $product->categories()->sync($data['category_ids']);
        if (!$productCategory){
            DB::rollBack();
            return false;
        }
        DB::commit();
        return $product;
    }

    /**
     * @param $data
     * @param $product
     * @return mixed
     */
    public function update($data, $id)
    {
        $product = Product::find($id, ['id', 'user_id', 'title']);
        if (!$product){
            return false;
        }

        $product->user_id = auth('api')->id();
        $product->title = $data['title'];
        DB::beginTransaction();
        if (!$product->save()){
            DB::rollBack();
        }

        if (!$product->categories()->sync($data['category_ids'])){
            DB::rollBack();
        }
        DB::commit();
        return $product;
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id)
    {
        return Product::destroy($id);
    }
}
