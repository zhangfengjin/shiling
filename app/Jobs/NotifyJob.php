<?php

namespace App\Jobs;

use App\Models\MeetUser;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NotifyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notify;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($notify)
    {
        $this->notify = $notify;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $meetUsers = $this->getMeetUsers($this->notify['meetId']);
        foreach ($meetUsers as $user) {
            if ($this->notify['send_sms'] && empty($user->phone)) {
                $this->sendSMS($user->phone);
            }
            if ($this->notify['send_email'] && empty($user->email)) {
                $this->sendEmail($user->email);
            }
        }
    }

    private function getMeetUsers($meetId)
    {
        $select = [
            'phone', 'email'
        ];
        $meetUsers = DB::table("meet_users as mu")
            ->leftJoin("users as u", 'u.id', '=', 'mu.user_id')
            ->where("mu.meet_id", $meetId)
            ->get($select);
        return $meetUsers;
    }

    private function sendEmail($email)
    {
    }

    private function sendSMS($phone)
    {
    }
}
