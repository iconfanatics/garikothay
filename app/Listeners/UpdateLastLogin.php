<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class UpdateLastLogin
{
    public function handle(Login $event): void
    {
        if ($event->user) {
            $event->user->last_login_at = now();
            $event->user->save();
            
            // Force update via DB just in case Eloquent silently ignores it
            if (method_exists($event->user, 'getTable')) {
                \Illuminate\Support\Facades\DB::table($event->user->getTable())
                    ->where('id', $event->user->id)
                    ->update(['last_login_at' => now()]);
            }
        }
    }
}
