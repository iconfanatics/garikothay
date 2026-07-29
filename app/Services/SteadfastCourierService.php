<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class SteadfastCourierService
{
    protected string $baseUrl = 'https://portal.steadfast.com.bd/api/v1';

    protected function getHeaders(): array
    {
        $apiKey = Setting::get('steadfast_api_key');
        $secretKey = Setting::get('steadfast_secret_key');

        if (empty($apiKey) || empty($secretKey)) {
            throw new Exception("Steadfast Courier API keys are not configured in Settings.");
        }

        return [
            'Api-Key' => $apiKey,
            'Secret-Key' => $secretKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * Create an order in Steadfast Courier.
     *
     * @param Order $order
     * @return array Contains 'consignment_id' and 'tracking_code' on success.
     * @throws Exception
     */
    public function createOrder(Order $order): array
    {
        $payload = [
            'invoice' => $order->order_number,
            'recipient_name' => $order->shipping_name,
            'recipient_phone' => $order->shipping_phone,
            'recipient_address' => $order->shipping_full_address,
            'cod_amount' => $order->payment_method->value === 'cash_on_delivery' ? $order->total : 0,
            'note' => $order->notes ?? 'Order from ' . Setting::get('site_name', 'Garikothay'),
        ];

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post($this->baseUrl . '/create_order', $payload);

            $data = $response->json();

            if ($response->successful() && isset($data['status']) && $data['status'] == 200) {
                return [
                    'consignment_id' => $data['consignment']['consignment_id'] ?? null,
                    'tracking_code' => $data['consignment']['tracking_code'] ?? null,
                    'status' => $data['consignment']['status'] ?? 'pending',
                ];
            }

            $errorMessage = $data['message'] ?? $data['error'] ?? 'Unknown error from Steadfast API';
            Log::error('Steadfast API Error: ' . json_encode($data));
            throw new Exception("Steadfast Error: " . $errorMessage);

        } catch (\Exception $e) {
            Log::error('Steadfast API Exception: ' . $e->getMessage());
            throw new Exception("Failed to connect to Steadfast Courier: " . $e->getMessage());
        }
    }

    /**
     * Check delivery status of a consignment.
     *
     * @param string $consignmentId Or tracking code depending on API
     * @return array
     */
    public function checkDeliveryStatus(string $consignmentId): array
    {
        try {
            // Steadfast typically provides status via consignment ID or tracking code.
            // Using /status_by_cid or /status_by_trackingcode. Assuming /status_by_cid/{id}
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->baseUrl . '/status_by_cid/' . $consignmentId);

            $data = $response->json();

            if ($response->successful() && isset($data['status']) && $data['status'] == 200) {
                return [
                    'delivery_status' => $data['delivery_status'] ?? 'unknown',
                ];
            }
            
            return ['delivery_status' => 'unknown', 'message' => $data['message'] ?? ''];

        } catch (\Exception $e) {
            Log::error('Steadfast Status Check Error: ' . $e->getMessage());
            return ['delivery_status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Fetch merchant balance.
     *
     * @return float|string
     */
    public function getCurrentBalance()
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->baseUrl . '/get_balance');

            $data = $response->json();

            if ($response->successful() && isset($data['status']) && $data['status'] == 200) {
                return $data['balance'] ?? 0;
            }

            return 'Error';
        } catch (\Exception $e) {
            return 'Connection Failed';
        }
    }
}
