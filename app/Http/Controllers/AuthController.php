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
use App\Models\MobileResetPassword;
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



    public function forgetPassword()
    {
        request()->validate([
            'email' => 'required|email|exists:users,email',
        ]);


        MobileResetPassword::notExpired()->where('email', request('email'))->delete();


        $code = "0000"; //Str::random(4);
        MobileResetPassword::create([
            'email' => request('email'),
            'code' => $code,
            'token' => "tok",
            'expires_at' => now()->addHour(),
            'created_at' => now()
        ]);

        return $this->sendSuccess([], 'Changed Successfully');
    }

    public function resetPassword()
    {
        request()->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:4',
            'password' => 'required|string|confirmed',
        ]);

        $record =  MobileResetPassword::notExpired()->where('email', request('email'))->first();
        if ($record == null) abort(400, 'no record');
        if ($record->code != request('code')) abort(400, 'invalid code');

        $user = User::where('email', request('email'))->first();
        $user->forceFill(['password' => Hash::make(request('password'))])->save();
        $record->delete();

        return DefaultResource::make(['message' => 'ok']);
    }

    public function verifyForgetCode()
    {
        request()->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:4',

        ]);
        $flag = false;
        $record =  MobileResetPassword::notExpired()->where('email', request('email'))->first();
        if ($record != null && $record->code == request('code')) $flag = true;


        return DefaultResource::make(['success' => $flag]);
    }


    public function changePassword()
    {
        request()->validate([
            'old_password' => 'required',
            'new_password' => 'required'
        ]);
        /** @var User */
        $user = auth()->user();


        if (!Hash::check(request('old_password'), $user->password)) return $this->sendError("Invalid Old Password");




        $user->forceFill(['password' => Hash::make(request('new_password'))])->save();
        return $this->sendSuccess([], "Success");
        return DefaultResource::make(['success' => true]);
    }

    //forgetPassword
}
