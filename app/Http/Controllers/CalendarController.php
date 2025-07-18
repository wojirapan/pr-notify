<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            // Admin sees all activities
            $activities = Activity::where('status', 'Active')
                ->where('act_date', '>=', $start)
                ->where('act_date', '<=', $end)
                ->get();
        } else {
            // Regular users see only their activities
            $activities = Activity::where('status', 'Active')
                ->where('user_id', $user->user_id)
                ->where('act_date', '>=', $start)
                ->where('act_date', '<=', $end)
                ->get();
        }
        
        $events = [];
        
        foreach ($activities as $activity) {
            $backgroundColor = $this->getStatusColor($activity->act_status);
            
            $events[] = [
                'id' => $activity->act_id,
                'title' => $activity->act_name,
                'start' => $activity->act_date . 'T' . $activity->act_time,
                'url' => route('activities.show', $activity->act_id),
                'backgroundColor' => $backgroundColor,
                'borderColor' => $backgroundColor,
                'textColor' => '#ffffff',
                'description' => $activity->act_location,
                'status' => $activity->act_status
            ];
        }
        
        return response()->json($events);
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