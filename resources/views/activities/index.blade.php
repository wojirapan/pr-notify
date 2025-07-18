@extends('layouts.app')

@section('title', 'รายการแจ้งประชาสัมพันธ์')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-list-alt me-2"></i>รายการแจ้งประชาสัมพันธ์</h2>
        
        <a href="{{ route('activities.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>แจ้งขอประชาสัมพันธ์
        </a>
    </div>
    
    <div class="card">
        <div class="card-header bg-white">
            <form action="{{ route('activities.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">- ทุกสถานะ -</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>รอดำเนินการ</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>กำลังดำเนินการ</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>เสร็จสิ้นแล้ว</option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="ค้นหาตามชื่อกิจกรรม" value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                
                @if(Auth::user()->isAdmin())
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
                @endif
                
                <div class="col-md-2">
                    <a href="{{ route('activities.index') }}" class="btn btn-outline-secondary w-100">
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
                            <th>ชื่อกิจกรรม</th>
                            <th>ประเภท</th>
                            <th>วันที่จัด</th>
                            @if(Auth::user()->isAdmin())
                                <th>ผู้แจ้ง</th>
                            @endif
                            <th>สถานะ</th>
                            <th>การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $activity)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <a href="{{ route('activities.show', $activity->act_id) }}" class="fw-bold text-decoration-none">
                                        {{ $activity->act_name }}
                                    </a>
                                </td>
                                <td>{{ $activity->activityType->act_type }}</td>
                                <td>{{ \Carbon\Carbon::parse($activity->act_date)->format('d/m/Y') }}</td>
                                @if(Auth::user()->isAdmin())
                                    <td>{{ $activity->user->fname }} {{ $activity->user->lname }}</td>
                                @endif
                                <td>
                                    @if($activity->act_status === 'pending')
                                        <span class="badge bg-warning">รอดำเนินการ</span>
                                    @elseif($activity->act_status === 'in_progress')
                                        <span class="badge bg-primary">กำลังดำเนินการ</span>
                                    @elseif($activity->act_status === 'completed')
                                        <span class="badge bg-success">เสร็จสิ้นแล้ว</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('activities.show', $activity->act_id) }}" class="btn btn-sm btn-info text-white">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if(Auth::user()->isAdmin() || Auth::id() === $activity->user_id)
                                        <a href="{{ route('activities.edit', $activity->act_id) }}" class="btn btn-sm btn-warning text-white">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <button type="button" class="btn btn-sm btn-danger btn-delete" 
                                                onclick="if(confirm('คุณแน่ใจหรือไม่ว่าต้องการลบรายการนี้?')) { document.getElementById('delete-form-{{ $activity->act_id }}').submit(); }">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        
                                        <form id="delete-form-{{ $activity->act_id }}" action="{{ route('activities.destroy', $activity->act_id) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ Auth::user()->isAdmin() ? '7' : '6' }}" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">ไม่พบข้อมูลกิจกรรม</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($activities->hasPages())
            <div class="card-footer">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
</div>
@endsection