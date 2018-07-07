<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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
        return $this->successResponse(['data' => $collection], $code);
    }

    /**
     * Success Response when showing single data
     * @param Model $model
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function showOne(Model $model, $code = 200)
    {
        return $this->successResponse(['data' => $model], $code);
    }

}