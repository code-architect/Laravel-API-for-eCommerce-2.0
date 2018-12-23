<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponser{

    /**
     * Returns Success Response
     * @param $data mixed The data that needed to be returned
     * @param $code integer Appropriate Response code
     * @return \Illuminate\Http\JsonResponse
     */
    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    /**
     * Handle Error
     * @param $message string The error message
     * @param $code integer Appropriate Response code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    /**
     * Success Response when showing all data
     * @param Collection $collection
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function showAll(Collection $collection, $code =200)
    {
        //if the collection is empty
        if($collection->isEmpty())
        {
            return $this->successResponse(['data' => $collection], $code);
        }
        //if the collection is not empty then transform the data accordingly and then return
        $transformer = $collection->first()->transformer;
        $collection = $this->sortData($collection);
        $collection = $this->transformData($collection, $transformer);
        return $this->successResponse($collection, $code);
    }

    /**
     * Success Response when showing single data
     * @param Model $model
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function showOne(Model $model, $code = 200)
    {
        $transformer = $model->first()->transformer;
        $model = $this->transformData($model, $transformer);
        return $this->successResponse($model, $code);
    }


    /**
     * Send message
     * @param $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function showMessage($message, $code = 200)
    {
        return $this->successResponse(['data' => $message], $code);
    }


    /**
     * Sorting the data e.g. if we have localhost/users?sort_by=name
     * @param Collection $collection
     * @return Collection
     */
    protected function sortData(Collection $collection)
    {
        if(request()->has('sort_by'))
        {
            $attribute = request()->sort_by;
            $collection = $collection->sortBy->{$attribute};
        }
        return $collection;
    }

    /**
     * Transforming data using fractals
     * @param $data
     * @param $transformer
     * @return array
     */
    protected function transformData($data, $transformer)
    {
        $transformation = fractal($data, new $transformer);

        return  $transformation->toArray();
    }

}