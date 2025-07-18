<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar');
    }
    
    public function getEvents(Request $request)
    {
        $start = $request->start;
        $end = $request->end;
        
        // ตรวจสอบว่ามี user ล็อกอินหรือไม่
        if (!Auth::check()) {
            Log::info('Calendar: No authenticated user');
            return response()->json([]);
        }
        
        $user = Auth::user();
        Log::info('Calendar: User authenticated', ['user_id' => $user->id, 'role_id' => $user->role_id ?? 'no_role']);
        
        // Check if user is admin
        $isAdmin = ($user->role_id === 1);
        
        Log::info('Calendar: User role check', ['isAdmin' => $isAdmin, 'role_id' => $user->role_id]);
        
        if ($isAdmin) {
            // Admin sees all activities
            $activities = Activity::where('status', 'Active')->get();
            Log::info('Calendar: Admin - loading all activities', ['count' => $activities->count()]);
        } else {
            // Regular users see only their activities - ใช้ user_id ที่ถูกต้อง
            $activities = Activity::where('status', 'Active')
                ->where('user_id', $user->user_id)
                ->get();
            Log::info('Calendar: User - loading user activities', ['user_id' => $user->user_id, 'count' => $activities->count()]);
        }
        
        $events = [];
        $dayEventCounts = [];
        
        // Count events per day
        foreach ($activities as $activity) {
            $date = (string) $activity->act_date;
            if (!isset($dayEventCounts[$date])) {
                $dayEventCounts[$date] = 0;
            }
            $dayEventCounts[$date]++;
        }
        
        foreach ($activities as $activity) {
            $backgroundColor = $this->getStatusColor($activity->act_status);
            $eventCount = $dayEventCounts[(string) $activity->act_date];
            
            // Fix date format: แยก date และ time แล้วนำมาต่อกันใหม่
            $actDate = date('Y-m-d', strtotime($activity->act_date));
            $actTime = date('H:i:s', strtotime($activity->act_time));
            $startDateTime = $actDate . 'T' . $actTime;
            
            $events[] = [
                'id' => $activity->act_id,
                'title' => $activity->act_name,
                'start' => $startDateTime,
                'backgroundColor' => $backgroundColor,
                'borderColor' => $backgroundColor,
                'textColor' => '#ffffff',
                'description' => $activity->act_location,
                'status' => $activity->act_status,
                'eventCount' => $eventCount
            ];
        }
        
        Log::info('Calendar: Events prepared', ['total_events' => count($events)]);
        return response()->json($events);
    }
    
    public function getDayCellContent(Request $request)
    {
        $start = $request->start;
        $end = $request->end;
        
        if (!Auth::check()) {
            Log::info('Calendar getDayCellContent: No authenticated user');
            return response()->json([]);
        }
        
        $user = Auth::user();
        $isAdmin = ($user->role_id === 1);
        
        Log::info('Calendar getDayCellContent: User check', ['user_id' => $user->user_id, 'isAdmin' => $isAdmin]);
        
        if ($isAdmin) {
            $activities = Activity::where('status', 'Active')
                ->where('act_date', '>=', $start)
                ->where('act_date', '<=', $end)
                ->get();
        } else {
            $activities = Activity::where('status', 'Active')
                ->where('user_id', $user->user_id)
                ->where('act_date', '>=', $start)
                ->where('act_date', '<=', $end)
                ->get();
        }
        
        $dayEventCounts = [];
        
        foreach ($activities as $activity) {
            $date = (string) $activity->act_date;
            if (!isset($dayEventCounts[$date])) {
                $dayEventCounts[$date] = 0;
            }
            $dayEventCounts[$date]++;
        }
        
        Log::info('Calendar getDayCellContent: Day counts', ['counts' => $dayEventCounts]);
        
        return response()->json($dayEventCounts);
    }
    
    private function getStatusColor($status)
    {
        switch ($status) {
            case 'pending':
                return '#ffc107'; // warning/yellow
            case 'in_progress':
                return '#0d6efd'; // primary/blue
            case 'completed':
                return '#198754'; // success/green
            default:
                return '#6c757d'; // secondary/gray
        }
    }
}