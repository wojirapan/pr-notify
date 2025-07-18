@extends('layouts.app')

@section('title', 'จัดการผู้ใช้งาน')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-users me-2"></i>จัดการผู้ใช้งาน</h2>
        
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus me-2"></i>เพิ่มผู้ใช้งานใหม่
        </a>
    </div>
    
    <div class="card">
        <div class="card-header bg-white">
            <form action="{{ route('users.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <select name="role" class="form-select">
                        <option value="">- ทุกสิทธิ์ -</option>
                        @foreach(\App\Models\Role::where('status', 'Active')->get() as $role)
                            <option value="{{ $role->role_id }}" {{ request('role') == $role->role_id ? 'selected' : '' }}>
                                {{ $role->role_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <select name="department" class="form-select">
                        <option value="">- ทุกกลุ่มงาน -</option>
                        @foreach(\App\Models\Department::where('status', 'Active')->get() as $department)
                            <option value="{{ $department->dep_id }}" {{ request('department') == $department->dep_id ? 'selected' : '' }}>
                                {{ $department->dep_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="ค้นหาตามชื่อหรือชื่อผู้ใช้" value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-redo-alt"></i> รีเซ็ต
                    </a>
                </div>
            </form>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover custom-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ชื่อผู้ใช้</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>กลุ่มงาน</th>
                            <th>สิทธิ์</th>
                            <th>สถานะ</th>
                            <th>การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->username }}</td>
                                <td>
                                    <a href="{{ route('users.show', $user->user_id) }}" class="text-decoration-none">
                                        {{ $user->title->title_th }} {{ $user->fname }} {{ $user->lname }}
                                    </a>
                                </td>
                                <td>{{ $user->department->dep_name }}</td>
                                <td>
                                    @if($user->role_id == 1)
                                        <span class="badge bg-danger">{{ $user->role->role_name }}</span>
                                    @else
                                        <span class="badge bg-info">{{ $user->role->role_name }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->status === 'Active')
                                        <span class="badge bg-success">ใช้งาน</span>
                                    @else
                                        <span class="badge bg-secondary">ไม่ใช้งาน</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('users.show', $user->user_id) }}" class="btn btn-sm btn-info text-white">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <a href="{{ route('users.edit', $user->user_id) }}" class="btn btn-sm btn-warning text-white">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <button type="button" class="btn btn-sm btn-danger btn-delete" 
                                            onclick="if(confirm('คุณแน่ใจหรือไม่ว่าต้องการลบผู้ใช้งานนี้?')) { document.getElementById('delete-form-{{ $user->user_id }}').submit(); }">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    
                                    <form id="delete-form-{{ $user->user_id }}" action="{{ route('users.destroy', $user->user_id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">ไม่พบข้อมูลผู้ใช้งาน</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($users->hasPages())
            <div class="card-footer">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection