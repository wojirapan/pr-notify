<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('create_date', 'desc')
            ->paginate(15);
            
        return view('notifications.index', compact('notifications'));
    }
    
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        
        // ตรวจสอบสิทธิ์
        if ($notification->user_id !== Auth::id()) {
            return redirect()->route('notifications.index')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงการแจ้งเตือนนี้');
        }
        
        $notification->is_read = 1;
        $notification->save();
        
        if ($notification->url) {
            return redirect($notification->url);
        }
        
        return redirect()->route('notifications.index')
            ->with('success', 'ทำเครื่องหมายว่าอ่านแล้ว');
    }
    
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', 0)
            ->update(['is_read' => 1]);
            
        return redirect()->route('notifications.index')
            ->with('success', 'ทำเครื่องหมายว่าอ่านแล้วทั้งหมด');
    }
    
    public function getUnreadCount()
    {
        $count = Notification::where('user_id', Auth::id())
            ->where('is_read', 0)
            ->count();
            
        return response()->json(['count' => $count]);
    }
}