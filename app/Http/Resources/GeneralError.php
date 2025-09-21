<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GeneralError extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return ([
            'status' => 'error',
            'code' => isset($this['code']) ? $this['code'] : 400,
            'message' => $this['message'],
            'data' => (isset($this['data']) && !empty($this['data'])) ? $this['data'] : (object)[],
            'toast' => isset($this['toast']) ? $this['toast'] : false,
        ]);
    }

    /*
     * Modify response for a request
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\JsonResponse  $response
     * @return \Illuminate\Http\JsonResponse $response with Error code 400
     */
    public function withResponse($request, $response)
    {
        return ($response)->setStatusCode(200);
    }


}
