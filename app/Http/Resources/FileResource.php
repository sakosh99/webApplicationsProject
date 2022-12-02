<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\FileUploader;
use Illuminate\Support\Facades\Auth;

class FileResource extends JsonResource
{
    use FileUploader;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $filePath = $this->file_path;
        if ($this->status == 'reserved' && $this->current_reserver_id != Auth::user()->id) {
            $filePath = null;
        }
        return [
            'id'                            => $this->id,
            'file_name'                     => $this->file_name,
            'file_path'                     => $filePath,
            'file_extension'                => $this->getFileExtension($this->file_path),
            'status'                        => $this->status,
            'current_reserver_id'           => $this->currentReserver != null ? $this->currentReserver->id : null,
            'current_reserver_name'         => $this->currentReserver != null ? $this->currentReserver->full_name : null,
            'current_reserver_userName'     => $this->currentReserver != null ? $this->currentReserver->user_name : null,
            'publisher_id'                  => $this->publisher->id,
            'publisher_name'                => $this->publisher->full_name,
            'publisher_userName'            => $this->publisher->user_name,
            'group_id'                      => $this->group->id,
            'group_name'                    => $this->group->group_name,
            'created_at'                    => $this->created_at,
            'updated_at'                    => $this->updated_at,
        ];
    }
}
