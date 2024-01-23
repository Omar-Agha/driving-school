<?php

namespace App\Enums;

enum QuestionToDoAnswerEnum: string
{
    case YES = 'yes';
    case NO = 'no';
    case NO_SUER = 'not-sure';
    

    public static function toArray()
    {
        return array_column(static::cases(), 'value');
    }
}
