<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ResourceWrapper extends JsonResource
{
    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function with($request)
    {
        $data =  [
            'status' => isset($this['status']) ? $this['status'] : 'success',
            'code' => 200,
            'message' =>  isset($this['message']) ? $this['message'] : '',
            'toast' => isset($this['toast']) ? $this['toast'] : false,
        ];
        if(isset($this['join_fee']) && $this['join_fee'] != null){
            $data['join_fee'] = isset($this['join_fee']) ? $this['join_fee'] : '';
        }
        return $data;
    }
}
