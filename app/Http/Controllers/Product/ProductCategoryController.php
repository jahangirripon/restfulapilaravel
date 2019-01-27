<?php

namespace App\Http\Controllers\PRoduct;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Category;

class ProductCategoryController extends ApiController
{

    public function index(Product $product)
    {
        $categories = $product->categories;

        return $this->showAll($categories);
    }

    public function update(Request $request, Product $product, Category $category)
    {
        // attach, sync, syncWithoutDetouch
        $product->categories()->syncWithoutDetaching([$category->id]);

        return $this->showAll($product->categories);
    }

    public function destroy(Product $product, Category $category)
    {
        if(!$product->categories()->find($category->id))
        {
            return $this->errorResponse('The specified category is not a category of this product', 404);
        }

        $product->categories()->detach($category->id);

        return $this->showAll($product->categories);
    }

}
