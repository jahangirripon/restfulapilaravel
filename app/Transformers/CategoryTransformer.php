<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Category;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'identifier' =>  (int) $category->id,
            'title' =>  (string) $category->name,
            'details' =>  (string) $category->description,
            'creationDate' => $category->created_at,
            'lastChange' => $category->updated_at,
            'deletedData' => isset($category->deleted_at) ? (string) $category->deleted_at : null,
            
            'links' => [
                'rel' => 'self',
                'href' => route('categories.show', $category->id),
            ],
            [
                'rel' => 'category.buyers',
                'href' => route('categories.buyers.index', $category->id),
            ],
            [
                'rel' => 'category.sellers',
                'href' => route('categories.sellers.index', $category->id),
            ],
            [
                'rel' => 'category.transactions',
                'href' => route('categories.transactions.index', $category->id),
            ],
        ];
    }
}
