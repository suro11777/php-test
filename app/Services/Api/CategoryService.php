<?php


namespace App\Services\Api;


use App\Models\Api\Category;

class CategoryService extends BaseService
{

    /**
     * @return mixed
     */
    public function getAllCategories()
    {
        return Category::paginate(15, ['id','name']);
    }

    public function store($data)
    {
        $data['user_id']  = auth('api')->id();
        $category = Category::create($data);
        return $category;
    }

    /**
     * @param $data
     * @param $product
     * @return mixed
     */
    public function update($data, $id)
    {
        $category = Category::find($id, ['id', 'user_id', 'name']);
        if (!$category){
            return false;
        }

        $category->user_id = auth('api')->id();
        $category->name = $data['name'];
        $category->save();
        return $category;
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id)
    {
        return Category::destroy($id);
    }
}
