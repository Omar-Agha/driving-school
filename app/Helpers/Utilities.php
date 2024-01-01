<?php

namespace App\Helpers;



class Utilities
{

    public static function generateRandomCode()
    {
        $size = 5;
        return substr(md5(microtime()), rand(0, 26), $size);
    }


}
