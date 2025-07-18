@extends('layouts.app')

@section('title', 'เข้าสู่ระบบ')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="auth-form">
                <div class="auth-logo mb-4">
                    <i class="fas fa-bullhorn mb-2"></i>
                    <h2>PR-Notify</h2>
                    <p class="text-muted">ระบบแจ้งเพื่อประชาสัมพันธ์</p>
                </div>
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" placeholder="ชื่อผู้ใช้" value="{{ old('username') }}" required autofocus>
                        <label for="username">ชื่อผู้ใช้</label>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="รหัสผ่าน" required>
                        <label for="password">รหัสผ่าน</label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            จดจำการเข้าสู่ระบบ
                        </label>
                    </div>
                    
                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i> เข้าสู่ระบบ
                        </button>
                    </div>
                    
                    <div class="text-center">
                        <p>ยังไม่มีบัญชีผู้ใช้? <a href="{{ route('register') }}">สมัครสมาชิก</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection