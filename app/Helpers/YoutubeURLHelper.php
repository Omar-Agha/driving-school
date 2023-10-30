<?php

namespace App\Helpers;

class YoutubeURLHelper{
    
    public static function buildUrl(string $url):string{
        $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
        $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';

        if (preg_match($longUrlRegex, $url, $matches)) {
            $youtube_id = $matches[count($matches) - 1];
        }

        if (preg_match($shortUrlRegex, $url, $matches)) {
            $youtube_id = $matches[count($matches) - 1];
        }
        return 'https://www.youtube.com/embed/' . $youtube_id;
    }


    public static function getVideoThumbnail(string $url): string
    {
       return "https://img.youtube.com/vi/V00lqQ7n83o/0.jpg";
    }
}