<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    public function unread()
    {
        $notifications = Auth::user()->unreadNotifications;
        return response()->json([
            'count' => $notifications->count(),
            'notifications' => $notifications->take(5)->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->data['type'] ?? 'general',
                    'title' => $notification->data['title'] ?? 'Notification',
                    'message' => $notification->data['message'] ?? '',
                    'url' => $notification->data['url'] ?? '#',
                    'created_at' => $notification->created_at->diffForHumans(),
                ];
            })
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        
        if ($notification) {
            $notification->markAsRead();
            
            // Redirect to notification URL if available
            if (isset($notification->data['url'])) {
                return redirect($notification->data['url']);
            }
        }
        
        return back();
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read');
    }

    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        
        if ($notification) {
            $notification->delete();
        }
        
        return back()->with('success', 'Notification deleted');
    }
}
