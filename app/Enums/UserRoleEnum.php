<?php

namespace App\Enums;

enum UserRoleEnum: string
{
    case ADMIN = 'admin';
    case STUDENT = 'student';
    case SCHOOL = 'school';
    case TEACHER = 'techer';

    public function isAdmin(): bool
    {
        return $this == self::ADMIN;
    }

    public function isSchool(): bool
    {
        return $this == self::SCHOOL;
    }

    public function isTeacher(): bool
    {
        return $this == self::TEACHER;
    }

    public function isStudent(): bool
    {
        return $this == self::STUDENT;
    }


    public static function toArray(){
        return array_column(static::cases(),'value');
    }

   
}