<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} - @yield('title', 'Dashboard')</title>

    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='15' fill='%234F46E5'/><text x='50' y='75' font-size='70' font-weight='bold' text-anchor='middle' fill='white'>N</text></svg>">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .stat-card {
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .sidebar-link {
            transition: all 0.2s;
        }
        
        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(4px);
        }
        
        .sidebar-link.active {
            background-color: rgba(255, 255, 255, 0.15);
            border-left: 4px solid white;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }
        
        .dropdown-menu {
            animation: slideIn 0.2s ease-out;
        }
    </style>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full" x-data="{ sidebarOpen: false, profileOpen: false }">
    <div class="min-h-full">
        <!-- Sidebar for desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col">
            <div class="flex flex-col flex-grow bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-700 overflow-y-auto">
                <!-- Logo -->
                <div class="flex items-center flex-shrink-0 px-6 py-5 border-b border-indigo-500/30">
                    <i class="fas fa-graduation-cap text-3xl text-white mr-3"></i>
                    <div>
                        <h1 class="text-xl font-bold text-white">{{ config('app.name') }}</h1>
                        <p class="text-xs text-indigo-200">Gestion Scolaire</p>
                    </div>
                </div>
                
                <!-- User Info -->
                <div class="px-6 py-4 border-b border-indigo-500/30">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            @if(auth()->user()->profile_picture)
                                <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="{{ auth()->user()->name }}" class="h-12 w-12 rounded-full object-cover border-2 border-white/30">
                            @else
                                <div class="h-12 w-12 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-lg">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-indigo-200">{{ ucfirst(auth()->user()->role) }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="flex-1 px-4 py-4 space-y-1">
                    <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-white">
                        <i class="fas fa-home mr-3 text-lg"></i>
                        Tableau de bord
                    </a>
                    
                    @if(auth()->user()->role === 'directeur')
                        <a href="{{ route('directeur.users.index') }}" class="sidebar-link group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-white">
                            <i class="fas fa-users mr-3 text-lg"></i>
                            Utilisateurs
                        </a>
                        <a href="{{ route('directeur.classes.index') }}" class="sidebar-link group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-white">
                            <i class="fas fa-door-open mr-3 text-lg"></i>
                            Classes
                        </a>
                        <a href="{{ route('directeur.subjects.index') }}" class="sidebar-link group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-white">
                            <i class="fas fa-book mr-3 text-lg"></i>
                            Matières
                        </a>
                    @endif
                    
                    @if(auth()->user()->role === 'enseignant')
                        <a href="{{ route('enseignant.classes.index') }}" class="sidebar-link group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-white">
                            <i class="fas fa-door-open mr-3 text-lg"></i>
                            Mes Classes
                        </a>
                        <a href="{{ route('enseignant.grades.index') }}" class="sidebar-link group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-white">
                            <i class="fas fa-clipboard-list mr-3 text-lg"></i>
                            Notes
                        </a>
                        <a href="{{ route('enseignant.attendance.index') }}" class="sidebar-link group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-white">
                            <i class="fas fa-calendar-check mr-3 text-lg"></i>
                            Absences
                        </a>
                    @endif
                    
                    @if(auth()->user()->role === 'etudiant')
                        <a href="{{ route('etudiant.grades.index') }}" class="sidebar-link group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-white">
                            <i class="fas fa-clipboard-list mr-3 text-lg"></i>
                            Mes Notes
                        </a>
                        <a href="{{ route('etudiant.attendance.index') }}" class="sidebar-link group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-white">
                            <i class="fas fa-calendar-check mr-3 text-lg"></i>
                            Mes Absences
                        </a>
                    @endif
                    
                    @if(auth()->user()->role === 'parent')
                        <a href="{{ route('parent.grades.index') }}" class="sidebar-link group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-white">
                            <i class="fas fa-clipboard-list mr-3 text-lg"></i>
                            Notes
                        </a>
                        <a href="{{ route('parent.attendance.index') }}" class="sidebar-link group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-white">
                            <i class="fas fa-calendar-check mr-3 text-lg"></i>
                            Absences
                        </a>
                    @endif
                </nav>
                
                <!-- Bottom Navigation -->
                <div class="flex-shrink-0 px-4 py-4 border-t border-indigo-500/30 space-y-1">
                    <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-white">
                        <i class="fas fa-user-circle mr-3 text-lg"></i>
                        Mon Profil
                    </a>
                    <a href="{{ route('settings.index') }}" class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-white">
                        <i class="fas fa-cog mr-3 text-lg"></i>
                        Paramètres
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="sidebar-link w-full group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-white hover:bg-red-500/20">
                            <i class="fas fa-sign-out-alt mr-3 text-lg"></i>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Mobile sidebar -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 lg:hidden" x-cloak>
            <div class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>
        </div>
        
        <div x-show="sidebarOpen" class="fixed inset-0 z-40 flex lg:hidden" x-cloak>
            <div @click.away="sidebarOpen = false" class="relative flex w-full max-w-xs flex-1 flex-col bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-700">
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button @click="sidebarOpen = false" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <i class="fas fa-times text-white text-xl"></i>
                    </button>
                </div>
                
                <!-- Mobile menu content (same as desktop) -->
                <div class="flex flex-col flex-grow overflow-y-auto">
                    <!-- Logo -->
                    <div class="flex items-center flex-shrink-0 px-6 py-5 border-b border-indigo-500/30">
                        <i class="fas fa-graduation-cap text-3xl text-white mr-3"></i>
                        <div>
                            <h1 class="text-xl font-bold text-white">{{ config('app.name') }}</h1>
                            <p class="text-xs text-indigo-200">Gestion Scolaire</p>
                        </div>
                    </div>
                    
                    <!-- User Info -->
                    <div class="px-6 py-4 border-b border-indigo-500/30">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-lg">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-indigo-200">{{ ucfirst(auth()->user()->role) }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navigation (same as desktop) -->
                    <nav class="flex-1 px-4 py-4 space-y-1">
                        <!-- Copy navigation from desktop sidebar -->
                    </nav>
                </div>
            </div>
        </div>
        
        <!-- Main content -->
        <div class="lg:pl-64 flex flex-col flex-1">
            <!-- Top navbar -->
            <div class="sticky top-0 z-10 flex h-16 flex-shrink-0 bg-white shadow-sm">
                <button @click="sidebarOpen = true" class="border-r border-gray-200 px-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 lg:hidden">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                
                <div class="flex flex-1 justify-between px-4 sm:px-6 lg:px-8">
                    <!-- Breadcrumb -->
                    <div class="flex flex-1 items-center">
                        <nav class="flex" aria-label="Breadcrumb">
                            <ol class="flex items-center space-x-2 text-sm">
                                <li>
                                    <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-500">
                                        <i class="fas fa-home"></i>
                                    </a>
                                </li>
                                <li>
                                    <div class="flex items-center">
                                        <i class="fas fa-chevron-right text-gray-300 mx-2 text-xs"></i>
                                        <span class="text-gray-700 font-medium">@yield('breadcrumb', 'Dashboard')</span>
                                    </div>
                                </li>
                            </ol>
                        </nav>
                    </div>
                    
                    <!-- Right side -->
                    <div class="ml-4 flex items-center space-x-4">
                        <!-- Date -->
                        <div class="hidden md:flex items-center text-sm text-gray-500">
                            <i class="fas fa-calendar mr-2"></i>
                            {{ now()->format('d/m/Y') }}
                        </div>
                        
                        <!-- Notifications Dropdown -->
                        <div class="relative" x-data="{ notifOpen: false }">
                            <button @click="notifOpen = !notifOpen" id="notificationButton" class="relative p-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full">
                                <i class="fas fa-bell text-xl"></i>
                                <span id="notificationBadge" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full" style="display: none;">0</span>
                            </button>
                            
                            <!-- Notifications Dropdown Menu -->
                            <div x-show="notifOpen" @click.away="notifOpen = false" 
                                 class="absolute right-0 mt-2 w-96 origin-top-right rounded-lg bg-white shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                 style="max-height: 500px; overflow-y: auto;"
                                 x-cloak>
                                <!-- Header -->
                                <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                                    <h6 class="text-sm font-semibold text-gray-900">Notifications</h6>
                                    <button id="markAllReadBtn" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                        Tout marquer comme lu
                                    </button>
                                </div>

                                <!-- Notifications List -->
                                <div id="notificationsList" class="divide-y divide-gray-100">
                                    <!-- Loading state -->
                                    <div class="text-center py-8" id="notificationsLoading">
                                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                                        <p class="text-sm text-gray-500 mt-2">Chargement...</p>
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="border-t border-gray-200 p-2">
                                    <a href="{{ route('notifications.all') }}" class="block text-center py-2 text-sm text-indigo-600 hover:text-indigo-800 font-medium rounded-md hover:bg-gray-50">
                                        Voir toutes les notifications
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Profile dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none">
                                @if(auth()->user()->profile_picture)
                                    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="{{ auth()->user()->name }}" class="h-10 w-10 rounded-full object-cover border-2 border-gray-200">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                    </div>
                                @endif
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
                                </div>
                                <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                            </button>
                            
                            <!-- Dropdown menu -->
                            <div x-show="open" @click.away="open = false" 
                                 class="dropdown-menu absolute right-0 mt-2 w-56 origin-top-right rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                 x-cloak>
                                <div class="p-2">
                                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                                        <i class="fas fa-user-circle mr-3 text-gray-400"></i>
                                        Mon Profil
                                    </a>
                                    <a href="{{ route('settings.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-md">
                                        <i class="fas fa-cog mr-3 text-gray-400"></i>
                                        Paramètres
                                    </a>
                                    <hr class="my-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex w-full items-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-md">
                                            <i class="fas fa-sign-out-alt mr-3"></i>
                                            Déconnexion
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main content area -->
            <main class="flex-1">
                <!-- Alerts -->
                @if(session('success'))
                    <div class="mx-4 sm:mx-6 lg:mx-8 mt-4 animate-slide-in">
                        <div class="rounded-lg bg-green-50 p-4 border-l-4 border-green-400">
                            <div class="flex">
                                <i class="fas fa-check-circle text-green-400 text-xl"></i>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                </div>
                                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="ml-auto">
                                    <i class="fas fa-times text-green-400"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mx-4 sm:mx-6 lg:mx-8 mt-4 animate-slide-in">
                        <div class="rounded-lg bg-red-50 p-4 border-l-4 border-red-400">
                            <div class="flex">
                                <i class="fas fa-exclamation-circle text-red-400 text-xl"></i>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                                </div>
                                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="ml-auto">
                                    <i class="fas fa-times text-red-400"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Page content -->
                <div class="py-6">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
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
                    updateBadge(data.unread_count);
                    displayNotifications(data.notifications);
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    notificationsList.innerHTML = `
                        <div class="text-center py-4 px-4">
                            <i class="fas fa-exclamation-triangle text-red-500 text-2xl mb-2"></i>
                            <p class="text-sm text-red-600">Erreur de chargement</p>
                        </div>
                    `;
                })
                .finally(() => {
                    notificationsLoading.style.display = 'none';
                });
        }

        // Update badge
        function updateBadge(count) {
            if (count > 0) {
                notificationBadge.textContent = count > 99 ? '99+' : count;
                notificationBadge.style.display = 'inline-flex';
            } else {
                notificationBadge.style.display = 'none';
            }
        }

        // Display notifications
        function displayNotifications(notifications) {
            if (notifications.length === 0) {
                notificationsList.innerHTML = `
                    <div class="text-center py-8 px-4">
                        <i class="fas fa-bell-slash text-gray-300 text-4xl mb-3"></i>
                        <p class="text-sm text-gray-500 font-medium">Aucune notification</p>
                        <p class="text-xs text-gray-400 mt-1">Vous êtes à jour!</p>
                    </div>
                `;
                return;
            }

            let html = '';
            notifications.forEach(notif => {
                const isRead = notif.read_at !== null;
                const readClass = isRead ? '' : 'bg-indigo-50';
                const iconClass = getIconForType(notif.type);
                const iconColor = getColorForType(notif.type);
                const timeAgo = formatTimeAgo(notif.created_at);

                html += `
                    <div class="notification-item px-4 py-3 hover:bg-gray-50 cursor-pointer transition ${readClass}"
                         data-id="${notif.id}">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full flex items-center justify-center" style="background-color: ${iconColor}20;">
                                    <i class="${iconClass}" style="color: ${iconColor};"></i>
                                </div>
                            </div>
                            <div class="ml-3 flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <p class="text-sm font-semibold text-gray-900 truncate">${notif.title}</p>
                                    ${!isRead ? '<span class="ml-2 flex-shrink-0 inline-block h-2 w-2 rounded-full bg-indigo-600"></span>' : ''}
                                </div>
                                <p class="text-sm text-gray-600 mt-1">${notif.message}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="far fa-clock mr-1"></i>${timeAgo}
                                </p>
                            </div>
                        </div>
                    </div>
                `;
            });

            notificationsList.innerHTML = html;

            // Add click handlers
            document.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', function() {
                    const notifId = this.dataset.id;
                    markAsRead(notifId);
                });
            });
        }

        // Get icon for notification type
        function getIconForType(type) {
            const icons = {
                'grade_added': 'fas fa-clipboard-list',
                'absence_marked': 'fas fa-calendar-check',
                'message': 'fas fa-envelope',
                'system': 'fas fa-info-circle',
            };
            return icons[type] || 'fas fa-bell';
        }

        // Get color for notification type
        function getColorForType(type) {
            const colors = {
                'grade_added': '#10B981',
                'absence_marked': '#3B82F6',
                'message': '#8B5CF6',
                'system': '#6B7280',
            };
            return colors[type] || '#6B7280';
        }

        // Format time ago
        function formatTimeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const seconds = Math.floor((now - date) / 1000);

            if (seconds < 60) return 'À l\'instant';
            if (seconds < 3600) return `Il y a ${Math.floor(seconds / 60)} min`;
            if (seconds < 86400) return `Il y a ${Math.floor(seconds / 3600)}h`;
            if (seconds < 604800) return `Il y a ${Math.floor(seconds / 86400)}j`;
            
            return date.toLocaleDateString('fr-FR', { 
                day: 'numeric', 
                month: 'short',
                year: date.getFullYear() !== now.getFullYear() ? 'numeric' : undefined
            });
        }

        // Mark as read
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
                    loadNotifications();
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Mark all as read
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
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
                        loadNotifications();
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        }

        // Load notifications when button clicked
        if (notificationButton) {
            notificationButton.addEventListener('click', function() {
                notificationsLoading.style.display = 'block';
                loadNotifications();
            });
        }

        // Initial load
        loadNotifications();

        // Refresh every 60 seconds
        setInterval(loadNotifications, 60000);
    });
    </script>
    
    @stack('scripts')
</body>
</html>
