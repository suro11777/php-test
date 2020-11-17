<?php


namespace App\Models\Api;


use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }
}
