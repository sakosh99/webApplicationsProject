<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                            => $this->id,
            'group_name'                    => $this->group_name,
            'group_type'                    => $this->group_type,
            'publisher_id'                  => $this->publisher_id,
            'publisher_userName'            => $this->publisher != null ? $this->publisher->user_name : null,
            'publisher_name'                => $this->publisher != null ? $this->publisher->full_name : null,
        ];
    }
}
