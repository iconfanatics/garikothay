<?php

declare(strict_types=1);

namespace App\Notifications\Traits;

use Illuminate\Support\Facades\Cache;

trait PreventsDuplicateNotifications
{
    /**
     * Determine if the notification should be sent.
     */
    public function shouldSend(object $notifiable, string $channel): bool
    {
        if (property_exists($this, 'bypassDuplicateCheck') && $this->bypassDuplicateCheck) {
            return true;
        }

        $cacheKey = $this->getDuplicateCheckCacheKey($notifiable, $channel);
        $duration = $this->getDuplicateCheckDuration();

        // Use add() so it only returns true if the key didn't exist and was set
        if (!Cache::add($cacheKey, true, $duration)) {
            return false;
        }

        return true;
    }

    /**
     * Get the cache key for duplicate prevention.
     */
    protected function getDuplicateCheckCacheKey(object $notifiable, string $channel): string
    {
        $notifiableId = method_exists($notifiable, 'getKey') 
            ? $notifiable->getKey() 
            : (isset($notifiable->id) ? $notifiable->id : 'unknown');
            
        $identifier = method_exists($this, 'getDuplicateIdentifier') 
            ? $this->getDuplicateIdentifier() 
            : '';

        return sprintf(
            'notif_sent_%s_%s_%s_%s',
            class_basename($this),
            $channel,
            $notifiableId,
            $identifier
        );
    }

    /**
     * Get the duration for which the notification should not be duplicated.
     */
    protected function getDuplicateCheckDuration(): \DateInterval|\DateTimeInterface|int
    {
        return property_exists($this, 'duplicateCheckDuration') 
            ? $this->duplicateCheckDuration 
            : now()->addHours(24);
    }
}
