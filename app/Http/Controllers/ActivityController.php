<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\ActivityImg;
use App\Models\Notification;
use App\Models\User;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ActivityController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $activities = Activity::orderBy('act_date', 'desc')->paginate(10);
        } else {
            $activities = Activity::where('user_id', $user->user_id)
                ->orderBy('act_date', 'desc')
                ->paginate(10);
        }
        
        return view('activities.index', compact('activities'));
    }
    
    public function create()
    {
        $activityTypes = ActivityType::where('status', 'Active')->get();
        return view('activities.create', compact('activityTypes'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'act_type_id' => 'required|exists:activity_type,act_type_id',
            'act_type_detail' => 'required_if:act_type_id,99',
            'act_date' => 'required|date',
            'act_time' => 'required',
            'act_location' => 'required|string|max:255',
            'act_name' => 'required|string|max:255',
            'act_objective' => 'nullable|string',
            'act_description' => 'nullable|string',
            'num_participants' => 'nullable|integer',
            'participating_agencies' => 'nullable|string|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $activity = new Activity();
        $activity->user_id = Auth::id();
        $activity->act_type_id = $request->act_type_id;
        $activity->act_type_detail = $request->act_type_detail;
        $activity->act_date = $request->act_date;
        $activity->act_time = $request->act_time;
        $activity->act_location = $request->act_location;
        $activity->act_name = $request->act_name;
        $activity->act_objective = $request->act_objective;
        $activity->act_description = $request->act_description;
        $activity->num_participants = $request->num_participants;
        $activity->participating_agencies = $request->participating_agencies;
        $activity->act_status = 'pending';
        $activity->status = 'Active';
        $activity->create_date = now();
        $activity->update_date = now();
        $activity->save();
        
        // บันทึกรูปภาพ (ถ้ามี)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = 'activity_images/' . $imageName;
                
                // Resize and save image
                $img = Image::make($image);
                $img->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                
                Storage::disk('public')->put($imagePath, (string) $img->encode());
                
                // บันทึกข้อมูลรูปภาพ
                ActivityImg::create([
                    'act_id' => $activity->act_id,
                    'act_img' => $imageName,
                    'act_img_path' => $imagePath,
                    'status' => 'Active',
                    'create_date' => now(),
                    'update_date' => now(),
                ]);
            }
        }
        
        // แจ้งเตือนไปยังผู้ดูแลระบบ (admin users)
        $admins = User::where('role_id', 1)->where('status', 'Active')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->user_id,
                'message' => 'มีการแจ้งขอประชาสัมพันธ์ใหม่: ' . $activity->act_name,
                'url' => route('activities.show', $activity->act_id),
                'is_read' => 0,
                'create_date' => now(),
            ]);
        }
        
        // บันทึก Log
        Log::create([
            'log' => 'สร้างกิจกรรมใหม่: ' . $activity->act_name,
            'datetime' => now(),
            'user_id' => Auth::id(),
            'status' => 'Active',
            'create_date' => now(),
            'update_date' => now(),
            'log_type' => 'create_activity',
        ]);
        
        return redirect()->route('activities.index')
            ->with('success', 'บันทึกข้อมูลกิจกรรมเรียบร้อยแล้ว');
    }
    
    public function show($id)
    {
        $activity = Activity::with('images', 'user', 'activityType')->findOrFail($id);
        
        // Mark related notifications as read
        Notification::where('user_id', Auth::id())
            ->where('url', route('activities.show', $id))
            ->update(['is_read' => 1]);
            
        return view('activities.show', compact('activity'));
    }
    
    public function edit($id)
    {
        $activity = Activity::findOrFail($id);
        $activityTypes = ActivityType::where('status', 'Active')->get();
        
        // ตรวจสอบสิทธิ์การแก้ไข
        if (!Auth::user()->isAdmin() && Auth::id() !== $activity->user_id) {
            return redirect()->route('activities.index')
                ->with('error', 'คุณไม่มีสิทธิ์แก้ไขข้อมูลนี้');
        }
        
        return view('activities.edit', compact('activity', 'activityTypes'));
    }
    
    public function update(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);
        
        // ตรวจสอบสิทธิ์การแก้ไข
        if (!Auth::user()->isAdmin() && Auth::id() !== $activity->user_id) {
            return redirect()->route('activities.index')
                ->with('error', 'คุณไม่มีสิทธิ์แก้ไขข้อมูลนี้');
        }
        
        $request->validate([
            'act_type_id' => 'required|exists:activity_type,act_type_id',
            'act_type_detail' => 'required_if:act_type_id,99',
            'act_date' => 'required|date',
            'act_time' => 'required',
            'act_location' => 'required|string|max:255',
            'act_name' => 'required|string|max:255',
            'act_objective' => 'nullable|string',
            'act_description' => 'nullable|string',
            'num_participants' => 'nullable|integer',
            'participating_agencies' => 'nullable|string|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $activity->act_type_id = $request->act_type_id;
        $activity->act_type_detail = $request->act_type_detail;
        $activity->act_date = $request->act_date;
        $activity->act_time = $request->act_time;
        $activity->act_location = $request->act_location;
        $activity->act_name = $request->act_name;
        $activity->act_objective = $request->act_objective;
        $activity->act_description = $request->act_description;
        $activity->num_participants = $request->num_participants;
        $activity->participating_agencies = $request->participating_agencies;
        $activity->update_date = now();
        $activity->save();
        
        // บันทึกรูปภาพเพิ่มเติม (ถ้ามี)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = 'activity_images/' . $imageName;
                
                // Resize and save image
                $img = Image::make($image);
                $img->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                
                Storage::disk('public')->put($imagePath, (string) $img->encode());
                
                // บันทึกข้อมูลรูปภาพ
                ActivityImg::create([
                    'act_id' => $activity->act_id,
                    'act_img' => $imageName,
                    'act_img_path' => $imagePath,
                    'status' => 'Active',
                    'create_date' => now(),
                    'update_date' => now(),
                ]);
            }
        }
        
        // บันทึก Log
        Log::create([
            'log' => 'แก้ไขข้อมูลกิจกรรม: ' . $activity->act_name,
            'datetime' => now(),
            'user_id' => Auth::id(),
            'status' => 'Active',
            'create_date' => now(),
            'update_date' => now(),
            'log_type' => 'update_activity',
        ]);
        
        return redirect()->route('activities.show', $activity->act_id)
            ->with('success', 'แก้ไขข้อมูลกิจกรรมเรียบร้อยแล้ว');
    }
    
    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        
        // ตรวจสอบสิทธิ์การลบ
        if (!Auth::user()->isAdmin() && Auth::id() !== $activity->user_id) {
            return redirect()->route('activities.index')
                ->with('error', 'คุณไม่มีสิทธิ์ลบข้อมูลนี้');
        }
        
        // อัปเดตสถานะเป็น Inactive แทนการลบจริง
        $activity->status = 'Inactive';
        $activity->save();
        
        // บันทึก Log
        Log::create([
            'log' => 'ลบข้อมูลกิจกรรม: ' . $activity->act_name,
            'datetime' => now(),
            'user_id' => Auth::id(),
            'status' => 'Active',
            'create_date' => now(),
            'update_date' => now(),
            'log_type' => 'delete_activity',
        ]);
        
        return redirect()->route('activities.index')
            ->with('success', 'ลบข้อมูลกิจกรรมเรียบร้อยแล้ว');
    }
    
    public function updateStatus(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);
        
        // ตรวจสอบสิทธิ์ (เฉพาะ admin)
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('activities.index')
                ->with('error', 'คุณไม่มีสิทธิ์ในการอัปเดตสถานะ');
        }
        
        $request->validate([
            'act_status' => 'required|in:pending,in_progress,completed',
        ]);
        
        $activity->act_status = $request->act_status;
        
        if ($request->act_status == 'in_progress') {
            $activity->act_status_in_progress = now();
        } elseif ($request->act_status == 'completed') {
            $activity->act_status_completed = now();
        }
        
        $activity->update_date = now();
        $activity->save();
        
        // แจ้งเตือนไปยังผู้แจ้ง
        Notification::create([
            'user_id' => $activity->user_id,
            'message' => 'สถานะกิจกรรมของคุณได้ถูกอัปเดตเป็น: ' . $this->getThaiStatus($request->act_status),
            'url' => route('activities.show', $activity->act_id),
            'is_read' => 0,
            'create_date' => now(),
        ]);
        
        // บันทึก Log
        Log::create([
            'log' => 'อัปเดตสถานะกิจกรรม: ' . $activity->act_name . ' เป็น ' . $this->getThaiStatus($request->act_status),
            'datetime' => now(),
            'user_id' => Auth::id(),
            'status' => 'Active',
            'create_date' => now(),
            'update_date' => now(),
            'log_type' => 'update_activity_status',
        ]);
        
        return redirect()->route('activities.show', $activity->act_id)
            ->with('success', 'อัปเดตสถานะกิจกรรมเรียบร้อยแล้ว');
    }
    
    private function getThaiStatus($status)
    {
        switch ($status) {
            case 'pending':
                return 'รอดำเนินการ';
            case 'in_progress':
                return 'กำลังดำเนินการ';
            case 'completed':
                return 'เสร็จสิ้น';
            default:
                return $status;
        }
    }
    
    public function deleteImage($id)
    {
        $image = ActivityImg::findOrFail($id);
        $activity = Activity::findOrFail($image->act_id);
        
        // ตรวจสอบสิทธิ์
        if (!Auth::user()->isAdmin() && Auth::id() !== $activity->user_id) {
            return redirect()->route('activities.index')
                ->with('error', 'คุณไม่มีสิทธิ์ลบรูปภาพนี้');
        }
        
        // ลบไฟล์
        if (Storage::disk('public')->exists($image->act_img_path)) {
            Storage::disk('public')->delete($image->act_img_path);
        }
        
        // อัปเดตสถานะเป็น Inactive
        $image->status = 'Inactive';
        $image->save();
        
        // บันทึก Log
        Log::create([
            'log' => 'ลบรูปภาพของกิจกรรม: ' . $activity->act_name,
            'datetime' => now(),
            'user_id' => Auth::id(),
            'status' => 'Active',
            'create_date' => now(),
            'update_date' => now(),
            'log_type' => 'delete_activity_image',
        ]);
        
        return redirect()->back()->with('success', 'ลบรูปภาพเรียบร้อยแล้ว');
    }
}