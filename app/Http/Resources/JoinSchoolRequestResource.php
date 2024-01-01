<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JoinSchoolRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);


        return array(
            'school' => $this->school->school_name,
            'id' => $this->id,
            'created_at' => $this->created_at,
            'school_avatar' => $this->school->avatar_url,
            'status' => $this->status
        );
    }
}
