<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Util\BaseMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use mysql_xdevapi\Exception;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    //

    public function login(Request $request)
    {


        $validated = Validator::make($request->all(), [
            'password' => 'required',
            'email' => 'required',
        ]);

        if ($validated->fails()) {
            return BaseMessage::someThingWrong();
        }

        if (!Auth::attempt($request->only("email", "password"))) {
            return (new BaseMessage("Invalid Email Or Password", "خطا في اسم المستخدم او كلمة السر", 401))->toJson();
        }

        $user = Auth::user();
        $token = $user->createToken('Token')->plainTextToken;
        return (new BaseMessage("Successful Login", "تم تسجيل الدخول بنجاح", 200, ["Token" => $token]))->toJson();

    }

    public function signUp(Request $request)
    {

        $validated = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required',
            'mail' => 'required',
        ]);

        if ($validated->fails()) {
            return BaseMessage::someThingWrong();
        }
        $user = null;
        if(User::where('email',$request->input('mail'))->first()){
            return (new BaseMessage("Email Is Repeated", "بريد مكرر", 401))->toJson();
        }



        try {
            $user = User::create([
                'name' => $request->input('name'),
                'password' => Hash::make($request->input('password')),
                'email' => $request->input('mail'),

            ]);
        } catch (\Throwable $e) {
            return BaseMessage::someThingWrong();
        }


        if ($user)
            return (new BaseMessage("Successful Registration", "تم التسجيل بنجاح", 201))->toJson();
        else
            return BaseMessage::someThingWrong();
    }

    public function user()
    {
        return (new BaseMessage("Successful Loaded User Data", "تم تحميل البيانات بنجاح", 200,Auth::user()))->toJson();

    }
}
