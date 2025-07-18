<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PR-Notify') }} - @yield('title', 'ระบบแจ้งเพื่อประชาสัมพันธ์')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Fullcalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    
    @yield('styles')
</head>
<body>
    <div id="app">
        @auth
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
                <div class="container">
                    <a class="navbar-brand" href="{{ route('dashboard') }}">
                        <i class="fas fa-bullhorn me-2"></i> PR-Notify
                    </a>
                    
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                    <i class="fas fa-home me-1"></i> หน้าหลัก
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('activities.*') ? 'active' : '' }}" href="{{ route('activities.index') }}">
                                    <i class="fas fa-list-alt me-1"></i> รายการแจ้ง
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('calendar') ? 'active' : '' }}" href="{{ route('calendar') }}">
                                    <i class="fas fa-calendar-alt me-1"></i> ปฏิทิน
                                </a>
                            </li>
                            
                            @if(Auth::user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                        <i class="fas fa-users me-1"></i> จัดการผู้ใช้
                                    </a>
                                </li>
                                
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('logs.*') ? 'active' : '' }}" href="{{ route('logs.index') }}">
                                        <i class="fas fa-history me-1"></i> ประวัติการใช้งาน
                                    </a>
                                </li>
                            @endif
                            
                            <!-- Notifications -->
                            <li class="nav-item dropdown">
                                <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-bell"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge" style="display: none;">
                                        0
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown">
                                    <li>
                                        <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                            การแจ้งเตือน
                                            <a href="{{ route('notifications.markAllAsRead') }}" 
                                               onclick="event.preventDefault(); document.getElementById('mark-all-read-form').submit();"
                                               class="text-primary small mark-all-read">อ่านทั้งหมด</a>
                                            <form id="mark-all-read-form" action="{{ route('notifications.markAllAsRead') }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </h6>
                                    </li>
                                    <div class="notification-list">
                                        <li><hr class="dropdown-divider"></li>
                                        <li><div class="dropdown-item text-center">ไม่มีการแจ้งเตือนใหม่</div></li>
                                    </div>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-center" href="{{ route('notifications.index') }}">
                                            ดูทั้งหมด
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            
                            <!-- User Menu -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->fname }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile') }}">
                                            <i class="fas fa-user me-2"></i> โปรไฟล์
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i> ออกจากระบบ
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        @endauth
        
        <main class="py-4">
            <div class="container">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
        
        <footer class="bg-light py-3 mt-5">
            <div class="container">
                <div class="text-center">
                    <p class="mb-0">PR-Notify &copy; {{ date('Y') }} - ระบบแจ้งเพื่อประชาสัมพันธ์</p>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Fullcalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    
    <!-- Custom JS -->
    <script src="{{ asset('js/custom.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            // Check for notifications
            function checkNotifications() {
                $.ajax({
                    url: '{{ route("notifications.unreadCount") }}',
                    method: 'GET',
                    success: function(data) {
                        if (data.count > 0) {
                            $('.notification-badge').text(data.count).show();
                            
                            // Load notifications for dropdown
                            $.ajax({
                                url: '{{ route("notifications.index") }}',
                                method: 'GET',
                                data: { ajax: true, limit: 5 },
                                success: function(response) {
                                    if (response.notifications && response.notifications.length > 0) {
                                        var html = '';
                                        $.each(response.notifications, function(index, notification) {
                                            html += '<li><a class="dropdown-item" href="' + notification.url + '">';
                                            html += '<div class="small text-muted">' + notification.created_at + '</div>';
                                            html += '<div class="' + (notification.is_read ? '' : 'fw-bold') + '">' + notification.message + '</div>';
                                            html += '</a></li><li><hr class="dropdown-divider"></li>';
                                        });
                                        $('.notification-list').html(html);
                                    } else {
                                        $('.notification-list').html('<li><hr class="dropdown-divider"></li><li><div class="dropdown-item text-center">ไม่มีการแจ้งเตือนใหม่</div></li>');
                                    }
                                }
                            });
                        } else {
                            $('.notification-badge').hide();
                        }
                    }
                });
            }
            
            // Check notifications on page load
            checkNotifications();
            
            // Check notifications every 60 seconds
            setInterval(checkNotifications, 60000);
        });
    </script>
    
    @yield('scripts')
</body>
</html>