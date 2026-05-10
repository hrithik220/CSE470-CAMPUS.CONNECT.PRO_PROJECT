<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    protected SmsService $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Send a notification via multiple channels (in-app + SMS).
     */
    public function notify(User $user, string $title, string $message, string $type = 'info', ?string $actionUrl = null): void
    {
        // Store in-app notification
        $user->notifications()->create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'App\\Notifications\\GeneralNotification',
            'data' => json_encode([
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'action_url' => $actionUrl,
            ]),
        ]);

        Log::info('Notification sent', [
            'user_id' => $user->id,
            'title' => $title,
            'type' => $type,
        ]);
    }

    /**
     * Send SMS + in-app notification.
     */
    public function notifyWithSms(User $user, string $title, string $message, ?string $actionUrl = null): void
    {
        $this->notify($user, $title, $message, 'info', $actionUrl);

        if ($user->phone) {
            $this->smsService->send($user->phone, $message);
        }
    }

    /**
     * Notify about ride request.
     */
    public function notifyRideRequest(User $rideOwner, User $requester, $ride): void
    {
        $title = 'New Ride Request';
        $message = "{$requester->name} wants to join your ride from {$ride->pickup_location} to {$ride->destination}";

        $this->notify($rideOwner, $title, $message, 'ride', route('rides.show', $ride));

        if ($rideOwner->phone) {
            $this->smsService->sendRideRequestNotification(
                $rideOwner->phone,
                $requester->name,
                "{$ride->pickup_location} → {$ride->destination}"
            );
        }
    }

    /**
     * Notify about session booking.
     */
    public function notifySessionBooked(User $tutor, User $student, $session): void
    {
        $title = 'New Tutoring Session Booked';
        $message = "{$student->name} booked a {$session->subject} session on {$session->session_date->format('M d, Y')}";

        $this->notify($tutor, $title, $message, 'tutoring', route('tutoring.sessions.show', $session));
    }

    /**
     * Send session reminder (24h before).
     */
    public function sendSessionReminder(User $user, $session): void
    {
        $title = 'Tutoring Session Tomorrow';
        $message = "Reminder: {$session->subject} session tomorrow at {$session->start_time}";

        $this->notify($user, $title, $message, 'reminder');

        if ($user->phone) {
            $this->smsService->sendSessionReminder(
                $user->phone,
                $session->subject,
                $session->session_date->format('M d') . ' at ' . $session->start_time,
                $session->location ?? 'TBD'
            );
        }
    }

    /**
     * Send deadline reminder.
     */
    public function sendDeadlineReminder(User $user, $deadline, string $timeframe): void
    {
        $title = "Deadline in {$timeframe}";
        $message = "'{$deadline->title}' for {$deadline->course_code} is due {$deadline->due_date->format('M d, Y g:i A')}";

        $this->notify($user, $title, $message, 'deadline', route('deadlines.index'));

        if ($user->phone) {
            $this->smsService->sendDeadlineReminder(
                $user->phone,
                $deadline->title,
                $deadline->due_date->diffForHumans()
            );
        }
    }
}
