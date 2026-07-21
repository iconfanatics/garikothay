<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class UpdateLastLogin
{
    public function handle(Login $event): void
    {
        if ($event->user) {
            $ip = request()->ip();

            $event->user->last_login_at = now();
            if ($event->user instanceof \App\Models\Admin) {
                $event->user->last_login_ip = $ip;
            }
            $event->user->save();
            
            // Force update via DB just in case Eloquent silently ignores it
            if (method_exists($event->user, 'getTable')) {
                $data = ['last_login_at' => now()];
                if ($event->user instanceof \App\Models\Admin) {
                    $data['last_login_ip'] = $ip;
                }
                \Illuminate\Support\Facades\DB::table($event->user->getTable())
                    ->where('id', $event->user->id)
                    ->update($data);
            }
        }
    }
}
