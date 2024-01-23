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
use Illuminate\Support\Facades\Password;

use App\Http\Requests\StudentRequest;
use App\Http\Resources\ConfigurationResource;
use App\Models\Instructor;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

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

        if (!auth()->attempt(request()->all()))
            throw ValidationException::withMessages(['credential' => 'invalid email/password']);


        /** @var User */
        $user = auth()->user();
        $token = $user->createToken(AuthConsts::LOGIN_TOKEN_NAME)->plainTextToken;

        return ConfigurationResource::make($user, $token);
    }

    public function accountConfiguration()
    {
        
        /** @var User */
        $user = auth()->user();

        return new ConfigurationResource($user);
    }



    public function forgetPassword($request){
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );
    }
}
