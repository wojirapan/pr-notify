<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // For PR department users (role_id = 1)
        if ($user->isAdmin()) {
            $pendingActivities = Activity::where('act_status', 'pending')
                ->orderBy('act_date', 'asc')
                ->take(5)
                ->get();
                
            $inProgressActivities = Activity::where('act_status', 'in_progress')
                ->orderBy('act_date', 'asc')
                ->take(5)
                ->get();
                
            $recentCompletedActivities = Activity::where('act_status', 'completed')
                ->orderBy('act_status_completed', 'desc')
                ->take(5)
                ->get();
                
            $totalPending = Activity::where('act_status', 'pending')->count();
            $totalInProgress = Activity::where('act_status', 'in_progress')->count();
            $totalCompleted = Activity::where('act_status', 'completed')->count();
            
        } else {
            // For regular users
            $pendingActivities = Activity::where('user_id', $user->user_id)
                ->where('act_status', 'pending')
                ->orderBy('act_date', 'asc')
                ->take(5)
                ->get();
                
            $inProgressActivities = Activity::where('user_id', $user->user_id)
                ->where('act_status', 'in_progress')
                ->orderBy('act_date', 'asc')
                ->take(5)
                ->get();
                
            $recentCompletedActivities = Activity::where('user_id', $user->user_id)
                ->where('act_status', 'completed')
                ->orderBy('act_status_completed', 'desc')
                ->take(5)
                ->get();
                
            $totalPending = Activity::where('user_id', $user->user_id)
                ->where('act_status', 'pending')
                ->count();
                
            $totalInProgress = Activity::where('user_id', $user->user_id)
                ->where('act_status', 'in_progress')
                ->count();
                
            $totalCompleted = Activity::where('user_id', $user->user_id)
                ->where('act_status', 'completed')
                ->count();
        }
        
        // Fetch unread notifications
        $unreadNotifications = Notification::where('user_id', $user->user_id)
            ->where('is_read', 0)
            ->orderBy('create_date', 'desc')
            ->take(5)
            ->get();
            
        return view('dashboard', compact(
            'pendingActivities',
            'inProgressActivities', 
            'recentCompletedActivities',
            'totalPending',
            'totalInProgress',
            'totalCompleted',
            'unreadNotifications'
        ));
    }
}