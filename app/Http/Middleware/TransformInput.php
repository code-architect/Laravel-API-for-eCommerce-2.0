<?php

namespace App\Http\Middleware;

use Closure;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
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

        return $next($request);
    }
}
