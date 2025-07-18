@extends('layouts.app')

@section('title', 'การแจ้งเตือนทั้งหมด')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-bell me-2"></i>การแจ้งเตือนทั้งหมด</h2>
        
        <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-primary">
                <i class="fas fa-check-double me-1"></i>ทำเครื่องหมายว่าอ่านทั้งหมดแล้ว
            </button>
        </form>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link {{ request('status') !== 'read' ? 'active' : '' }}" href="{{ route('notifications.index') }}">
                        ยังไม่ได้อ่าน <span class="badge bg-danger">{{ Auth::user()->notifications->where('is_read', 0)->count() }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') === 'read' ? 'active' : '' }}" href="{{ route('notifications.index', ['status' => 'read']) }}">
                        อ่านแล้ว <span class="badge bg-secondary">{{ Auth::user()->notifications->where('is_read', 1)->count() }}</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @forelse($notifications as $notification)
                    <a href="{{ route('notifications.markAsRead', $notification->noti_id) }}" class="list-group-item list-group-item-action {{ $notification->is_read ? '' : 'fw-bold' }}">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">{{ $notification->message }}</h5>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($notification->create_date)->diffForHumans() }}</small>
                        </div>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($notification->create_date)->format('d/m/Y H:i') }}</small>
                    </a>
                @empty
                    <div class="text-center py-5">
                        <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                        <p class="text-muted">ไม่มีการแจ้งเตือน</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        @if($notifications->hasPages())
            <div class="card-footer">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection