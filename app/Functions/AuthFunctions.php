<?php

use App\Models\Instructor;
use App\Models\School;
use App\Models\Student;

function school(): School
{
    return auth()->user()->school;
}

function instructor(): Instructor
{
    return auth()->user()->instructor;
}

function student(): Student
{
    return auth()->user()->student;
}
