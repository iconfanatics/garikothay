<?php

declare(strict_types=1);

namespace App\Payments;

use App\Contracts\PaymentGateway;
use App\Events\PaymentReceived;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SslCommerzGateway implements PaymentGateway
{
    private string $storeId;

    private string $storePassword;

    private bool $isSandbox;

    public function __construct()
    {
        $this->storeId = config('payment.gateways.sslcommerz.store_id', '');
        $this->storePassword = config('payment.gateways.sslcommerz.store_password', '');
        $this->isSandbox = config('payment.gateways.sslcommerz.sandbox', true);
    }

    private function getApiUrl(string $endpoint): string
    {
        $base = $this->isSandbox
            ? 'https://sandbox.sslcommerz.com'
            : 'https://securepay.sslcommerz.com';

        return "{$base}/{$endpoint}";
    }

    public function initiate(Order $order): array
    {
        $transactionId = strtoupper(substr(Setting::get('site_name', 'DIP'), 0, 3)).'-'.strtoupper(Str::random(12));

        $postData = [
            'store_id' => $this->storeId,
            'store_passwd' => $this->storePassword,
            'total_amount' => $order->total,
            'currency' => 'BDT',
            'tran_id' => $transactionId,
            'success_url' => route('payment.sslcommerz.success'),
            'fail_url' => route('payment.sslcommerz.fail'),
            'cancel_url' => route('payment.sslcommerz.cancel'),
            'ipn_url' => route('payment.sslcommerz.ipn'),
            'cus_name' => $order->user->name,
            'cus_email' => $order->user->email,
            'cus_phone' => $order->user->phone,
            'cus_add1' => $order->shipping_address['address_line_1'] ?? '',
            'cus_city' => $order->shipping_address['city'] ?? 'Dhaka',
            'cus_country' => 'Bangladesh',
            'shipping_method' => 'Courier',
            'ship_name' => $order->shipping_address['full_name'] ?? $order->user->name,
            'ship_add1' => $order->shipping_address['address_line_1'] ?? '',
            'ship_add2' => $order->shipping_address['address_line_2'] ?? '',
            'ship_city' => $order->shipping_address['city'] ?? 'Dhaka',
            'ship_state' => $order->shipping_address['division'] ?? 'Dhaka',
            'ship_postcode' => $order->shipping_address['postal_code'] ?? '1000',
            'ship_country' => 'Bangladesh',
            'product_name' => Setting::get('site_name', 'Garikothay').' Products',
            'product_category' => 'IT Accessories',
            'product_profile' => 'general',
        ];

        $response = Http::asForm()->post($this->getApiUrl('gwprocess/v4/api.php'), $postData);
        $result = $response->json();

        if ($result['status'] === 'SUCCESS') {
            Payment::create([
                'order_id' => $order->id,
                'transaction_id' => $transactionId,
                'payment_method' => 'sslcommerz',
                'amount' => $order->total,
                'currency' => 'BDT',
                'status' => 'pending',
                'gateway_response' => $result,
            ]);

            return ['redirect' => $result['GatewayPageURL']];
        }

        throw new \RuntimeException('SSLCommerz initiation failed: '.($result['failedreason'] ?? 'Unknown error'));
    }

    public function verify(Request $request): Payment
    {
        $payment = Payment::where('transaction_id', $request->get('tran_id'))->firstOrFail();

        $validationResponse = Http::get($this->getApiUrl('validator/api/validationserverAPI.php'), [
            'val_id' => $request->get('val_id'),
            'store_id' => $this->storeId,
            'store_passwd' => $this->storePassword,
        ]);

        $result = $validationResponse->json();

        if ($result['status'] === 'VALID' || $result['status'] === 'VALIDATED') {
            $payment->update([
                'status' => 'completed',
                'paid_at' => now(),
                'gateway_response' => $result,
            ]);
            $payment->order->update(['payment_status' => 'paid', 'status' => 'confirmed']);
            event(new PaymentReceived($payment));
        }

        return $payment;
    }

    public function refund(Payment $payment): bool
    {
        $payment->update(['status' => 'refunded']);

        return true;
    }
}
