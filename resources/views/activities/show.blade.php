@extends('layouts.app')

@section('title', 'รายละเอียดการแจ้งประชาสัมพันธ์')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-clipboard-list me-2"></i>รายละเอียดการแจ้งประชาสัมพันธ์</h2>
        
        <div>
            @if(Auth::user()->isAdmin() || Auth::id() === $activity->user_id)
                <a href="{{ route('activities.edit', $activity->act_id) }}" class="btn btn-warning text-white me-2">
                    <i class="fas fa-edit me-1"></i>แก้ไข
                </a>
            @endif
            
            <a href="{{ route('activities.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>กลับไปรายการ
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>ข้อมูลกิจกรรม</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">ชื่อกิจกรรม:</div>
                        <div class="col-md-8">{{ $activity->act_name }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">ประเภทกิจกรรม:</div>
                        <div class="col-md-8">
                            {{ $activity->activityType->act_type }}
                            @if($activity->act_type_id == 99)
                                <span class="text-muted">({{ $activity->act_type_detail }})</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">วันที่จัดกิจกรรม:</div>
                        <div class="col-md-8">
                            {{ \Carbon\Carbon::parse($activity->act_date)->format('d/m/Y') }}
                            <span class="ms-2 badge bg-info">{{ \Carbon\Carbon::parse($activity->act_time)->format('H:i') }} น.</span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">สถานที่จัดกิจกรรม:</div>
                        <div class="col-md-8">{{ $activity->act_location }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">จำนวนผู้เข้าร่วม:</div>
                        <div class="col-md-8">{{ $activity->num_participants ?? 'ไม่ระบุ' }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">หน่วยงานที่เข้าร่วม:</div>
                        <div class="col-md-8">{{ $activity->participating_agencies ?? 'ไม่มี' }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">วัตถุประสงค์:</div>
                        <div class="col-md-8">{!! nl2br(e($activity->act_objective)) ?? 'ไม่ระบุ' !!}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">รายละเอียดกิจกรรม:</div>
                        <div class="col-md-8">{!! nl2br(e($activity->act_description)) ?? 'ไม่ระบุ' !!}</div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 fw-bold">ภาพประกอบ:</div>
                        <div class="col-md-8">
                            <div class="row">
                                @if($activity->images && $activity->images->where('status', 'Active')->count() > 0)
                                    @foreach($activity->images->where('status', 'Active') as $image)
                                        <div class="col-md-4 col-6 mb-3">
                                            <img src="{{ asset('storage/' . $image->act_img_path) }}" class="img-fluid rounded activity-image" alt="ภาพกิจกรรม">
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-12 text-muted">ไม่มีภาพประกอบ</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-clock me-2"></i>ข้อมูลการแจ้ง</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-5 fw-bold">ผู้แจ้ง:</div>
                        <div class="col-7">{{ $activity->user->title->title_th }} {{ $activity->user->fname }} {{ $activity->user->lname }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-5 fw-bold">กลุ่มงาน:</div>
                        <div class="col-7">{{ $activity->user->department->dep_name }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-5 fw-bold">วันที่แจ้ง:</div>
                        <div class="col-7">{{ \Carbon\Carbon::parse($activity->create_date)->format('d/m/Y H:i') }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-5 fw-bold">สถานะ:</div>
                        <div class="col-7">
                            @if($activity->act_status === 'pending')
                                <span class="badge bg-warning">รอดำเนินการ</span>
                            @elseif($activity->act_status === 'in_progress')
                                <span class="badge bg-primary">กำลังดำเนินการ</span>
                                <div class="small text-muted mt-1">
                                    เริ่มดำเนินการเมื่อ: {{ \Carbon\Carbon::parse($activity->act_status_in_progress)->format('d/m/Y H:i') }}
                                </div>
                            @elseif($activity->act_status === 'completed')
                                <span class="badge bg-success">เสร็จสิ้นแล้ว</span>
                                <div class="small text-muted mt-1">
                                    เสร็จสิ้นเมื่อ: {{ \Carbon\Carbon::parse($activity->act_status_completed)->format('d/m/Y H:i') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @if(Auth::user()->isAdmin())
                        <hr>
                        <form action="{{ route('activities.updateStatus', $activity->act_id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="act_status" class="form-label fw-bold">อัปเดตสถานะ:</label>
                                <select class="form-select" id="act_status" name="act_status">
                                    <option value="pending" {{ $activity->act_status === 'pending' ? 'selected' : '' }}>รอดำเนินการ</option>
                                    <option value="in_progress" {{ $activity->act_status === 'in_progress' ? 'selected' : '' }}>กำลังดำเนินการ</option>
                                    <option value="completed" {{ $activity->act_status === 'completed' ? 'selected' : '' }}>เสร็จสิ้นแล้ว</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-1"></i>บันทึกสถานะ
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            
            @if(Auth::user()->isAdmin() || Auth::id() === $activity->user_id)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-trash-alt me-2"></i>ลบรายการ</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">หากคุณต้องการลบรายการนี้ กรุณากดปุ่มด้านล่าง</p>
                        <form action="{{ route('activities.destroy', $activity->act_id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100 btn-delete" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบรายการนี้?')">
                                <i class="fas fa-trash-alt me-1"></i>ลบรายการนี้
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection