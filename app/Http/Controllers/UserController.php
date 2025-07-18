<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Title;
use App\Models\Department;
use App\Models\Role;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // ตรวจสอบสิทธิ์ (เฉพาะ admin)
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }
        
        $users = User::with('title', 'department', 'role')
            ->orderBy('create_date', 'desc')
            ->paginate(10);
            
        return view('users.index', compact('users'));
    }
    
    public function create()
    {
        // ตรวจสอบสิทธิ์ (เฉพาะ admin)
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }
        
        $titles = Title::where('status', 'Active')->get();
        $departments = Department::where('status', 'Active')->get();
        $roles = Role::where('status', 'Active')->get();
        
        return view('users.create', compact('titles', 'departments', 'roles'));
    }
    
    public function store(Request $request)
    {
        // ตรวจสอบสิทธิ์ (เฉพาะ admin)
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }
        
        $request->validate([
            'username' => 'required|string|max:255|unique:user,username',
            'password' => 'required|string|min:6|confirmed',
            'title_id' => 'required|exists:title,title_id',
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:11',
            'email' => 'nullable|string|email|max:50',
            'dep_id' => 'required|exists:department,dep_id',
            'role_id' => 'required|exists:role,role_id',
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
            'role_id' => $request->role_id,
            'status' => 'Active',
            'create_date' => now(),
            'update_date' => now(),
        ]);
        
        // บันทึก Log
        Log::create([
            'log' => 'สร้างผู้ใช้งานใหม่: ' . $user->username,
            'datetime' => now(),
            'user_id' => Auth::id(),
            'status' => 'Active',
            'create_date' => now(),
            'update_date' => now(),
            'log_type' => 'create_user',
        ]);
        
        return redirect()->route('users.index')
            ->with('success', 'สร้างผู้ใช้งานเรียบร้อยแล้ว');
    }
    
    public function show($id)
    {
        $user = User::with('title', 'department', 'role')->findOrFail($id);
        
        // ตรวจสอบสิทธิ์ (เฉพาะ admin หรือเจ้าของบัญชี)
        if (!Auth::user()->isAdmin() && Auth::id() !== $user->user_id) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }
        
        return view('users.show', compact('user'));
    }
    
    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        // ตรวจสอบสิทธิ์ (เฉพาะ admin หรือเจ้าของบัญชี)
        if (!Auth::user()->isAdmin() && Auth::id() !== $user->user_id) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }
        
        $titles = Title::where('status', 'Active')->get();
        $departments = Department::where('status', 'Active')->get();
        $roles = Role::where('status', 'Active')->get();
        
        return view('users.edit', compact('user', 'titles', 'departments', 'roles'));
    }
    
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // ตรวจสอบสิทธิ์ (เฉพาะ admin หรือเจ้าของบัญชี)
        if (!Auth::user()->isAdmin() && Auth::id() !== $user->user_id) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }
        
        $rules = [
            'username' => 'required|string|max:255|unique:user,username,' . $id . ',user_id',
            'title_id' => 'required|exists:title,title_id',
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:11',
            'email' => 'nullable|string|email|max:50',
            'dep_id' => 'required|exists:department,dep_id',
        ];
        
        // เฉพาะ admin ถึงจะแก้ไข role ได้
        if (Auth::user()->isAdmin()) {
            $rules['role_id'] = 'required|exists:role,role_id';
        }
        
        // ถ้ามีการกรอกรหัสผ่านใหม่
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:6|confirmed';
        }
        
        $request->validate($rules);
        
        $user->username = $request->username;
        $user->title_id = $request->title_id;
        $user->fname = $request->fname;
        $user->lname = $request->lname;
        $user->phone_number = $request->phone_number;
        $user->email = $request->email;
        $user->dep_id = $request->dep_id;
        
        // เฉพาะ admin ถึงจะแก้ไข role ได้
        if (Auth::user()->isAdmin()) {
            $user->role_id = $request->role_id;
        }
        
        // ถ้ามีการกรอกรหัสผ่านใหม่
        if ($request->filled('password')) {
            $user->password = $request->password;
        }
        
        $user->update_date = now();
        $user->save();
        
        // บันทึก Log
        Log::create([
            'log' => 'แก้ไขข้อมูลผู้ใช้งาน: ' . $user->username,
            'datetime' => now(),
            'user_id' => Auth::id(),
            'status' => 'Active',
            'create_date' => now(),
            'update_date' => now(),
            'log_type' => 'update_user',
        ]);
        
        return redirect()->route(Auth::user()->isAdmin() ? 'users.index' : 'dashboard')
            ->with('success', 'แก้ไขข้อมูลผู้ใช้งานเรียบร้อยแล้ว');
    }
    
    public function destroy($id)
    {
        // ตรวจสอบสิทธิ์ (เฉพาะ admin)
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์ลบผู้ใช้งาน');
        }
        
        $user = User::findOrFail($id);
        
        // อัปเดตสถานะเป็น Inactive แทนการลบจริง
        $user->status = 'Inactive';
        $user->save();
        
        // บันทึก Log
        Log::create([
            'log' => 'ลบผู้ใช้งาน: ' . $user->username,
            'datetime' => now(),
            'user_id' => Auth::id(),
            'status' => 'Active',
            'create_date' => now(),
            'update_date' => now(),
            'log_type' => 'delete_user',
        ]);
        
        return redirect()->route('users.index')
            ->with('success', 'ลบผู้ใช้งานเรียบร้อยแล้ว');
    }
    
    public function profile()
    {
        $user = User::with('title', 'department', 'role')->findOrFail(Auth::id());
        return view('users.profile', compact('user'));
    }
    
    public function updateProfile(Request $request)
    {
        $user = User::findOrFail(Auth::id());
        
        $rules = [
            'phone_number' => 'nullable|string|max:11',
            'email' => 'nullable|string|email|max:50',
        ];
        
        // ถ้ามีการกรอกรหัสผ่านใหม่
        if ($request->filled('current_password')) {
            $rules['current_password'] = 'required';
            $rules['password'] = 'required|string|min:6|confirmed';
        }
        
        $request->validate($rules);
        
        // ตรวจสอบรหัสผ่านปัจจุบัน
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง']);
            }
            
            $user->password = $request->password;
        }
        
        $user->phone_number = $request->phone_number;
        $user->email = $request->email;
        $user->update_date = now();
        $user->save();
        
        // บันทึก Log
        Log::create([
            'log' => 'แก้ไขข้อมูลส่วนตัว',
            'datetime' => now(),
            'user_id' => Auth::id(),
            'status' => 'Active',
            'create_date' => now(),
            'update_date' => now(),
            'log_type' => 'update_profile',
        ]);
        
        return redirect()->route('profile')
            ->with('success', 'แก้ไขข้อมูลส่วนตัวเรียบร้อยแล้ว');
    }
}