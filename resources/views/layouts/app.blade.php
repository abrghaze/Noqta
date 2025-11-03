<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Noqta') }} - @yield('title', 'Dashboard')</title>

    <!-- Favicon (SVG with "N" for Noqta) -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='15' fill='%234F46E5'/><text x='50' y='75' font-size='70' font-weight='bold' text-anchor='middle' fill='white'>N</text></svg>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #7c3aed;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            margin: 0.25rem 0;
            border-radius: 0.5rem;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .stat-card.success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        .stat-card.warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .stat-card.info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(79, 70, 229, 0.05);
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse" id="sidebarMenu">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="navbar-brand">
                            <i class="bi bi-mortarboard-fill"></i> {{ config('app.name') }}
                        </h4>
                        <p class="small mb-0">{{ auth()->user()->name }}</p>
                        <span class="badge bg-light text-dark">{{ ucfirst(auth()->user()->role) }}</span>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Tableau de bord
                            </a>
                        </li>

                        @if(auth()->user()->role === 'directeur')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('directeur.users.index') }}">
                                    <i class="bi bi-people"></i> Utilisateurs
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('directeur.classes.index') }}">
                                    <i class="bi bi-door-open"></i> Classes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('directeur.subjects.index') }}">
                                    <i class="bi bi-book"></i> Matières
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->role === 'enseignant')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('enseignant.classes.index') }}">
                                    <i class="bi bi-door-open"></i> Mes Classes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('enseignant.grades.index') }}">
                                    <i class="bi bi-journal-text"></i> Notes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('enseignant.attendance.index') }}">
                                    <i class="bi bi-calendar-check"></i> Absences
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->role === 'etudiant')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('etudiant.grades.index') }}">
                                    <i class="bi bi-journal-text"></i> Mes Notes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('etudiant.attendance.index') }}">
                                    <i class="bi bi-calendar-check"></i> Mes Absences
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->role === 'parent')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('parent.grades.index') }}">
                                    <i class="bi bi-journal-text"></i> Notes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('parent.attendance.index') }}">
                                    <i class="bi bi-calendar-check"></i> Absences
                                </a>
                            </li>
                        @endif

                        <li class="nav-item mt-3">
                            <a class="nav-link" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person-circle"></i> Profil
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-start w-100">
                                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Top navbar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-4 mt-3 rounded">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="ms-auto d-flex align-items-center">
                            <!-- Notifications Dropdown -->
                            <div class="dropdown me-3" id="notificationsDropdown">
                                <button class="btn btn-link position-relative p-0" type="button" id="notificationButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-bell-fill fs-4 text-primary"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationBadge" style="display: none; font-size: 0.65rem;">
                                        0
                                    </span>
                                </button>
                                
                                <div class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notificationButton" style="width: 350px; max-height: 500px; overflow-y: auto;">
                                    <!-- Header -->
                                    <div class="dropdown-header d-flex justify-content-between align-items-center border-bottom pb-2">
                                        <h6 class="mb-0">Notifications</h6>
                                        <button class="btn btn-sm btn-link text-decoration-none p-0" id="markAllReadBtn" style="font-size: 0.8rem;">
                                            Tout marquer comme lu
                                        </button>
                                    </div>

                                    <!-- Notifications List -->
                                    <div id="notificationsList">
                                        <!-- Loading state -->
                                        <div class="text-center py-4" id="notificationsLoading">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                <span class="visually-hidden">Chargement...</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Footer -->
                                    <div class="dropdown-divider"></div>
                                    <a href="{{ route('notifications.all') }}" class="dropdown-item text-center text-primary">
                                        Voir toutes les notifications
                                    </a>
                                </div>
                            </div>

                            <span class="navbar-text">
                                <i class="bi bi-calendar3"></i> {{ now()->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                </nav>

                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Notifications JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const notificationButton = document.getElementById('notificationButton');
        const notificationBadge = document.getElementById('notificationBadge');
        const notificationsList = document.getElementById('notificationsList');
        const notificationsLoading = document.getElementById('notificationsLoading');
        const markAllReadBtn = document.getElementById('markAllReadBtn');

        // Function to load notifications
        function loadNotifications() {
            fetch('{{ route('notifications.data') }}')
                .then(response => response.json())
                .then(data => {
                    // Update badge
                    const unreadCount = data.unread_count;
                    if (unreadCount > 0) {
                        notificationBadge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                        notificationBadge.style.display = 'block';
                    } else {
                        notificationBadge.style.display = 'none';
                    }

                    // Display notifications
                    displayNotifications(data.notifications);
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    notificationsList.innerHTML = '<div class="dropdown-item text-danger">Erreur de chargement</div>';
                })
                .finally(() => {
                    notificationsLoading.style.display = 'none';
                });
        }

        // Function to display notifications
        function displayNotifications(notifications) {
            if (notifications.length === 0) {
                notificationsList.innerHTML = `
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-bell-slash fs-2 mb-2"></i>
                        <p class="mb-0">Aucune notification</p>
                    </div>
                `;
                return;
            }

            let html = '';
            notifications.forEach(notif => {
                const isRead = notif.read_at !== null;
                const readClass = isRead ? '' : 'bg-light';
                const iconClass = getIconForType(notif.type);
                const timeAgo = formatTimeAgo(notif.created_at);

                html += `
                    <div class="dropdown-item ${readClass} notification-item" data-id="${notif.id}" style="white-space: normal; cursor: pointer;">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <i class="${iconClass} fs-5"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 small fw-bold">${notif.title}</h6>
                                <p class="mb-1 small text-muted">${notif.message}</p>
                                <small class="text-muted">${timeAgo}</small>
                            </div>
                            ${!isRead ? '<div class="ms-2"><span class="badge bg-primary rounded-circle" style="width: 8px; height: 8px;"></span></div>' : ''}
                        </div>
                    </div>
                `;
            });

            notificationsList.innerHTML = html;

            // Add click handlers to mark as read
            document.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', function() {
                    const notifId = this.dataset.id;
                    markAsRead(notifId);
                });
            });
        }

        // Function to get icon based on notification type
        function getIconForType(type) {
            const icons = {
                'grade_added': 'bi bi-clipboard-check text-success',
                'absence_marked': 'bi bi-calendar-x text-danger',
                'message': 'bi bi-envelope text-info',
                'system': 'bi bi-info-circle text-primary',
            };
            return icons[type] || 'bi bi-bell text-secondary';
        }

        // Function to format time ago
        function formatTimeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const seconds = Math.floor((now - date) / 1000);

            if (seconds < 60) return 'À l\'instant';
            if (seconds < 3600) return `Il y a ${Math.floor(seconds / 60)} min`;
            if (seconds < 86400) return `Il y a ${Math.floor(seconds / 3600)} h`;
            if (seconds < 604800) return `Il y a ${Math.floor(seconds / 86400)} j`;
            return date.toLocaleDateString('fr-FR');
        }

        // Function to mark notification as read
        function markAsRead(notifId) {
            fetch(`/notifications/${notifId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications(); // Reload to update UI
                }
            })
            .catch(error => console.error('Error marking as read:', error));
        }

        // Mark all as read
        markAllReadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            fetch('{{ route('notifications.markAllRead') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications(); // Reload to update UI
                }
            })
            .catch(error => console.error('Error marking all as read:', error));
        });

        // Load notifications when dropdown opens
        notificationButton.addEventListener('click', function() {
            loadNotifications();
        });

        // Initial load
        loadNotifications();

        // Refresh every 60 seconds
        setInterval(loadNotifications, 60000);
    });
    </script>
    
    @stack('scripts')
</body>
</html>
