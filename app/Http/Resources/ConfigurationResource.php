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
        $phone_number = '';
        $street_name = '';
        $house_number = '';
        $city = '';
        $town = '';
        $student_id =null;

        if ($this->isStudent()) {
            $school = $this->student->school;
            $phone_number = $this->student->phone_number;
            $street_name = $this->student->street_name;
            $house_number = $this->student->house_name;
            $city = $this->student->city;
            $town = $this->student->country;
            $student_id = $this->student->id;
        }

        if ($this->isInstructor())
            $school = $this->instructor->school;





        return [
            'userName' => $this->username,
            'email' => $this->email,
            'avatar' => $this->getAvatar(),
            'role' => $this->role,
            'is_suspended' => $this->is_suspended,
            'school' => $school,
            'token' => $this->whenNotNull($this->token),
            'phone_number' => $phone_number,
            'street_name' => $street_name,
            'house_number' => $house_number,
            'city' => $city,
            'town' => $town,
            'user_id'=>$this->id,
            'student_id'=> $student_id
        ];
    }
}
