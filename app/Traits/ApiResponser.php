<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

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
        $collection = $this->filterData($collection, $transformer);     // filter data if input is given by the user
        $collection = $this->sortData($collection, $transformer);       // sort the data if parameters are given
        $collection = $this->paginate($collection);       // sort the data if parameters are given
        $collection = $this->transformData($collection, $transformer);  // transform the data
        $collection = $this->cacheResponse($collection);

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
     * Filtering data based on data fetched from the request which is coming from the user (input)
     * @param Collection $collection
     * @param $transformer
     * @return Collection|static
     */
    protected function filterData(Collection $collection, $transformer)
    {
        foreach(request()->query() as $key => $value)
        {
            $attribute = $transformer::originalAttribute($key);

            if(isset($attribute, $value)) {
                $collection = $collection->where($attribute, $value);
            }
        }
        return $collection;
    }


    /**
     * Sorting the data e.g. if we have localhost/users?sort_by=name
     * @param Collection $collection
     * @param $transformer
     * @return Collection
     */
    protected function sortData(Collection $collection, $transformer)
    {
        if(request()->has('sort_by'))
        {
            // based on the given transformer, we are getting the corresponding value of the database
            $attribute = $transformer::originalAttribute(request()->sort_by);
            $collection = $collection->sortBy->{$attribute};
        }
        return $collection;
    }


    /**
     * Paginating the results
     * @param Collection $collection
     * @return LengthAwarePaginator
     */
    protected function paginate(Collection $collection)
    {
        //validation for setting page limit by user
        $rules = [
            'per_page'  => 'integer|min:2|max:50'
        ];

        Validator::validate(request()->all(), $rules);

        $page = LengthAwarePaginator::resolveCurrentPage();         // this is a laravel internal method and class
        $perPage = 15;                                              // data per page

        // check if request has a per_page value, if yes overwrite the existing $per_page
        if(request()->has('per_page'))
        {
            $perPage = (int)request()->per_page;
        }

        $results = $collection->slice(($page-1) * $perPage, $perPage)->values();
        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page,
            [ 'path' => LengthAwarePaginator::resolveCurrentPath() ]
        );

        $paginated->appends(request()->all());      // appending the other request parameters like sort_by etc
        return $paginated;
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


    private function cacheResponse($data)
    {
        $url = request()->url();
        $queryParams = request()->query();      // getting the query parameters from url
        ksort($queryParams);                    // sorting the query parameters based on the key of the array

        $queryString = http_build_query($queryParams);
        $fullUrl = "{$url}?{$queryString}";         // building the url
        return Cache::remember($fullUrl, 30/60, function() use($data){
            return $data;
        });
    }

}