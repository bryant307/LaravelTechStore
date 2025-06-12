<?php
// Ese componente Livewire se encarga de manejar las notificaciones en la aplicación.
// Permite agregar y eliminar notificaciones, y las muestra en la interfaz de usuario.
namespace App\Livewire;

use Livewire\Component;

class Notifications extends Component
{
    public $notifications = [];
      #[\Livewire\Attributes\On('showNotification')]
    public function addNotification($message, $type = 'success', $timeout = 3000)
    {
        $id = uniqid();
        $this->notifications[] = [
            'id' => $id,
            'message' => $message,
            'type' => $type,
        ];
        
        // Eliminar la notificación después de un tiempo usando JavaScript
        $this->js("setTimeout(() => { 
            Livewire.dispatch('remove-notification', { id: '{$id}' }); 
        }, {$timeout});");
    }
    
    #[\Livewire\Attributes\On('remove-notification')]
    public function removeNotification($id)
    {
        $this->notifications = array_filter($this->notifications, function ($notification) use ($id) {
            return $notification['id'] !== $id;
        });
    }
    
    public function render()
    {
        return view('livewire.notifications');
    }
}
