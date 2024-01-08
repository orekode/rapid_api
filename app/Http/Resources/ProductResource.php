<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $response = parent::toArray($request);

        unset($response["created_at"]);
        unset($response["updated_at"]);

        $response['image'] = env('APP_URL') . '/' . $response['image'];

        $response['categories'] = CategoryResource::collection($this->whenLoaded('categories'));
        $response['images']     = ProductImageResource::collection($this->whenLoaded('images'));
        $response['properties'] = ProductPropertyResource::collection($this->whenLoaded('properties'));

        return $response;
    }
}
