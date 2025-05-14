@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white'])

@php
$alignmentClasses = match($align) {
    'left' => 'origin-top-left left-0',
    'top' => 'origin-top',
    'right' => 'origin-top-right right-0',
    'none' => '',
};

$royalBlue = '#1a237e';
$goldenBrown = '#b87a3d';
@endphp

<div class="relative" 
    x-data="notificationSystem()" 
    x-init="initNotifications()"
    @click.outside="open = false" 
    @close.stop="open = false">
    
    <div @click="open = !open">
        <button class="relative inline-flex items-center p-2 text-sm font-medium text-center text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <div x-show="unreadCount > 0" 
                 x-text="unreadCount"
                 class="absolute inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 rounded-full -top-1 -right-1">
            </div>
        </button>
    </div>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute z-50 mt-2 {{ $width ? 'w-'.$width : '' }} rounded-md shadow-lg {{ $alignmentClasses }}"
         style="display: none;">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">
            <div class="max-h-[400px] overflow-y-auto">
                <div class="px-4 py-3 text-sm text-gray-900 border-b border-gray-200 flex justify-between items-center">
                    <div class="font-semibold">Notifications</div>
                    <button x-show="unreadCount > 0" 
                            @click="markAllAsRead()"
                            class="text-xs text-[{{ $royalBlue }}] hover:text-[{{ $royalBlue }}]/80">
                        Mark all as read
                    </button>
                </div>
                
                <!-- Dynamic Notifications -->
                <div class="divide-y divide-gray-100">
                    <template x-for="notification in notifications" :key="notification.id">
                        <a :href="notification.link" 
                           @click.prevent="handleNotificationClick(notification)"
                           :class="{'bg-blue-50': !notification.is_read}"
                           class="flex px-4 py-3 hover:bg-gray-50">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5" 
                                     :class="notification.is_read ? 'text-gray-400' : 'text-[{{ $royalBlue }}]'"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="w-full pl-3">
                                <div class="text-sm" :class="notification.is_read ? 'text-gray-500' : 'font-medium text-gray-900'"
                                     x-text="notification.message">
                                </div>
                                <div class="text-xs text-gray-500 mt-1"
                                     x-text="formatDate(notification.created_at)">
                                </div>
                            </div>
                        </a>
                    </template>
                    
                    <!-- Empty State -->
                    <div x-show="notifications.length === 0" 
                         class="px-4 py-6 text-center text-gray-500">
                        No notifications yet
                    </div>
                </div>
            </div>
            <a href="/notifications" 
               class="block py-2 text-sm font-medium text-center text-gray-900 bg-gray-50 hover:bg-gray-100 rounded-b-md">
                View all notifications
            </a>
        </div>
    </div>
</div>

<script>
function notificationSystem() {
    return {
        open: false,
        notifications: [],
        unreadCount: 0,
        
        initNotifications() {
            this.fetchNotifications();
            this.fetchUnreadCount();
            
            // Refresh notifications every 30 seconds
            setInterval(() => {
                this.fetchNotifications();
                this.fetchUnreadCount();
            }, 30000);
        },
        
        async fetchNotifications() {
            try {
                const response = await fetch('/notifications', {
                    headers: {
                        'X-CSRF-TOKEN': window.csrfToken,
                        'Accept': 'application/json'
                    }
                });
                if (response.ok) {
                    this.notifications = await response.json();
                }
            } catch (error) {
                console.error('Error fetching notifications:', error);
            }
        },
        
        async fetchUnreadCount() {
            try {
                const response = await fetch('/notifications/unread-count', {
                    headers: {
                        'X-CSRF-TOKEN': window.csrfToken,
                        'Accept': 'application/json'
                    }
                });
                if (response.ok) {
                    const data = await response.json();
                    this.unreadCount = data.count;
                }
            } catch (error) {
                console.error('Error fetching unread count:', error);
            }
        },
        
        async markAsRead(id) {
            try {
                const response = await fetch(`/notifications/${id}/mark-as-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': window.csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });
                if (response.ok) {
                    await this.fetchNotifications();
                    await this.fetchUnreadCount();
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        },
        
        async markAllAsRead() {
            try {
                const response = await fetch('/notifications/mark-all-as-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': window.csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });
                if (response.ok) {
                    await this.fetchNotifications();
                    await this.fetchUnreadCount();
                }
            } catch (error) {
                console.error('Error marking all notifications as read:', error);
            }
        },
        
        async handleNotificationClick(notification) {
            if (!notification.is_read) {
                await this.markAsRead(notification.id);
            }
            window.location.href = notification.link;
        },
        
        formatDate(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diff = now - date;
            
            // Less than 1 minute
            if (diff < 60000) {
                return 'Just now';
            }
            // Less than 1 hour
            if (diff < 3600000) {
                const minutes = Math.floor(diff / 60000);
                return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
            }
            // Less than 1 day
            if (diff < 86400000) {
                const hours = Math.floor(diff / 3600000);
                return `${hours} hour${hours > 1 ? 's' : ''} ago`;
            }
            // Less than 7 days
            if (diff < 604800000) {
                const days = Math.floor(diff / 86400000);
                return `${days} day${days > 1 ? 's' : ''} ago`;
            }
            // Default to date format
            return date.toLocaleDateString();
        }
    }
}</script> 