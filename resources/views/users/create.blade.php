@extends('layouts.app')

@section('title', 'เพิ่มผู้ใช้งานใหม่')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-user-plus me-2"></i>เพิ่มผู้ใช้งานใหม่</h2>
        
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>กลับไปรายการผู้ใช้
        </a>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="username" class="form-label">ชื่อผู้ใช้ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="email" class="form-label">อีเมล</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="password" class="form-label">รหัสผ่าน <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">ยืนยันรหัสผ่าน <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="title_id" class="form-label">คำนำหน้า <span class="text-danger">*</span></label>
                        <select class="form-select @error('title_id') is-invalid @enderror" id="title_id" name="title_id" required>
                            <option value="">-- เลือกคำนำหน้า --</option>
                            @foreach($titles as $title)
                                <option value="{{ $title->title_id }}" {{ old('title_id') == $title->title_id ? 'selected' : '' }}>
                                    {{ $title->title_th }}
                                </option>
                            @endforeach
                        </select>
                        @error('title_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="fname" class="form-label">ชื่อ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('fname') is-invalid @enderror" id="fname" name="fname" value="{{ old('fname') }}" required>
                        @error('fname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="lname" class="form-label">นามสกุล <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('lname') is-invalid @enderror" id="lname" name="lname" value="{{ old('lname') }}" required>
                        @error('lname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="phone_number" class="form-label">เบอร์โทรศัพท์</label>
                        <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="dep_id" class="form-label">กลุ่มงาน <span class="text-danger">*</span></label>
                        <select class="form-select @error('dep_id') is-invalid @enderror" id="dep_id" name="dep_id" required>
                            <option value="">-- เลือกกลุ่มงาน --</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->dep_id }}" {{ old('dep_id') == $department->dep_id ? 'selected' : '' }}>
                                    {{ $department->dep_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('dep_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="role_id" class="form-label">สิทธิ์การใช้งาน <span class="text-danger">*</span></label>
                        <select class="form-select @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                            <option value="">-- เลือกสิทธิ์การใช้งาน --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->role_id }}" {{ old('role_id') == $role->role_id ? 'selected' : '' }}>
                                    {{ $role->role_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <button type="reset" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-redo-alt me-1"></i>รีเซ็ต
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection