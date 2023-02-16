<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    protected $path = 'users/';
    protected $disk = 'public';

    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role_id' => $this->role_id,
            'role' => new RoleResource($this->whenLoaded('role')),
            'image_url' => $this->image ? Storage::disk($this->disk)->url($this->path . $this->image) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
        if ($this->api_token) $data['api_token'] = $this->api_token;

        return $data;
    }
}
