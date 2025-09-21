<?php

namespace App\Http\Resources;

class GeneralResponse extends ResourceWrapper
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return isset($this['data']) ? $this['data'] : array();
    }
}
