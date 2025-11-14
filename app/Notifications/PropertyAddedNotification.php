<?php

namespace App\Notifications;

use App\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PropertyAddedNotification extends Notification
{
    use Queueable;

    protected $property;

    public function __construct(Property $property)
    {
        $this->property = $property;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'property_id' => $this->property->id,
            'property_title' => $this->property->title,
            'message' => 'New property added: ' . $this->property->title,
            'url' => route('properties.show', $this->property),
        ];
    }
}
