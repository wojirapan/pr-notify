<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function index(Request $request)
    {
        // ตรวจสอบสิทธิ์ (เฉพาะ admin)
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }
        
        $query = Log::with('user')->orderBy('datetime', 'desc');
        
        // กรองตามประเภท
        if ($request->has('log_type') && $request->log_type) {
            $query->where('log_type', $request->log_type);
        }
        
        // กรองตามช่วงเวลา
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('datetime', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('datetime', '<=', $request->end_date);
        }
        
        $logs = $query->paginate(20);
        
        // รายการประเภทของ log
        $logTypes = Log::select('log_type')
            ->distinct()
            ->whereNotNull('log_type')
            ->orderBy('log_type')
            ->pluck('log_type');
            
        return view('logs.index', compact('logs', 'logTypes'));
    }
}