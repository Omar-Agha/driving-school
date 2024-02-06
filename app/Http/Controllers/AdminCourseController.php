<?php

namespace App\Http\Controllers;

use App\Models\AdminCourse;
use Illuminate\Http\Request;

class AdminCourseController extends Controller
{
    public function all()
    {
        return $this->sendSuccess(AdminCourse::all());
    }
}
