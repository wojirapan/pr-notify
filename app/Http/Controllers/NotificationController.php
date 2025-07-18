<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->get('status');
        
        Log::info('Notifications page accessed', [
            'user_id' => $user->user_id,
            'status_filter' => $status,
            'is_ajax' => $request->ajax(),
            'user_notifications_count' => $user->notifications->count()
        ]);
        
        // For AJAX requests (dropdown)
        if ($request->ajax()) {
            $limit = $request->get('limit', 5);
            $notifications = Notification::where('user_id', $user->user_id)
                ->where('is_read', 0)
                ->orderBy('create_date', 'desc')
                ->limit($limit)
                ->get();
                
            return response()->json([
                'notifications' => $notifications->map(function($notification) {
                    return [
                        'id' => $notification->noti_id,
                        'message' => $notification->message,
                        'url' => route('notifications.markAsRead', $notification->noti_id),
                        'is_read' => $notification->is_read,
                        'created_at' => Carbon::parse($notification->create_date)->diffForHumans()
                    ];
                })
            ]);
        }
        
        // For regular page requests
        if ($status === 'read') {
            $notifications = Notification::where('user_id', $user->user_id)
                ->where('is_read', 1)
                ->orderBy('create_date', 'desc')
                ->paginate(15);
        } else {
            $notifications = Notification::where('user_id', $user->user_id)
                ->where('is_read', 0)
                ->orderBy('create_date', 'desc')
                ->paginate(15);
        }
        
        Log::info('Notifications query result', [
            'total_count' => $notifications->total(),
            'current_page_count' => $notifications->count(),
            'notifications' => $notifications->toArray()
        ]);
            
        return view('notifications.index', compact('notifications'));
    }
    
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $user = Auth::user();
        
        // ตรวจสอบสิทธิ์
        if ($notification->user_id !== $user->user_id) {
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
        $user = Auth::user();
        Notification::where('user_id', $user->user_id)
            ->where('is_read', 0)
            ->update(['is_read' => 1]);
            
        return redirect()->route('notifications.index')
            ->with('success', 'ทำเครื่องหมายว่าอ่านแล้วทั้งหมด');
    }
    
    public function getUnreadCount()
    {
        $user = Auth::user();
        $count = Notification::where('user_id', $user->user_id)
            ->where('is_read', 0)
            ->count();
            
        return response()->json(['count' => $count]);
    }
}