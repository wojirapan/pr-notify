@extends('layouts.app')

@section('title', 'หน้าหลัก')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="fas fa-tachometer-alt me-2"></i>แดชบอร์ด</h2>
    
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="dashboard-stats d-flex align-items-center">
                <div class="stats-icon pending">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ $totalPending }}</h3>
                    <p class="mb-0 text-muted">รอดำเนินการ</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="dashboard-stats d-flex align-items-center">
                <div class="stats-icon in-progress">
                    <i class="fas fa-spinner"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ $totalInProgress }}</h3>
                    <p class="mb-0 text-muted">กำลังดำเนินการ</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="dashboard-stats d-flex align-items-center">
                <div class="stats-icon completed">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ $totalCompleted }}</h3>
                    <p class="mb-0 text-muted">เสร็จสิ้นแล้ว</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-header bg-warning text-white">
                    <i class="fas fa-clock me-2"></i>รอดำเนินการ
                </div>
                <div class="card-body">
                    @if($pendingActivities->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($pendingActivities as $activity)
                                <li class="list-group-item">
                                    <a href="{{ route('activities.show', $activity->act_id) }}" class="text-decoration-none">
                                        <div class="fw-bold text-truncate">{{ $activity->act_name }}</div>
                                        <div class="text-muted small">
                                            <i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($activity->act_date)->format('d/m/Y') }}
                                            <i class="far fa-clock ms-2 me-1"></i> {{ \Carbon\Carbon::parse($activity->act_time)->format('H:i') }} น.
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="text-center mt-3">
                            <a href="{{ route('activities.index', ['status' => 'pending']) }}" class="btn btn-sm btn-outline-warning">ดูทั้งหมด</a>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>ไม่มีรายการที่รอดำเนินการ</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-spinner me-2"></i>กำลังดำเนินการ
                </div>
                <div class="card-body">
                    @if($inProgressActivities->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($inProgressActivities as $activity)
                                <li class="list-group-item">
                                    <a href="{{ route('activities.show', $activity->act_id) }}" class="text-decoration-none">
                                        <div class="fw-bold text-truncate">{{ $activity->act_name }}</div>
                                        <div class="text-muted small">
                                            <i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($activity->act_date)->format('d/m/Y') }}
                                            <i class="far fa-clock ms-2 me-1"></i> {{ \Carbon\Carbon::parse($activity->act_time)->format('H:i') }} น.
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="text-center mt-3">
                            <a href="{{ route('activities.index', ['status' => 'in_progress']) }}" class="btn btn-sm btn-outline-primary">ดูทั้งหมด</a>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-tasks fa-3x mb-3"></i>
                            <p>ไม่มีรายการที่กำลังดำเนินการ</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-check-circle me-2"></i>เสร็จสิ้นล่าสุด
                </div>
                <div class="card-body">
                    @if($recentCompletedActivities->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($recentCompletedActivities as $activity)
                                <li class="list-group-item">
                                    <a href="{{ route('activities.show', $activity->act_id) }}" class="text-decoration-none">
                                        <div class="fw-bold text-truncate">{{ $activity->act_name }}</div>
                                        <div class="text-muted small">
                                            <i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($activity->act_date)->format('d/m/Y') }}
                                            <i class="far fa-check-circle ms-2 me-1"></i> {{ \Carbon\Carbon::parse($activity->act_status_completed)->format('d/m/Y H:i') }}
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="text-center mt-3">
                            <a href="{{ route('activities.index', ['status' => 'completed']) }}" class="btn btn-sm btn-outline-success">ดูทั้งหมด</a>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-clipboard-check fa-3x mb-3"></i>
                            <p>ไม่มีรายการที่เสร็จสิ้น</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card dashboard-card">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-calendar-alt me-2"></i>กิจกรรมที่กำลังจะมาถึง
                </div>
                <div class="card-body">
                    <div id="mini-calendar"></div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card dashboard-card">
                <div class="card-header bg-secondary text-white">
                    <i class="fas fa-bell me-2"></i>การแจ้งเตือนล่าสุด
                </div>
                <div class="card-body">
                    @if($unreadNotifications->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($unreadNotifications as $notification)
                                <li class="list-group-item">
                                    <a href="{{ route('notifications.markAsRead', $notification->noti_id) }}" class="text-decoration-none">
                                        <div class="fw-bold">{{ $notification->message }}</div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($notification->create_date)->diffForHumans() }}</small>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="text-center mt-3">
                            <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-secondary">ดูทั้งหมด</a>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-bell-slash fa-3x mb-3"></i>
                            <p>ไม่มีการแจ้งเตือนใหม่</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12 text-center">
            <a href="{{ route('activities.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus-circle me-2"></i>แจ้งขอประชาสัมพันธ์
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('mini-calendar');
        
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            locale: 'th',
            height: 'auto',
            events: '{{ route("calendar.events") }}',
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            },
            eventClick: function(info) {
                if (info.event.url) {
                    window.location.href = info.event.url;
                    return false;
                }
            },
            eventDidMount: function(info) {
                $(info.el).tooltip({
                    title: info.event.title,
                    placement: 'top',
                    trigger: 'hover',
                    container: 'body'
                });
            }
        });
        
        calendar.render();
    });
</script>
@endsection