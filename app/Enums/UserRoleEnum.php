<?php

namespace App\Enums;

enum UserRoleEnum: string
{
    case ADMIN = 'admin';
    case STUDENT = 'student';
    case SCHOOL = 'school';
    case INSTRUCTOR = 'instructor';



    public function isAdmin(): bool
    {
        return $this == self::ADMIN;
    }

    public function isSchool(): bool
    {
        return $this == self::SCHOOL;
    }

    public function isInstructor(): bool
    {
        return $this == self::INSTRUCTOR;
    }

    public function isStudent(): bool
    {
        return $this == self::STUDENT;
    }


    public static function toArray()
    {
        return array_column(static::cases(), 'value');
    }


}