<?php

namespace App\Console\Commands;

use App\Enums\OrderStatus;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReviewRequestsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-review-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send review request emails to customers who received their orders 3 days ago';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Find orders delivered exactly 3 days ago
        $targetDate = Carbon::now()->subDays(3)->toDateString();

        $orders = Order::with('user')
            ->where('status', OrderStatus::Delivered)
            ->whereDate('updated_at', $targetDate)
            ->whereHas('user')
            ->get();

        $count = 0;

        foreach ($orders as $order) {
            try {
                $order->user->notify(new \App\Notifications\ReviewRequestNotification($order));
                $count++;
            } catch (\Throwable $e) {
                $this->error("Failed to send review request for order #{$order->order_number}: " . $e->getMessage());
            }
        }

        $this->info("Successfully sent {$count} review request emails.");
    }
}
