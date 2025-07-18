@extends('layouts.app')

@section('title', 'แจ้งขอประชาสัมพันธ์')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-plus-circle me-2"></i>แจ้งขอประชาสัมพันธ์</h2>
        
        <a href="{{ route('activities.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>กลับไปรายการ
        </a>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('activities.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="act_type_id" class="form-label">ท่านต้องการให้กลุ่มสื่อสารฯดำเนินกิจกรรมใด <span class="text-danger">*</span></label>
                        <select class="form-select @error('act_type_id') is-invalid @enderror" id="act_type_id" name="act_type_id" required>
                            <option value="">-- เลือกประเภทกิจกรรม --</option>
                            <option value="news_pr" {{ old('act_type_id') == 'news_pr' ? 'selected' : '' }}>ทำข่าวประชาสัมพันธ์</option>
                            <option value="photo_news" {{ old('act_type_id') == 'photo_news' ? 'selected' : '' }}>ถ่ายภาพพร้อมทำข่าวประชาสัมพันธ์</option>
                            <option value="kiosk_pr" {{ old('act_type_id') == 'kiosk_pr' ? 'selected' : '' }}>ประชาสัมพันธ์ตู้ Kiosk</option>
                            <option value="website_pr" {{ old('act_type_id') == 'website_pr' ? 'selected' : '' }}>ประชาสัมพันธ์บนเว็บไซต์ (Website) หน่วยงาน</option>
                            <option value="other" {{ old('act_type_id') == 'other' ? 'selected' : '' }}>อื่นๆ</option>
                        </select>
                        @error('act_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Other activity detail field -->
                <div class="row mb-3" id="other_detail_field" style="display: none;">
                    <div class="col-md-12">
                        <label for="other_detail" class="form-label">รายละเอียดกิจกรรมอื่นๆ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('other_detail') is-invalid @enderror" id="other_detail" name="other_detail" value="{{ old('other_detail') }}">
                        @error('other_detail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Dynamic Content Section - Initially Hidden -->
                <div id="dynamic_content" style="display: none;">
                
                <!-- Activity Name (for news_pr and photo_news) -->
                <div class="row mb-3" id="activity_name_section" style="display: none;">
                    <div class="col-md-12">
                        <label for="act_name" class="form-label">ชื่อกิจกรรม <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('act_name') is-invalid @enderror" id="act_name" name="act_name" value="{{ old('act_name') }}">
                        @error('act_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Date and Time Section (for news_pr and photo_news) -->
                <div class="row mb-3" id="datetime_section" style="display: none;">
                    <div class="col-md-6">
                        <label for="act_date" class="form-label" id="date_label">วันที่จัดกิจกรรม/วันที่ดำเนินกิจกรรม <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('act_date') is-invalid @enderror" id="act_date" name="act_date" value="{{ old('act_date') }}">
                        @error('act_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="act_time" class="form-label" id="time_label">เวลาที่จัดกิจกรรม/เวลาที่ดำเนินกิจกรรม <span class="text-danger">*</span></label>
                        <input type="time" class="form-control @error('act_time') is-invalid @enderror" id="act_time" name="act_time" value="{{ old('act_time') }}">
                        @error('act_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Location Section for Photo News Only -->
                <div class="row mb-3" id="location_section" style="display: none;">
                    <div class="col-md-12">
                        <label class="form-label">สถานที่จัดกิจกรรม <span class="text-danger">*</span></label>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="location_type" id="location_inside" value="inside" {{ old('location_type') == 'inside' ? 'checked' : '' }}>
                                <label class="form-check-label" for="location_inside">
                                    สถานที่จัดกิจกรรมใน สปคม.
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="location_type" id="location_outside" value="outside" {{ old('location_type') == 'outside' ? 'checked' : '' }}>
                                <label class="form-check-label" for="location_outside">
                                    กรณีดำเนินกิจกรรมนอกหน่วยงาน
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Inside Location Options -->
                <div class="row mb-3" id="inside_location_field" style="display: none;">
                    <div class="col-md-12">
                        <label for="inside_location" class="form-label">เลือกสถานที่ใน สปคม. <span class="text-danger">*</span></label>
                        <select class="form-select @error('inside_location') is-invalid @enderror" id="inside_location" name="inside_location">
                            <option value="">-- เลือกสถานที่ --</option>
                            <option value="ห้องประชุม 601" {{ old('inside_location') == 'ห้องประชุม 601' ? 'selected' : '' }}>ห้องประชุม 601</option>
                            <option value="ห้องประชุม 301 (ห้องประชุมใหญ่)" {{ old('inside_location') == 'ห้องประชุม 301 (ห้องประชุมใหญ่)' ? 'selected' : '' }}>ห้องประชุม 301 (ห้องประชุมใหญ่)</option>
                            <option value="ห้องประชุม 302" {{ old('inside_location') == 'ห้องประชุม 302' ? 'selected' : '' }}>ห้องประชุม 302</option>
                            <option value="ห้องประชุม 303" {{ old('inside_location') == 'ห้องประชุม 303' ? 'selected' : '' }}>ห้องประชุม 303</option>
                            <option value="ห้องประชุม 304" {{ old('inside_location') == 'ห้องประชุม 304' ? 'selected' : '' }}>ห้องประชุม 304</option>
                            <option value="ห้องประชุม 201 (EOC)" {{ old('inside_location') == 'ห้องประชุม 201 (EOC)' ? 'selected' : '' }}>ห้องประชุม 201 (EOC)</option>
                            <option value="ลานหน้าลิฟท์" {{ old('inside_location') == 'ลานหน้าลิฟท์' ? 'selected' : '' }}>ลานหน้าลิฟท์</option>
                            <option value="คลินิกโรคผิวหนังชั้น 1" {{ old('inside_location') == 'คลินิกโรคผิวหนังชั้น 1' ? 'selected' : '' }}>คลินิกโรคผิวหนังชั้น 1</option>
                            <option value="คลินิก ARI" {{ old('inside_location') == 'คลินิก ARI' ? 'selected' : '' }}>คลินิก ARI</option>
                            <option value="other_inside" {{ old('inside_location') == 'other_inside' ? 'selected' : '' }}>อื่นๆ</option>
                        </select>
                        @error('inside_location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Other inside location detail -->
                <div class="row mb-3" id="other_inside_location_field" style="display: none;">
                    <div class="col-md-12">
                        <label for="other_inside_location" class="form-label">ระบุสถานที่อื่นๆ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('other_inside_location') is-invalid @enderror" id="other_inside_location" name="other_inside_location" value="{{ old('other_inside_location') }}">
                        @error('other_inside_location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Outside location and photo requirement -->
                <div class="row mb-3" id="outside_location_fields" style="display: none;">
                    <div class="col-md-6">
                        <label for="outside_location" class="form-label">สถานที่จัดกิจกรรมนอกหน่วยงาน <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('outside_location') is-invalid @enderror" id="outside_location" name="outside_location" value="{{ old('outside_location') }}">
                        @error('outside_location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="photo_staff_required" class="form-label">ท่านต้องการให้เจ้าหน้าที่ลงถ่ายภาพนอกสถานที่พร้อมกลุ่มงานท่านด้วยหรือไม่? <span class="text-danger">*</span></label>
                        <select class="form-select @error('photo_staff_required') is-invalid @enderror" id="photo_staff_required" name="photo_staff_required">
                            <option value="">-- เลือก --</option>
                            <option value="yes" {{ old('photo_staff_required') == 'yes' ? 'selected' : '' }}>ต้องการ</option>
                            <option value="no" {{ old('photo_staff_required') == 'no' ? 'selected' : '' }}>ไม่ต้องการ</option>
                        </select>
                        @error('photo_staff_required')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Objective Section (for news_pr and photo_news) -->
                <div class="mb-3" id="objective_section" style="display: none;">
                    <label for="act_objective" class="form-label">วัตถุประสงค์ของการดำเนินกิจกรรม</label>
                    <textarea class="form-control @error('act_objective') is-invalid @enderror" id="act_objective" name="act_objective" rows="3">{{ old('act_objective') }}</textarea>
                    @error('act_objective')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Activity Description (for news_pr and photo_news) -->
                <div class="mb-3" id="description_section" style="display: none;">
                    <label for="act_description" class="form-label">กิจกรรมที่ดำเนินการ/ดำเนินกิจกรรม</label>
                    <textarea class="form-control @error('act_description') is-invalid @enderror" id="act_description" name="act_description" rows="5">{{ old('act_description') }}</textarea>
                    @error('act_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Participants Section (for news_pr and photo_news) -->
                <div class="mb-3" id="participants_section" style="display: none;">
                    <label for="participating_agencies" class="form-label">ผู้เข้าร่วมกิจกรรม/เครือข่ายอื่นที่เกี่ยวข้อง (ระบุชื่อเครือข่าย/หน่วยงาน จำนวนคนที่เข้าร่วม)</label>
                    <textarea class="form-control @error('participating_agencies') is-invalid @enderror" id="participating_agencies" name="participating_agencies" rows="3">{{ old('participating_agencies') }}</textarea>
                    @error('participating_agencies')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Additional Details (for news_pr and photo_news) -->
                <div class="mb-3" id="additional_details_section" style="display: none;">
                    <label for="additional_details" class="form-label">รายละเอียดหรือเนื้อหาอื่นๆที่ต้องการเพิ่มเติม</label>
                    <textarea class="form-control @error('additional_details') is-invalid @enderror" id="additional_details" name="additional_details" rows="3">{{ old('additional_details') }}</textarea>
                    @error('additional_details')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Media Type Section for Kiosk -->
                <div class="mb-3" id="kiosk_media_section" style="display: none;">
                    <label class="form-label">สื่อที่ต้องการให้ประชาสัมพันธ์หรือเผยแพร่ <span class="text-danger">*</span></label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="kiosk_media_type[]" value="welcome_banner" id="welcome_banner" {{ in_array('welcome_banner', old('kiosk_media_type', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="welcome_banner">
                            ป้ายหรือภาพต้อนรับการประชุม/คณะนิเทศ/คณะดูงาน
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="kiosk_media_type[]" value="health_infographic" id="health_infographic" {{ in_array('health_infographic', old('kiosk_media_type', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="health_infographic">
                            ภาพหรือ Infographic ความรู้/มาตรการโรคและภัยสุขภาพที่เกี่ยวข้อง
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="kiosk_media_type[]" value="health_video" id="health_video" {{ in_array('health_video', old('kiosk_media_type', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="health_video">
                            VDO ความรู้/มาตรการโรคและภัยสุขภาพที่เกี่ยวข้อง
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="kiosk_media_type[]" value="kiosk_other" id="kiosk_other" {{ in_array('kiosk_other', old('kiosk_media_type', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="kiosk_other">
                            อื่นๆ
                        </label>
                    </div>
                    @error('kiosk_media_type')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Other Kiosk media detail -->
                <div class="mb-3" id="kiosk_other_detail_field" style="display: none;">
                    <label for="kiosk_other_detail" class="form-label">ระบุสื่ออื่นๆ <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('kiosk_other_detail') is-invalid @enderror" id="kiosk_other_detail" name="kiosk_other_detail" value="{{ old('kiosk_other_detail') }}">
                    @error('kiosk_other_detail')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Media Type Section for Website -->
                <div class="mb-3" id="website_media_section" style="display: none;">
                    <label class="form-label">สื่อที่ต้องการให้ประชาสัมพันธ์หรือเผยแพร่ <span class="text-danger">*</span></label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="website_media_type[]" value="academic_work" id="academic_work" {{ in_array('academic_work', old('website_media_type', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="academic_work">
                            ผลงานวิชาการ/บทความ/อวช.
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="website_media_type[]" value="pr_infographic" id="pr_infographic" {{ in_array('pr_infographic', old('website_media_type', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="pr_infographic">
                            Infographic ประชาสัมพันธ์ / รับสมัครงาน / ประกาศ
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="website_media_type[]" value="pr_video" id="pr_video" {{ in_array('pr_video', old('website_media_type', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="pr_video">
                            VDO ประชาสัมพันธ์ / โรคและภัยสุขภาพ
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="website_media_type[]" value="website_other" id="website_other" {{ in_array('website_other', old('website_media_type', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="website_other">
                            อื่นๆ
                        </label>
                    </div>
                    @error('website_media_type')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Other Website media detail -->
                <div class="mb-3" id="website_other_detail_field" style="display: none;">
                    <label for="website_other_detail" class="form-label">ระบุสื่ออื่นๆ <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('website_other_detail') is-invalid @enderror" id="website_other_detail" name="website_other_detail" value="{{ old('website_other_detail') }}">
                    @error('website_other_detail')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                </div> <!-- End of dynamic_content -->
                
                <div class="mb-3">
                    <label for="images" class="form-label">ภาพประกอบ (ถ้ามี)</label>
                    <input type="file" class="form-control @error('images.*') is-invalid @enderror" id="images" name="images[]" multiple accept="image/*">
                    @error('images.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">อัปโหลดไฟล์รูปภาพได้หลายไฟล์ (jpg, png, gif)</div>
                </div>
                
                <div class="mb-4">
                    <div class="row" id="image-preview"></div>
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const actTypeSelect = document.getElementById('act_type_id');
        const otherDetailField = document.getElementById('other_detail_field');
        const dynamicContent = document.getElementById('dynamic_content');
        const activityNameSection = document.getElementById('activity_name_section');
        const datetimeSection = document.getElementById('datetime_section');
        const locationSection = document.getElementById('location_section');
        const objectiveSection = document.getElementById('objective_section');
        const descriptionSection = document.getElementById('description_section');
        const participantsSection = document.getElementById('participants_section');
        const additionalDetailsSection = document.getElementById('additional_details_section');
        const kioskMediaSection = document.getElementById('kiosk_media_section');
        const websiteMediaSection = document.getElementById('website_media_section');
        const insideLocationField = document.getElementById('inside_location_field');
        const outsideLocationFields = document.getElementById('outside_location_fields');
        const otherInsideLocationField = document.getElementById('other_inside_location_field');
        const kioskOtherDetailField = document.getElementById('kiosk_other_detail_field');
        const websiteOtherDetailField = document.getElementById('website_other_detail_field');
        
        function toggleFields() {
            const selectedType = actTypeSelect.value;
            
            // Hide all sections first
            otherDetailField.style.display = 'none';
            dynamicContent.style.display = 'none';
            activityNameSection.style.display = 'none';
            datetimeSection.style.display = 'none';
            locationSection.style.display = 'none';
            objectiveSection.style.display = 'none';
            descriptionSection.style.display = 'none';
            participantsSection.style.display = 'none';
            additionalDetailsSection.style.display = 'none';
            kioskMediaSection.style.display = 'none';
            websiteMediaSection.style.display = 'none';
            insideLocationField.style.display = 'none';
            outsideLocationFields.style.display = 'none';
            otherInsideLocationField.style.display = 'none';
            kioskOtherDetailField.style.display = 'none';
            websiteOtherDetailField.style.display = 'none';
            
            // Remove required attributes
            document.getElementById('other_detail').removeAttribute('required');
            
            if (selectedType === '') {
                // No selection - hide everything
                return;
            } else if (selectedType === 'other') {
                otherDetailField.style.display = 'block';
                document.getElementById('other_detail').setAttribute('required', 'required');
            } else {
                // Show dynamic content for all other selections
                dynamicContent.style.display = 'block';
                
                if (selectedType === 'news_pr') {
                    // Show sections for news PR
                    activityNameSection.style.display = 'block';
                    datetimeSection.style.display = 'block';
                    objectiveSection.style.display = 'block';
                    descriptionSection.style.display = 'block';
                    participantsSection.style.display = 'block';
                    additionalDetailsSection.style.display = 'block';
                    
                    // Set labels for news PR
                    document.getElementById('date_label').innerHTML = 'วันที่จัดกิจกรรม/วันที่ดำเนินกิจกรรม <span class="text-danger">*</span>';
                    document.getElementById('time_label').innerHTML = 'เวลาที่จัดกิจกรรม/เวลาที่ดำเนินกิจกรรม <span class="text-danger">*</span>';
                } else if (selectedType === 'photo_news') {
                    // Show sections for photo news
                    activityNameSection.style.display = 'block';
                    datetimeSection.style.display = 'block';
                    locationSection.style.display = 'block';
                    objectiveSection.style.display = 'block';
                    descriptionSection.style.display = 'block';
                    participantsSection.style.display = 'block';
                    
                    // Set labels for photo news
                    document.getElementById('date_label').innerHTML = 'วันที่ท่านจัดกรรมหรือวันที่ต้องการให้ถ่ายภาพ <span class="text-danger">*</span>';
                    document.getElementById('time_label').innerHTML = 'เวลาที่จัดกิจกรรมหรือเวลาที่ต้องการให้ถ่ายภาพ <span class="text-danger">*</span>';
                } else if (selectedType === 'kiosk_pr') {
                    // Show sections for kiosk PR
                    kioskMediaSection.style.display = 'block';
                } else if (selectedType === 'website_pr') {
                    // Show sections for website PR
                    websiteMediaSection.style.display = 'block';
                }
            }
        }
        
        function toggleLocationFields() {
            const locationInside = document.getElementById('location_inside');
            const locationOutside = document.getElementById('location_outside');
            
            insideLocationField.style.display = 'none';
            outsideLocationFields.style.display = 'none';
            otherInsideLocationField.style.display = 'none';
            
            if (locationInside && locationInside.checked) {
                insideLocationField.style.display = 'block';
            } else if (locationOutside && locationOutside.checked) {
                outsideLocationFields.style.display = 'block';
            }
        }
        
        function toggleOtherInsideLocation() {
            const insideLocationSelect = document.getElementById('inside_location');
            const selectedLocation = insideLocationSelect.value;
            
            if (selectedLocation === 'other_inside') {
                otherInsideLocationField.style.display = 'block';
                document.getElementById('other_inside_location').setAttribute('required', 'required');
            } else {
                otherInsideLocationField.style.display = 'none';
                document.getElementById('other_inside_location').removeAttribute('required');
            }
        }
        
        function toggleKioskOtherDetail() {
            const kioskOther = document.getElementById('kiosk_other');
            
            if (kioskOther && kioskOther.checked) {
                kioskOtherDetailField.style.display = 'block';
                document.getElementById('kiosk_other_detail').setAttribute('required', 'required');
            } else {
                kioskOtherDetailField.style.display = 'none';
                document.getElementById('kiosk_other_detail').removeAttribute('required');
            }
        }
        
        function toggleWebsiteOtherDetail() {
            const websiteOther = document.getElementById('website_other');
            
            if (websiteOther && websiteOther.checked) {
                websiteOtherDetailField.style.display = 'block';
                document.getElementById('website_other_detail').setAttribute('required', 'required');
            } else {
                websiteOtherDetailField.style.display = 'none';
                document.getElementById('website_other_detail').removeAttribute('required');
            }
        }
        
        // Initial setup
        toggleFields();
        
        // Event listeners
        actTypeSelect.addEventListener('change', function() {
            toggleFields();
            // Reset location fields when activity type changes
            const locationRadios = document.querySelectorAll('input[name="location_type"]');
            locationRadios.forEach(radio => radio.checked = false);
            toggleLocationFields();
        });
        
        // Location radio buttons
        const locationRadios = document.querySelectorAll('input[name="location_type"]');
        locationRadios.forEach(radio => {
            radio.addEventListener('change', toggleLocationFields);
        });
        
        // Inside location select
        const insideLocationSelect = document.getElementById('inside_location');
        if (insideLocationSelect) {
            insideLocationSelect.addEventListener('change', toggleOtherInsideLocation);
        }
        
        // Kiosk media checkboxes
        const kioskOther = document.getElementById('kiosk_other');
        if (kioskOther) {
            kioskOther.addEventListener('change', toggleKioskOtherDetail);
        }
        
        // Website media checkboxes
        const websiteOther = document.getElementById('website_other');
        if (websiteOther) {
            websiteOther.addEventListener('change', toggleWebsiteOtherDetail);
        }
    });
</script>
@endsection