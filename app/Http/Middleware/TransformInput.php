<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param $transformer
     * @return mixed
     */
    public function handle($request, Closure $next, $transformer)
    {
        $transformerInput = [];
        foreach ($request->request->all() as $key => $value)
        {
            // getting the original value from the transformer
            $transformerInput[$transformer::originalAttribute($key)] = $value;
        }

        // replace the original request with the new one
        $request->replace($transformerInput);

        $response = $next($request);

        // checking if there is an exception in the request, if yes change the name i.e. name to title
        if(isset($response->exception) && $response->exception instanceof ValidationException)
        {
            $data = $response->getData();       // get the data of the response

            $transformerErrors = [];
            foreach ($data->error as $key => $error)
            {
                // getting the original value from the transformer
                $transformerKey =  $transformer::transformedAttribute($key);

                // we also need to replace the fields in the error response messages
                $transformerErrors[$transformerKey] = str_replace($key, $transformerKey, $error);
            }
            $data->error = $transformerErrors;
            $response->setData($data);
        }
        return $response;
    }
}
