@extends('layouts.app')

@section('title', 'ข้อมูลผู้ใช้งาน')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-user me-2"></i>ข้อมูลผู้ใช้งาน</h2>
        
        <div>
            <a href="{{ route('users.edit', $user->user_id) }}" class="btn btn-warning text-white me-2">
                <i class="fas fa-edit me-1"></i>แก้ไข
            </a>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>กลับไปรายการ
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>ข้อมูลส่วนตัว</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">ชื่อผู้ใช้:</div>
                        <div class="col-md-8">{{ $user->username }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">ชื่อ-นามสกุล:</div>
                        <div class="col-md-8">{{ $user->title->title_th }} {{ $user->fname }} {{ $user->lname }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">เบอร์โทรศัพท์:</div>
                        <div class="col-md-8">{{ $user->phone_number ?? 'ไม่ระบุ' }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">อีเมล:</div>
                        <div class="col-md-8">{{ $user->email ?? 'ไม่ระบุ' }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">กลุ่มงาน:</div>
                        <div class="col-md-8">{{ $user->department->dep_name }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">สิทธิ์การใช้งาน:</div>
                        <div class="col-md-8">
                            @if($user->role_id == 1)
                                <span class="badge bg-danger">{{ $user->role->role_name }}</span>
                            @else
                                <span class="badge bg-info">{{ $user->role->role_name }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">สถานะ:</div>
                        <div class="col-md-8">
                            @if($user->status === 'Active')
                                <span class="badge bg-success">ใช้งาน</span>
                            @else
                                <span class="badge bg-secondary">ไม่ใช้งาน</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 fw-bold">วันที่สร้าง:</div>
                        <div class="col-md-8">{{ \Carbon\Carbon::parse($user->create_date)->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            @if(Auth::user()->isAdmin())
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-user-times me-2"></i>จัดการสถานะ</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('users.destroy', $user->user_id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <p class="text-muted mb-3">คุณสามารถเปลี่ยนสถานะผู้ใช้งานเป็น "ไม่ใช้งาน" โดยการกดปุ่มด้านล่าง</p>
                            <button type="submit" class="btn btn-danger w-100 btn-delete" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการปิดการใช้งานบัญชีนี้?')">
                                <i class="fas fa-user-slash me-1"></i>ปิดการใช้งาน
                            </button>
                        </form>
                    </div>
                </div>
            @endif
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>สถิติการใช้งาน</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>การแจ้งประชาสัมพันธ์ทั้งหมด</span>
                            <span class="badge bg-primary">{{ $user->activities->count() }}</span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>รอดำเนินการ</span>
                            <span class="badge bg-warning">{{ $user->activities->where('act_status', 'pending')->count() }}</span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-warning" role="progressbar" 
                                 style="width: {{ $user->activities->count() > 0 ? ($user->activities->where('act_status', 'pending')->count() / $user->activities->count() * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>กำลังดำเนินการ</span>
                            <span class="badge bg-info">{{ $user->activities->where('act_status', 'in_progress')->count() }}</span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-info" role="progressbar" 
                                 style="width: {{ $user->activities->count() > 0 ? ($user->activities->where('act_status', 'in_progress')->count() / $user->activities->count() * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>เสร็จสิ้นแล้ว</span>
                            <span class="badge bg-success">{{ $user->activities->where('act_status', 'completed')->count() }}</span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $user->activities->count() > 0 ? ($user->activities->where('act_status', 'completed')->count() / $user->activities->count() * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection