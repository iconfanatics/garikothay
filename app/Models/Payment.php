<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Payment extends Model
{
    use LogsActivity;
    protected $fillable = [
        'order_id', 'transaction_id', 'payment_method', 'amount',
        'currency', 'status', 'gateway_response', 'paid_at',
        'payment_reference', 'gateway_response_code', 'gateway_response_message',
        'refund_amount', 'refund_date', 'refund_transaction_id', 'refund_reason',
        'remarks', 'created_by_admin_id'
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'refund_amount' => 'decimal:2',
            'gateway_response' => 'array',
            'paid_at' => 'datetime',
            'refund_date' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'amount', 'payment_method'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Payment {$eventName}");
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function createdByAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by_admin_id');
    }

    public function scopePending($query): void
    {
        $query->where('status', 'pending');
    }

    public function scopeCompleted($query): void
    {
        $query->where('status', 'completed');
    }
}
