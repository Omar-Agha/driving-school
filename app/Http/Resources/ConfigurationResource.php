<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfigurationResource extends JsonResource
{

    private $token;

    public function __construct($resource, $token = null)
    {
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

        $school = null;
        if ($this->isStudent())
            $school = $this->student->school;

        if ($this->isInstructor())
            $school = $this->instructor->school;


        return [
            'userName' => $this->username,
            'email' => $this->email,
            'avatar' => $this->getAvatar(),
            'role' => $this->role,
            'is_suspended' => $this->is_suspended,
            'school' => $school,
            'token' => $this->whenNotNull($this->token)
        ];
    }
}
