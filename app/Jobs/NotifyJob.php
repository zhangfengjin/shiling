<?php

namespace App\Jobs;

use App\Http\Services\TelEmailService;
use App\Models\MeetUser;
use App\Utils\EmailHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        $content = "最新通知：" . $this->notify['notify_content'];
        foreach ($meetUsers as $user) {
            if ($this->notify['send_sms'] && !empty($user->phone)) {
                $this->sendSMS($user->phone, $content);
            }
            if ($this->notify['send_email'] && !empty($user->email)) {
                $this->sendEmail($user->email, $content);
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

    private function sendSMS($phone, $content)
    {
        $telService = new TelEmailService();
        $telService->sendNotifySMS($phone, $content);
    }

    private function sendEmail($email, $content)
    {
        Log::info("email");
        //todo 通知
        Mail::raw($content, function ($message) use ($email) {
            $message->to($email)->subject("领师通知");
        });
    }
}
