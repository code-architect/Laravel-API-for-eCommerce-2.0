<?php

namespace App\Transformers;

use App\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Category $category
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'id'            =>  (int)$category->id,
            'title'         =>  (string)$category->name,
            'details'       =>  (string)$category->description,
            'creationDate'  =>  (string)$category->created_at,
            'lastChange'    =>  (string)$category->updated_at,
            'deleteDate'    =>  isset($category->deleted_at) ? (string)$category->deleted_at : null,
        ];
    }


    /**
     * Preventing sort function from accessing the original names from database
     * @param $index
     * @return mixed|null
     */
    public static function originalAttribute($index)
    {
        $attribute = [
            'id'            =>  'id',
            'title'         =>  'name',
            'details'       =>  'description',
            'creationDate'  =>  'created_at',
            'lastChange'    =>  'updated_at',
            'deleteDate'    =>  'deleted_at',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
