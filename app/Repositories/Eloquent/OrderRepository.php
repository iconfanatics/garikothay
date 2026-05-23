<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class OrderRepository implements OrderRepositoryInterface
{
    public function getByUser(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return Order::with(['items', 'payment'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    public function findByOrderNumber(string $orderNumber): ?Order
    {
        return Order::with(['items.product', 'user', 'payment'])
            ->where('order_number', $orderNumber)
            ->first();
    }

    public function getByStatus(OrderStatus $status): Collection
    {
        return Order::with(['user', 'items'])
            ->where('status', $status)
            ->latest()
            ->get();
    }

    public function getSalesReport(Carbon $from, Carbon $to): array
    {
        $orders = Order::whereBetween('created_at', [$from, $to])
            ->where('payment_status', 'paid')
            ->selectRaw('COUNT(*) as total_orders, SUM(total) as total_revenue, AVG(total) as average_order_value')
            ->first();

        return [
            'total_orders' => (int) ($orders->total_orders ?? 0),
            'total_revenue' => (float) ($orders->total_revenue ?? 0),
            'average_order_value' => (float) ($orders->average_order_value ?? 0),
        ];
    }

    public function getRevenueByDate(Carbon $from, Carbon $to): Collection
    {
        return Order::whereBetween('created_at', [$from, $to])
            ->where('payment_status', 'paid')
            ->selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
}
