@extends('layouts.app')

@section('title', 'โปรไฟล์ของฉัน')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-user-circle me-2"></i>โปรไฟล์ของฉัน</h2>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>แก้ไขข้อมูลส่วนตัว</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label">ชื่อผู้ใช้</label>
                                <input type="text" class="form-control" id="username" value="{{ $user->username }}" readonly disabled>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="fullname" class="form-label">ชื่อ-นามสกุล</label>
                                <input type="text" class="form-control" id="fullname" value="{{ $user->title->title_th }} {{ $user->fname }} {{ $user->lname }}" readonly disabled>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="department" class="form-label">กลุ่มงาน</label>
                                <input type="text" class="form-control" id="department" value="{{ $user->department->dep_name }}" readonly disabled>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="role" class="form-label">สิทธิ์การใช้งาน</label>
                                <input type="text" class="form-control" id="role" value="{{ $user->role->role_name }}" readonly disabled>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone_number" class="form-label">เบอร์โทรศัพท์</label>
                                <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label">อีเมล</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>บันทึกข้อมูล
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-key me-2"></i>เปลี่ยนรหัสผ่าน</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="current_password" class="form-label">รหัสผ่านปัจจุบัน <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">รหัสผ่านใหม่ <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">ยืนยันรหัสผ่านใหม่ <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-key me-1"></i>เปลี่ยนรหัสผ่าน
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
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
            
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-bullhorn me-2"></i>ทางลัด</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('activities.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-1"></i>แจ้งขอประชาสัมพันธ์
                        </a>
                        <a href="{{ route('activities.index') }}" class="btn btn-info text-white">
                            <i class="fas fa-list-alt me-1"></i>รายการแจ้งของฉัน
                        </a>
                        <a href="{{ route('calendar') }}" class="btn btn-success">
                            <i class="fas fa-calendar-alt me-1"></i>ปฏิทินกิจกรรม
                        </a>
                        <a href="{{ route('notifications.index') }}" class="btn btn-warning">
                            <i class="fas fa-bell me-1"></i>การแจ้งเตือนทั้งหมด
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection