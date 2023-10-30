<?php

namespace App\Http\Resources;

use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id'=> $this->id,
            'created_at'=> $this->created_at,
            'image'=> FileHelper::getFileUrl($this->image),
            'title'=> $this->title,
            'description'=> $this->description,
            'price'=> $this->price,
        ];
    }
}
