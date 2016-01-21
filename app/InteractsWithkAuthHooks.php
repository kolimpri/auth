<?php

namespace Kolimpri\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

trait InteractsWithkAuthHooks
{
    /**
     * Get the response from a custom validator callback.
     *
     * @param  callable|string  $callback
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function callCustomValidator($callback, Request $request)
    {
        if (! $callback instanceof ValidatorContract) {
            $validator = $this->getCustomValidator($callback, $request);
        } else {
            $validator = $callback;
        }

        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }
    }

    /**
     * Get the custom validator based on the given callback.
     *
     * @param  callable|string  $callback
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function getCustomValidator($callback, Request $request)
    {
        if (is_string($callback)) {
            list($class, $method) = explode('@', $callback);

            $callback = [app($class), $method];
        }

        $validator = call_user_func($callback, $request);

        return $validator instanceof ValidatorContract
            ? $validator
            : Validator::make($request->all(), $validator);
    }

    /**
     * Call a custom Auth updater callback.
     *
     * @param  callable|string  $callback
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $arguments
     * @return mixed
     */
    public function callCustomUpdater($callback, Request $request, array $arguments = [])
    {
        if (is_string($callback)) {
            list($class, $method) = explode('@', $callback);

            $callback = [app($class), $method];
        }

        return call_user_func_array($callback, array_merge([$request], $arguments));
    }
}
