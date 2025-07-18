@extends('layouts.app')

@section('title', 'แก้ไขข้อมูลการแจ้งประชาสัมพันธ์')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-edit me-2"></i>แก้ไขข้อมูลการแจ้งประชาสัมพันธ์</h2>
        
        <div>
            <a href="{{ route('activities.show', $activity->act_id) }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-eye me-1"></i>ดูรายละเอียด
            </a>
            <a href="{{ route('activities.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>กลับไปรายการ
            </a>
        </div>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('activities.update', $activity->act_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="act_type_id" class="form-label">ประเภทกิจกรรม <span class="text-danger">*</span></label>
                        <select class="form-select @error('act_type_id') is-invalid @enderror" id="act_type_id" name="act_type_id" required>
                            <option value="">-- เลือกประเภทกิจกรรม --</option>
                            @foreach($activityTypes as $type)
                                <option value="{{ $type->act_type_id }}" {{ old('act_type_id', $activity->act_type_id) == $type->act_type_id ? 'selected' : '' }}>
                                    {{ $type->act_type }}
                                </option>
                            @endforeach
                        </select>
                        @error('act_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6" id="act_type_detail_field" style="{{ old('act_type_id', $activity->act_type_id) == 99 ? 'display: block;' : 'display: none;' }}">
                        <label for="act_type_detail" class="form-label">รายละเอียดประเภทกิจกรรมอื่นๆ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('act_type_detail') is-invalid @enderror" id="act_type_detail" name="act_type_detail" value="{{ old('act_type_detail', $activity->act_type_detail) }}">
                        @error('act_type_detail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="act_name" class="form-label">ชื่อกิจกรรม <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('act_name') is-invalid @enderror" id="act_name" name="act_name" value="{{ old('act_name', $activity->act_name) }}" required>
                        @error('act_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="act_location" class="form-label">สถานที่จัดกิจกรรม <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('act_location') is-invalid @enderror" id="act_location" name="act_location" value="{{ old('act_location', $activity->act_location) }}" required>
                        @error('act_location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="act_date" class="form-label">วันที่จัดกิจกรรม <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('act_date') is-invalid @enderror" id="act_date" name="act_date" value="{{ old('act_date', $activity->act_date->format('Y-m-d')) }}" required>
                        @error('act_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="act_time" class="form-label">เวลาที่จัดกิจกรรม <span class="text-danger">*</span></label>
                        <input type="time" class="form-control @error('act_time') is-invalid @enderror" id="act_time" name="act_time" value="{{ old('act_time', $activity->act_time->format('H:i')) }}" required>
                        @error('act_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="num_participants" class="form-label">จำนวนผู้เข้าร่วม</label>
                        <input type="number" class="form-control @error('num_participants') is-invalid @enderror" id="num_participants" name="num_participants" value="{{ old('num_participants', $activity->num_participants) }}">
                        @error('num_participants')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="participating_agencies" class="form-label">หน่วยงานที่เข้าร่วม (ถ้ามี)</label>
                        <input type="text" class="form-control @error('participating_agencies') is-invalid @enderror" id="participating_agencies" name="participating_agencies" value="{{ old('participating_agencies', $activity->participating_agencies) }}">
                        @error('participating_agencies')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="act_objective" class="form-label">วัตถุประสงค์</label>
                    <textarea class="form-control @error('act_objective') is-invalid @enderror" id="act_objective" name="act_objective" rows="3">{{ old('act_objective', $activity->act_objective) }}</textarea>
                    @error('act_objective')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="act_description" class="form-label">รายละเอียดกิจกรรม</label>
                    <textarea class="form-control @error('act_description') is-invalid @enderror" id="act_description" name="act_description" rows="5">{{ old('act_description', $activity->act_description) }}</textarea>
                    @error('act_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="images" class="form-label">เพิ่มภาพประกอบ (ถ้ามี)</label>
                    <input type="file" class="form-control @error('images.*') is-invalid @enderror" id="images" name="images[]" multiple accept="image/*">
                    @error('images.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">อัปโหลดไฟล์รูปภาพได้หลายไฟล์ (jpg, png, gif)</div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">ภาพประกอบที่มีอยู่</label>
                    <div class="row">
                        @if($activity->images && $activity->images->where('status', 'Active')->count() > 0)
                            @foreach($activity->images->where('status', 'Active') as $image)
                                <div class="col-md-3 mb-3">
                                    <div class="card">
                                        <img src="{{ asset('storage/' . $image->act_img_path) }}" class="card-img-top activity-image" alt="ภาพกิจกรรม">
                                        <div class="card-body p-2 text-center">
                                            <form action="{{ route('activity.deleteImage', $image->act_img_id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger btn-delete" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบรูปภาพนี้?')">
                                                    <i class="fas fa-trash-alt"></i> ลบ
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12 text-center text-muted py-3">
                                <i class="far fa-images fa-3x mb-3"></i>
                                <p>ไม่มีรูปภาพ</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('activities.show', $activity->act_id) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>ยกเลิก
                    </a>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>บันทึกการแก้ไข
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const actTypeSelect = document.getElementById('act_type_id');
        const actTypeDetailField = document.getElementById('act_type_detail_field');
        
        function toggleActTypeDetailField() {
            if (actTypeSelect.value === '99') {
                actTypeDetailField.style.display = 'block';
                document.getElementById('act_type_detail').setAttribute('required', 'required');
            } else {
                actTypeDetailField.style.display = 'none';
                document.getElementById('act_type_detail').removeAttribute('required');
            }
        }
        
        // Initial check
        toggleActTypeDetailField();
        
        // On change
        actTypeSelect.addEventListener('change', toggleActTypeDetailField);
    });
</script>
@endsection