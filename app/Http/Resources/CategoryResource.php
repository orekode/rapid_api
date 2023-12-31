<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id'       => $this->id,
            'category' => $this->name,
            'image'    => env('APP_URL') . '/' . $this->image,
            'subs'     => CategoryResource::collection($this->whenLoaded('subs')),
            'parent'   => $this->parent_id,
            
        ];
    }
}
