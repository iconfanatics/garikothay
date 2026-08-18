<?php

namespace App\Console\Commands;

use App\Mail\SpecialOfferMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReengagementOfferCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-reengagement-offer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a special offer email to users who registered 30 days ago';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetDate = Carbon::now()->subDays(30)->toDateString();

        $users = User::whereDate('created_at', $targetDate)->get();

        $count = 0;

        foreach ($users as $user) {
            try {
                // In a real application, you might generate a unique coupon code here.
                // For simplicity, we use a generic welcome code.
                $couponCode = 'WELCOME10';
                
                Mail::to($user->email)->send(new SpecialOfferMail($user, $couponCode));
                $count++;
            } catch (\Throwable $e) {
                $this->error("Failed to send offer to user #{$user->id}: " . $e->getMessage());
            }
        }

        $this->info("Successfully sent {$count} special offer emails.");
    }
}
