<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\PasswordResetMail;

class PasswordResetController extends Controller
{

    public function showForgotPasswordForm()
    {
        return view("auth.forgot-password");
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Tạo token đặt lại mật khẩu
        $token = Str::random(60);

        // Xóa token cũ nếu có
        DB::table('password_resets')->where('email', $request->email)->delete();

        // Lưu token mới vào bảng `password_resets`
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        // Gửi email chứa liên kết đặt lại mật khẩu
        Mail::to($request->email)->send(new PasswordResetMail($token, $request->email));

        return back()->with('status', 'We have emailed your password reset link!');
    }

    public function showResetPasswordForm($token)
    {
        $email = request()->query('email');
        return view('auth.reset-password', ['token' => $token, 'email' => $email]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required',
        ]);

        // Kiểm tra token hợp lệ
        $resetRecord = DB::table('password_resets')
                        ->where('email', $request->email)
                        ->where('token', $request->token)
                        ->first();

        if (!$resetRecord || Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            return back()->withErrors(['email' => 'Invalid or expired reset token.']);
        }

        // Đặt lại mật khẩu cho người dùng
        DB::table('users')->where('email', $request->email)->update([
            'password' => bcrypt($request->password),
        ]);

        // Xóa token sau khi sử dụng
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Your password has been reset!');
    }
}
