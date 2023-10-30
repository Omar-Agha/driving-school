<?php

namespace App\Http\Resources;

use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{


    private string $token ;
    public function __construct($resource,$token){
        parent::__construct($resource);
        $this->token = $token;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'token'=> $this->token,
            'role'=> $this->role,
            'email'=> $this->email,
            'username'=> $this->username,
            'avatar'=> FileHelper::getFileUrl($this->avatar),
        ];
    }
}
