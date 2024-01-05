<?php

namespace App\Http\Controllers;

use App\Http\Resources\DefaultResource;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{

    public function index()
    {
        return DefaultResource::collection(Video::all());
    }

    public function get(Video $video)
    {
        return DefaultResource::make($video);
    }
}
