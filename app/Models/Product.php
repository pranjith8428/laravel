<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'price',
        'product_code',
        'description',
        'delete_status'
    ];

    public function getAllProducts()
    {
        return self::all();
    }

    public function getPaginatedProducts($perPage)
    {
        return self::where('delete_status', '!=', 1)->paginate($perPage);
    }

    public function createProduct(array $data)
    {
        return $this->create($data);
    }

    public function updateProduct(array $data)
    {
        return $this->update($data);
    }

    public function getProductById($id)
    {
        return self::findOrFail($id);
    }

    public function deleteProduct()
    {
        $this->update(['delete_status' => 1]);
    }

    public static function getProductCount()
    {
        return self::where('delete_status', '!=', 1)->count();
    }
}
