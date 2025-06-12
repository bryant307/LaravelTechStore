<div>
    <div class="fixed top-20 right-4 z-50 space-y-4">
        @foreach($notifications as $notification)
            <div wire:key="notification-{{ $notification['id'] }}"
                 class="bg-white border-l-4 p-4 shadow-lg rounded-md min-w-80 max-w-md animate__animated animate__fadeInRight 
                        {{ $notification['type'] == 'success' ? 'border-green-500' : '' }}
                        {{ $notification['type'] == 'error' ? 'border-red-500' : '' }}
                        {{ $notification['type'] == 'info' ? 'border-blue-500' : '' }}
                        {{ $notification['type'] == 'warning' ? 'border-yellow-500' : '' }}">
                <div class="flex items-start">
                    @if($notification['type'] == 'success')
                        <div class="mr-3 text-green-500">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    @elseif($notification['type'] == 'error')
                        <div class="mr-3 text-red-500">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                    @elseif($notification['type'] == 'info')
                        <div class="mr-3 text-blue-500">
                            <i class="fas fa-info-circle"></i>
                        </div>
                    @elseif($notification['type'] == 'warning')
                        <div class="mr-3 text-yellow-500">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    @endif

                    <div class="flex-1 mr-2">
                        <p class="text-sm text-gray-700">{{ $notification['message'] }}</p>
                    </div>
                    
                    <button wire:click="removeNotification('{{ $notification['id'] }}')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
    
    @script
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('remove-notification', id => {
            const notification = document.querySelector(`[wire\\:key="notification-${id}"]`);
            if (notification) {
                notification.classList.remove('animate__fadeInRight');
                notification.classList.add('animate__fadeOutRight');
                setTimeout(() => {
                    @this.removeNotification(id);
                }, 500);
            }
        });
    });
    @endscript
</div>
