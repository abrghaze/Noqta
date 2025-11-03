@extends('layouts.modern')

@section('title', 'Toutes les Notifications')
@section('breadcrumb', 'Notifications')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-bell mr-3 text-indigo-600"></i>
                Toutes les Notifications
            </h1>
            <p class="text-gray-600 mt-2">Gérez et consultez toutes vos notifications</p>
        </div>
        <button class="px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-md flex items-center" id="markAllReadBtn">
            <i class="fas fa-check-double mr-2"></i>
            Tout marquer comme lu
        </button>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            @if($notifications->isNotEmpty())
                <div class="space-y-3">
                    @foreach($notifications as $notification)
                        <div class="{{ $notification->read_at ? 'bg-white' : 'bg-indigo-50' }} border border-gray-200 rounded-lg p-4 hover:shadow-md transition notification-item" data-id="{{ $notification->id }}">
                            <div class="flex items-start gap-4">
                                <!-- Icon -->
                                <div class="flex-shrink-0">
                                    @if($notification->type === 'grade_added')
                                        <div class="h-12 w-12 rounded-full flex items-center justify-center bg-green-100">
                                            <i class="fas fa-clipboard-list text-green-600 text-xl"></i>
                                        </div>
                                    @elseif($notification->type === 'absence_marked')
                                        <div class="h-12 w-12 rounded-full flex items-center justify-center bg-red-100">
                                            <i class="fas fa-calendar-times text-red-600 text-xl"></i>
                                        </div>
                                    @elseif($notification->type === 'message')
                                        <div class="h-12 w-12 rounded-full flex items-center justify-center bg-blue-100">
                                            <i class="fas fa-envelope text-blue-600 text-xl"></i>
                                        </div>
                                    @else
                                        <div class="h-12 w-12 rounded-full flex items-center justify-center bg-gray-100">
                                            <i class="fas fa-info-circle text-gray-600 text-xl"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="text-base font-semibold text-gray-900">{{ $notification->title }}</h3>
                                        <span class="text-xs text-gray-500 ml-2">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700 mb-2">{{ $notification->message }}</p>
                                    <p class="text-xs text-gray-500 flex items-center">
                                        <i class="far fa-clock mr-1"></i> {{ $notification->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>

                                <!-- Actions -->
                                <div class="flex-shrink-0 flex flex-col gap-2">
                                    @if(!$notification->read_at)
                                        <button class="px-3 py-1.5 text-xs font-medium text-white bg-indigo-600 rounded hover:bg-indigo-700 transition mark-read-btn" data-id="{{ $notification->id }}">
                                            <i class="fas fa-check mr-1"></i> Marquer lu
                                        </button>
                                    @else
                                        <span class="px-3 py-1.5 text-xs font-semibold text-green-700 bg-green-100 rounded">Lu</span>
                                    @endif
                                    <button class="px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700 transition delete-btn" data-id="{{ $notification->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-bell-slash text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucune notification</h3>
                    <p class="text-gray-500">Vous n'avez pas encore de notifications</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modale de Confirmation de Suppression -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 rounded-full bg-red-100 flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Confirmer la suppression</h3>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="px-6 py-4">
                <p class="text-gray-700">Êtes-vous sûr de vouloir supprimer cette notification ?</p>
                <p class="text-sm text-gray-500 mt-2">Cette action est irréversible.</p>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex justify-end gap-3">
                <button id="cancelDelete" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-times mr-2"></i>Annuler
                </button>
                <button id="confirmDelete" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-trash mr-2"></i>Supprimer
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark single notification as read
    document.querySelectorAll('.mark-read-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const notifId = this.dataset.id;
            markAsRead(notifId);
        });
    });

    // Mark all as read
    document.getElementById('markAllReadBtn').addEventListener('click', function() {
        markAllAsRead();
    });

    // Delete notification
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const notifId = this.dataset.id;
            deleteNotification(notifId);
        });
    });

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
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function markAllAsRead() {
        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Variables pour la modale
    let notificationToDelete = null;
    const deleteModal = document.getElementById('deleteModal');
    const cancelDeleteBtn = document.getElementById('cancelDelete');
    const confirmDeleteBtn = document.getElementById('confirmDelete');

    function deleteNotification(notifId) {
        // Afficher la modale
        notificationToDelete = notifId;
        deleteModal.classList.remove('hidden');
    }

    // Annuler la suppression
    cancelDeleteBtn.addEventListener('click', function() {
        deleteModal.classList.add('hidden');
        notificationToDelete = null;
    });

    // Fermer la modale en cliquant sur le fond
    deleteModal.addEventListener('click', function(e) {
        if (e.target === deleteModal) {
            deleteModal.classList.add('hidden');
            notificationToDelete = null;
        }
    });

    // Confirmer la suppression
    confirmDeleteBtn.addEventListener('click', function() {
        if (notificationToDelete) {
            fetch(`/notifications/${notificationToDelete}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    deleteModal.classList.add('hidden');
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
});
</script>
@endpush
@endsection
