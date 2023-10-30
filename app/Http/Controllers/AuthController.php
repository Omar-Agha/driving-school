<?php

namespace App\Http\Controllers;

use App\Consts\AuthConsts;
use App\Consts\FolderPathConsts;
use App\Enums\UserRoleEnum;
use App\Helpers\FileHelper;
use App\Http\Resources\DefaultResource;
use App\Http\Resources\LoginResource;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\StudentRequest;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function registerStudent(StudentRequest $request)
    {

        $student = null;
        /** @var User */
        $user = null;
        DB::transaction(function () use ($request, &$student, &$user) {
            $user = new User($request->only(['email', 'password', 'username']));
            $user->role = UserRoleEnum::STUDENT;
            $user->save();
            $student = new Student($request->except(['email', 'password', 'username']));
            $avatar = $request->file('avatar');
            $student->avatar = FileHelper::saveFile($avatar, FolderPathConsts::STUDENT_FOLDER);

            $student->user()->associate($user);
            $student->save();
        });


        $response = [
            'token' => $user->createToken("Customer Token")->plainTextToken,
            'email' => $user->email
        ];
        return DefaultResource::make($response);

    }


    function login()
    {
        request()->validate([
            'email' => 'email|required',
            'password' => 'required',
        ]);

        if (!Auth::attempt(request()->all()))
            throw ValidationException::withMessages(['credential' => 'invalid email/password']);

            
        /** @var User */
        $user = Auth::user();
        $token = $user->createToken(AuthConsts::LOGIN_TOKEN_NAME)->plainTextToken;
        return new LoginResource($user,$token);
    }
}