<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Redirect to destination URL and mark as read
    public function read($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect($notification->data['url'] ?? route('dashboard'));
    }

    // Mark all as read
    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('status', 'All notifications marked as read.');
    }

    // Notifications index page
    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(15);
        return view('notifications.index', compact('notifications'));
    }
}
