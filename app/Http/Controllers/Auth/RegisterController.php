<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Title;
use App\Models\Department;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $titles = Title::where('status', 'Active')->get();
        $departments = Department::where('status', 'Active')->get();
        
        return view('auth.register', compact('titles', 'departments'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:user,username',
            'password' => 'required|string|min:6|confirmed',
            'title_id' => 'required|exists:title,title_id',
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:11',
            'email' => 'nullable|string|email|max:50',
            'dep_id' => 'required|exists:department,dep_id',
        ]);
        
        $user = User::create([
            'username' => $request->username,
            'password' => $request->password, // Model will hash the password
            'title_id' => $request->title_id,
            'fname' => $request->fname,
            'lname' => $request->lname,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'dep_id' => $request->dep_id,
            'role_id' => 2, // Default to regular user
            'status' => 'Active',
            'create_date' => now(),
            'update_date' => now(),
        ]);
        
        // บันทึก Log
        Log::create([
            'log' => 'สมัครสมาชิกใหม่: ' . $user->username,
            'datetime' => now(),
            'user_id' => null,
            'status' => 'Active',
            'create_date' => now(),
            'update_date' => now(),
            'log_type' => 'register',
        ]);
        
        Auth::login($user);
        
        return redirect()->route('dashboard');
    }
}