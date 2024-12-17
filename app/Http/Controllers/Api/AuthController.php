<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                "email" => "The provided credentials are incorrect."
            ]);

        }

        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                "password" => "The provided credentials are incorrect."
            ]);
        }
        // createToken လုပ်လိုက်ရုံနဲ့ personal_access_tokens ထဲက token column ထဲမှာ သိမ်းသွားပြီ user_id နဲ့ ။
        // user ဘက်ကို ပြန်ထည့်ပေးရခြင်းက အဲ့ဒီ token ရှိမှ အခြား route တွေကို ထပ်ပြီးသွားလို့ရအောင် သတ်မှတ်ပေးချင်လို့ ထည့်ပေးတာ။
        // ကြိုက်တဲ့ key နဲ့ထည့်ပေးလိုက်။ backend ဘက်မှာ သိမ်းထားတဲ့ token နဲ့သွားစစ်မှာ
        $token = $user->createToken('api-token');
        $user['my_token'] = $token->plainTextToken; //

        return response()->json(['user' => $user]);

    }


}
