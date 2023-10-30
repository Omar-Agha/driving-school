<?php

namespace App\Helpers;



class FileHelper
{

    public static function getFileUrl($value)
    {

        if ($value == '' || $value == null)
            return null;

        return asset('storage/' . $value);
    }



    public static function saveFile($file, $path)
    {
        if ($file == null)
            return null;
        $imageName = time() . '.' . $file->extension();
        return $file->move("uploads/$path", $imageName);
    }
}