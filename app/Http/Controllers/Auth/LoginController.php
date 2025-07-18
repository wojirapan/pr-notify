<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);
        
        $user = User::where('username', $credentials['username'])->first();
        
        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            
            // บันทึก Log
            Log::create([
                'log' => 'ผู้ใช้เข้าสู่ระบบ',
                'datetime' => now(),
                'user_id' => $user->user_id,
                'status' => 'Active',
                'create_date' => now(),
                'update_date' => now(),
                'log_type' => 'login',
            ]);
            
            $request->session()->regenerate();
            
            return redirect()->intended(route('dashboard'));
        }
        
        return back()->withErrors([
            'username' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        // บันทึก Log
        if (Auth::check()) {
            Log::create([
                'log' => 'ผู้ใช้ออกจากระบบ',
                'datetime' => now(),
                'user_id' => Auth::id(),
                'status' => 'Active',
                'create_date' => now(),
                'update_date' => now(),
                'log_type' => 'logout',
            ]);
        }
        
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}