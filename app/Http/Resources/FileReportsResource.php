<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FileReportsResource extends JsonResource
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
            'name_of_subject'           => $this->user->full_name,
            'userName_of_subject'       => $this->user->user_name,
            'action'                    => $this->action,
            'to_group'                  => $this->group != null ? $this->group->group_name : null,
            'old_file_name'             => $this->old_file_name,
            'new_file_name'             => $this->new_file_name,
            'created_at'                => $this->created_at,
        ];
    }
}
