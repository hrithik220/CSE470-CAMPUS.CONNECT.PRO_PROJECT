<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected string $sid;
    protected string $token;
    protected string $from;

    public function __construct()
    {
        $this->sid = config('services.twilio.sid', '');
        $this->token = config('services.twilio.auth_token', '');
        $this->from = config('services.twilio.phone_number', '');
    }

    /**
     * Send an SMS message via Twilio.
     */
    public function send(string $to, string $message): bool
    {
        if (empty($this->sid) || empty($this->token)) {
            Log::warning('SMS Service: Twilio credentials not configured. Message not sent.', [
                'to' => $to,
                'message' => $message,
            ]);
            return false;
        }

        try {
            $response = Http::withBasicAuth($this->sid, $this->token)
                ->asForm()
                ->post(
                    "https://api.twilio.com/2010-04-01/Accounts/{$this->sid}/Messages.json",
                    [
                        'From' => $this->from,
                        'To' => $to,
                        'Body' => $message,
                    ]
                );

            if ($response->successful()) {
                Log::info('SMS sent successfully', ['to' => $to, 'sid' => $response->json('sid')]);
                return true;
            }

            Log::error('SMS send failed', [
                'to' => $to,
                'status' => $response->status(),
                'error' => $response->json(),
            ]);
            return false;

        } catch (\Exception $e) {
            Log::error('SMS Service Exception', [
                'to' => $to,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Send ride request notification.
     */
    public function sendRideRequestNotification(string $to, string $passengerName, string $rideDetails): bool
    {
        $message = "🚗 Campus Connect: {$passengerName} wants to join your ride ({$rideDetails}). Login to accept/decline.";
        return $this->send($to, $message);
    }

    /**
     * Send session reminder.
     */
    public function sendSessionReminder(string $to, string $subject, string $dateTime, string $location): bool
    {
        $message = "📚 Campus Connect Reminder: Your tutoring session for {$subject} is scheduled for {$dateTime} at {$location}.";
        return $this->send($to, $message);
    }

    /**
     * Send deadline reminder.
     */
    public function sendDeadlineReminder(string $to, string $title, string $dueDate): bool
    {
        $message = "⏰ Campus Connect: Reminder - '{$title}' is due {$dueDate}. Don't forget!";
        return $this->send($to, $message);
    }
}
