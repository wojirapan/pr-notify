@extends('layouts.app')

@section('title', 'ปฏิทินกิจกรรม')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-calendar-alt me-2"></i>ปฏิทินกิจกรรม</h2>
        
        <a href="{{ route('activities.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>แจ้งขอประชาสัมพันธ์
        </a>
    </div>
    
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>คำอธิบายสถานะ</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <div class="d-flex align-items-center">
                                <div class="me-2" style="width: 20px; height: 20px; background-color: #ffc107; border-radius: 3px;"></div>
                                <span>รอดำเนินการ</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="d-flex align-items-center">
                                <div class="me-2" style="width: 20px; height: 20px; background-color: #0d6efd; border-radius: 3px;"></div>
                                <span>กำลังดำเนินการ</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="d-flex align-items-center">
                                <div class="me-2" style="width: 20px; height: 20px; background-color: #198754; border-radius: 3px;"></div>
                                <span>เสร็จสิ้นแล้ว</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            locale: 'th',
            buttonText: {
                today: 'วันนี้',
                month: 'เดือน',
                week: 'สัปดาห์',
                day: 'วัน'
            },
            themeSystem: 'bootstrap5',
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
                    title: info.event.title + ' - ' + info.event.extendedProps.description,
                    placement: 'top',
                    trigger: 'hover',
                    container: 'body'
                });
            },
            eventsSet: function(events) {
                console.log('Events loaded:', events.length);
                
                // ลบ CSS rules เก่าก่อน
                const existingStyle = document.getElementById('event-count-style');
                if (existingStyle) {
                    existingStyle.remove();
                }
                
                // นับจำนวนงานในแต่ละวัน
                const eventCounts = {};
                events.forEach(event => {
                    const dateStr = event.start.toISOString().split('T')[0];
                    eventCounts[dateStr] = (eventCounts[dateStr] || 0) + 1;
                    console.log('Event on date:', dateStr, 'Count:', eventCounts[dateStr]);
                });
                
                // สร้าง CSS dynamically
                let cssRules = '';
                Object.keys(eventCounts).forEach(dateStr => {
                    if (eventCounts[dateStr] > 0) {
                        cssRules += `
                            .fc-daygrid-day[data-date="${dateStr}"]:after {
                                content: "${eventCounts[dateStr]} งาน";
                                position: absolute;
                                bottom: 2px;
                                left: 2px;
                                background-color: #dc3545;
                                color: white;
                                padding: 2px 6px;
                                border-radius: 10px;
                                font-size: 12px;
                                font-weight: bold;
                                z-index: 10;
                                pointer-events: none;
                                display: block;
                            }
                        `;
                        console.log('Adding CSS rule for date:', dateStr, 'with count:', eventCounts[dateStr]);
                    }
                });
                
                // เพิ่ม CSS ใหม่
                if (cssRules) {
                    const style = document.createElement('style');
                    style.id = 'event-count-style';
                    style.textContent = cssRules;
                    document.head.appendChild(style);
                }
                
                console.log('CSS rules created for dates:', Object.keys(eventCounts));
            },
            datesSet: function(info) {
                // ลบ CSS rules เก่าออกเมื่อเปลี่ยนเดือน
                const existingStyle = document.getElementById('event-count-style');
                if (existingStyle) {
                    existingStyle.remove();
                }
            },
            loading: function(isLoading) {
                if (isLoading) {
                    // แสดง loading indicator
                    calendarEl.classList.add('opacity-50');
                } else {
                    // ซ่อน loading indicator
                    calendarEl.classList.remove('opacity-50');
                }
            }
        });
        
        calendar.render();
    });
</script>

<style>
.fc-daygrid-day {
    position: relative !important;
}

.event-count-badge {
    z-index: 10 !important;
}

.fc-event-title {
    font-size: 12px;
}

.fc-daygrid-event {
    font-size: 11px;
}

/* เพิ่มสไตล์สำหรับปฏิทินให้สวยงาม */
.fc-daygrid-day:hover {
    background-color: #f8f9fa;
}

.fc-day-today {
    background-color: #e3f2fd !important;
}

.fc-daygrid-day-number {
    font-weight: 500;
}
</style>
@endsection