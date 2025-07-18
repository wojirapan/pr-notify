@extends('layouts.app')

@section('title', 'ประวัติการใช้งานระบบ')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-history me-2"></i>ประวัติการใช้งานระบบ</h2>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <form action="{{ route('logs.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="log_type" class="form-label">ประเภทการกระทำ</label>
                    <select name="log_type" id="log_type" class="form-select">
                        <option value="">-- ทั้งหมด --</option>
                        @foreach($logTypes as $type)
                            <option value="{{ $type }}" {{ request('log_type') == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="start_date" class="form-label">ตั้งแต่วันที่</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                
                <div class="col-md-3">
                    <label for="end_date" class="form-label">ถึงวันที่</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                
                <div class="col-md-3 d-flex align-items-end">
                    <div class="d-grid gap-2 w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>ค้นหา
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover custom-table mb-0">
                    <thead>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 15%">วันที่/เวลา</th>
                            <th style="width: 15%">ผู้ใช้งาน</th>
                            <th style="width: 15%">ประเภท</th>
                            <th style="width: 50%">การกระทำ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($log->datetime)->format('d/m/Y H:i:s') }}</td>
                                <td>
                                    @if($log->user)
                                        <a href="{{ route('users.show', $log->user->user_id) }}" class="text-decoration-none">
                                            {{ $log->user->fname }} {{ $log->user->lname }}
                                        </a>
                                    @else
                                        <span class="text-muted">ไม่ระบุ</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->log_type)
                                        <span class="badge bg-info">{{ $log->log_type }}</span>
                                    @else
                                        <span class="badge bg-secondary">ไม่ระบุ</span>
                                    @endif
                                </td>
                                <td>{{ $log->log }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">ไม่พบข้อมูลประวัติการใช้งาน</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($logs->hasPages())
            <div class="card-footer">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection